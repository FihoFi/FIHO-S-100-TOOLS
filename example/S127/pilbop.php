<?php
/**
 * This is a testproduct containing Pilot boarding data
 */
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

//list of pilot boarding places
$pilbop = array();
$pilbop[] = addPilbop('Kemi N','Bay of Bothnia and Kvarken', 'POINT((24.4483333333333 65.5516666666667))');
$pilbop[] = addPilbop('Kemi S','Bay of Bothnia and Kvarken', 'POINT((24.317 65.488))');
$pilbop[] = addPilbop('Oulu N','Bay of Bothnia and Kvarken', 'POINT((24.3 65.1825))');
$pilbop[] = addPilbop('Oulu S','Bay of Bothnia and Kvarken', 'POINT((24.2823333333333 65.1166666666667))');
$pilbop[] = addPilbop('Raahe ','Bay of Bothnia and Kvarken ', 'POINT((24.2053333333333 64.6463333333333))');
$pilbop[] = addPilbop('Kalajoki ','Bay of Bothnia and Kvarken ', 'POINT((23.5 64.2583333333333))');
$pilbop[] = addPilbop('Kokkola ','Bay of Bothnia and Kvarken ', 'POINT((22.8181666666667 64.0086666666667))');
$pilbop[] = addPilbop('Pietarsaari ','Bay of Bothnia and Kvarken ', 'POINT((22.4748333333333 63.7418333333333))');
$pilbop[] = addPilbop('Vaasa N','Bay of Bothnia and Kvarken', 'POINT((20.8531666666667 63.2628333333333))');
$pilbop[] = addPilbop('Vaasa S','Bay of Bothnia and Kvarken', 'POINT((20.7563333333333 63.2016666666667))');

$bayOfBothnia = new PilotageDistrict();
$bayOfBothnia->featureName = addFeatureName('Bay of Bothnia and Kvarken pilot district');
foreach($pilbop as $pb)
{
    $bayOfBothnia->PilotageDistrictAssociation_consistsOf_PilotBoardingPlace = $pb;
}
$bayOfBothnia->Geometry = addWktGeometry('POLYGON ((20.4586118202082 63.2846180184285, 25.8655069527992 63.2846180184285, 25.8655069527992 66.2022062848417, 20.4586118202082 66.2022062848417, 20.4586118202082 63.2846180184285))');
$s127->services = $bayOfBothnia;

$pilbop = array();
$pilbop[] = addPilbop('Kaskinen','Sea of Bothnia', 'POINT((21.0851666666667 62.2585))');
$pilbop[] = addPilbop('Pori N','Sea of Bothnia', 'POINT((21.2833333333333 61.6133333333333))');
$pilbop[] = addPilbop('Pori S','Sea of Bothnia', 'POINT((21.3683333333333 61.5666666666667))');
$pilbop[] = addPilbop('Valkeakari','Sea of Bothnia', 'POINT((21.2511666666667 61.1773333333333))');
$pilbop[] = addPilbop('Rauma S','Sea of Bothnia', 'POINT((21.1791666666667 61.1208333333333))');
$pilbop[] = addPilbop('Isokari NW','Sea of Bothnia', 'POINT((20.9086666666667 60.7423333333333))');
$pilbop[] = addPilbop('Isokari SE','Sea of Bothnia', 'POINT((20.9983333333333 60.7016666666667))');

$seaOfBothnia = new PilotageDistrict();
$seaOfBothnia->featureName = addFeatureName('Sea of Bothnia pilot district');

foreach($pilbop as $pb)
{
    $seaOfBothnia->PilotageDistrictAssociation_consistsOf_PilotBoardingPlace = $pb;
}

$seaOfBothnia->Geometry = addWktGeometry('POLYGON ((16.7475744586532 60.2386555479651, 21.9764746457753 60.2386555479651, 21.9764746457753 63.7986623192533, 16.7475744586532 63.7986623192533, 16.7475744586532 60.2386555479651))');
$s127->services = $seaOfBothnia;

