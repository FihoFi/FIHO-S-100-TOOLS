<?php

function createAuthority($category, $name, $description, $phone, $url, $address, $weekHours, $wkndHours = null)
{
    //Test Authority creation
    $auth = new Authority();
    
    $auth->categoryOfAuthority = $category;
    
    $tCont = new textContent();
    $tCont->categoryOfText = 1; //$tCat;
    $nfo = new information();
    $nfo->headline = $name;
    $nfo->text = $description;
    $tCont->information = $nfo;
    $auth->textContent = $tCont;
    
    $conDet = new ContactDetails();
    $tCom = new telecommunications();
    $tCom->telecommunicationIdentifier = $phone;
    $conDet->telecommunications = $tCom;
    $oRes = new OnlineResource();
    $oRes->linkage = $url;
    $conDet->onlineResource = $oRes;
    $addr = new ContactAddress();
    $addr->deliveryPoint = $address[0];
    $addr->postalCode = $address[1];
    $addr->cityName = $address[2];
    $addr->countryName = $address[3];
    $conDet->contactAddress = $addr;
    $auth->AuthorityContact_theContactDetails_ContactDetails = $conDet;
    
    $srvh = new ServiceHours();
    $sched = new scheduleByDayOfWeek();
    
    $tInt = new timeIntervalsByDayOfWeek();
    $mon = new dayOfWeek(1); //days
    $fri = new dayOfWeek(5);
    $tStart = new timeOfDayStart(); //times
    $tStart-> value = $weekHours[0];
    $tEnd= new timeOfDayEnd();
    $tEnd-> value =  $weekHours[1];
    $tInt->dayOfWeek = $mon; //add range mon-fri
    $tInt->dayOfWeek = $fri;
    $tInt->dayOfWeekIsRange = true;
    $tInt->timeOfDayStart = $tStart; //add times
    $tInt->timeOfDayEnd = $tEnd;
    $sched->timeIntervalsByDayOfWeek = $tInt;
    
    if ($wkndHours != null)
    {
        $wknd = new timeIntervalsByDayOfWeek();
        $mon = new dayOfWeek(6); //days
        $fri = new dayOfWeek(7);
        $tStart = new timeOfDayStart(); //times
        $tStart-> value = "10:00";
        $tEnd= new timeOfDayEnd();
        $tEnd-> value = "14:00";
        $wknd->dayOfWeek = $mon; //add range mon-fri
        $wknd->dayOfWeek = $fri;
        $wknd->dayOfWeekIsRange = true;
        $wknd->timeOfDayStart = $tStart; //add times
        $wknd->timeOfDayEnd = $tEnd;
        $sched->timeIntervalsByDayOfWeek = $wknd;
    }
    
    $nonSta = new NonStandardWorkingDay();
    
    
    $varD = new dateVariable();
    $varD = "No service during public national holidays.";
    $nonSta->dateVariable = $varD;
    $srvh->ExceptionalWorkday_partialWorkingDay_NonStandardWorkingDay = $nonSta;
    $srvh->scheduleByDayOfWeek = $sched;
    $auth->AuthorityHours_theServiceHours_ServiceHours = $srvh;
    return $auth;
}
?>