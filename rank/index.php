<?php
$method = 'elo';
$DB = mysql_connect('127.0.0.1', 'root', '');
mysql_select_db('wca', $DB);
//mysql_set_charset ('utf8', $DB);

function displayTime($time, $type) {
	global $eventFormat;
	$type = $eventFormat[$type];
	if($type == "time") {
		$milli = str_pad($time % 100,2,"0",STR_PAD_LEFT);
		$seconds = floor($time / 100);
		$time -= $milli;
		$minutes = floor($seconds / 60);
		$seconds = $seconds % 60;
		return ($minutes ? $minutes.":".str_pad($seconds,2,"0",STR_PAD_LEFT) : $seconds).".".$milli;
	}
	if($type == "number")
		return $time;
	if($type == "multi") {
		return $time;
	}
}
function cmpScore($a, $b) {
    if ($a['score'] == $b['score']) {
        return count($b)-count($a);
    }
    return ($a['score'] > $b['score']) ? -1 : 1;
}

function countPerEvent($eventId) {
	global $method;
	if($method == 'elo') return 1000;
//	return ($eventId == '333fm' ? 6 : 7);
	return 5;
}
function computeResults($id, $arr, $eventId = '') {
	$eId = $eventId;
	if(!$eventId)
		$eId = $id;

	$count = countPerEvent($eventId);
	uasort($arr[$id], "cmpScore");
	$score = 0;
	foreach($arr[$id] as $i => $value) {
		$arr[$id][$i]['valid'] = $count--;
		if($count >= 0)
			$score += $value['score'];
	}
	$arr[$id]['score'] = $score;
	return $arr;
}

$eventId = '';
$personId = '';
if($_GET['method']) { $method = $_GET['method']; }
if($_GET['event']) { $eventId = $_GET['event']; }
else if($_GET['person']) { $personId = $_GET['person']; }
else
	$eventId = '333';

$result = mysql_query("SELECT id, cellName as name, format FROM Events WHERE rank <= 70 ORDER BY rank");
$events = array();
	$eventFormat = array();
while ($row = mysql_fetch_array($result)) {
	$events[$row['id']] = $row['name'];
	$eventFormat[$row['id']] = $row['format'];
}

$result = mysql_query("SELECT personId as id, personName as name FROM ".$method."score GROUP BY personName, personId ORDER BY personName ASC");
$persons = array();
while ($row = mysql_fetch_array($result)) {
	$persons[$row['id']] = $row['name'];
}

if($eventId)
	$query = "SELECT personId as id, competitionId, pos, score FROM ".$method."score WHERE eventId = '".$eventId."' ORDER BY personId ASC";
else
	$query = "SELECT eventId as id, competitionId, pos, score FROM ".$method."score WHERE personId = '".$personId."' ORDER BY eventId ASC";
$result = mysql_query($query);
$rank = array();
$oldId = '';
while ($row = mysql_fetch_array($result)) {
	$id = $row['id'];
	if ($id != $oldId) {
		if($oldId) {
			$rank = computeResults($oldId, $rank, $eventId);
		}
		$rank[$id] = array();
	}
	$rank[$id][$row['competitionId']] = array('pos' => $row['pos'], 'score' => $row['score']);
	$oldId = $id;
}
$rank = computeResults($oldId, $rank, $eventId);
//uasort($rank, "cmpScore");

$result = mysql_query("SELECT ".($eventId ? "personId" : "eventId")." as id, total, count, average, best, pos, nltotal FROM final".($method == "f1" ? "" : $method)."score WHERE ".($personId ? "personId = '".$personId."'" : "eventId = '".$eventId."'")." ORDER BY pos ASC, total DESC");
$score = array();
while ($row = mysql_fetch_array($result)) {
	$score[$row['id']] = array($row['total'], $row['count'], $row['average'], $row['best'], $row['pos'], $row['nltotal']);
}

$result = mysql_query("SELECT id, name, class FROM competitions JOIN opensize ON (id=competitionId) WHERE id IN (SELECT DISTINCT competitionId FROM (".$query.") c) ORDER BY year ASC, month ASC, day ASC");
$competitions = array();
while ($row = mysql_fetch_array($result)) {
	$competitions[$row['id']] = array(str_replace(" ", "&nbsp;", str_replace(" Open 2011", "", $row['name'])), $row['class']);
}
$ratio = 100/(count($competitions)+4);
$ratio1st = 2*$ratio;


mysql_close($DB);
header('Content-Type: text/html; charset=utf-8'); 
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Coupe de France</title>
		<link href="style/general.css" type="text/css" rel="stylesheet"/>
