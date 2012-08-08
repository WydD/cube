<?php
require("imagecube.php");
/*
 * ImageCube by Lars Vandenbergh
 * 3rd septmber 2004
 *
 * Modification par Amaury SECHET 2007-2008 .
 * 23 December 2007
 *
 * A PHP script for generating static 3D views of the 3x3x3 Rubik's Cube
 */

 
/******************************************
    Encapsulation du cube dans un objet
******************************************/
class Cube{
    var $stickers;
    var $stickerspos=array(
        'FUL'    =>0,
        'FU'    =>1,
        'FUR'    =>2,
        'FL'    =>3,
        'F'    =>4,
        'FR'    =>5,
        'FDL'    =>6,
        'FD'    =>7,
        'FDR'    =>8,
        'RUF'    =>36,
        'RU'    =>37,
        'RUB'    =>38,
        'RF'    =>39,
        'R'    =>40,
        'RB'    =>41,
        'RDF'    =>42,
        'RD'    =>43,
        'RDB'    =>44,
        'DFL'    =>18,
        'DF'    =>19,
        'DFR'    =>20,
        'DL'    =>21,
        'D'    =>22,
        'DR'    =>23,
        'DBL'    =>24,
        'DB'    =>25,
        'DBR'    =>26,
        'BUR'    =>27,
        'BU'    =>28,
        'BUL'    =>29,
        'BR'    =>30,
        'B'    =>31,
        'BL'    =>32,
        'BDR'    =>33,
        'BD'    =>34,
        'BDL'    =>35,
        'LUB'    =>9,
        'LU'    =>10,
        'LUF'    =>11,
        'LB'    =>12,
        'L'    =>13,
        'LF'    =>14,
        'LDB'    =>15,
        'LD'    =>16,
        'LDF'    =>17,
        'UBL'    =>45,
        'UB'    =>46,
        'UBR'    =>47,
        'UL'    =>48,
        'U'    =>49,
        'UR'    =>50,
        'UFL'    =>51,
        'UF'    =>52,
        'UFR'    =>53
    );
    
    function Cube($pstickers = "000000000111111111222222222333333333444444444555555555"){
        if(strlen($pstickers)!=54) exit();
        for($i = 0; $i<54; $i++){
            $this->stickers[$i] = $pstickers{$i};
        }
    }
    
