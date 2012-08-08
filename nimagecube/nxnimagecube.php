<?php
/*
 * NxN ImageCube by Joël van Noort
 * 21th februari 2007
 *
 * A PHP script for generating static 3D views of the NxNxN Cube .
 *
 * This script is inspired by Imagecube and Imagerevenge, by Lars Vandenbergh.
 */

//This function makes lines between 4 points. Use this function twice can form a grid on one face.
function makelines(&$image, $n, $col_poly, $scalepx, $x1, $y1, $x2,$y2, $x3,$y3, $x4, $y4){
	$vector_x = ($x3-$x1);	$vector_y = ($y3-$y1);
	$vector_a = ($x4-$x2);	$vector_b = ($y4-$y2);
	
	for($i=1;$i < $n; $i++)
		imageline($image,($x1+$vector_x*($i/$n))*$scalepx,($y1+$vector_y*($i/$n))*$scalepx,($x2+$vector_a*($i/$n))*$scalepx,($y2+$vector_b*($i/$n))*$scalepx,$col_poly);
}

//This function calculates the coordinates of the stickers of one face:
function calculate_coordinates(&$datax,&$datay,$n, $x1,$y1,$x2,$y2,$x3,$y3,$x4,$y4) {
	//The calculation will be based on vectors. Calculating the first two here. 
	//These vectors will help us to locate the stickers:
	$vector_Vx = ($x3-$x1);	$vector_Vy = ($y3-$y1);
	$vector_Wx = ($x4-$x2);	$vector_Wy = ($y4-$y2);

	$number=0;

	for($j=0; $j <$n+1; $j++){

	    //Making the 3rd vector... This one changes all the time...:
		$local_x1 = $x1 + $vector_Vx*($j/$n);
		$local_y1 = $y1 + $vector_Vy*($j/$n);
		$local_x2 = $x2 + $vector_Wx*($j/$n);
		$local_y2 = $y2 + $vector_Wy*($j/$n);
		$directionx = $local_x2 - $local_x1;
		$directiony = $local_y2 - $local_y1;

		for($i=0; $i < $n+1; $i++){
			$datax[$number] = $local_x1 + ($directionx)*($i/$n)  ;
			$datay[$number] = $local_y1 + ($directiony)*($i/$n)  ;
			$number++;
		}
	}
}

//This function fills up the coordinates of one face. We will use the result of the previous function to do this.
//To locate the center of a facelet, I use 2 coordinates, and go to the 'middle'...
function fill_up(&$im,$n, $scalepx, $datax, $datay, $colors, $stickers){

	$colorcount=0;
	$count=0;

	while($count < $n*($n+1) ){

		$xx = ($datax[$count] + $datax[$count+$n+2])/2;
		$yy = ($datay[$count] + $datay[$count+$n+2])/2;
	       if( ($count+1)%($n+1) != 0){
			imagefill($im,$scalepx*$xx, $scalepx*$yy, $colors[substr($stickers,$colorcount,1)]);

			$colorcount++;
		}
		$count++;
	}
}

