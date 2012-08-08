<?php
require("metacube.php");
require("parser.php");

$size = 3;
require("notation.php");
$cube = new MetaCube(3, false);

$exalg = parseAlgo($argv[1]);
$exalg->applyAlg($cube);

function searchCycle($search, $arr, $mirrorsearch) {
	$n = strlen($search);
	$cycle = 0;
    while(!array_key_exists($search,$arr) && $cycle < $n) {
    	$cycle++;
    	$search = $search{$n-1}.substr($search, 0, $n-1);
    	$mirrorsearch = $mirrorsearch{$n-1}.substr($mirrorsearch, 0, $n-1);
    }
    return array($arr[$search],$cycle,$mirrorsearch);
}
function searchPerm($search, $arr) {
	$n = strlen($search);
	$cycle = 0;
    while(!array_key_exists($search,$arr) && $cycle < $n) {
    	$cycle++;
    	for($i = 0 ; $i < $n ; $i++)
    		$search{$i} = ((intval($search{$i})+3)%4);
    }
    return array($arr[$search],$cycle);
}

$oll = array(
	"1101" => "a",
	"0201" => "l",
	"1200" => "t",
	"1122" => "p",
	"2121" => "h",
	"2100" => "u",
	"0000" => "n",
	"2220" => "s",
);
$pll = array(
	"0123" => 0,
	"0321" => 1,
	"0213" => 2,
	"0132" => 3,
	"3120" => 4,
	"1023" => 5,
);

$cycles = $cube->getCornerCycles();
$permut = substr($cycles[1],0,4);
$orient = substr($cycles[0],0,4);
$orient = searchCycle($orient, $oll, $permut);
$permut = searchPerm($orient[2], $pll);
print($orient[0]." ".$orient[1]." ".$permut[0]." ".$permut[1]."\n");
?>