    function drawCube($scheme = '012345', $size = 145){
        $scale=2;
        $pstickers = "";
        for($i = 45 ; $i < 54 ; $i++) $pstickers.=$this->stickers[$i%54];
        for($i = 0 ; $i < 9 ; $i++) $pstickers.=$this->stickers[$i%54];
        for($i = 36 ; $i < 45 ; $i++) $pstickers.=$this->stickers[$i%54];
        $colors = array("0"=>"b", "1"=>"o", "2"=>"w", "3"=>"g", "4"=>"r", "5"=>"y");
        for($i = 0 ; $i < 6 ; $i++)
            $pstickers = str_replace($i,$colors[$scheme{$i}], $pstickers);
           
        return drawNxNImageCube($size, 3, $scale, $thickness, $pstickers);
        $scalepx=$size*$scale/200;
        $im = imagecreate($size*$scale,$size*$scale);
        imageantialias ($im,true);
        
        $blue = imagecolorallocate($im, 0, 51, 115);
        $orange = imagecolorallocate($im, 255, 70, 0);
        $white = imagecolorallocate($im,248,248,248);
        $green = imagecolorallocate($im, 0, 115, 47);
        $red = imagecolorallocate($im, 140, 0, 15);
        $yellow = imagecolorallocate($im, 255, 210, 0);
        $grey = imagecolorallocate($im, 112, 112, 112);
        $light_grey = imagecolorallocate($im, 64, 64, 64);
        $dark_grey = imagecolorallocate($im, 153, 153, 153);
        $purple = imagecolorallocate($im, 96, 13, 117);
        
        $custom_colors = array(
            "0"=>$blue,
            "1"=>$orange,
            "2"=>$white,
            "3"=>$green,
            "4"=>$red,
            "5"=>$yellow
        );
        
        $colors = array(
            "0"=>$custom_colors[$scheme{0}],
            "1"=>$custom_colors[$scheme{1}],
            "2"=>$custom_colors[$scheme{2}],
            "3"=>$custom_colors[$scheme{3}],
            "4"=>$custom_colors[$scheme{4}],
            "5"=>$custom_colors[$scheme{5}],
            "6"=>$grey,
            "7"=>$light_grey,
            "8"=>$dark_grey,
            "g"=>$grey,
            "v"=>$purple
        );

        $back = imagecolorallocate($im, 255, 255, 255);
        $col_poly = imagecolorallocate($im, 0, 0, 0);

        // draw cube silhouette
        imagepolygon($im, 
            array (
                78*$scalepx, 16*$scalepx,
                183*$scalepx, 28*$scalepx,
                185*$scalepx, 133*$scalepx,
                141*$scalepx, 194*$scalepx,
                31*$scalepx, 168*$scalepx,
                15*$scalepx,62*$scalepx
            ),
            6,
            $col_poly);
        
        // draw inner edges
        imageline($im,134*$scalepx,78*$scalepx,183*$scalepx,28*$scalepx,$col_poly);
        imageline($im,134*$scalepx,78*$scalepx,141*$scalepx,194*$scalepx,$col_poly);
        imageline($im,134*$scalepx,78*$scalepx,15*$scalepx,62*$scalepx,$col_poly);
        // draw BEF layers
        imageline($im,60*$scalepx,29*$scalepx,168*$scalepx,43*$scalepx,$col_poly);
        imageline($im,168*$scalepx,43*$scalepx,172*$scalepx,151*$scalepx,$col_poly);
        imageline($im,39*$scalepx,45*$scalepx,151*$scalepx,60*$scalepx,$col_poly);
        imageline($im,151*$scalepx,60*$scalepx,158*$scalepx,171*$scalepx,$col_poly);
        // draw LMR layers
        imageline($im,108*$scalepx,19*$scalepx,54*$scalepx,66*$scalepx,$col_poly);
        imageline($im,54*$scalepx,66*$scalepx,65*$scalepx,177*$scalepx,$col_poly);
        imageline($im,144*$scalepx,23*$scalepx,93*$scalepx,73*$scalepx,$col_poly);
        imageline($im,93*$scalepx,73*$scalepx,103*$scalepx,185*$scalepx,$col_poly);
        // draw UED layers
        imageline($im,21*$scalepx,100*$scalepx,136*$scalepx,119*$scalepx,$col_poly);
        imageline($im,136*$scalepx,119*$scalepx,184*$scalepx,66*$scalepx,$col_poly);
        imageline($im,26*$scalepx,136*$scalepx,138*$scalepx,158*$scalepx,$col_poly);
        imageline($im,138*$scalepx,158*$scalepx,185*$scalepx,102*$scalepx,$col_poly);
        
        //UBL sticker
        imagefill($im,$scalepx*85,25*$scalepx,$colors[$this->stickers[$this->stickerspos['UBL']]]);
        //UB sticker
        imagefill($im,$scalepx*123,30*$scalepx,$colors[$this->stickers[$this->stickerspos['UB']]]);
        //UBR sticker
        imagefill($im,$scalepx*158,32*$scalepx,$colors[$this->stickers[$this->stickerspos['UBR']]]);
        //UL sticker
        imagefill($im,$scalepx*71,40*$scalepx,$colors[$this->stickers[$this->stickerspos['UL']]]);
        //U sticker
        imagefill($im,$scalepx*101,40*$scalepx,$colors[$this->stickers[$this->stickerspos['U']]]);
        //UR sticker
        imagefill($im,$scalepx*143,46*$scalepx,$colors[$this->stickers[$this->stickerspos['UR']]]);
        //UFL sticker
        imagefill($im,$scalepx*43,56*$scalepx,$colors[$this->stickers[$this->stickerspos['UFL']]]);
        //UF sticker
        imagefill($im,$scalepx*88,57*$scalepx,$colors[$this->stickers[$this->stickerspos['UF']]]);
        //UFR sticker
        imagefill($im,$scalepx*121,63*$scalepx,$colors[$this->stickers[$this->stickerspos['UFR']]]);
        //FUL sticker
        imagefill($im,$scalepx*35,91*$scalepx,$colors[$this->stickers[$this->stickerspos['FUL']]]);
        //FU sticker
        imagefill($im,$scalepx*78,91*$scalepx,$colors[$this->stickers[$this->stickerspos['FU']]]);
        //FUR sticker
        imagefill($im,$scalepx*120,91*$scalepx,$colors[$this->stickers[$this->stickerspos['FUR']]]);
        //FL sticker
        imagefill($im,$scalepx*40,135*$scalepx,$colors[$this->stickers[$this->stickerspos['FL']]]);
        //F sticker
        imagefill($im,$scalepx*80,135*$scalepx,$colors[$this->stickers[$this->stickerspos['F']]]);
        //FR sticker
        imagefill($im,$scalepx*120,135*$scalepx,$colors[$this->stickers[$this->stickerspos['FR']]]);
        //FDL sticker
        imagefill($im,$scalepx*50,170*$scalepx,$colors[$this->stickers[$this->stickerspos['FDL']]]);
        //FD sticker
        imagefill($im,$scalepx*90,170*$scalepx,$colors[$this->stickers[$this->stickerspos['FD']]]);
        //FDR sticker
        imagefill($im,$scalepx*130,170*$scalepx,$colors[$this->stickers[$this->stickerspos['FDR']]]);
        //RUF sticker
        imagefill($im,$scalepx*146,87*$scalepx,$colors[$this->stickers[$this->stickerspos['RUF']]]);
        //RU sticker
        imagefill($im,$scalepx*162,68*$scalepx,$colors[$this->stickers[$this->stickerspos['RU']]]);
        //RUB sticker
        imagefill($im,$scalepx*177,51*$scalepx,$colors[$this->stickers[$this->stickerspos['RUB']]]);
        //RF sticker
        imagefill($im,$scalepx*150,130*$scalepx,$colors[$this->stickers[$this->stickerspos['RF']]]);
        //R sticker
        imagefill($im,$scalepx*160,110*$scalepx,$colors[$this->stickers[$this->stickerspos['R']]]);
        //RB sticker
        imagefill($im,$scalepx*180,95*$scalepx,$colors[$this->stickers[$this->stickerspos['RB']]]);
        //RDF sticker
        imagefill($im,$scalepx*150,170*$scalepx,$colors[$this->stickers[$this->stickerspos['RDF']]]);
        //RD sticker
        imagefill($im,$scalepx*160,145*$scalepx,$colors[$this->stickers[$this->stickerspos['RD']]]);
        //RDB sticker
        imagefill($im,$scalepx*180,130*$scalepx,$colors[$this->stickers[$this->stickerspos['RDB']]]);
        
        // resample image and make background transparent
        $interm=ImageCreateTrueColor($size,$size);
        imagecopyresampled($interm,$im,0,0,0,0,$size,$size,$size*$scale,$size*$scale);
        $output=ImageCreate($size,$size);
        imagecopyresized($output,$interm,0,0,0,0,$size,$size,$size,$size);
        $back = imagecolorallocate($output,221,221,221);
        imagecolortransparent($output,$back);
        imagefill($output,0,0,$back);
        
        //On nettoye
        imagedestroy($im);

        return $output;
    }