<link href="style/pageMenu.css" type="text/css" rel="stylesheet"/>
<link href="style/tables.css" type="text/css" rel="stylesheet"/>
<link href="style/links.css" type="text/css" rel="stylesheet"/>
<style type="text/css">
.opensize0 { color: #fff !important; }
.opensize2 { color: #F60 !important; }
</style>
	</head>
	<body>
		<h1>Coupe de France de Speedcubing</h1>
		<p style='font-size:0.75em; clear:both;'>Cette application est une simulation du classement tel que spécifié dans le règlement de la Coupe de France avec les résultats de 2011. <br/><b>Légende</b> : S = Score, SNL = Score non limité, V = Victoires, PB = Personal Best (Single / Average). Couleur des compétitions : blanc = mineur, gris = intermédiaire, orange = majeur.<br/>Spécificité : Tous les participants sont considérés comme membres de l'AFS. Le nombre de compétitions prises en compte est basé sur un taux de 50% des compétitions de 2010, donc 5 pour toutes les catégories.</p>
		<div style="float:left; position:absolute; ">
		<form method="get" action="">
		<table id="choices" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td valign="bottom">
<div class="space">
Catégorie : <br/><select class="drop" name="event" onChange="window.location.href = '?event='+this.options[this.selectedIndex].id;"><option id="">Sélectionnez...</option>
			<?php foreach($events as $id => $name) { 
				echo "<option id='".$id."'".($eventId == $id ? "selected='selected'" : '').">".$name."</option>"; 
			} ?>
			</select>
</div>
</td>
<td valign="bottom">
<div class="space">
Compétiteur :<br/><select name="person" class="drop" onChange="window.location.href = '?person='+this.options[this.selectedIndex].id;">
			<option id="">Sélectionnez...</option>
			<?php foreach($persons as $id => $name) { echo "<option id='".$id."'".($personId == $id ? "selected='selected'" : '').">".$name."</option>"; } ?></select>
			</div>
</td>
</tr>
</table>
		</form>
		</div>
		
	<div style="width:100%;">
		<br/>
		<h2><?php echo ($eventId ? "Catégorie ".$events[$eventId] : "Compétiteur ".$persons[$personId]); ?></h2>
		</div>

		<table class="results" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
				<th><?php echo ($personId ? "Position" : "#"); ?></th>
				<th><?php echo ($personId ? "Catégorie" : "Compétiteur"); ?></th>
				<th>S</th>
<?php foreach($competitions as $id => $value) { echo "<th class='opensize".$value[1]."'>&nbsp;".$value[0]."&nbsp;</th>"; } ?>
<!--				<th class="f">&nbsp;</th>-->

				<th>SNL</th>
				<th>V</th>
				<th>PB</th>
				</tr>
		<?php 
$c = 0;
$r = 0;
$oldscore = 0;
foreach($score as $id => $arr) {
	if($personId && !$events[$id]) continue;
	//($row['total'], $row['count'], $row['average'], $row['best'], $row['pos'])
	if($oldscore != $arr[0]) 
		$r = $c;
	$oldscore = $arr[0];
		
	echo "<tr align='center'".($c % 2 == 0 ? "" : " class='e'").">";
		
		echo "<td>".$arr[4]."</td>";
	echo "<td>";
	if($personId)
		echo "<a href='?event=".$id."'>".$events[$id]."</a>";
	else
		echo "<a href='?person=".$id."'>".$persons[$id]."</a>";
	echo "</td>";
	echo "<td>".$arr[0]."</td>"; 
	$eId = $eventId;
	if($personId)
		$eId = $id;
	foreach($competitions as $cId => $value) {
		echo "<td>";
		if($rank[$id][$cId]) {
			if($rank[$id][$cId]['valid'] <= 0)
				echo "<s>";
			echo $rank[$id][$cId]['score']." (".$rank[$id][$cId]['pos'].")";

			if($rank[$id][$cId]['valid'] <= 0)
				echo "</s>";
		} else
			echo "-";
		echo "</td>";
	}
	echo "<td>".$arr[5]."</td>"; 
	echo "<td>".($arr[1] ? str_pad("",$arr[1],"*") : "")."</td>";
	
	if($arr[2]) 
		echo "<td>".displayTime($arr[3], $eId)." / ".displayTime($arr[2], $eId)."</td>";
	else
		echo "<td>".displayTime($arr[3], $eId)."</td>";
//	echo "<td>&nbsp;</td>";
	echo "</tr>";
	$c++;
}
		?>
			</tbody>
		</table>
	</body>
</html>