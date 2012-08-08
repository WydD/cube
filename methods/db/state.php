<?php
include('../common.php');

$action = $_POST['action'];
$cubie = getPost("cubie");
$placed = getPost("placed");
$oriented = getPost("oriented");
$id = getPost("id");

if($action == "i") {
	$sql = "INSERT INTO cubiestate (id, cubie, placed, oriented) VALUES (".$id.", ".$cubie.",".$placed.",".$oriented.") ON DUPLICATE KEY UPDATE placed=".($placed == "DEFAULT" ? "placed" : $placed).", oriented=".($oriented == "DEFAULT" ? "oriented" : $oriented);
	if (!$res = mysql_query($sql)) die(mysql_error()); 
} else {
	$sql = "DELETE FROM cubiestate WHERE id=".$id." AND cubie=".$cubie;
	if (!$res = mysql_query($sql)) die(mysql_error()); 
}
?>