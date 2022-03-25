<?php  
/******************************************************************************
*
* Project:  FIHO-S-100-TOOLS
* Purpose:  Generate S-100 based GML- products
* Author:   Stefan Engström / traficom.fi
*
***************************************************************************
*   Copyright (C) 2019 by Stefan Engström / traficom.fi                                 *
*                                                                         *
*   This program is free software; you can redistribute it and/or modify  *
*   it under the terms of the GNU General Public License as published by  *
*   the Free Software Foundation; either version 2 of the License, or     *
*   (at your option) any later version.                                   *
*                                                                         *
*   This program is distributed in the hope that it will be useful,       *
*   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
*   GNU General Public License for more details.                          *
*                                                                         *
*   You should have received a copy of the GNU General Public License     *
*   along with this program; if not, write to the                         *
*   Free Software Foundation, Inc.,                                       *
*   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
***************************************************************************
*/

class Geometry extends CommonS100Type
{
    private $wkt = null;
    private $permittedPrimitivesArr = array('point', 'curve', 'surface');
    
    //GML ID
    public $gmlId = null;
    
    public function __construct()
    {
        //GENERATE ID
        $this->gmlId = 'fiho.'.get_class($this).'.'.CommonS100Type::nextId();
    }
    
    public function addWKT($wkt)
    {
        if (!is_string($wkt))
            throw new Exception("Position is not a WKT-string");
            
        $this->wkt = $wkt;
    }
    
    /*
     * Set the permitted primitives accordint to S-100, assumed values include
     * noGeometry, point, pointSet, curve, surface, coverage
     */
    public function setPermittedPrimitives($permittedPrimitivesArr)
    {
        $this->permittedPrimitivesArr = $permittedPrimitivesArr;
    }
    
    //Return WKT as Poslist
    private function getPosList($invert)
    {
        $posList = array();
        //MATCH POSITIONS;
        $matches = array();
        preg_match_all('/[0-9\.]+/', $this->wkt, $matches);
        
        //no inversion
        if (!$invert)
            return implode(" ", $matches[0]);
        
        //reorder lon/lat into lat/lon
        for($i=0; $i < count($matches[0]) ; $i+=2)
        {
            $posList[] = $matches[0][$i+1];
            $posList[] = $matches[0][$i];
        }
        
        return implode(" ", $posList);
    }
    public function getType()
    {
        switch (substr($this->wkt, 0, 5))
        {
            case 'POINT':
                if (!in_array('point', $this->permittedPrimitivesArr))
                    throw new Exception('Geometry: '. $this->wkt.' is not supported by this FeatureType');
                return 'POINT'; 
                break;
            
            case 'POLYG':
                if (!in_array('surface', $this->permittedPrimitivesArr))
                    throw new Exception('Geometry: '. $this->wkt.' is not supported by this FeatureType');
                    return 'SURFACE';
                break;
                
            case 'LINES':
                if (!in_array('curve', $this->permittedPrimitivesArr))
                    throw new Exception('Geometry: '. $this->wkt.' is not supported by this FeatureType');
                return 'LINE';
                break;
            
            default:
                throw new Exception('Geometry: '. $this->wkt.' is not supported by class Geometry');
        }
        
    }
    
    public function oPrint($invert=false)
    {
        return $this->getPosList($invert);
    }
}

?>