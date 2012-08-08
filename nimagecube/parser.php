<?php 
require_once("executor.php"); 
/**
 * Generic implem of the parser
 * We could run a LL(1) parser but it was easiest in LALR 
 *
 * The Grammar 
 *  {ALG}   -> {SUBALG}{MOD}{ALG}
 *  {SUBALG}-> ({ALG})|{TOKEN}
 *  {TOKEN} -> R|F|U|..... given by the notation file
 *  {MOD}   -> {INT}{MOD} | '{MOD} | ε
 *
 * Unfortunately, randelshofer has some weird stuff like
 *    CUFL : Rotate the cube around the corner UFL
 * But there is also CU and F L :).
 * I fix this problem by giving priority to the largest string
 * That's why there is a krsort at the end of notation.php
 */

class CubeParser {
    private $buf;
    private $eof = false;
    function CubeParser($algo) {
        // clean everything
        $this->buf = preg_replace('/\s+/', '', $algo);
        $this->buf = str_replace('.','',$this->buf);
    }
    // My LALR primitives
    private function consume($len=1) {
        for ($i = 0; $i < $len; $i++) {
            if (strlen($this->buf) > 0)
                $this->buf = substr($this->buf,1);
            else 
                break;
        }
        $this->eof = strlen($this->buf) == 0;
    }
    private function skipChar($tok) {
        if($this->eof)  return false;
        $res = $this->buf{0} == $tok;
        if($res)
            $this->consume();
        return $res;
    }
    private function skipToken($tok) {
        if($this->eof)  return false;
        $n = strlen($tok);
        if(strlen($this->buf) < $n) return false;
        $res = substr($this->buf,0,$n) == $tok;
        if($res)
            $this->consume($n);
        return $res;
    }
    // Returns the instance of Alg that runs the algorithm
    public function parse() {
        return new SequenceAlg($this->ALG());
    }
    // The $list is the current list of algs to run
    private function ALG($list=array()) {
        if($this->eof || $this->buf{0} == ')') return $list;
        $subalg = $this->SUBALG();
        $list[] = $this->MOD($subalg);// Add the brand new one
        return $this->ALG($list);
    }
    private function SUBALG() {
        if($this->skipChar('(')) {
            $list = $this->ALG();
            if(!$this->skipChar(')'))
                throw new Exception("Il manque une parenthèse quelque part !");
            return new SequenceAlg($list);
        }
        return $this->TOKEN();
    }
    // Finds the core alg in the global array
    private function TOKEN() {   
        global $algs;
        foreach($algs as $s => $alg) {
            if($this->skipToken($s))
                return $alg;
        }
        throw new Exception("Token inconnu ".$this->buf);
    }
    // Slightly modify the core alg by adding ' or n or nothing
    private function MOD($subalg) {
        $n = $this->INT();
        if($n !== false) 
            return $this->MOD(new RepeatAlg($subalg,$n));
        if($this->skipChar("'"))
            return $this->MOD(new InvAlg($subalg));
        return $subalg;
    }
    // Parse an INT at the top of the buffer
    // returns false if no int was found
    //   * yes I COULD have used regexp *
    private function INT() {
        if($this->eof) return false;
        $res = 0;
        $i = 0;
        $c = ord($this->buf)-48;
        while($c >= 0 && $c <= 9) {
            $res = $i*$res + $c;
            $this->consume();
            $i++;
            if($this->eof)
                break;
            $c = ord($this->buf)-48;
            
        }
        return ($i==0) ? false : $res; 
    }
}
// This is a shortcut of CubeParser::parse
function parseAlgo($algo) {
    $parser = new CubeParser($algo);
    return $parser->parse($algo);
}
?>