    function rotateR(){
        //Coins R
        $tmp = $this->stickers[$this->stickerspos['RUF']];
        $this->stickers[$this->stickerspos['RUF']] = $this->stickers[$this->stickerspos['RDF']];
        $this->stickers[$this->stickerspos['RDF']] = $this->stickers[$this->stickerspos['RDB']];
        $this->stickers[$this->stickerspos['RDB']] = $this->stickers[$this->stickerspos['RUB']];
        $this->stickers[$this->stickerspos['RUB']] = $tmp;
        
        //Coins autres
        $tmp = $this->stickers[$this->stickerspos['FUR']];
        $this->stickers[$this->stickerspos['FUR']] = $this->stickers[$this->stickerspos['DFR']];
        $this->stickers[$this->stickerspos['DFR']] = $this->stickers[$this->stickerspos['BDR']];
        $this->stickers[$this->stickerspos['BDR']] = $this->stickers[$this->stickerspos['UBR']];
        $this->stickers[$this->stickerspos['UBR']] = $tmp;

        $tmp = $this->stickers[$this->stickerspos['FDR']];
        $this->stickers[$this->stickerspos['FDR']] = $this->stickers[$this->stickerspos['DBR']];
        $this->stickers[$this->stickerspos['DBR']] = $this->stickers[$this->stickerspos['BUR']];
        $this->stickers[$this->stickerspos['BUR']] = $this->stickers[$this->stickerspos['UFR']];
        $this->stickers[$this->stickerspos['UFR']] = $tmp;
        
        //Arretes R
        $tmp = $this->stickers[$this->stickerspos['RU']];
        $this->stickers[$this->stickerspos['RU']] = $this->stickers[$this->stickerspos['RF']];
        $this->stickers[$this->stickerspos['RF']] = $this->stickers[$this->stickerspos['RD']];
        $this->stickers[$this->stickerspos['RD']] = $this->stickers[$this->stickerspos['RB']];
        $this->stickers[$this->stickerspos['RB']] = $tmp;
        
        //Arretes autres
        $tmp = $this->stickers[$this->stickerspos['UR']];
        $this->stickers[$this->stickerspos['UR']] = $this->stickers[$this->stickerspos['FR']];
        $this->stickers[$this->stickerspos['FR']] = $this->stickers[$this->stickerspos['DR']];
        $this->stickers[$this->stickerspos['DR']] = $this->stickers[$this->stickerspos['BR']];
        $this->stickers[$this->stickerspos['BR']] = $tmp;
    }

