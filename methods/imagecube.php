<?php 
$stateId = $_GET['state'];
$changesId = $_GET['change'];
$drawSetup = $_GET['drawSetup'];
$IMGSIZE = 300;
$scheme="012345";
$size = 3;
$cubealg = stripslashes($_GET['algo']);

$NIMAGECUBEPATH = "../nimagecube/";
require($NIMAGECUBEPATH."metacube.php");
require($NIMAGECUBEPATH."parser.php");
require($NIMAGECUBEPATH."notation.php");


// Initialize stickers
$stickers = "";
for($i = 0 ; $i < 6 ; $i++) {
    for($j = 0 ; $j < 3*3 ; $j++) 
        $stickers .= "x";
}

/**
 *
 * Corners
 * 1 - UFL FLU LUF
 * 2 - UBL BLU LUB
 * 3 - UBR BRU RUB
 * 4 - UFR FRU RUF
 * 5 - DFL FLD LDF
 * 6 - DBL BLD LDB
 * 7 - DBR BRD RDB
 * 8 - DFR FRD RDF
 *
 * Centers
 * U - CU
 * V - CF
 * W - CR
 * X - CB
 * Y - CL
 * Z - CD
 *
 * Edges
 * A - UF FU
 * B - UL LU
 * C - UB BU
 * D - UR RU
 * E - FL LF
 * F - BL LB
 * G - BR RB
 * H - FR RF
 * I - DF FD
 * J - DL LD
 * K - DB BD
 * L - DR RD
 *
 * Recall : "F"=>0,"L"=>1, "D"=>2, "B"=>3, "R"=>4, "U"=>5
 */
$cubieStickers = array(
	"1" => array(5*9+6, 0*9+0, 1*9+2),
	"2" => array(5*9+0, 3*9+6, 1*9+0),
	"3" => array(5*9+2, 3*9+8, 4*9+2),
	"4" => array(5*9+8, 0*9+2, 4*9+0),
	"5" => array(2*9+0, 0*9+6, 1*9+8),
	"6" => array(2*9+6, 3*9+0, 1*9+6),
	"7" => array(2*9+8, 3*9+2, 4*9+8),
	"8" => array(2*9+2, 0*9+8, 4*9+6),
	"U" => array(0*9+4),
	"V" => array(1*9+4),
	"W" => array(2*9+4),
	"X" => array(3*9+4),
	"Y" => array(4*9+4),
	"Z" => array(5*9+4),
	"A" => array(5*9+7, 0*9+1),
	"B" => array(5*9+3, 1*9+1),
	"C" => array(5*9+1, 3*9+7),
	"D" => array(5*9+5, 4*9+1),
	"E" => array(0*9+3, 1*9+5),
	"F" => array(3*9+3, 1*9+3),
	"G" => array(3*9+5, 4*9+5),
	"H" => array(0*9+5, 4*9+3),
	"I" => array(2*9+1, 0*9+7),
	"J" => array(2*9+3, 1*9+7),
	"K" => array(2*9+7, 3*9+1),
	"L" => array(2*9+5, 4*9+7)
);

$drawcubies = "";

include("connect.php");

$query = "SELECT setup, displayOrientFaces FROM cubestate WHERE id = '".$stateId."'";
$res = mysql_query($query);

//$res = query($query);
$state = mysql_fetch_array($res);

$query = "SELECT * FROM cubiestate WHERE id = '".$stateId."'";
$res = mysql_query($query);

while($row = mysql_fetch_array($res)) {
	$sIndex = $cubieStickers[$row['cubie']];
	if($row['placed'] && $row['oriented']) {
		foreach($sIndex as $index) {
			$stickers{$index} = floor($index/9);
		}
	} else if($row['placed']) {
		for($i = 0 ; $i < count($sIndex) ; $i++) {
			$stickers{$sIndex[$i]} = floor($sIndex[($i+1)%count($sIndex)]/9);
		}
	} else if($row['oriented']) {
		if($state['displayOrientFaces']) {
			if(floor($sIndex[0]/9) == 5)
				$stickers{$sIndex[0]} = floor($sIndex[0]/9);
			else
				$stickers{$sIndex[0]} = "X";
			for($i = 1 ; $i < count($sIndex) ; $i++) {
				$stickers{$sIndex[$i]} = "X";
			}
		} else {
			foreach($sIndex as $index) {
				$stickers{$index} = "X";
			}
		}
	}
}

mysql_close($db);

$drawcubies = "";

// Draw the cubies
for($i = 0 ; $i < strlen($drawcubies) ; $i++) {
	$sIndex = $cubieStickers[$drawcubies{$i}];
	foreach($sIndex as $index) {
		$stickers{$index} = floor($index/9);
	}
}

$cube = new MetaCube($size, $stickers);

if($cubealg) {
	$exalg = parseAlgo($cubealg);
	$exalg->applyAlg($cube);
}
if($drawSetup && $state['setup']) {
	$execalg = "(".$state['setup'].")'";
	$exalg = parseAlgo($execalg);
	$exalg->applyAlg($cube);
}
$im = $cube->drawCube($scheme,$IMGSIZE);

header("Content-type: image/png");
imagepng($im);
imagedestroy($im);


?>