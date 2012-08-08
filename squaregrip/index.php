<?php
$REGRIP_COST = 1;
$WRONG_GRIP_FACTOR = 4;

$lines = explode("\n", $_GET['input']);
$sequences = array();
foreach($lines as $line) {
	$movStr = explode("/",$line);
	$final = array();
	for($i = 0 ; $i < count($movStr) ; $i++) {
	    if($movStr[$i] == "") {
	        $final[$i] = array(0,0);
	        continue;
	    }
	    $nums = array();
	    preg_match_all("/-?\d+/",$movStr[$i],$nums);
	    $final[$i] = $nums[0];
	}
	$sequences[] = $final;
}

// Grip : array of {0,1}. 0 means palm down, 1 up

function evaluate($seq, $grip) {
	global $REGRIP_COST, $WRONG_GRIP_FACTOR;
	$res = 0;

	for($i = 0 ; $i < count($seq) ; $i++) {
		if($i > 0 && $grip[$i-1] == $grip[$i]) {
			$res += $REGRIP_COST;
		}
		$res += (1+abs($seq[$i][0])/10) * ($seq[$i][0] < 0 && $grip[$i] ? 1 : $WRONG_GRIP_FACTOR);
		$res += (1+abs($seq[$i][1])/10) * ($seq[$i][1] < 0 && $grip[$i] ? 1 : $WRONG_GRIP_FACTOR);
	}
	return $res;
}

function createGrip($count, $points = FALSE) {
	$grip = array(0);
	$ind = 0;
	for($i = 1 ; $i < $count ; $i++) {
		if($points && $points[$ind] == $i) {
			$grip[$i] = $grip[$i-1];
			$ind++;
		} else { 
			$grip[$i] = 1-$grip[$i-1];
		}
	}
	return $grip;
}

if(count($sequences) > 0) {
	echo "Natural evaluation: ";
	$size = count($sequences[0]);
	$j = (1 << count($sequences[0]));
	$min = evaluate($sequences[0], createGrip($size)); 
	echo $min."<br/>";
	$bestGrips = array();
	for($i = 0 ; $i < $j ; $i++) {
		$grip = sprintf("%0".$size."b",$i);
		$eval = evaluate($sequences[0],$grip);
		if($min > $eval) {
			$bestGrips = array($grip);
			$min = $eval;
		} else if($min == $eval) {
			array_push($bestGrips, $grip);
		}
	}
	echo "Best grip found (score=".$min.") :<br/>";
	echo "<table><tr align='center'>";
	for($i = 0 ; $i < $size ; $i++) {
		echo "<td>/ (".$sequences[0][$i][0].",".$sequences[0][$i][1].")</td>";
	}
	echo "</tr>";
	for($n = 0 ; $n < count($bestGrips) ; $n++) {
		echo "<tr align='center'>";
		for($i = 0 ; $i < $size ; $i++) {
			echo "<td>".$bestGrips[$n][$i]."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
?>
<html>
<head><title>Square-1 gripper</title></head>
<body>
	<form action="." method="get">
		<textarea cols="60" rows="10" name="input"></textarea>
		<input type="submit" value="Grip"/>
	</form>
</body>
</html>