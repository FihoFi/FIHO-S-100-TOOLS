<?php
/*
 * Minimalist S123 edition 1.0
*/

//add the S100 Application-schema for S-123
include 'ApplicationSchemaS123.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100Printer.php');

//Create the dataset
$s123 = new S123MarineRadioServices();
$ccs = new CoastguardStation();
$s123->services = $ccs;
$ccs->isMRCC = new isMRCC(true);

//print the dataset using the XML-printer function
$xml = getS100xml($s123);

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>