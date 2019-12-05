<?php

/*
	*point
	pointSet
	curve
	*surface
	coverage
	arcByCenterPoint
	circleByCenterPoint
	noGeometry
	
	<geometry>
		<S100:pointProperty>
			<S100:Point gml:id="CB.PILBOP.P.US5VA15M.US001873947800050.BOYLAT" srsDimension="2" srsName="urn:ogc:def:crs:EPSG::4326">
				<gml:pos>
					38.351 -76.361
				</gml:pos>
			</S100:Point>
		</S100:pointProperty>
	</geometry>
		
	 <geometry>                
		<S100:surfaceProperty>
			<S100:Polygon gml:id="CB.PILBOP.P.ReedyPtAnchorageArea" srsDimension="2" srsName="urn:ogc:def:crs:EPSG::4326">
				<gml:exterior>
					<gml:LinearRing>
						<gml:posList>
							39.571 -75.572
							39.574 -75.569
							39.566 -75.557
							39.564 -75.559
							39.571 -75.572
						</gml:posList>
					</gml:LinearRing>
				</gml:exterior>
			</S100:Polygon>
			</S100:surfaceProperty>
	</geometry>

*/

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
    
    public function oPrint()
    {
        return "TBD";
    }
}
    

class surface extends geometry{}
class point extends geometry{}

?>