<?php
$db = mysql_connect('127.0.0.1', 'root', '');
$result = mysql_select_db('cubemethods');

function query($query, $echoquery = 0) {
	global $db;
	if($echoquery) echo $query;

	$result = mysql_query($query);
	if ($result === FALSE) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
	}
	return $result;
}
?>