$pilbop = array();
$pilbop[] = addPilbop('Marhällan','Sea of Åland and Archipelago Sea', 'POINT((19.842 60.016))');
$pilbop[] = addPilbop('Nyhamn','Sea of Åland and Archipelago Sea', 'POINT((19.9383333333333 59.9383333333333))');
$pilbop[] = addPilbop('Utö','Sea of Åland and Archipelago Sea', 'POINT((21.342 59.7428333333333))');
$pilbop[] = addPilbop('Lillmälö','Sea of Åland and Archipelago Sea', 'POINT((22.1101666666667 60.2306666666667))');
$pilbop[] = addPilbop('Fläckgrund','Sea of Åland and Archipelago Sea', 'POINT((22.8075 59.8816666666667))');

$archipelagoSea = new PilotageDistrict();
$archipelagoSea->featureName = addFeatureName('Sea of Åland and Archipelago Sea pilot district');

foreach($pilbop as $pb)
{
    $archipelagoSea->PilotageDistrictAssociation_consistsOf_PilotBoardingPlace = $pb;
}

$archipelagoSea->Geometry = addWktGeometry('POLYGON ((17.2610784656591 59.8333333262785, 20.3333333310223 59.8333333262785, 20.3333333310223 60.4999999978931, 17.2610784656591 60.4999999978931, 17.2610784656591 59.8333333262785 19.9166667315149 59.8333331648617, 23.5763536565759 59.8333331648617, 23.5763536565759 60.916135631334, 19.9166667315149 60.916135631334, 19.9166667315149 59.8333331648617))');

$s127->services = $archipelagoSea;

$pilbop = array();
$pilbop[] = addPilbop('Hanko N','Gulf of Finland  ', 'POINT((23.0051666666667 59.7811666666667))');
$pilbop[] = addPilbop('Hanko S','Gulf of Finland  ', 'POINT((23.0966666666667 59.7083333333333))');
$pilbop[] = addPilbop('Porkkala N','Gulf of Finland  ', 'POINT((24.2366666666667 59.9276666666667))');
$pilbop[] = addPilbop('Porkkala S','Gulf of Finland  ', 'POINT((24.1946666666667 59.8933333333333))');
$pilbop[] = addPilbop('Helsinki S','Gulf of Finland  ', 'POINT((24.9483333333333 59.9833333333333))');
$pilbop[] = addPilbop('Helsinki N','Gulf of Finland  ', 'POINT((24.97 60.0666666666667))');
$pilbop[] = addPilbop('Vuosaari','Gulf of Finland   ', 'POINT((25.1631666666667 60.0826666666667))');
$pilbop[] = addPilbop('Emäsalo S','Gulf of Finland  ', 'POINT((25.5436666666667 60.0001666666667))');
$pilbop[] = addPilbop('Emäsalo N','Gulf of Finland  ', 'POINT((25.5633333333333 60.0433333333333))');
$pilbop[] = addPilbop('Orrengrund','Gulf of Finland  ', 'POINT((26.4226666666667 60.2383333333333))');
$pilbop[] = addPilbop('Kotkan majakka','Gulf of Finland  ', 'POINT((26.6033333333333 60.1666666666667))');
$pilbop[] = addPilbop('Haapasaari','Gulf of Finland  ', 'POINT((27.2816666666667 60.2508333333333))');
$pilbop[] = addPilbop('Santio','Gulf of Finland  ', 'POINT((27.696 60.4451666666667))');

$gulfOfFinland = new PilotageDistrict();
$gulfOfFinland->featureName = addFeatureName('Gulf of Finland pilot district');
foreach($pilbop as $pb)
{
    $gulfOfFinland->PilotageDistrictAssociation_consistsOf_PilotBoardingPlace = $pb;
}

