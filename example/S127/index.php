<?php
/*
* This example does the following;

 * Create an S-127 dataset
 * Add one UnderkeelClearanceAllowanceArea
  * Add (indirect) Applicability association to the area using a PermssionType AssociationClass
 * Add one UnderkeelClearanceManagementArea.(The Authority- object is created in a separate function in an referenced file.)
 * Add 2 PilotBoarding- places.
 * Data is printed to screen and to a file in RES- folder, using S100GMLPrinter.
*/

//add the S100 Application-schema for S-127
include 'ApplicationSchemaS127.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100GmlPrinter.php');

//Function for creating authority
include 'createAuthority.php';

//Create the dataset
$s127 = new S127TrafficService();

//Create UKC- allowancearea
$ukcAllowanceArea= new UnderkeelClearanceAllowanceArea();

//14.1.2020 SE TEST OF APPLICABILITY
$app = new Applicability();
$app->categoryOfVessel = 1;
  $vslMeasure = new vesselsMeasurements();
    $vslMeasure->vesselsCharacteristics = 4; //draught
    $vslMeasure->comparisonOperator = 4; //less than or equal to
    $vslMeasure->vesselsCharacteristicsValue = 10.0; //draught
    $vslMeasure->vesselsCharacteristicsUnit = 1; //metres
$app->vesselsMeasurements = $vslMeasure;

//InformationAssociation PermissionType has an attribute => direct association not possible
//In this case, instead of the Applicability- object, the PermissionType must be added as attribute
$perm = new PermissionType();
$perm->categoryOfRelationship = 3; //permitted

//The actual Applicability object is added to the Association as "associatedType"
$perm->associatedType = $app;

//permission is added to area
$ukcAllowanceArea->PermissionType_permission_Applicability = $perm;

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
$area->addWkt('POLYGON ((25 60, 25.5 60.5, 25.2 60.2, 25 60))');
$ukcAllowanceArea->Geometry = $area;
    
//add service
$s127->services = $ukcAllowanceArea;


//add ManagementArea
$ukcManagementArea = new UnderkeelClearanceManagementArea();

//set dynamicResource
$dynRes = new dynamicResource(1);
$ukcManagementArea->dynamicResource = $dynRes;

//A separate function is used to create the Authority- object. Function is included from separate file
//PARAMETERS: $category, $name, $description, $phone, $url, $address, $weekHours, $wkndHours = null
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

$ukcManagementArea->SrvControl_controlAuthority_Authority = $traficom;

//add geometry
$manarea = new Geometry();
$manarea->addWkt('POLYGON ((23.7020433774647 60.4487770871112, 23.7055084002909 60.4472713380439, 25.7146005866902 60.4511561456963, 25.7020433774647 60.4487770871112))');
$ukcManagementArea->Geometry = $manarea;

//add service
$s127->services = $ukcManagementArea;

//Contact details for PILBOP:s
$conDet = new ContactDetails();
$tCom = new telecommunications();
$tCom->telecommunicationIdentifier = "+358 00 123 456";
$conDet->telecommunications = $tCom;
$oRes = new OnlineResource();
$oRes->linkage = "http://pilotorders.fi";
$conDet->onlineResource = $oRes;
$addr = new ContactAddress();
$addr->deliveryPoint = "Luotsikatu 3";
$addr->postalCode = "01234";
$addr->cityName = "Harmaja";
$addr->countryName = "Finland";
$conDet->contactAddress = $addr;

//add pilbop
$pilbop = new PilotBoardingPlace();
$point = new Geometry();
$point->addWkt('POINT (24.7 60.3)');
$pilbop->Geometry = $point;
$pilbop->SrvContact_theContactDetails_ContactDetails = $conDet;
$s127->services = $pilbop;

//add pilbop
$pilbop2 = new PilotBoardingPlace();
$point2 = new Geometry();
$point2->addWkt('POINT (24.945831 60.192059)');
$pilbop2->Geometry = $point2;
$pilbop2->SrvContact_theContactDetails_ContactDetails = $conDet;
$s127->services = $pilbop2;

$archipelago_vts = new VesselTrafficServiceArea();
$archipelago_vts->requirementsForMaintenanceOfListeningWatch = 'Vessel in transit shall listen to designated VTS- channel on VHF';
$archipelago_vts_area = new Geometry();
$archipelago_vts_area->addWkt('POLYGON ((21.1585 60.95333333, 20.77516667 60.95333333, 20.19833333 60.52833333, 19.87166667	60.03166667, 22.8835 60.10833333))');
$archipelago_vts->Geometry = $archipelago_vts_area;
$s127->services = $archipelago_vts;

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

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>