function drawNxNImageCube($size, $n, $scale, $thickness,$stickers) {
    $size=intval($size);
    $n=intval($n);
    $scale=intval($scale);
    $thickness=intval($thickness);
    
    if(!$size)	
        $size=300;
    if($size>750)	
        $size=750;
    if($size<10)	
        $size=10;
    
    if(!$n)		
        $n=3;
    if($n>25)	
        $n=25;
    if($n<1)	
        $n=1;
    
    if(!$scale)		
        $scale=3;
    if($scale>4)	
        $scale=4;
    if($scale<1)	
        $scale=1;
    
    if(!$thickness)		
        $thickness=1;
    if($thickness>3)	
        $thickness=3;
    if($thickness<1)	
        $thickness=1;
    
    if(!$stickers){
        
        for($i = 1; $i<=$n*$n;$i++){
            $stickers .= "y";
        }
        for($i = 1; $i<=$n*$n;$i++){
            $stickers .= "r";
        }
        for($i = 1; $i<=$n*$n;$i++){
            $stickers .= "g";
        }
    }
    
    $scalepx=$size*$scale/220;
    $im = imagecreate($size*$scale,$size*$scale);
    imageantialias ($im,true);
    imagesetthickness($im,$thickness);
    
    // Define coordinates of the 6 'key'-points that define the cube shape cube:
    //Default orientation of the cube, based on ImageRevenge:
    if(!$orient){
    $x1=89; 	$y1=18;		$x2=200;	$y2=31;
    $x3=14;		$y3=59;		$x4=138;	$y4=77;
    $x5=29;		$y5=185;	$x6=147;	$y6=211;
    $x7=206;	$y7=151;
    }
    
    //Alternative orientation of the cube, based on ImageCube:
    if($orient=="1"){
        $x1=78; 	$y1=16;		$x2=183;	$y2=28;
        $x3=15;		$y3=62;		$x4=134;	$y4=78;	
        $x5=31;		$y5=168;	$x6=141;	$y6=194;
        $x7=185;	$y7=133;
    }
    
    // Allocate colors:
    /*$back = imagecolorallocate($im, 255, 255, 255);
    $col_poly = imagecolorallocate($im, 0, 0, 0);
    $red = imagecolorallocate($im, 200, 0, 0);
    $orange = imagecolorallocate($im, 255, 161, 0);
    $blue = imagecolorallocate($im, 0, 0, 182);
    $green = imagecolorallocate($im, 0, 182, 72);
    $white = imagecolorallocate($im,247,247,247);
    $yellow = imagecolorallocate($im, 239, 239, 0);
    $grey = imagecolorallocate($im, 200, 200, 200);*/
    $blue = imagecolorallocate($im, 0, 51, 115);
    $col_poly = imagecolorallocate($im, 0, 0, 0);
        $orange = imagecolorallocate($im, 255, 70, 0);
        $white = imagecolorallocate($im,248,248,248);
        $green = imagecolorallocate($im, 0, 115, 47);
        $red = imagecolorallocate($im, 140, 0, 15);
        $yellow = imagecolorallocate($im, 255, 210, 0);
        $grey = imagecolorallocate($im, 112, 112, 112);
        $dark_grey = imagecolorallocate($im, 64, 64, 64);
        $light_grey = imagecolorallocate($im, 153, 153, 153);
        $purple = imagecolorallocate($im, 96, 13, 117);
        $purple = imagecolorallocate($im, 182, 132, 194);

    $colors=array("r"=>$red,
           "o"=>$orange,
           "b"=>$blue,
           "g"=>$green,
           "w"=>$white,
           "y"=>$yellow,
           "p"=>$purple,
           "X"=>$grey,
           "x"=>$light_grey);
    
    // Draw cube silhouette:
        imagepolygon($im,
                 array (
                       $x2*$scalepx, $y2*$scalepx,
                       $x1*$scalepx, $y1*$scalepx,
                       $x3*$scalepx, $y3*$scalepx,
                       $x5*$scalepx, $y5*$scalepx,
                       $x6*$scalepx, $y6*$scalepx,
                       $x7*$scalepx, $y7*$scalepx
            ),
                 6,
                 $col_poly);
    
    //finishing the basic 1x1 cube shape, by drawing 3 more lines...
    imageline($im,$x4*$scalepx,$y4*$scalepx,$x3*$scalepx,$y3*$scalepx,$col_poly);
    imageline($im,$x4*$scalepx,$y4*$scalepx,$x2*$scalepx,$y2*$scalepx,$col_poly);
    imageline($im,$x4*$scalepx,$y4*$scalepx,$x6*$scalepx,$y6*$scalepx,$col_poly);
    
    //Making the grid on all 3 faces. For one grid, the makelines function is used 2 times, as
    //that function draws lines in only 1 direction:
    makelines($im, $n, $col_poly, $scalepx, $x1, $y1, $x2, $y2, $x3,$y3, $x4, $y4);
    makelines($im, $n, $col_poly, $scalepx, $x1, $y1, $x3, $y3, $x2,$y2, $x4, $y4);
    makelines($im, $n, $col_poly, $scalepx, $x3, $y3, $x4, $y4, $x5,$y5, $x6, $y6);
    makelines($im, $n, $col_poly, $scalepx, $x3, $y3, $x5, $y5, $x4,$y4, $x6, $y6);
    makelines($im, $n, $col_poly, $scalepx, $x4, $y4, $x2, $y2, $x6,$y6, $x7, $y7);
    makelines($im, $n, $col_poly, $scalepx, $x4, $y4, $x6, $y6, $x2,$y2, $x7, $y7);
    
    //Extracting the stickers of U, F and R faces:
    $stickers_U=substr($stickers,0,($n)*($n));
    $stickers_F=substr($stickers,$n*$n,($n)*($n));
    $stickers_R=substr($stickers,$n*$n*2,($n)*($n));
    
    //Calculate coordinates and fill up the U, F and R faces respectively:
    calculate_coordinates($datax,$datay,$n, $x1,$y1,$x2,$y2,$x3,$y3,$x4,$y4);
    fill_up($im, $n, $scalepx, $datax, $datay, $colors, $stickers_U);
    
    calculate_coordinates($datax,$datay,$n, $x3,$y3,$x4,$y4,$x5,$y5,$x6,$y6);
    fill_up($im, $n, $scalepx, $datax, $datay, $colors, $stickers_F);
    
    calculate_coordinates($datax,$datay,$n, $x4,$y4,$x2,$y2,$x6,$y6,$x7,$y7);
    fill_up($im, $n, $scalepx, $datax, $datay, $colors, $stickers_R);
    
    // Resample image and make background transparent:
    $interm=ImageCreateTrueColor($size,$size);
    imagecopyresampled($interm,$im,0,0,0,0,$size,$size,$size*$scale,$size*$scale);
    $output=ImageCreate($size,$size);
    imagecopyresized($output,$interm,0,0,0,0,$size,$size,$size,$size);
    $back = imagecolorallocate($output,221,221,221);
    imagecolortransparent($output,$back);
    imagefill($output,0,0,$back);
    return $output;
}
/*
// initialize paramaters... Adjust parameters if they are too small/big/not integers... We don't want someone like Lars to enter $n = 2.5 :)
import_request_variables("PG","");
$output = drawNxNImageCube($size, $n, $scale, $thickness,$stickers);

// Send image to client:
header("Content-type: image/png");
imagepng($output);
imagedestroy($im);
imagedestroy($output);
*/
?>
