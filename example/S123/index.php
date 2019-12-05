<?php
/*
 * Minimalist S123 edition 1.0
*/

//add the S100 Application-schema for S-123
include 'ApplicationSchemaS123.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100GmlPrinter.php');

//Create the dataset
$s123 = new S123MarineRadioServices();
$ccs = new CoastguardStation();
$s123->services = $ccs;
$ccs->isMRCC = new isMRCC(true);

//print GML
$printer = new S100GmlPrinter($s123);
$xml = $printer->printStructure();

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>