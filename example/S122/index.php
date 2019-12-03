<?php
/*
 * Minimalist S122 MPA edition 1.0
*/

//add the S100 Application-schema for S-122
include 'ApplicationSchemaS122.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100Printer.php');

//Create the dataset
$s122 = new S122MarineProtectedAreas();
$mpa = new MarineProtectedArea();
$s122->services = $mpa;
$mpa->categoryOfMarineProtectedArea = new categoryOfMarineProtectedArea(4);
$mpa->jurisdiction = new jurisdiction(2);

//print the dataset using the XML-printer function
$xml = getS100xml($s122);

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>