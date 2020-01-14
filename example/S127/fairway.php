<?php
//add the S100 Application-schema for S-127
include 'ApplicationSchemaS127.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100GmlPrinter.php');

//Path to save the GML-file
define ( 'GML_PATH', '../../res/S100_GML/data/ECLIPSE_GENERATE/S127/');

//Path to helper functions
include ( 'class/S127_data_helper_functions.php');

//Create the dataset
$s127 = new S127TrafficService();

$handle = fopen("res/vayla_alue.csv", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
		$parts = explode(';', $line);

		$name = $parts[0];
		$area = $parts[1];
		$swept = $parts[2];
		$maxDraught = $parts[3];
		$verDat = $parts[4];
		$wkt = $parts[5];
		$fw = createFairwayArea($name, $area, $swept, $maxDraught, $verDat, $wkt);

		$s127->services = $fw;

    }

    fclose($handle);
} else {
    // error opening the file.
} 
//$line = "Akkasaari - Lappeenrannan satama väylä;191103; 4.8; 4.2;NN+75.10;POLYGON ((28.2261551507322 61.5491230158192, 28.2301829221667 61.5659030971667, 28.2309437080955 61.5681480724415, 28.2316521623381 61.5689138358093, 28.234757053802 61.5704264527921, 28.2352787146832 61.5710339386524, 28.2375057406089 61.5722435339942, 28.2380117924135 61.5747312034504, 28.236518494086 61.5769015919682, 28.2363594776126 61.5773321975538, 28.2420553237767 61.5816692460557, 28.2468623521667 61.5851274278333, 28.2508848662105 61.585659030239, 28.2493538768287 61.5865978040131, 28.2419144562159 61.5854877379095, 28.2353539504145 61.5786561154358, 28.2345638061869 61.5778330919624, 28.2361068687482 61.5746279249136, 28.2357092508633 61.5727721365794, 28.2349369968033 61.5717273309838, 28.2338172427687 61.5707320569591, 28.2274568799877 61.5677668072467, 28.2230904401667 61.5601993653333, 28.2152987723333 61.5415783383333, 28.2261551507322 61.5491230158192))";

//Specific namespace-data for GML- printer
$schemaLocation = 'xsi:schemaLocation="http://www.iho.int/S127/gml/cs0/1.0 ../../../../S100_GML/schemas/S127/1.0.0/20181129/S127.xsd"';
$productName = "S127";
$productNs = "http://www.iho.int/S127/gml/cs0/1.0";
$rolesNs = 'http://www.iho.int/S127/gml/1.0/roles/';

//Specific metadata for printer
$title = "S127 fairway areas traficom.fi (S.Engstrom)";
$abstract = "This product is created as a test of the FIHO S100 tools";

//Use the GMLPrinter to print GML
$printer = new S100GmlPrinter($s127, $productName, $productNs, $rolesNs, $title, $abstract, $schemaLocation);

//header('Content-Type: application/json; charset=utf-8');
$xml = $printer->printGML();

//print GML- file to res/data -folder
file_put_contents(GML_PATH.'S127_fairway.gml', $xml);

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>