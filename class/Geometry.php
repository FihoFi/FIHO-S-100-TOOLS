<?php

class Geometry extends CommonS100Type
{
    private $positions = array();
    private $wkt = null;
    private $posCount = 0;
    
    /**
     * Add WGS-84 position
     */
    public function addPosition($lat, $lon)
    {
         if (!is_numeric($lat) || !is_numeric($lon))
             throw new Exception("Position is non-numeric");
         
         $this->positions[$this->posCount]['lat'] = $lat;
         $this->positions[$this->posCount]['lon'] = $lon;
         $this->posCount++;
    }
    
    public function addWKT($wkt)
    {
        if (!is_string($wkt))
            throw new Exception("Position is not a WKT-string");
            
        $this->wkt = $wkt;
    }
    
    public function arrayPrint()
    {
        $this->positions['wkt'] = $this->wkt; //add wkt to array
        return $this->positions;
    }
}

class surface extends geometry{}
class point extends geometry{}

?>