    function rotateX(){
        //Coins L/R
        $tmp = $this->stickers[$this->stickerspos['RUF']];
        $this->stickers[$this->stickerspos['RUF']] = $this->stickers[$this->stickerspos['RDF']];
        $this->stickers[$this->stickerspos['RDF']] = $this->stickers[$this->stickerspos['RDB']];
        $this->stickers[$this->stickerspos['RDB']] = $this->stickers[$this->stickerspos['RUB']];
        $this->stickers[$this->stickerspos['RUB']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['LUF']];
        $this->stickers[$this->stickerspos['LUF']] = $this->stickers[$this->stickerspos['LDF']];
        $this->stickers[$this->stickerspos['LDF']] = $this->stickers[$this->stickerspos['LDB']];
        $this->stickers[$this->stickerspos['LDB']] = $this->stickers[$this->stickerspos['LUB']];
        $this->stickers[$this->stickerspos['LUB']] = $tmp;
        
        //Coins autres
        $tmp = $this->stickers[$this->stickerspos['FUR']];
        $this->stickers[$this->stickerspos['FUR']] = $this->stickers[$this->stickerspos['DFR']];
        $this->stickers[$this->stickerspos['DFR']] = $this->stickers[$this->stickerspos['BDR']];
        $this->stickers[$this->stickerspos['BDR']] = $this->stickers[$this->stickerspos['UBR']];
        $this->stickers[$this->stickerspos['UBR']] = $tmp;

        $tmp = $this->stickers[$this->stickerspos['FDR']];
        $this->stickers[$this->stickerspos['FDR']] = $this->stickers[$this->stickerspos['DBR']];
        $this->stickers[$this->stickerspos['DBR']] = $this->stickers[$this->stickerspos['BUR']];
        $this->stickers[$this->stickerspos['BUR']] = $this->stickers[$this->stickerspos['UFR']];
        $this->stickers[$this->stickerspos['UFR']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['FUL']];
        $this->stickers[$this->stickerspos['FUL']] = $this->stickers[$this->stickerspos['DFL']];
        $this->stickers[$this->stickerspos['DFL']] = $this->stickers[$this->stickerspos['BDL']];
        $this->stickers[$this->stickerspos['BDL']] = $this->stickers[$this->stickerspos['UBL']];
        $this->stickers[$this->stickerspos['UBL']] = $tmp;

        $tmp = $this->stickers[$this->stickerspos['FDL']];
        $this->stickers[$this->stickerspos['FDL']] = $this->stickers[$this->stickerspos['DBL']];
        $this->stickers[$this->stickerspos['DBL']] = $this->stickers[$this->stickerspos['BUL']];
        $this->stickers[$this->stickerspos['BUL']] = $this->stickers[$this->stickerspos['UFL']];
        $this->stickers[$this->stickerspos['UFL']] = $tmp;
        
        //Arretes L/R
        $tmp = $this->stickers[$this->stickerspos['RU']];
        $this->stickers[$this->stickerspos['RU']] = $this->stickers[$this->stickerspos['RF']];
        $this->stickers[$this->stickerspos['RF']] = $this->stickers[$this->stickerspos['RD']];
        $this->stickers[$this->stickerspos['RD']] = $this->stickers[$this->stickerspos['RB']];
        $this->stickers[$this->stickerspos['RB']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['LU']];
        $this->stickers[$this->stickerspos['LU']] = $this->stickers[$this->stickerspos['LF']];
        $this->stickers[$this->stickerspos['LF']] = $this->stickers[$this->stickerspos['LD']];
        $this->stickers[$this->stickerspos['LD']] = $this->stickers[$this->stickerspos['LB']];
        $this->stickers[$this->stickerspos['LB']] = $tmp;
        
        //Arretes L/R 2
        $tmp = $this->stickers[$this->stickerspos['UR']];
        $this->stickers[$this->stickerspos['UR']] = $this->stickers[$this->stickerspos['FR']];
        $this->stickers[$this->stickerspos['FR']] = $this->stickers[$this->stickerspos['DR']];
        $this->stickers[$this->stickerspos['DR']] = $this->stickers[$this->stickerspos['BR']];
        $this->stickers[$this->stickerspos['BR']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['UL']];
        $this->stickers[$this->stickerspos['UL']] = $this->stickers[$this->stickerspos['FL']];
        $this->stickers[$this->stickerspos['FL']] = $this->stickers[$this->stickerspos['DL']];
        $this->stickers[$this->stickerspos['DL']] = $this->stickers[$this->stickerspos['BL']];
        $this->stickers[$this->stickerspos['BL']] = $tmp;
        
        //Arretes millieu
        $tmp = $this->stickers[$this->stickerspos['FU']];
        $this->stickers[$this->stickerspos['FU']] = $this->stickers[$this->stickerspos['DF']];
        $this->stickers[$this->stickerspos['DF']] = $this->stickers[$this->stickerspos['BD']];
        $this->stickers[$this->stickerspos['BD']] = $this->stickers[$this->stickerspos['UB']];
        $this->stickers[$this->stickerspos['UB']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['UF']];
        $this->stickers[$this->stickerspos['UF']] = $this->stickers[$this->stickerspos['FD']];
        $this->stickers[$this->stickerspos['FD']] = $this->stickers[$this->stickerspos['DB']];
        $this->stickers[$this->stickerspos['DB']] = $this->stickers[$this->stickerspos['BU']];
        $this->stickers[$this->stickerspos['BU']] = $tmp;
        
        //Centres
        $tmp = $this->stickers[$this->stickerspos['U']];
        $this->stickers[$this->stickerspos['U']] = $this->stickers[$this->stickerspos['F']];
        $this->stickers[$this->stickerspos['F']] = $this->stickers[$this->stickerspos['D']];
        $this->stickers[$this->stickerspos['D']] = $this->stickers[$this->stickerspos['B']];
        $this->stickers[$this->stickerspos['B']] = $tmp;
    }

