<?php
require("metacube.php");
require("parser.php");

$generator="";
$cubealg = stripslashes($_GET['algo']);
$size = $_GET['size'];
$imgsize = $_GET['imgsize'];
if(!$imgsize)
	$imgsize = 300;
$stickers = $_GET['stickers'];
if(!$stickers)
	$stickers = false;
$scheme="012345";

require("notation.php");

if($generator != 'G') $cubealg = '('.$cubealg.')\'';
$execalg .= '.'.$cubealg;

$cube = new MetaCube($size, $stickers);
//$cube = new Cube($cubepos);

$exalg = parseAlgo($execalg);
$exalg->applyAlg($cube);
$im = $cube->drawCube($scheme,$imgsize);

//On sauvegrade le resultat
$root = $_SERVER["DOCUMENT_ROOT"];
//imagepng($im,$root.$url);

//Et on affiche
header("Content-type: image/png");
imagepng($im);
imagedestroy($im);

?>