<?php
/*
* This example does the following;

 * Create an S-127 dataset
 * Add one UnderkeelClearanceAllowanceArea
 * Add one UnderkeelClearanceManagementArea.(The Authority- object is created in a separate function in an referenced file.)
 * Add 2 PilotBoarding- places.
 * Data is printed to screen and to a file in RES- folder, using S100GMLPrinter.
*/

//add the S100 Application-schema for S-127
include 'ApplicationSchemaS127.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100GmlPrinter.php');

//Path to save the GML-file
define ( 'GML_PATH', '../../res/S100_GML/data/ECLIPSE_GENERATE/S127/');

//Create the dataset
$s127 = new S127TrafficService();

//Create UKC- allowancearea
$ukcAllowanceArea= new UnderkeelClearanceAllowanceArea();

//add featureName
$fn = new featureName();
$fn->name = "UKC-area test";
$ukcAllowanceArea->featureName = $fn;

//add another featureName
$fna = new featureName();
$fna->name = "Another name for the area";
$ukcAllowanceArea->featureName = $fna;

//textContent
$tx = new textContent();
	
//information
$information = new information();
$information->text = "The fixed UKC- allowance is the recommended value for normal conditions.";
$tx->information = $information;

//information
$information2 = new information();
$information2->text = "UKC 2.0m is based on designed draught (12.0 m) and swept depth (14.0 m).";
$tx->information = $information2;

//information
$information3 = new information();
$information3->text = "Vertical datum used is N2000.";
$tx->information = $information3;
	
$ukcAllowanceArea->textContent = $tx;

//add underkeelAllowance
$ukc= new underkeelAllowance();
$ukc->underkeelAllowanceFixed = 2.0;
$ukcAllowanceArea->underkeelAllowance = $ukc;

//add Geometry
$area = new Geometry();
$area->addWkt('POLYGON ((25 60, 25.5 60.5, 25.2 60.2, 25 60, ))');
$ukcAllowanceArea->Geometry = $area;
    
//add service
$s127->services = $ukcAllowanceArea;


//add ManagementArea
$ukcManagementArea = new UnderkeelClearanceManagementArea();

//set dynamicResource
$dynRes = new dynamicResource(1);
$ukcManagementArea->dynamicResource = $dynRes;

//A separate function is used to create the Authority- object. Function is included from separate file class/createAuthority
//PARAMETERS: $category, $name, $description, $phone, $url, $address, $weekHours, $wkndHours = null
include 'class/createAuthority.php';
$traficom = createAuthority(
    15, 
    "Finnish Transport and Communications Agency Traficom", 
    "The Finnish Transport and Communications Agency Traficom is an authority in licence, registration and approval matters. We promote traffic safety and the smooth functioning of the transport system. We also ensure that everyone in Finland has access to high-quality and secure communications connections and services.",
    "+358 29 534 5000",
    "http://www.traficom.fi/en",
    array("Opastinsilta 12 A", "00240", "Helsinki", "Finland"),
    array("08:00:00", "16:15:00"),
    null
    );

$ukcManagementArea->SrvControl_controlAuthority = $traficom;

//add geometry
$manarea = new Geometry();
$manarea->addWkt('POLYGON ((23.7020433774647 60.4487770871112, 23.7055084002909 60.4472713380439, 25.7146005866902 60.4511561456963, 25.7020433774647 60.4487770871112))');
$ukcManagementArea->Geometry = $manarea;

//add service
$s127->services = $ukcManagementArea;

//add pilbop
$pilbop = new PilotBoardingPlace();
$point = new Geometry();
$point->addWkt('POINT (24.7 60.3)');
$pilbop->Geometry = $point;
$s127->services = $pilbop;

//add pilbop
$pilbop2 = new PilotBoardingPlace();
$point2 = new Geometry();
$point2->addWkt('POINT (24.945831 60.192059)');
$pilbop2->Geometry = $point2;
$s127->services = $pilbop2;

//Specific namespace-data for GML- printer
$schemaLocation = 'xsi:schemaLocation="http://www.iho.int/S127/gml/cs0/1.0 ../../../../S100_GML/schemas/S127/1.0.0/20181129/S127.xsd"';
$productName = "S127";
$productNs = "http://www.iho.int/S127/gml/cs0/1.0";
$rolesNs = 'http://www.iho.int/S127/gml/1.0/roles/';
//Specific metadata for printer
$title = "S127 test product by traficom.fi (S.Engstrom)";
$abstract = "This product is created as a test of the FIHO S100 tools";

//Use the GMLPrinter to print GML
$printer = new S100GmlPrinter($s127, $productName, $productNs, $rolesNs, $title, $abstract, $schemaLocation);

//header('Content-Type: application/json; charset=utf-8');
$xml = $printer->printGML();

//print GML- file to res/data -folder
file_put_contents(GML_PATH.'S127_test.gml', $xml);

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>