    function rotateY(){
        //Coins U/D
        $tmp = $this->stickers[$this->stickerspos['UFR']];
        $this->stickers[$this->stickerspos['UFR']] = $this->stickers[$this->stickerspos['UBR']];
        $this->stickers[$this->stickerspos['UBR']] = $this->stickers[$this->stickerspos['UBL']];
        $this->stickers[$this->stickerspos['UBL']] = $this->stickers[$this->stickerspos['UFL']];
        $this->stickers[$this->stickerspos['UFL']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['DFR']];
        $this->stickers[$this->stickerspos['DFR']] = $this->stickers[$this->stickerspos['DBR']];
        $this->stickers[$this->stickerspos['DBR']] = $this->stickers[$this->stickerspos['DBL']];
        $this->stickers[$this->stickerspos['DBL']] = $this->stickers[$this->stickerspos['DFL']];
        $this->stickers[$this->stickerspos['DFL']] = $tmp;
        
        //Coins autres
        $tmp = $this->stickers[$this->stickerspos['FUR']];
        $this->stickers[$this->stickerspos['FUR']] = $this->stickers[$this->stickerspos['RUB']];
        $this->stickers[$this->stickerspos['RUB']] = $this->stickers[$this->stickerspos['BUL']];
        $this->stickers[$this->stickerspos['BUL']] = $this->stickers[$this->stickerspos['LUF']];
        $this->stickers[$this->stickerspos['LUF']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['FDR']];
        $this->stickers[$this->stickerspos['FDR']] = $this->stickers[$this->stickerspos['RDB']];
        $this->stickers[$this->stickerspos['RDB']] = $this->stickers[$this->stickerspos['BDL']];
        $this->stickers[$this->stickerspos['BDL']] = $this->stickers[$this->stickerspos['LDF']];
        $this->stickers[$this->stickerspos['LDF']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['FUL']];
        $this->stickers[$this->stickerspos['FUL']] = $this->stickers[$this->stickerspos['RUF']];
        $this->stickers[$this->stickerspos['RUF']] = $this->stickers[$this->stickerspos['BUR']];
        $this->stickers[$this->stickerspos['BUR']] = $this->stickers[$this->stickerspos['LUB']];
        $this->stickers[$this->stickerspos['LUB']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['FDL']];
        $this->stickers[$this->stickerspos['FDL']] = $this->stickers[$this->stickerspos['RDF']];
        $this->stickers[$this->stickerspos['RDF']] = $this->stickers[$this->stickerspos['BDR']];
        $this->stickers[$this->stickerspos['BDR']] = $this->stickers[$this->stickerspos['LDB']];
        $this->stickers[$this->stickerspos['LDB']] = $tmp;
        
        //Arretes U/D
        $tmp = $this->stickers[$this->stickerspos['UF']];
        $this->stickers[$this->stickerspos['UF']] = $this->stickers[$this->stickerspos['UR']];
        $this->stickers[$this->stickerspos['UR']] = $this->stickers[$this->stickerspos['UB']];
        $this->stickers[$this->stickerspos['UB']] = $this->stickers[$this->stickerspos['UL']];
        $this->stickers[$this->stickerspos['UL']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['DF']];
        $this->stickers[$this->stickerspos['DF']] = $this->stickers[$this->stickerspos['DR']];
        $this->stickers[$this->stickerspos['DR']] = $this->stickers[$this->stickerspos['DB']];
        $this->stickers[$this->stickerspos['DB']] = $this->stickers[$this->stickerspos['DL']];
        $this->stickers[$this->stickerspos['DL']] = $tmp;
        
        //Arretes U/D 2
        $tmp = $this->stickers[$this->stickerspos['FU']];
        $this->stickers[$this->stickerspos['FU']] = $this->stickers[$this->stickerspos['RU']];
        $this->stickers[$this->stickerspos['RU']] = $this->stickers[$this->stickerspos['BU']];
        $this->stickers[$this->stickerspos['BU']] = $this->stickers[$this->stickerspos['LU']];
        $this->stickers[$this->stickerspos['LU']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['FD']];
        $this->stickers[$this->stickerspos['FD']] = $this->stickers[$this->stickerspos['RD']];
        $this->stickers[$this->stickerspos['RD']] = $this->stickers[$this->stickerspos['BD']];
        $this->stickers[$this->stickerspos['BD']] = $this->stickers[$this->stickerspos['LD']];
        $this->stickers[$this->stickerspos['LD']] = $tmp;
        
        //Arretes millieu
        $tmp = $this->stickers[$this->stickerspos['FR']];
        $this->stickers[$this->stickerspos['FR']] = $this->stickers[$this->stickerspos['RB']];
        $this->stickers[$this->stickerspos['RB']] = $this->stickers[$this->stickerspos['BL']];
        $this->stickers[$this->stickerspos['BL']] = $this->stickers[$this->stickerspos['LF']];
        $this->stickers[$this->stickerspos['LF']] = $tmp;
        
        $tmp = $this->stickers[$this->stickerspos['FL']];
        $this->stickers[$this->stickerspos['FL']] = $this->stickers[$this->stickerspos['RF']];
        $this->stickers[$this->stickerspos['RF']] = $this->stickers[$this->stickerspos['BR']];
        $this->stickers[$this->stickerspos['BR']] = $this->stickers[$this->stickerspos['LB']];
        $this->stickers[$this->stickerspos['LB']] = $tmp;
        
        //Centres
        $tmp = $this->stickers[$this->stickerspos['R']];
        $this->stickers[$this->stickerspos['R']] = $this->stickers[$this->stickerspos['B']];
        $this->stickers[$this->stickerspos['B']] = $this->stickers[$this->stickerspos['L']];
        $this->stickers[$this->stickerspos['L']] = $this->stickers[$this->stickerspos['F']];
        $this->stickers[$this->stickerspos['F']] = $tmp;
    }
}

