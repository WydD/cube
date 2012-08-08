<?php
include("connect.php");
include("db/schema.php");

function getPost($str) {
	global $_POST, $_GET;
	if(isset($_POST[$str])) {
		return "'".addslashes($_POST[$str])."'";
	}
	if(isset($_GET[$str])) {
		return "'".addslashes($_GET[$str])."'";
	}
	return "DEFAULT";
}

function concatenateExceptFirst($arr, $askedsep, $mapFunction = FALSE) {
	$l = count($arr);
	$res = "";
	$sep = "";
	for($i = 0 ; $i < $l ; $i++) {
		if($mapFunction) {
			$v = $mapFunction($arr[$i]);
			if($v === FALSE)
				continue;
			$res .= $sep.$mapFunction($arr[$i]);
		} else 
			$res .= $sep.$arr[$i];
		$sep = $askedsep;
	}
	return $res;
}

function updateField($name) {
	$str = getPost($name);
	if($str == "DEFAULT")
		return FALSE;
	return $name." = ".$str;
}
?>