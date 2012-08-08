<?php

include('../common.php');

$action = $_GET['action'];
if(!$action)
	$action = $_POST['action'];
if(!$action)
	die("No action is asked (duh!)");
$entity = $_GET['entity'];
if(!$entity)
	$entity = $_POST['entity'];
if(!$entity)
	die("No entity is specified (duh!)");
if(!isset($DB[$entity])) die("Entity does not exist!");

// Security ?

$primaryKeys = $DB[$entity]['PRI'];
$attributes = $DB[$entity]['ATT'];

if($action == "i") {
	$sql = "INSERT INTO ".$entity." (";
	$sql .= concatenateExceptFirst($attributes, ", ");
	$sql .= ") VALUES (".concatenateExceptFirst($attributes, ", ", getPost).")";
	$sql .= " ON DUPLICATE KEY UPDATE ";
	$sql .= concatenateExceptFirst($attributes, ", ", updateField);
	if (!$res = mysql_query($sql)) die(mysql_error());
} else if($action == "d") {
	$sql .= "DELETE FROM ".$entity." WHERE ";
	$sql .= concatenateExceptFirst($primaryKeys, " AND ", updateField);
	if (!$res = mysql_query($sql)) die(mysql_error());
} else if($action == "g") {
	$sql .= "SELECT * FROM ".$entity." WHERE ";
	$sql .= concatenateExceptFirst($attributes, " AND ", updateField);
	if (!$res = mysql_query($sql)) die(mysql_error());
	$first = TRUE;
	echo "[";
	while($tab = mysql_fetch_array($res)) {
		echo "{\n";
		echo concatenateExceptFirst(array_keys($tab), ",\n", function($v) { 
			global $tab; 
			if (is_int($v)) 
				return FALSE;
			$r = $tab[$v]; 
			if (!$r)
				return FALSE;
			if (is_string($r))
				$r = "\"".$r."\"";
			return $v.": ".$r;
		});
		echo "}";
		if(!$first)
			echo ",\n",
		$first = FALSE;
	}
	echo "]";
}

?>