$gulfOfFinland->Geometry = addWktGeometry('POLYGON ((22.9999999110742 59.1814414159622, 30.534509772802 59.1814414159622, 30.534509772802 60.8471608053302, 22.9999999110742 60.8471608053302, 22.9999999110742 59.1814414159622))');
$s127->services = $gulfOfFinland;

$pilbop = array();
$pilbop[] = addPilbop('Juustila ','Saimaa waterways', 'POINT((28.7383333333333 60.81))');
$pilbop[] = addPilbop('Soskua ','Saimaa waterways', 'POINT((28.4006666666667 61.0398333333333))');
$pilbop[] = addPilbop('Mälkiä ','Saimaa waterways', 'POINT((28.3038333333333 61.071))');
$pilbop[] = addPilbop('Puumala ','Saimaa waterways', 'POINT((28.17 61.5228333333333))');
$pilbop[] = addPilbop('Simuna ','Saimaa waterways', 'POINT((28.8716666666667 61.8483333333333))');
$pilbop[] = addPilbop('Haapavesi ','Saimaa waterways', 'POINT((28.8233333333333 61.8995))');
$pilbop[] = addPilbop('Taipaleen kanava','Saimaa waterways', 'POINT((27.9101666666667 62.3031666666667))');
$pilbop[] = addPilbop('Vuokala ','Saimaa waterways', 'POINT((29.205 62.1561666666667))');

$saimaaWaterways = new PilotageDistrict();
$saimaaWaterways->featureName = addFeatureName('Saimaa waterways pilot district');
foreach($pilbop as $pb)
{
    $saimaaWaterways->PilotageDistrictAssociation_consistsOf_PilotBoardingPlace = $pb;
}

$saimaaWaterways->Geometry = addWktGeometry('POLYGON ((26.0594172158146 60.8077470616921, 32.063315775987 60.8077470616921, 32.063315775987 64.2987850847395, 26.0594172158146 64.2987850847395, 26.0594172158146 60.8077470616921))');
$s127->services = $saimaaWaterways;

function addPilbop($name, $area, $wkt)
{
    $pilbop = new PilotBoardingPlace();
    
    $fn = new featureName();
    $fn->name = $name;
    
    $tc = new textContent();
     $nf = new information();
      $nf->text = "Pilot boarding areas are grouped according to sea areas. This boarding place is in area $area.".
        "In the present regulation pilot boarding area means a location marked on the chart, in the vicinity of".
        " which, subject to weather or ice conditions, the pilot shall board and disembark the vessel.";
     $tc->information = $nf;
      
    $point = new Geometry();
    $point->addWkt($wkt);
    
    $pilbop->featureName = $fn;
    $pilbop->textContent = $tc;
    $pilbop->Geometry = $point;
    
    return $pilbop;
}

foreach($pilbop as $pb)
{
    $s127->services = $pb;
}

//Specific namespace-data for GML- printer
$schemaLocation = 'xsi:schemaLocation="http://www.iho.int/S127/gml/cs0/1.0 ../../../../S100_GML/schemas/S127/1.0.0/20181129/S127.xsd"';
$productName = "S127";
$productNs = "http://www.iho.int/S127/gml/cs0/1.0";
$rolesNs = 'http://www.iho.int/S127/gml/1.0/roles/';

//Specific metadata for printer
$title = "S127 pilot boarding areas traficom.fi (S.Engstrom)";
$abstract = "This product is created as a test of the FIHO S100 tools";

//Use the GMLPrinter to print GML
$printer = new S100GmlPrinter($s127, $productName, $productNs, $rolesNs, $title, $abstract, $schemaLocation);

//header('Content-Type: application/json; charset=utf-8');
$xml = $printer->printGML();

//print GML- file to res/data -folder
file_put_contents(GML_PATH.'S127_pilbop.gml', $xml);

//output to screen
header('Content-Type: application/xml; charset=utf-8');
echo $xml;

?>