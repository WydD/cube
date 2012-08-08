<?php
$size = 64;
$perm = preg_split('/\|/', $_GET['perm']);
function line($im, $x1, $y1, $x2, $y2, $thick, $color) {
	$h = 0;
	if($x2 != $x1) {
		$d = abs(($y2-$y1)/($x2-$x1));
		$h = $d >= 1 ? 0 : 1;
		if($d >= 0.8 && $d <= 1.25) $thick *= 1.5;
	}
	for($i = -$thick/2 ; $i < $thick/2 ; $i++) {
		if($h > 0) 
			imageline($im, $x1, $y1+$i, $x2, $y2+$i, $color);
		else 
			imageline($im, $x1+$i, $y1, $x2+$i, $y2, $color);
	}
}

function arrow($im, $x1, $y1, $x2, $y2, $alength, $awidth, $color, $color2) {
    $distance = sqrt(pow($x1 - $x2, 2) + pow($y1 - $y2, 2));
	if($distance == 0) return;
    $dx = $x2 + ($x1 - $x2) * $alength / $distance;
    $dy = $y2 + ($y1 - $y2) * $alength / $distance;

    $k = $awidth / $alength;

    $x2o = $x2 - $dx;
    $y2o = $dy - $y2;

    $x3 = $y2o * $k + $dx;
    $y3 = $x2o * $k + $dy;

    $x4 = $dx - $y2o * $k;
    $y4 = $dy - $x2o * $k;


    line($im, $x1, $y1, $x2, $y2, 2, $color);
imagesetthickness($im, 1);
    imagefilledpolygon($im, array($x2, $y2, $x3, $y3, $x4, $y4), 3, $color2);
    imagepolygon($im, array($x2, $y2, $x3, $y3, $x4, $y4), 3, $color);
}

$img = imagecreatetruecolor($size, $size);
imageantialias($img, true);

$back = imagecolorallocate($img, 220, 220, 220);
imagefill($img, 0, 0, $back);

$COLOR_LINE = imagecolorallocate($img, 0, 0, 0);

imagesetthickness($img, 4);
imagerectangle($img, 0, 1, $size-1, $size-2, $COLOR_LINE);
imagesetthickness($img, 1);
$r1 = 1/2.73203;
$r2 = 1.73203/2.73203;

line($img, 0, $size*$r1, $size, $size*$r2, 2, $COLOR_LINE);
line($img, 0, $size*$r2, $size, $size*$r1, 2, $COLOR_LINE);
line($img, $size*$r1+1, 0, $size*$r2, $size, 2, $COLOR_LINE);
line($img, $size*$r2+1, 0, $size*$r1, $size, 2, $COLOR_LINE);

$p = array(1/2, 8/9, 1/9, 1/2, 1/2, 1/9, 8/9, 1/2);

foreach($perm as $draw) {
	$draw = preg_split('/,/', $draw);
	for($i = 0 ; $i < count($draw) ; $i++) {
		$j = ($draw[$i]-1)*2;
		$c1 = round($p[$j]*$size);
		$c2 = round($p[$j+1]*$size);
		$j = ($draw[($i+1)%count($draw)]-1)*2;
		$c3 = round($p[$j]*$size);
		$c4 = round($p[$j+1]*$size);
		
		arrow($img, $c1, $c2, $c3, $c4, 8, 3, $COLOR_LINE, $COLOR_LINE);
	}
}

header('Content-type: image/png');
imagepng($img);
imagedestroy($img);
?>