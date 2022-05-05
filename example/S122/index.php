<?php
namespace fiho\s100\testS122;
use fiho\s100\S100GmlPrinter;
/*
 * Minimalist S122 MPA edition 1.0
*/

//add the S100 Application-schema for S-122
include 'ApplicationSchemaS122.php';

//add printer
define ( 'PRINT_PATH', '../../print/');

//Create the dataset
$s122 = new S122MarineProtectedAreas();
$mpa = new MarineProtectedArea();
$s122->services = $mpa;
$mpa->categoryOfMarineProtectedArea = new categoryOfMarineProtectedArea(4);
$mpa->jurisdiction = new jurisdiction(2);

//print GML
$printer = new S100GmlPrinter($s122, 'S122');
$xml = $printer->printGml();

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>