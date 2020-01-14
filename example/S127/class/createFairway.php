<?php

function createFairwayArea($name, $area, $swept, $maxDraught, $verDat, $wkt)
{
    $fairway= new UnderkeelClearanceAllowanceArea();
    
    //featureName
    $fn = new featureName();
    $fn->name = $name;
    $fairway->featureName = $fn;
    
    $fna = new featureName();
    $fna->name = $area;
    $fairway->featureName = $fna;
    
	if (is_numeric($swept) && is_numeric($maxDraught))
	{
	
			$ukc = ($swept * 1.0 - $maxDraught * 1.0) * 1.0;
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
			$information2->text = "UKC ($ukc m) is based on designed draught ($maxDraught m) and swept depth ($swept m).";
			$tx->information = $information2;
			
			//information
			$information3 = new information();
			$information3->text = "Vertical datum used is $verDat.";
			$tx->information = $information3;
			
			
		$fairway->textContent = $tx;
		
		//TextPlacement
		
		
		//underkeelAllowance
		$uka = new underkeelAllowance();
		
		$uka->underkeelAllowanceFixed = $ukc;
		$fairway->underkeelAllowance = $uka;
	}
    //waterLeveltrend
    
    //Geometry
    $area = new Geometry();
    $area->addWkt($wkt);
    
    $fairway->Geometry = $area;
    
    return $fairway;
}
?>