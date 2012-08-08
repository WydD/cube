<?php
require("../square/square1.php");

class GraphNode {
	public $content;
	public $nodes, $labels;
	
	public function __construct($content) {
		$this->content = $content;
		$this->nodes = Array();
		$this->labels = Array();
	}
		
	public function lookFor($other, $level) {
		if(level < 0) return FALSE;
		$equiv = $this->content->equivalent($other);
		if($equiv !== FALSE) {
			if($level == 0)
				return Array($this, $equiv);
			return 0;
		}
		$found = 0;
		foreach($this->nodes as $node) {
			$res = $node->lookFor($other, $level-1);
			if($res)
				return $res;
			if($res === 0)
				$found = 1;
		}
		return $found > 0 ? 0 : FALSE;
	}
	
	public function alreadyLinked($content) {
		foreach($this->nodes as $node) {
		 	if($node->content->equivalent($content))
		 		return true;
		}
		return false;
	}
	
	public function link($node, $label) {
		$this->nodes[] = $node;
		$this->labels[] = $label;
	}
	
	public function countNode() {
		return count($this->nodes)+1;
	}
	
}



function iterateNode($node, $level) {
	global $tree;
	$square = $node->content->copy();
	$str = $square->toString();
	for($down = 0 ; $down < 12 ; $down++) {
		for($up = 0 ; $up < 12 ; $up++) {
//			if($str == "SquareOne(ecCcCeecCecC ; cCecCecCeecC)")
//			echo "(".$up." ; ".$down.") ";
			if(!$square->flip()) {	
				$square->rotate(1,0);
//			if($str == "SquareOne(ecCcCeecCecC ; cCecCecCeecC)")
				//echo "Bugé !!!!<br/>";
				continue;
			}
			if($node->alreadyLinked($square) || ($seek = $tree->lookFor($square, $level)) === 0) { // Found but not at the right level so we can just ignore it
				$square->flip();
				$square->rotate(1,0);
				
//			if($str == "SquareOne(ecCcCeecCecC ; cCecCecCeecC)")
				//echo "Found<br/>";
				continue;
			}
			if($seek === FALSE) { // Position not found at all
				$seek = Array(new GraphNode($square->copy()), Array(0,0));
				//echo "Building ".$square->toString()."<br/>";
			}
			
			$resUp = $up;
			$resDown = $down;
    		if($resUp > 6) $resUp = -12+$resUp;
			if($resDown > 6) $resDown = -12+$resDown;
			$node->link($seek[0], Array(Array($resUp, $resDown), $seek[1]));
			$square->flip();	
			$square->rotate(1,0);
		}
		$square->rotate(0,1);
	}
	return $node->nodes;
}

function iterateLevel($nodes, $level) {
	$nextLevel = Array();
	foreach ($nodes as $node) {
		$nextLevel = array_merge($nextLevel, iterateNode($node, $level));
	}
	return array_unique($nextLevel, SORT_REGULAR);
}
/*

$square = new SquareOne();
$tree = new GraphNode($square);

$nextLevel = Array($tree);
for($level = 0 ; $level < 8 ; $level++) {
	$nextLevel = iterateLevel($nextLevel, $level+1);
	echo "Level ".$level." found ".count($nextLevel)." new case(s)<br/>";
	
}

$fp = fopen("graph.dat", 'w');
fwrite($fp, serialize($tree));
fclose($fp);
//

$handle = fopen("graph.dat", "r");
$contents = fread($handle, filesize("graph.dat"));
fclose($handle);
$tree = unserialize($contents);
//*/

/*
$leafCount = 0;
$linkCount = 0;
$nodeSeen = Array();
$reverseTree = Array();
function searchLeaf($node, $level) {
	global $nodeSeen, $linkCount, $reverseTree;
	$str = $node->content->toString();
	if(isset($nodeSeen[$str])) {
		return $str;
	}
	$nodeSeen[$str] = new GraphNode($node->content);
	if(count($node->nodes) == 0) {
		//echo $str." : ".$level."<br/>";
		$reverseTree[] = $nodeSeen[$str];
	}
	
	foreach($node->nodes as $key => $child) {
		$linkCount++;
		$childStr = searchLeaf($child, $level+1);
		$label = Array(Array(-$node->labels[$key][0][0], -$node->labels[$key][0][1]), Array(-$node->labels[$key][1][0], -$node->labels[$key][1][1]));
		$nodeSeen[$childStr]->link($nodeSeen[$str], $label);
		if($childStr == "SquareOne(ecCcCecCeecC ; ecCecCecCecC)")
		  echo "Youki";
	}
	return $str;
}
searchLeaf($tree,0);
//echo "Leafs found: ".count($reverseTree)." nodes: ".count($nodeSeen).", links: ".$linkCount."<br/>";

$fp = fopen("reverse.dat", 'w');
fwrite($fp, serialize(Array($reverseTree, $nodeSeen)));
fclose($fp);
/*/
$handle = fopen("reverse.dat", "r");
$contents = fread($handle, filesize("reverse.dat"));
fclose($handle);
$contents = unserialize($contents);
$reverseTree = $contents[0];
$nodeSeen = $contents[1];
//*/

