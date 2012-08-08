<?php
mysql_connect('127.0.0.1','root', '');
$sql = "SELECT table_name, column_name, column_key FROM information_schema.columns WHERE table_schema = 'cubemethods' ORDER BY table_name";
if(!$res = mysql_query($sql)) die(mysql_error());
echo "<?php";
$oldtable = "";
while($row = mysql_fetch_array($res)) {
	if($row['table_name'] != $oldtable) {
		echo "\n\$DB['".$row['table_name']."'] = array('PRI' => array(), 'ATT' => array());\n";
		$oldtable = $row['table_name'];
	}
	if($row['column_key'] != "PRI") $row['column_key'] = "ATT";
	else {
		echo "\$DB['".$row['table_name']."']['ATT'][] = '".$row['column_name']."';\n";
	}
	echo "\$DB['".$row['table_name']."']['".$row['column_key']."'][] = '".$row['column_name']."';\n";
}
echo "?>";
?>