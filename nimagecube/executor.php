<?php
// Creating a table of core algs
// Each core algorithm is instanciated once and for all
// Core = No modifier
$algs = array();

/**
 * Abstractions (largely inspired from Lars Vandenbergh and Amaury Sechet)
 */
abstract class Alg {
    abstract function applyAlg(&$cube);
    abstract function applyInv(&$cube);
    protected function getAlg($alg) {
        global $algs;
        if($alg instanceof Alg) 
            return $alg;
        else
            return $algs[$alg];
    }
}
abstract class RootAlg extends Alg {
    function applyInv(&$cube) {
        $this->applyAlg(&$cube);
        $this->applyAlg(&$cube);
        $this->applyAlg(&$cube);
    }
}

/*********************************
 * Definition of root algorithms *
 *********************************/
class AlgR extends RootAlg {
    function applyAlg(&$cube){
        $cube->rotateR();
    }
}
class AlgX extends RootAlg {
    function applyAlg(&$cube){
        $cube->rotateX();
    }
}
class AlgY extends RootAlg {
    function applyAlg(&$cube){
        $cube->rotateY();
    }
}
class AlgMkR extends RootAlg {
    private $k;
    function AlgMkR($k = 1) { $this->k = $k; }
    function applyAlg(&$cube){
        $cube->rotateMR($this->k);
    }
}
/***************************
 * Basic operation on algs *
 ***************************/
// Inverse
class InvAlg extends Alg {
    private $alg;
    function InvAlg($alg) {
        $this->alg = $this->getAlg($alg);
    }
    function applyAlg(&$cube){
        $this->alg->applyInv($cube);
    }
    
    function applyInv(&$cube){
        $this->alg->applyAlg($cube);
    }
}
// Repeat nb times the algorithm
class RepeatAlg extends Alg {
    private $alg;
    private $nb;
    function RepeatAlg($alg, $nb) {
        $this->alg = $this->getAlg($alg);
        $this->nb = $nb;
    }
    function applyAlg(&$cube){
        for($i = 0 ; $i < $this->nb ; $i++)
            $this->alg->applyAlg($cube);
    }
    
    function applyInv(&$cube){
        for($i = 0 ; $i < $this->nb ; $i++)
            $this->alg->applyInv($cube);
    }
}

// Executes the sequences
class SequenceAlg extends Alg {
    private $items;
    function SequenceAlg($items) {
        for($i = 0 ; $i < count($items) ; $i++) 
            $this->items[$i] = $this->getAlg($items[$i]);
    }
    function applyAlg(&$cube){
        for($i = 0 ; $i < count($this->items) ; $i++)
            $this->items[$i]->applyAlg($cube);
    }
    function applyInv(&$cube){
        for($i = count($this->items)-1 ; $i >= 0 ; $i--)
            $this->items[$i]->applyInv($cube);
    }
}
// Create an alias of an algorithm
class AliasAlg extends Alg {
    private $alg;
    function AliasAlg($alg) {
        $this->alg = $this->getAlg($alg);
    }
    function applyAlg(&$cube){
        $this->alg->applyAlg($cube);
    }
    
    function applyInv(&$cube){
        $this->alg->applyInv($cube);
    }
}
?>