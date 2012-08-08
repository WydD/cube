<?php
// This file creates the core algorithms of the cube
//  It uses $size to determine the final set of algs

global $algs;

// Transpose the cube to execute the permutation on the U-D-F-B-L layer
function transpose($axis, $permut) {
    if(!is_array($permut)) $permut = array($permut);
    if($axis == "U")
        $switch = "z"; 
    else if($axis == "D")
        $switch = new InvAlg("z");
    else if($axis == "F")
        $switch = new InvAlg("y");
    else if($axis == "B")
        $switch = "y";
    else if($axis == "L")
        $switch = new RepeatAlg("y",2);
    if($switch) {
        array_unshift($permut, $switch);
        $permut[] = new InvAlg($switch);
    }
    return $permut;
}
// Creates the base permutation (on R layer) and 
//  uses transpose to instanciate the other one
function createRelatedAlgs($prefix, $permut) {
    global $algs;
    $to_transpose = $prefix."R";
    if(is_array($permut)) {
        $permut = new SequenceAlg($permut);
    }
    $algs[$prefix."R"] = $permut;
    foreach(array("U","D","F","B","L") as $m) 
        $algs[$prefix.$m] = new SequenceAlg(transpose($m,$prefix."R"));
}

/*****************************
 * Creates common algorithms *
 *****************************/
$algs["x"] = new AlgX();
$algs["y"] = new AlgY();
$algs["z"] = new SequenceAlg(array("x", "y", new InvAlg("x")));
createRelatedAlgs("", new AlgR());

// 3x3 specific
if($size == 3) {
    $algs["r"] = new SequenceAlg(array("L","x"));
    $algs["u"] = new SequenceAlg(array("D","y"));
    $algs["d"] = new SequenceAlg(array("U",new InvAlg("y")));
    $algs["f"] = new SequenceAlg(array("B","z"));
    $algs["b"] = new SequenceAlg(array("F",new InvAlg("z")));
    $algs["l"] = new SequenceAlg(array("R",new InvAlg("x")));
    
    $algs["M"] = new SequenceAlg(array("R",new InvAlg("r")));
    $algs["S"] = new SequenceAlg(array("B",new InvAlg("b")));
    $algs["E"] = new SequenceAlg(array("U",new InvAlg("u")));
    $algs["m"] = new SequenceAlg(array("L",new InvAlg("R")));
    $algs["s"] = new SequenceAlg(array("F",new InvAlg("B")));
    $algs["e"] = new SequenceAlg(array("D",new InvAlg("U")));
} else if($size == 4) {
    createRelatedAlgs("M", new AlgMkR(1));
    createRelatedAlgs("T", array("R", "MR"));
} else if($size == 5) {
    createRelatedAlgs("M", new AlgMkR(2));
    createRelatedAlgs("M1", new AlgMkR(1));
    createRelatedAlgs("T", array("R", "M1R"));
    createRelatedAlgs("S", array("R", new InvAlg("L")));
} 

if($size != 3) {
    // Aliases for CR CU etc...
    $algs["CR"] = new AliasAlg("x");
    $algs["CL"] = new InvAlg("x");
    $algs["CU"] = new AliasAlg("y");
    $algs["CD"] = new InvAlg("y");
    $algs["CF"] = new AliasAlg("z");
    $algs["CB"] = new InvAlg("z");
    unset($algs["z"],$algs["x"],$algs["y"]);
    // Disable those moves as they are not supported by randelshofer
}
krsort($algs); //important (see notation.php)
?>