$searchStates = Array();
function findAllNodes($node, $level = 0) {
	global $searchStates;
	$searchStates[$level][$node->content->toString()] = $node;
	foreach($node->nodes as $child) {
		findAllNodes($child, $level+1);
	}
}



function drawGraph($size, $maxStates, $levels) {
	global $searchStates;
	$width = $size*3*$levels-$size+15;
	$height = $size*1.5*$maxStates+5;
	
	$img = imagecreatetruecolor($width, $height);
	imagesavealpha($img, true);
	imageantialias($img, true);
	
	$back = imagecolorallocatealpha($img, 255, 255, 255, 127);
	imagefill($img, 0, 0, $back);
	$COLOR_LINE = imagecolorallocate($img, 0, 0, 0);
	$COLOR_RECT = imagecolorallocate($img, 204, 204, 230);
	$COLOR_FILL = imagecolorallocate($img, 180, 180, 180);


	$x = $size/2+5;
	$idForStates = Array();
	for($i = 0 ; $i < $levels ; $i++) {
		$spaceY = $height / (count($searchStates[$i]));
		$y = $spaceY/2+5;
		$j = 0;
		foreach($searchStates[$i] as $key => $states) {
			imagefilledrectangle($img, $x-$size/2-5, $y-$size/2-5,$x+$size*3/2+5, $y+$size/2+5, $COLOR_RECT);
			imagerectangle($img, $x-$size/2-5, $y-$size/2-5,$x+$size*3/2+5, $y+$size/2+5, $COLOR_LINE);
			$states->content->draw($img, $x, $y, $size, $COLOR_LINE, $COLOR_FILL, 1);
			$states->content->draw($img, $x+$size, $y, $size, $COLOR_LINE, $COLOR_FILL, 0);
			$y += $spaceY;
			$idForStates[$i][$states->content->toString()] = $j++;
        }
        $x += $size*3;
	}
	
	
	$x = $size*2+5;
	for($i = 0 ; $i < $levels ; $i++) {
		$spaceY = $height / (count($searchStates[$i]));
		if($searchStates[$i+1])
		$spaceYNext = $height / (count($searchStates[$i+1]));
		$y = $spaceY/2+5;
		$j = 0;
		foreach($searchStates[$i] as $states) {
			foreach($states->nodes as $key => $node)
				imageline($img, $x+5, $y, $x+$size-5, 5+$spaceYNext/2+$spaceYNext*$idForStates[$i+1][$node->content->toString()], $COLOR_LINE);
			$y += $spaceY;
        }
        $x += $size*3;
	}
	if($levels > 1)
		ImageString($img, 5, 0, 0, "Depth: ".($levels-1), $COLOR_LINE);
	imagepng($img);
	imagedestroy($img);
}

$size = 68;


$search = new SquareOne($_GET['up'],$_GET['down']);
header("Content-type: image/png");
foreach($nodeSeen as $leaf) {
    if($res = $search->equivalent($leaf->content)) {
        findAllNodes($leaf);
        //echo "Found the state, number of flips: ".(count($searchStates)-1)."<br/>";
        $max = 0;
        foreach($searchStates as $states) {
			if($max < count($states)) $max = count($states);
        }
        
        drawGraph($size, $max, count($searchStates));
        $search = 0;
        break;
    }
}

if($search) {
	$img = imagecreatetruecolor(200, 20);
	
	imagesavealpha($img, true);
	imageantialias($img, true);
	
	$back = imagecolorallocatealpha($img, 255, 255, 255, 127);
	$COLOR_LINE = imagecolorallocate($img, 0, 0, 0);
	imagefill($img, 0, 0, $back);
	imagestring($img, 5, 0, 0, "Wrong Square-1 State", $COLOR_LINE);
	imagepng($img);
	imagedestroy($img);
}
?>