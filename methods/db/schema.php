<?php
$DB['algorithm'] = array('PRI' => array(), 'ATT' => array());
$DB['algorithm']['ATT'][] = 'id';
$DB['algorithm']['PRI'][] = 'id';
$DB['algorithm']['ATT'][] = 'caseId';
$DB['algorithm']['ATT'][] = 'formula';
$DB['algorithm']['ATT'][] = 'descriptionId';

$DB['algorithmSet'] = array('PRI' => array(), 'ATT' => array());
$DB['algorithmSet']['ATT'][] = 'id';
$DB['algorithmSet']['PRI'][] = 'id';
$DB['algorithmSet']['ATT'][] = 'symetric';
$DB['algorithmSet']['ATT'][] = 'applysetup';

$DB['case'] = array('PRI' => array(), 'ATT' => array());
$DB['case']['ATT'][] = 'descriptionId';
$DB['case']['ATT'][] = 'algorithmSetId';
$DB['case']['ATT'][] = 'id';
$DB['case']['PRI'][] = 'id';

$DB['cubestate'] = array('PRI' => array(), 'ATT' => array());
$DB['cubestate']['ATT'][] = 'descriptionId';
$DB['cubestate']['ATT'][] = 'id';
$DB['cubestate']['PRI'][] = 'id';
$DB['cubestate']['ATT'][] = 'setup';
$DB['cubestate']['ATT'][] = 'displayOrientFaces';

$DB['cubiestate'] = array('PRI' => array(), 'ATT' => array());
$DB['cubiestate']['ATT'][] = 'subgroup';
$DB['cubiestate']['ATT'][] = 'oriented';
$DB['cubiestate']['ATT'][] = 'placed';
$DB['cubiestate']['ATT'][] = 'cubie';
$DB['cubiestate']['PRI'][] = 'cubie';
$DB['cubiestate']['ATT'][] = 'id';
$DB['cubiestate']['PRI'][] = 'id';

$DB['descriptions'] = array('PRI' => array(), 'ATT' => array());
$DB['descriptions']['ATT'][] = 'id';
$DB['descriptions']['PRI'][] = 'id';
$DB['descriptions']['ATT'][] = 'langage';
$DB['descriptions']['PRI'][] = 'langage';
$DB['descriptions']['ATT'][] = 'title';
$DB['descriptions']['ATT'][] = 'description';

$DB['equivalentTo'] = array('PRI' => array(), 'ATT' => array());
$DB['equivalentTo']['ATT'][] = 'id';
$DB['equivalentTo']['PRI'][] = 'id';
$DB['equivalentTo']['ATT'][] = 'parent';
$DB['equivalentTo']['PRI'][] = 'parent';

$DB['relatedTo'] = array('PRI' => array(), 'ATT' => array());
$DB['relatedTo']['ATT'][] = 'type';
$DB['relatedTo']['ATT'][] = 'parent';
$DB['relatedTo']['PRI'][] = 'parent';
$DB['relatedTo']['ATT'][] = 'id';
$DB['relatedTo']['PRI'][] = 'id';

$DB['sequence'] = array('PRI' => array(), 'ATT' => array());
$DB['sequence']['ATT'][] = 'id';
$DB['sequence']['PRI'][] = 'id';
$DB['sequence']['ATT'][] = 'substep';
$DB['sequence']['PRI'][] = 'substep';
$DB['sequence']['ATT'][] = 'order';

$DB['step'] = array('PRI' => array(), 'ATT' => array());
$DB['step']['ATT'][] = 'from';
$DB['step']['ATT'][] = 'to';
$DB['step']['ATT'][] = 'descriptionId';
$DB['step']['ATT'][] = 'isMethod';
$DB['step']['ATT'][] = 'id';
$DB['step']['PRI'][] = 'id';
$DB['step']['ATT'][] = 'langage';
?>