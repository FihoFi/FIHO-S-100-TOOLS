<?php
/*
* This example does the following;

 * Create an S-127 dataset
 * Add one UnderkeelClearanceAllowanceArea
 * Add one UnderkeelClearanceManagementArea. The Authority- object is created in a separate function in an refernced file.
 * Data is printed out as XML.
*/

//add the S100 Application-schema for S-127
include 'ApplicationSchemaS127.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100GmlPrinter.php');


//Create the dataset
$s127 = new S127TrafficService();
    
$ukcAllowanceArea= new UnderkeelClearanceAllowanceArea();

//add featureName
$fn = new featureName();
$fn->name = "UKC-area test";
$ukcAllowanceArea->featureName = $fn;

//add another featureName
$fna = new featureName();
$fna->name = "Another name for the area";
$ukcAllowanceArea->featureName = $fna;

//fixedDateRange
//periodicDateRange
//sourceIndication

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

//TextPlacement

//underkeelAllowance
$ukc= new underkeelAllowance();

$ukc->underkeelAllowanceFixed = 2.0;
$ukcAllowanceArea->underkeelAllowance = $ukc;

//waterLeveltrend

//Geometry
$area = new Geometry();
$area->addWkt('POLYGON ((25.7020433774647 60.4487770871112, 25.7055084002909 60.4472713380439, 25.7146005866902 60.4511561456963, 25.7189352578333 60.456562736, 25.7236744815567 60.456812612382, 25.7236690554147 60.4572613304076, 25.7180467783976 60.4574739982463, 25.7136723958333 60.4527407243333, 25.7020433774647 60.4487770871112))');

$ukcAllowanceArea->Geometry = $area;
    
//add service
$s127->services = $ukcAllowanceArea;

//add service
$ukcManagementArea = new UnderkeelClearanceManagementArea();
$dynRes = new dynamicResource(1);
$ukcManagementArea->dynamicResource = $dynRes;

//EXAMPLE: A separate function is used to create the Authority- object. Function is included from separate file class/createAuthority
//PARAMETERS: $category, $name, $description, $phone, $url, $address, $weekHours, $wkndHours = null
include 'class/createAuthority.php';
$traficom = createAuthority(
    15, 
    "Finnish Transport and Communications Agency Traficom", 
    "The Finnish Transport and Communications Agency Traficom is an authority in licence, registration and approval matters. We promote traffic safety and the smooth functioning of the transport system. We also ensure that everyone in Finland has access to high-quality and secure communications connections and services.",
    "+358 29 534 5000",
    "http://www.traficom.fi/en",
    array("Opastinsilta 12 A", "00240", "Helsinki", "Finland"),
    array("0800", "1615"),
    null
    );
	
$ukcManagementArea->SrvControl_controlAuthority = $traficom;
$s127->services = $ukcManagementArea;

$pilbop = new PilotBoardingPlace();
$point = new Geometry();
$point->addWkt('POINT (24.945831 60.192059)');
$pilbop->Geometry = $point;
$s127->services = $pilbop;

$pilbop2 = new PilotBoardingPlace();
$point2 = new Geometry();
$point2->addWkt('LINESTRING (24.945831 60.192059, 24.945831 59.192059 )');
$pilbop2->Geometry = $point2;
$s127->services = $pilbop2;

//Specicif namespace-data for GML- printer
$productName = "S127";
$productNs = "http://www.iho.int/S127/gml/cs0/1.0";
$rolesNs = 'http://www.iho.int/S127/gml/1.0/roles/';
//Specific metadata for printer
$title = "S127 test product by traficom.fi (S.Engstrom)";
$abstract = "This product is created as a test of the FIHO S100 tools";

$printer = new S100GmlPrinter($s127, $productName, $productNs, $rolesNs, $title, $abstract);

//header('Content-Type: application/json; charset=utf-8');
$xml = $printer->printStructure();

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>