/******************************************
        Mouvements de base
******************************************/
abstract class alg{
    abstract function applyAlg(&$cube);
    abstract function applyInv(&$cube);
}

class algR extends alg{
    function applyAlg(&$cube){
        $cube->rotateR();
    }
    
    function applyInv(&$cube){
        $cube->rotateR();
        $cube->rotateR();
        $cube->rotateR();
    }
}

class algX extends alg{
    function applyAlg(&$cube){
        $cube->rotateX();
    }
    
    function applyInv(&$cube){
        $cube->rotateX();
        $cube->rotateX();
        $cube->rotateX();
    }
}

class algY extends alg{
    function applyAlg(&$cube){
        $cube->rotateY();
    }
    
    function applyInv(&$cube){
        $cube->rotateY();
        $cube->rotateY();
        $cube->rotateY();
    }
}

class algPt extends alg{
    function applyAlg(&$cube){}
    function applyInv(&$cube){}
}

/******************************************
        Operateur sur Algo
******************************************/
abstract class modifiedAlg extends alg{
    var $myAlg;

    function applyAlg(&$cube){
        $this->myAlg->applyAlg($cube);
    }
    
    function applyInv(&$cube){
        $this->myAlg->applyInv($cube);
    }
}

class invAlg extends modifiedAlg{
    function invAlg($algo,$dummy=''){
        $this->myAlg = $algo;
    }
    
    function applyAlg(&$cube){
        $this->myAlg->applyInv($cube);
    }
    
    function applyInv(&$cube){
        $this->myAlg->applyAlg($cube);
    }
}

class multAlg extends modifiedAlg{
    var $nb;
    
    function multAlg($algo,$nb){
        $this->myAlg = $algo;
        $this->nb = $nb;
    }
    
    function applyAlg(&$cube){
        for($i=0;$i<$this->nb;$i++) $this->myAlg->applyAlg($cube);
    }
    
    function applyInv(&$cube){
        for($i=0;$i<$this->nb;$i++) $this->myAlg->applyInv($cube);
    }
}

class savedAlg extends modifiedAlg{
    function savedAlg($algo,$nb){
        $nb = substr($nb,1,strlen($nb)-2);
        global $precalcAlg;
        $this->myAlg = $precalcAlg[$nb];
    }
}

/******************************************
        Algo plus construits
******************************************/
$baseAlg  = array(
    'R'        =>    'algR',
    'L'        =>    'algL',
    'U'        =>    'algU',
    'D'        =>    'algD',
    'F'        =>    'algF',
    'B'        =>    'algB',
    'r'        =>    'algRw',
    'l'        =>    'algLw',
    'u'        =>    'algUw',
    'd'        =>    'algDw',
    'f'        =>    'algFw',
    'b'        =>    'algBw',
    'x'        =>    'algX',
    'y'        =>    'algY',
    'z'        =>    'algZ',
    'M'        =>    'algM',
    'S'        =>    'algS',
    'E'        =>    'algE',
    'm'        =>    'algMw',
    's'        =>    'algSw',
    'e'        =>    'algEw',
    '\.'        =>    'algPt'
);

$baseOp = array(
    '\''    =>    'invAlg',
    '\d+'    =>    'multAlg',
    '#\d+#'    =>    'savedAlg'
);

$scanAlgRegex = '/^(';
foreach ($baseAlg as $i => $value) $scanAlgRegex .= $i.'|';
$scanAlgRegex = substr($scanAlgRegex,0,strlen($scanAlgRegex)-1).')/';

