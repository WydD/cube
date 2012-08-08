<?php

class SquareOne {
    private $contentDown, $contentUp;
    
    //draw parameters
    private $centerx, $centery;
    private $size;
    public function __construct($contentUp = "cCecCecCecCe", $contentDown = "ecCecCecCecC") {
        $this->contentUp = $contentUp;
        $this->contentDown = $contentDown;
    }
    
    public function flip() {
        if($this->contentUp{11} == 'c' ||
           $this->contentUp{5} == 'c' ||
           $this->contentDown{11} == 'c' ||
           $this->contentDown{5} == 'c') 
            return 0;
        
        $newDown = substr($this->contentUp, 0, 6).substr($this->contentDown, 6, 6);
        $newUp = substr($this->contentDown, 0, 6).substr($this->contentUp, 6, 6);
        $this->contentUp = $newUp;
        $this->contentDown = $newDown;
        return 1;
    }
    
    public function equivalent($other) {
    	$up = strpos($other->contentUp.$other->contentUp, $this->contentUp);
    	$down = strpos($other->contentDown.$other->contentDown, $this->contentDown);
    	if($up === FALSE || $down === FALSE) { // Try symetric
    		$up = strpos($other->contentUp.$other->contentUp, $this->contentDown);
    		$down = strpos($other->contentDown.$other->contentDown, $this->contentUp);
    	}
    	
    	if($up !== FALSE && $down !== FALSE) {
    		if($up > 6) $up = -12+$up;
			if($down > 6) $down = -12+$down;
    		return Array($up,$down);
    	}
    	return FALSE;
    }
    
    public function copy() {
        return new SquareOne($this->contentUp, $this->contentDown);
    }
    
    public function rotate($up, $down) {
    $back = $this->contentUp;
        $this->contentUp = substr($this->contentUp.$this->contentUp.$this->contentUp, 12-$up, 12);
        $this->contentDown = substr($this->contentDown.$this->contentDown.$this->contentDown, 12-$down, 12);
//        echo $this->contentUp."(".$up.")/".$back."!";
    }
    
    public function draw($img, $centerx, $centery, $size, $colorLine, $colorFill, $up) {
        $this->centerx = $centerx;
        $this->centery = $centery;
        $this->colorLine = $colorLine;
        $this->colorFill = $colorFill;
        $this->size = $size;
                
        $angle = 0;
        $str = $this->contentUp;
        if(!$up)
            $str = $this->contentDown;
        for($i = 0 ; $i < 12 ; $i++) {
            if($str{$i} == 'e')
                $this->drawEdge($img, $i*30);
            else if($str{$i} == 'c') 
                $this->drawCorner($img, $i*30);
        }
    }
    private function polar2xy($deg, $r) {
        $x = $this->centerx + $r * cos(deg2rad($deg-90));
        $y = $this->centery + $r * sin(deg2rad($deg-90));
        return array(round($x),round($y));
    }
    
    private function drawEdge($img, $deg) {        
        $edge = $this->size/2.73203;
        $xy1 = $this->polar2xy($deg, $edge);
        $xy2 = $this->polar2xy($deg+30, $edge);
        $coords = array($this->centerx, $this->centery, $xy1[0], $xy1[1], $xy2[0], $xy2[1]);
        imagefilledpolygon($img, $coords, 3, $this->colorFill);
        imagepolygon($img, $coords, 3, $this->colorLine);
    }
    function drawCorner($img, $deg) {
        $edge = $this->size/2.73203; //2*sqrt(2)*cos(Pi/12)
        $xy1 = $this->polar2xy($deg, $edge);
        $xy2 = $this->polar2xy($deg+30, $this->size/2);
        $xy3 = $this->polar2xy($deg+60, $edge);
        $coords = array($this->centerx, $this->centery, $xy1[0], $xy1[1], $xy2[0], $xy2[1], $xy3[0], $xy3[1]);
        imagefilledpolygon($img, $coords, 4, $this->colorFill);
        imagepolygon($img, $coords, 4, $this->colorLine);
    }
    
    public function toString() {
    	return "SquareOne(".$this->contentUp." ; ".$this->contentDown.")";
    }
}

?>