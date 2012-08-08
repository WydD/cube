<?php
include('../common.php');

$action = $_POST['action'];
$setup = getPost("setup");
$displayOrientFaces = getPost("displayOrientFaces");
$id = getPost("id");
echo $action;
if ($action == "i") {
	$sql = "INSERT INTO cubestate (id, setup, displayOrientFaces) VALUES (".$id.", ".$setup.",".$displayOrientFaces.") ON DUPLICATE KEY UPDATE ".updateField($setup, "setup").", ".updateField($displayOrientFaces, "displayOrientFaces");
	if (!$res = mysql_query($sql)) die(mysql_error()); 
} else if ($action == "d") {
	$sql = "DELETE FROM cubestate WHERE id=".$id;
	if (!$res = mysql_query($sql)) die(mysql_error()); 
} else if ($action == "g") {
	$sql = "SELECT * FROM cubestate WHERE id = ".$id;
	if (!$res = mysql_query($sql)) die(mysql_error()); 
	if (!$row = mysql_fetch_array($res)) die(mysql_error());
	echo "{setup: \"".$row['setup']."\", displayOrientFaces: \"".$row['displayOrientFaces']."\" }";
}
?>