$scanOpRegex = '/^(';
foreach ($baseOp as $i => $value) $scanOpRegex .= $i.'|';
$scanOpRegex = substr($scanOpRegex,0,strlen($scanOpRegex)-1).')/';
$scanOpBlankRegex = str_replace('/^(','/\s+(',$scanOpRegex);

$precalcAlg = array();
function precomputeAlg(&$algo){
    //On enleve les caracteres blancs devant les operateurs
    global $scanOpBlankRegex;
    $algo = preg_replace($scanOpBlankRegex, '\\1', $algo);
    //On remplace les blancs par des point (operateur nul) car les enlever simplement peut creer des contre sens.
    $algo = preg_replace('/\s+/', '.', $algo);
    //On enleve les points multiples
    $algo = preg_replace('/\.+/', '.', $algo);
    //Et on remplace les sous algo entre parentheses
    global $precalcAlg;
    $lalg = strlen($algo);
    $l = count($precalcAlg);
    $nbp = 0;
    $salg = '';
    for($i=0;$i<$lalg;$i++){
        if($algo{$i} == '('){
            $nbp = 1;
            $salg = '';
            $i++;
            while($nbp>0){
                $salg .= $algo{$i};
                if($algo{$i} == '(') $nbp++;
                if($algo{$i} == ')') $nbp--;
                $i++;
            }
            $salg = substr($salg,0,strlen($salg)-1);
            $precalcAlg[$l] = new genericAlg($salg);
            $algo = str_replace('('.$salg.')','#'.($l++).'#',$algo);
            //Retour au debut de la chaine
            $i = -1;
        }
    }
}

class genericAlg extends alg{
    var $algToDo;
    
    function genericAlg($algo){
        precomputeAlg($algo);
echo $algo."<br/>";
        global $scanAlgRegex;
        global $scanOpRegex;
        global $baseAlg;
        global $baseOp;
        $offset = 0;
        $l = strlen($algo);
        $nbsalg = 0;
        while($offset<$l){
            if(preg_match($scanAlgRegex,substr($algo,$offset),$m)){
                $m = $m[0];
                $offset += strlen($m);
                //cas du point
                if($m == '.') $m = '\.';
                $this->algToDo[$nbsalg++] = new $baseAlg[$m]();
            }elseif(preg_match($scanOpRegex,substr($algo,$offset),$m)){
                if(!$nbsalg){
                    $this->algToDo[$nbsalg++] = new algPt();
                }
                $m = $m[0];
                $offset += strlen($m);
                foreach($baseOp as $i => $opAlg) if(preg_match('/^('.$i.')/',$m)){
                    //Cas des parentheses
                    if($i == '#\d+#') $this->algToDo[$nbsalg++] = new algPt();
                    $this->algToDo[$nbsalg-1] = new $opAlg($this->algToDo[$nbsalg-1],$m);
                }
            }else{
                echo 'Erreur de scan de l\'algo';
                exit();
            }
        }
    }
    
    function applyAlg(&$cube){
        $l = count($this->algToDo);
        for($i=0;$i<$l;$i++){
            $this->algToDo[$i]->applyAlg($cube);
        }
    }
    
    function applyInv(&$cube){
        $l = count($this->algToDo);
        for($i=$l;$i>0;$i--){
            $this->algToDo[$i-1]->applyInv($cube);
        }
    }
}

/******************************************
        Mouvements de base
******************************************/
class algZ extends genericAlg{
    function algZ(){
        $this->algToDo[0] = new algX();
        $this->algToDo[1] = new algY();
        $this->algToDo[2] = new invAlg(new algX());
    }
}

class algU extends genericAlg{
    function algU(){
        $this->algToDo[0] = new algZ();
        $this->algToDo[1] = new algR();
        $this->algToDo[2] = new invAlg(new algZ());
    }
}

class algD extends genericAlg{
    function algD(){
        $this->algToDo[0] = new invAlg(new algZ());
        $this->algToDo[1] = new algR();
        $this->algToDo[2] = new algZ();
    }
}

class algF extends genericAlg{
    function algF(){
        $this->algToDo[0] = new invAlg(new algY());
        $this->algToDo[1] = new algR();
        $this->algToDo[2] = new algY();
    }
}

class algB extends genericAlg{
    function algB(){
        $this->algToDo[0] = new algY();
        $this->algToDo[1] = new algR();
        $this->algToDo[2] = new invAlg(new algY());
    }
}

class algL extends genericAlg{
    function algL(){
        $this->algToDo[0] = new multAlg(new algY(),2);
        $this->algToDo[1] = new algR();
        $this->algToDo[2] = new multAlg(new algY(),2);
    }
}

/******************************************
        Mouvements multicouches
******************************************/
class algRw extends genericAlg{
    function algRw(){
        $this->algToDo[0] = new algL();
        $this->algToDo[1] = new algX();
    }
}

