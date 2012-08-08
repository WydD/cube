<?php
include("connect.php");

$query = "SELECT * FROM step WHERE isMethod = '1' ORDER BY name ASC";
echo "Available methods:<br/>";
$res = query($query);
while($row = mysql_fetch_array($res)) {
	echo $row['id'].": ".$row['name'];
}


mysql_close($db);
?>