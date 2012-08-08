<?php
require_once('simpletest/autorun.php');
require_once("metacube.php");

function generateDefaultCube($n, $F=false, $face="") {
    $pstickers = "";
    for($i = 0 ; $i < 6 ; $i++) {
        if($F !== $i)
            for($j = 0 ; $j < $n*$n; $j++) 
                $pstickers .= $i;
        else 
            $pstickers .= $face;
    }
    return new MetaCube($n, $pstickers);
}

class TestMetaCube extends UnitTestCase {    
    function TestMetaCube() {
        $this->UnitTestCase('Meta Cube Tests');
    }
    function testNewDefaultCube() {
        $stickers = "000000000"."111111111"."222222222"."333333333"."444444444"."555555555";
        $cube = new MetaCube(3);
        $this->assertEqual($cube->getStickersString(), $stickers); 
        $cube = generateDefaultCube(3);
        $this->assertEqual($cube->getStickersString(), $stickers); 
    }
    function testFace() {
        $t = "AAAABAAAA";
        $cube = generateDefaultCube(3, 3, $t);
        $this->assertEqual($cube->getFaceString('B'), $t);
    }
    function testR() {
        $t = "ABCD".
             "EFGH".
             "IJKL".
             "MNOP";
        $cube = generateDefaultCube(4, 4, $t);
        $stickers = $cube->getStickersString();
        $this->assertEqual($cube->getFaceString('R'), $t);
        $cube->rotateR();
        
        $resR= "MIEA".
               "NJFB".
               "OKGC".
               "PLHD";
        $resD= "2223".
               "2223".
               "2223".
               "2223";
        $resB= "3335".
               "3335".
               "3335".
               "3335";
        $this->assertEqual($cube->getFaceString('R'), $resR);
        $this->assertEqual($cube->getFaceString('D'), $resD);
        $this->assertEqual($cube->getFaceString('B'), $resB);
        $cube->rotateR();
        $cube->rotateR();
        $cube->rotateR();
        $this->assertEqual($cube->getFaceString('R'), $t); // R^4 = Id
        $this->assertEqual($cube->getStickersString(), $stickers);
    }
    function testMR() {
        $t = "ABCD".
             "EFGH".
             "IJKL".
             "MNOP";
        $cube = generateDefaultCube(4, 4, $t);
        $stickers = $cube->getStickersString();
        $this->assertEqual($cube->getFaceString('R'), $t);
        $cube->rotateMR();
        
        $resD= "2232".
               "2232".
               "2232".
               "2232";
        $resB= "3353".
               "3353".
               "3353".
               "3353";
        $this->assertEqual($cube->getFaceString('R'), $t); // R face still the same
        $this->assertEqual($cube->getFaceString('D'), $resD);
        $this->assertEqual($cube->getFaceString('B'), $resB);
        $cube->rotateMR();
        $cube->rotateMR();
        $cube->rotateMR();
        $this->assertEqual($cube->getFaceString('R'), $t); // MR^4 = Id
        $this->assertEqual($cube->getStickersString(), $stickers);
    }
    /**
     *          EF
     *          GH
     *         ----
     *          IJ
     *          KL
     *         ----
     *       AB|12|ab
     *       CD|34|cd
     *         ----
     *          56
     *          78
     */
    function testX()  {
        //2x2 test
        //              F    L      D       B      R     U
        $stickers = "1234"."ABCD"."5678"."EFGH"."abcd"."IJKL";
        $cube = new MetaCube(2,$stickers);
        $cube->rotateX(); // F -> U -> B -> D -> F
        $this->assertEqual($cube->getFaceString('F'), "5678");
        $this->assertEqual($cube->getFaceString('U'), "1234");
        $this->assertEqual($cube->getFaceString('B'), "IJKL");
        $this->assertEqual($cube->getFaceString('D'), "EFGH");
        //   a b   =>   c a
        //   c d   =>   d b
        $this->assertEqual($cube->getFaceString('R'), "cadb");
        //   A B   =>   B D
        //   C D   =>   A C
        $this->assertEqual($cube->getFaceString('L'), "BDAC");
        $cube->rotateX();$cube->rotateX();$cube->rotateX();
        $this->assertEqual($cube->getStickersString(), $stickers);
    }
    function testY()  {
        //2x2 test
        //              F    L      D       B      R     U
        $stickers = "1234"."ABCD"."5678"."EFGH"."abcd"."IJKL";
        $cube = new MetaCube(2,$stickers);
        $cube->rotateY(); // F -> L -> B -> R

        $this->assertEqual($cube->getFaceString('F'), "abcd");
        $this->assertEqual($cube->getFaceString('L'), "1234");
        $this->assertEqual($cube->getFaceString('B'), "DCBA"); // B is reversed
        $this->assertEqual($cube->getFaceString('R'), "HGFE"); // B was reversed
        
        //   I J   =>   K I
        //   K L   =>   L J
        $this->assertEqual($cube->getFaceString('U'), "KILJ"); 
        //   5 6   =>   6 8
        //   7 8   =>   5 7
        $this->assertEqual($cube->getFaceString('D'), "6857"); 
    }
}
?>