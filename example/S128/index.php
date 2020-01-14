<?php
/*
 * Minimalist S128
*/

//add the S100 Application-schema for S-123
include 'ApplicationSchemaS128.php';

//add printer
define ( 'PRINT_PATH', '../../print/');
include (PRINT_PATH.'S100GmlPrinter.php');

//Path to save the GML-file
define ( 'GML_PATH', '../../res/S100_GML/data/ECLIPSE_GENERATE/S128/');

function addFeatureName($name)
{
    $fn = new featureName();
    $fn->name = $name;
    return $fn;
}

//Create the dataset
$s128 = new S128Catalogue();

$cat = new CatalogueOfNauticalProduct();
$cat->editionNumber = 1;
$cat->issueDate = '20191223';
$cat->featureName = addFeatureName("FIHO-catalog test");

$cd = new ContactDetails();
$cd->contactInstructions = "Preferred way of contact is email or phone.";
$or = new onlineResource();
$or->linkage = "http://www.traficom.fi";
$cd->onlineResource = $or;
$cat->CatalogueContanctsAssociation_catalogueContact_ContactDetails = $cd;

$prod = new PaperChart();
$prod->chartNumber = "99";
$prod->issueDate ="20190101";
$cat->CatalogueElementsAssociation_hasProducts_CatalogueElements = $prod;

$enc = new ElectronicChart();
$enc->chartNumber = "FIB879";
$enc->issueDate ="20190101";
$enc->productSpecification = "ref";
$cat->CatalogueElementsAssociation_hasProducts_CatalogueElements = $enc;

$npub = new NauticalProducts();
$npub->issueDate ="20190101";
$npub->content = "NPUB";
$npub->publicationNumber ="99";
$cat->CatalogueElementsAssociation_hasProducts_CatalogueElements = $npub;

//add catalogue
$s128->services = $cat;

//Specific namespace-data for GML- printer
$schemaLocation = 'xsi:schemaLocation="http://www.iala-aism.org/S128/gml/1.0 ../../../../S100_GML/schemas/S128/0.7.5/20191120/S128-Schema.xsd"';
$productName = "S128";
$productNs = "http://www.iala-aism.org/S128/gml/1.0";
$rolesNs = 'http://www.iho.int/S128/gml/0.7.5/roles/';
//Specific metadata for printer
$title = "S128 test product by traficom.fi (S.Engstrom)";
$abstract = "This product is created as a test of the FIHO S100 tools";

//Use the GMLPrinter to print GML
$printer = new S100GmlPrinter($s128, $productName, $productNs, $rolesNs, $title, $abstract, $schemaLocation);

//header('Content-Type: application/json; charset=utf-8');
$xml = $printer->printGML();

//print GML- file to res/data -folder
file_put_contents(GML_PATH.'S128_test.gml', $xml);

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>