<?php
$size = 68;
$str = $_GET['sequence'];
$start = intval($_GET['end']);
$end = intval($_GET['start']);
require("square1.php");
header('Content-type: image/png');
$movStr = explode("/",$str);
$final = array();
for($i = 0 ; $i < count($movStr) ; $i++) {
    if($movStr[$i] == "") {
        $final[$i] = array(0,0);
        continue;
    }
    $nums = array();
    preg_match_all("/-?\d+/",$movStr[$i],$nums);
    $final[$i] = $nums[0];
}
function CenterImageString($offset, $image, $image_width, $string, $font_size, $y, $color)
 {
 $text_width = imagefontwidth($font_size)*strlen($string);
 $center = ceil($image_width / 2);
 $x = $offset + $center - (ceil($text_width/2));
 ImageString($image, $font_size, $x, $y, $string, $color);
 } 

 
if(!$start) $start = count($final)-1;
if(empty($end)) $end = 0;
$img = imagecreatetruecolor($size+($start-$end)*($size+5), $size*2+12);
imagesavealpha($img, true);
imageantialias($img, true);

$back = imagecolorallocatealpha($img, 255, 255, 255, 127);
imagefill($img, 0, 0, $back);
$COLOR_LINE = imagecolorallocate($img, 0, 0, 0);
$COLOR_FILL = imagecolorallocate($img, 180, 180, 180);

$sq1 = new SquareOne();
for($i = count($final)-1 ; $i >= $end ; $i--) {
    if($start >= $i) {
        $sq1->draw($img, ($size+5)*($i-$end)+$size/2, $size/2, $size, $COLOR_LINE, $COLOR_FILL, 1);
        $sq1->draw($img, ($size+5)*($i-$end)+$size/2, $size/2+$size+12, $size, $COLOR_LINE, $COLOR_FILL, 0);
    
        if($final[$i][0] != 0 || $final[$i][1] != 0) 
            CenterImageString(($size+5)*($i-$end),$img, $size, "(".$final[$i][0].",".$final[$i][1].")", 2, $size, $COLOR_LINE);
    }
    $sq1->rotate(-$final[$i][0], -$final[$i][1]);
    if($i > 0) {
        if(!$sq1->flip()) break;
        if($start < $i) continue;
        $base = ($size+5)*($i-$end)-6;
        ImageString($img, 5, $base, $size, "/", $COLOR_LINE);
    }
}
imagepng($img);
imagedestroy($img);
?>