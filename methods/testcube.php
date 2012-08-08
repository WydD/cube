<?php
// Cubie : 0 = NA, 1 oriented, 2 placed, 3 solved 


function Cube() {
	$cube = Array();
	// Corners
	for($i = 1 ; $i <= 8 ; $i++)
		$cube[$i] = 0;
	// Edges
	for($i = 0 ; $i < 12 ; $i++)
		$cube[chr(65+$i)] = 0;
	// Centers
	for($i = 0 ; $i < 12 ; $i++)
		$cube[chr(65+18+$i)] = 3;
}

$rlSwap = Array("B" => "D", "E" => "H", "F" => "G", "J" => "L", 1 => 4, 2 => 3, 5 => 8, 6 => 7);
$fbSwap = Array("A" => "C", "E" => "F", "G" => "H", "I" => "K", 1 => 2, 3 => 4, 5 => 6, 7 => 8);

function symMap($map, $rl) {
	global $rlSwap, $fbSwap;
	$swap = $rl > 0 ? $rlSwap : $fbSwap;
	$res = Array();
	foreach($swap as $k => $v) {
		if(array_key_exists($v, $map))
			$res[$k] = $map[$v];
		if(array_key_exists($k, $map))
			$res[$v] = $map[$k];
	}
	return $res;
}
abstract class Step {
	var $requires;
	var $name;
	var $description;
	abstract function apply(&$cube);
}

class SequenceStep extends Step {
	var $name;
	var $steps;
	var $description;
	function SequenceStep($name, $steps, $description) {
		$this->name = $name;
		$this->requires = $steps[0]->requires;
		$this->steps = $steps;
		$this->description = $description;
	}
	function apply(&$cube) {
		$res = 0;
		foreach($steps as $step) {
			$r = $step->apply($cube);
			if($r <= 0) return $r;
			$res += $r;
		}
		return $res;
	}
}

class AtomicStep extends Step {
/*	var $requires;
	var $changes;
	var $name;
	var $description; */
	var $changes;
	function AtomicStep($name, $requires, $changes, $description) {
		$this->name = $name;
		$this->requires = $requires;
		$this->changes = $changes;
		$this->description = $description;
	}
	function apply(&$cube) {
		foreach($this->requires as $key => $req) {
			if(($cube[$key] & $req) == 0)
				return -1;
		}
		$nothing = 1;
		foreach($this->changes as $key => $change) {
			if(($cube[$key] & $change) == 0) {
				$nothing = 0;
			}
			$cube[$key] = $cube[$key] | $change;
		}
		if($nothing > 0) return 0; // Nothing was changed !!
		return 1;
	}
	function symetry($rl) {
		return new AtomicStep($this->name, symMap($this->requires, $rl), symMap($this->changes, $rl), $description);
	}
}

class SequenceSet {
	var $size;
	var $name;
	var $parentSet;
}

$cross = new AtomicStep("Cross", Array(), Array('I' => 3, 'J' => 3, 'K' => 3, 'L' => 3), "Apply a cross");
$setup = new AtomicStep("Setup", Array("S" => 1), Array("S" => FALSE), "Apply the prepared setup");
$f2lp = new AtomicStep("Prepare F2L-pair", $cross->changes, Array('H' => 3, 8 => 3, "E" => 4, "F" => 4, "G" => 4, 5 => 4, 6 => 4, 7 => 4, "S" => "RU'R'"), "Prepares a pair edge-corner in order to insert it in the slot");
$f2lo = new AtomicStep("Prepare F2L-opposite", $cross->changes, Array('H' => 3, 8 => 3, "E" => 4, "F" => 4, "G" => 4, 5 => 4, 6 => 4, 7 => 4, "S" => "RUR'"), "Prepares an opposite pair edge-corner in order to insert it in the slot");
$f2l = new SequenceStep("F2L as pair");

$f2l2 = $f2l1->symetry(1);
$f2l3 = $f2l2->symetry(0);
$f2l4 = $f2l1->symetry(0);
$cube = Cube();
function apply($step) {
	global $cube;
	echo "Applying ".$step->name.", result: ".$step->apply($cube)."\n";
}

apply($cross);
apply($f2l1);
apply($f2l2);
apply($f2l3);
apply($f2l3);
apply($f2l4);
?>