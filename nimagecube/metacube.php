<?php
require_once("nxnimagecube.php");
class MetaCube {
    protected $size;
    protected $stickers;
    protected $stickersPerFace;
    protected $faceAssociation = array("F"=>0,"L"=>1, "D"=>2, "B"=>3, "R"=>4, "U"=>5);
    function MetaCube($size, $pstickers = false) {
        $this->size = $size;
        $this->cardFace = $size*$size;
        if(!$pstickers) {
            $pstickers = "";
            for($i = 0 ; $i < 6 ; $i++) {
                for($j = 0 ; $j < $this->cardFace ; $j++) 
                    $pstickers .= $i;
            }
        }
        if(strlen($pstickers) != $this->cardFace*6) exit();
        $this->stickers = $pstickers; 
    }
    public function getStickersString() {
        return $this->stickers;
    }
   private function cornerIndex($m, $k, $n) {
    	if($m == "B") {
    		$k = ($k == 0 ? 3 : ($k == 1 ? 2 : ($k == 3 ? 0 : 1)));
    	}
    	return $this->getIndex($m, $n*($k%2) + ($n+1)*$n*floor($k/2));
    }
    public function getCornerCycles() {
    	$t = array("L", "B", "R", "F");
    	$pos = array(0, 1, 3, 2);
    	$c = array(
			    "513" => 0,
			    "534" => 1,
			    "540" => 2,
			    "501" => 3,
			    "231" => 4,
			    "243" => 5,
			    "204" => 6,
			    "210" => 7);
    	$permutation = "";
    	$orientation = "";
    	$a = array();
    	$n = $this->size-1;
    	for($i = 0 ; $i < 8 ; $i++) {
    		if($i < 4) {
	    		$tmp = array(
	    				$this->cornerIndex("U",$pos[$i],$n),
	    				$this->cornerIndex($t[$i],0,$n),
	    				$this->cornerIndex($t[($i+1)%4],1,$n));
    		} else {
    			$j = $i-4;
	    		$tmp = array(
	    				$this->cornerIndex("D",$pos[3-$j],$n),
	    				$this->cornerIndex($t[($j+1)%4],3,$n),
	    				$this->cornerIndex($t[($j)%4],2,$n));
    		}
    		$str = $this->stickers{$tmp[0]}.
    							$this->stickers{$tmp[1]}.
    							$this->stickers{$tmp[2]};
    		$orient = 0;
    		while($str{0} != 2 && $str{0} != 5 && $orient < 3) {
    			$orient++;
    			$str = substr($str, 1).$str{0};
    		}
    		$a[$str] = $i;
    		$permutation .= $c[$str];
    		$orientation .= $orient;
    	}
    	return array($orientation, $permutation);
    }
    
    // Get the i-th sticker of the face 
    private function getIndex($face, $i) {
        return $this->faceAssociation[$face]*$this->cardFace+$i;
    }
    // Get the string that represents a face
    public function getFaceString($face) {
        $index = $this->cardFace * $this->faceAssociation[$face{0}];
        return substr($this->stickers, $index, $this->cardFace);
    } 
    // Call the NxNImageCube primitive
    function drawCube($scheme = '012345', $size = 500){
        $scale=2;
        $pstickers = "";
        $pstickers.= $this->getFaceString("U");
        $pstickers.= $this->getFaceString("F");
        $pstickers.= $this->getFaceString("R");
        $colors = array("0"=>"b", "1"=>"o", "2"=>"w", "3"=>"g", "4"=>"r", "5"=>"y");
        for($i = 0 ; $i < 6 ; $i++)
            $pstickers = str_replace($i,$colors[$scheme{$i}], $pstickers);
        return drawNxNImageCube($size, $this->size, $scale, 1, $pstickers);
    }
    private function rotateStickers($faces, $index) { 
        $n = count($faces);
        $tmp = $this->stickers{$this->getIndex($faces[$n-1],$index)};
        
        for($i = $n-1 ; $i > 0 ; $i--)    
            $this->stickers{$this->getIndex($faces[$i],$index)} = $this->stickers{$this->getIndex($faces[$i-1],$index)};
    
        $this->stickers{$this->getIndex($faces[0],$index)} = $tmp;
    }
    // Switch coord inside a face with circles indexed by index
    private function changeCoord($x,$y,$circle,$index) {
        return $index+$circle+($y+$circle)*$this->size+$x;
    }
    private function rotateFace($F, $cw=true) {
        // This rotation is done circle by circle
        // Firstly the outer circle, the easiest    
        // Let i the position of the stickers on the first row (< n-1) 
        // Here is the permutation : (i,0) -> (n-1,i) -> (n-i-1, n-1) -> (0,n-i-1)
        // Then for the next circle we do exactly the same permutation with 
        //   a coordinate change and n -= 2
        //   ... iterate till n <= 1
        
        $index = $this->getIndex($F,0); // Base of the face
        $n = $this->size; 
        $c = 0;
        while($n > 1) {
            for($i = 0 ; $i < $n-1 ; $i++) {
                if($cw) {
                    $permut = array(
                        $this->changeCoord($i,0,$c,$index), 
                        $this->changeCoord(0,$n-$i-1,$c,$index),
                        $this->changeCoord($n-$i-1,$n-1,$c,$index),
                        $this->changeCoord($n-1,$i,$c,$index));
                } else {
                    $permut = array(
                        $this->changeCoord($i,0,$c,$index), 
                        $this->changeCoord($n-1,$i,$c,$index),
                        $this->changeCoord($n-$i-1,$n-1,$c,$index),
                        $this->changeCoord(0,$n-$i-1,$c,$index));
                }
                $tmp = $this->stickers{$permut[0]};
                for($j = 0 ; $j < 3 ; $j++)    
                    $this->stickers{$permut[$j]} = $this->stickers{$permut[$j+1]};
                $this->stickers{$permut[3]} = $tmp;
            }
            $c++; $n -= 2;
        }
    }
    // Performs a MkR
    // Col is the column on which it performs the move
    function rotateMR($k=1) {
        $path = array("F", "U", "B", "D");
        $col = $this->size-1-$k;
        // F -> U -> B -> D
        for($i = 0 ; $i < $this->size ; $i++) {
            $this->rotateStickers($path, $i*$this->size+$col);
        }
    }
    // Performs a R
    function rotateR() {
        $this->rotateMR(0);
        $this->rotateFace("R");
    }
    // Performs a x
    function rotateX() {
        $path = array("F", "U", "B", "D");
        for($i = 0 ; $i < $this->cardFace ; $i++) {
            $this->rotateStickers($path, $i);
        }
        $this->rotateFace("L",false);
        $this->rotateFace("R");
    }
    // Performs a y
    function rotateY() {
        $path = array("F", "L", "B", "R");
        for($i = 0 ; $i < $this->cardFace ; $i++) {
            $this->rotateStickers($path, $i);
        }
        $this->rotateFace("D",false);
        $this->rotateFace("U");
        // The representation of the B face is upside-down compared to F
        // We must fix everything 
        $this->rotateFace("R");
        $this->rotateFace("R");
        $this->rotateFace("B");
        $this->rotateFace("B");
    }
}
?>