class algUw extends genericAlg{
    function algUw(){
        $this->algToDo[0] = new algD();
        $this->algToDo[1] = new algY();
    }
}

class algDw extends genericAlg{
    function algDw(){
        $this->algToDo[0] = new algU();
        $this->algToDo[1] = new invAlg(new algY());
    }
}

class algFw extends genericAlg{
    function algFw(){
        $this->algToDo[0] = new algB();
        $this->algToDo[1] = new algZ();
    }
}

class algBw extends genericAlg{
    function algBw(){
        $this->algToDo[0] = new algF();
        $this->algToDo[1] = new invAlg(new algZ());
    }
}

class algLw extends genericAlg{
    function algLw(){
        $this->algToDo[0] = new algR();
        $this->algToDo[1] = new invAlg(new algX());
    }
}

/******************************************
        Mouvement tranches
******************************************/
class algM extends genericAlg{
    function algM(){
        $this->algToDo[0] = new algR();
        $this->algToDo[1] = new invAlg(new algRw());
    }
}

class algS extends genericAlg{
    function algS(){
        $this->algToDo[0] = new algB();
        $this->algToDo[1] = new invAlg(new algBw());
    }
}

class algE extends genericAlg{
    function algE(){
        $this->algToDo[0] = new algU();
        $this->algToDo[1] = new invAlg(new algUw());
    }
}

class algMw extends genericAlg{
    function algMw(){
        $this->algToDo[0] = new algL();
        $this->algToDo[1] = new invAlg(new algR());
    }
}

class algSw extends genericAlg{
    function algSw(){
        $this->algToDo[0] = new algF();
        $this->algToDo[1] = new invAlg(new algB());
    }
}

class algEw extends genericAlg{
    function algEw(){
        $this->algToDo[0] = new algD();
        $this->algToDo[1] = new invAlg(new algU());
    }
}

/******************************************
        Execution du code
******************************************/
define('INSITE',true);

function error404(){
    //On affiche un 404
    $root = $_SERVER["DOCUMENT_ROOT"];
    
    $fp = fopen($root.'/template/404.data',"r");
    $texte = fread($fp,filesize($root.'/template/404.data'));
    fclose($fp);
    
    $title = 'Francocube Portail d\'infos sur le Rubik\'s cube';
    $desc = 'Site de methodes simples pour finir les puzzles de type rubik\'s cube. Et si la solution du rubik\'s cube, c\'&eacute;tait facile ?';
    $keywords = '';
    $author = '';
    
    $texte = str_replace('<h2', '<hr class="clear" /><p class="mvtop">[<a href="#top">Haut de page</a>]</p><h2', $texte);
    $texte = str_replace('<h3', '<hr class="clear" /><p class="mvtop">[<a href="#top">Haut de page</a>]</p><h3', $texte);
    
    include($root.'/template/template.php');
    
    exit();
}

function not333($url){
    $urltest = ':^/img/imagecube/([0-8gv]{24}|[0-8gv]{54}|[0-8gv]{96}|[0-8gv]{150})(G?)([xyzrludfbmseRLUDFBMSE0-9\\(\\)\'\\.\\t TC]*)I([xyzrludfbmseRLUDFBMSE0-9\\(\\)\'\\.\\t TC]*)([0-5]{6})\.png$:';
    if(!preg_match($urltest,$url)) error404();
    else{
        header("Content-type: image/png");
        $root = $_SERVER["DOCUMENT_ROOT"];
        readfile($root.'/img/imagecube/loading.png');
    }
}
/*
//url decode necessaire ??
$url = urldecode($_SERVER['REQUEST_URI']);
$urltest = ':^/img/imagecube/([0-8gv]{54})(G?)([xyzrludfbmseRLUDFBMSE0-9\\(\\)\'\\.\\t ]*)I([xyzrludfbmseRLUDFBMSE0-9\\(\\)\'\\.\\t ]*)([0-5]{6})\.png$:';
if(!preg_match($urltest,$url)) not333($url);

$cubepos = preg_replace($urltest,'\\1',$url);
$generator = preg_replace($urltest,'\\2',$url);
$cubealg = preg_replace($urltest,'\\3',$url);
$scheme = preg_replace($urltest,'\\5',$url);
*/

//On execute le init move
//$execalg = preg_replace($urltest,'\\4',$url);
//Si on est pas en generator, on inverse la suite
if($generator != 'G') $cubealg = '('.$cubealg.')\'';
$execalg .= '.'.$cubealg;;

$cube = new MetaCube($size);
//$cube = new Cube($cubepos);
precomputeAlg($execalg);
/*$exalg = new genericAlg($execalg);
$exalg->applyAlg($cube);
$im = $cube->drawCube($scheme);

//On sauvegrade le resultat
$root = $_SERVER["DOCUMENT_ROOT"];
//imagepng($im,$root.$url);

//Et on affiche
header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
*/
?>
