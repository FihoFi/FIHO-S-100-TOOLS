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

/**
 * Class prints the object as GML
 * @author Stefan Engstr�m
 *
 */
class S100GmlPrinter
{
    private $rootClass; //shall be a subclass of ComplexAttribute
    private $gmlId;
    private $xml; //holds the SimpleXML
    private $objectList = array(); //a list of references to the objects printed as $objectList[$gmlId] = SimpleXMLNode
    
    //NAMESPACE
    private $s100Ns = "http://www.iho.int/s100gml/1.0";
    private $gmlNs = 'http://www.opengis.net/gml/3.2';
    private $defaultNs = "http://www.traficom.fi";
   
    //set specific to PS
    private $schemaLocation = 'xsi:schemaLocation="http://www.iho.int/S127/gml/cs0/0.1 ../../../schemas/S127/0.2/20180824/S127.xsd"';
    private $productName = "S12X";
    private $productNs = "http://www.iho.int/S12X/gml/cs0/1.0";
    private $rolesNs = 'http://www.iho.int/s12X/gml/1.0/roles/';
    private $title = "TITLE not set";
    private $abstract = "ABSTRACT not set";
    
    private $epsg = 'urn:ogc:def:crs:EPSG:4326';
   
    function __construct ($rootClass, $productName = null, $productNs = null, $rolesNs = null, $title = null, $abstract=null, $schemaLocation=null)
    {
        $this->rootClass = $rootClass;
        $this->gmlId = 'FIHO.GML.'.uniqid();
       
        //Update PS- specific data if given
        $this->productName = ( $productName == null ) ? $this->productName : $productName;
        $this->productNs = ( $productNs == null ) ? $this->productNs : $productNs;
        $this->rolesNs = ( $rolesNs == null ) ? $this->rolesNs : $rolesNs;
        $this->title = ( $title == null ) ? $this->title : $title;
        $this->abstract = ( $abstract == null ) ? $this->abstract : $abstract;
        $this->schemaLocation = ( $schemaLocation == null ) ? $this->schemaLocation : $schemaLocation;
    
        $this->initializeXML();

    }
     
        function initializeXML()
        {
            $stub = '<?xml version="1.0"?>
            <{$this->productName}:Dataset {$this->schemaLocation}
            xmlns="{$this->defaultNs}"
            xmlns:{$this->productName}="{$this->productNs}"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:gml="http://www.opengis.net/gml/3.2"
            xmlns:S100="{$this->s100Ns}"
            xmlns:s100_profile="http://www.iho.int/S-100/profile/s100_gmlProfile"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            gml:id="{$this->gmlId}">
            </{$this->productName}:Dataset>';
                
            $stub = str_replace('{$this->productName}', $this->productName, $stub);
            $stub = str_replace('{$this->schemaLocation}', $this->schemaLocation, $stub);
            $stub = str_replace('{$this->defaultNs}', $this->defaultNs, $stub);
            $stub = str_replace('{$this->productNs}', $this->productNs, $stub);
            $stub = str_replace('{$this->s100Ns}', $this->s100Ns, $stub);
            $stub = str_replace('{$this->gmlId}', $this->gmlId, $stub);
            
            // creating object of SimpleXMLElement
            $doc = new SimpleXMLElement($stub);
        
        $this->xml = $doc;
    }
        
    /**
     * Basic function for printing the S-100 dataset as GML. 
     * The root object to use is passed into the constructor.  
     * @return string The full GML- document as a string
     */
    function printGML()
    { 
        //reset objectlist
        $this->objectList = array();
        
        //The function returns the attributes- array of the rootClass
        $this->printObject($this->rootClass->getAllAttributes(), $this->xml, $this->rootClass->gmlId);
        
        /*
         $this->attributes[$name]['name'] = $name;
         $this->attributes[$name]['type'] = $type;
         $this->attributes[$name]['minOccur'] = $minOccur;
         $this->attributes[$name]['maxOccur'] = $maxOccur;
         $this->attributes[$name]['instances'] = array();
         */
        
        //Prettyprint XML- document
        $xmlDomDoc = new DomDocument('1.0');
        $xmlDomDoc->preserveWhiteSpace = false;
        $xmlDomDoc->formatOutput = true;
        $xmlDomDoc->loadXML($this->xml->saveXML());
        $result = $xmlDomDoc->saveXML();
        return $this->cleanupGML($result);
    }
    
    /**
     * Function cleans up the GML
     * @param string $gmlString
     */
    private function cleanupGml($gmlString)
    {
        //remove default xmlns- declaration, needed for correct behaviour of SimpleXML
        $gmlString = str_replace('xmlns="'.$this->defaultNs.'"', '', $gmlString);
                
        //XXX different in S128 0.7.5
        if ($this->productName == "S128")
            $gmlString = str_replace('Dataset', 'DataSet', $gmlString);
        
        return trim($gmlString);
    }
    
    /**
     * This is an recursive function, creating the GML- structure as a SimpleXML- document
     * @param ArrayObject $attributes array of the calling "parent"
     * @param SimpleXMLElement $parentNode the XML- node of the calling "parent"
     * @param string $gmlId GML ID of the parent node for easy access. NULL if parent is a Complex Attribute
     */
    function printObject($attributes, $parentNode, $parentGmlId)
    {
       //iterate all attributes (as specified in the PS)
        foreach($attributes as $attribute)
        {   
            /*
                $attribute['name'] = $name;
                $attribute['type'] = $type;
                $attribute['minOccur'] = $minOccur;
                $attribute['maxOccur'] = $maxOccur;
                $attribute['instances'] = array();
            */
            
            //iterate alla instances (as populated in this product)
            foreach($attribute['instances'] as $instance)
            {

                //Print feature or information <member> or <imember>
                if ($instance instanceOf AbstractFeatureType || $instance instanceOf AbstractInformationType)
                {
                    $this->printFeature($parentNode, $attribute, $instance);
                }
                
                //Print Complex attribute, it has no final values
                elseif ($instance instanceOf ComplexAttributeType)
                {
                    $this->printComplex($parentNode, $instance);
                }
                
                //Print Simple attribute (The actual values)
                else
                {   
                   $this->printSimple($parentNode, $instance);
                }
            }
        }
    }
    
    /**
     * Print a Feature or Information type
     * @param SimpleXMLElement $parentNode
     * @param array $attribute
     * @param CommonS100Type $instance
     */
    private function printFeature($parentNode, $attribute, $instance)
    {
        $node = null;
        
        //Check if this feature was referenced elsewhere, and is already printed
        $isDuplicate = isset($this->objectList[$instance->gmlId]);
        
        //use duplicate as node instead of printing new
        if ($isDuplicate)
        {
            //use existing node if already printed to XML
            $node = $this->objectList[$instance->gmlId];
        }
        else
        {
            //features in member
            $memberType = $instance instanceOf AbstractFeatureType ? 'member' : 'imember';
            
            //add current feature as a new iMember at root- level
            $newParentNode = $this->xml->addChild($memberType, null, $this->defaultNs);
            $node = $newParentNode->addChild(get_class($instance), null, $this->productNs); // get the type of the object
            $node->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
            
            //add node to list, and reference list to not print again
            $this->objectList[$instance->gmlId] = $node;
        }
        
        
        //do not add reference for top-level objects
        if ($parentNode != $this->xml)
        {
            //The current $attribute holds the reference
            //The current $attribute['name'] holds the rolenames of the association
            //<theContactDetails xlink:href="#CP.CONDET.PILOT.AMP" xlink:arcrole="http://www.iho.int/s127/gml/1.0/roles/authorityContact"/>
            
            //the attribute name holds the rolenames separated by  underscore
            $rolenames = explode('_', $attribute['name']);
            
            $xlink_href = "#".$instance->gmlId;
            $xlink_role = $this->rolesNs;
            
            //add ref to parent
            $ref = $parentNode->addChild($rolenames[1], null, $this->defaultNs);
            $ref->addAttribute('xlink:href', $xlink_href, "http://www.w3.org/1999/xlink");
            $ref->addAttribute('xlink:arcrole', $xlink_role.$rolenames[1], "http://www.w3.org/1999/xlink");
            
            //$arc_xlink_href = "#".$parentGmlId;
            //add arc-ref to node
            //XXX ABILITY TO PRINT ARCREF, BUT INVALID BY XSD
             //XXX in existing node insert is done at the end of file
             /*
             $ref = $node->addChild($rolenames[0], null, $this->defaultNs);
             $ref->addAttribute('xlink:href', $arc_xlink_href, "http://www.w3.org/1999/xlink");
             $ref->addAttribute('xlink:arcrole', $xlink_role.$rolenames[1], "http://www.w3.org/1999/xlink");
             */
            
            //This works, but needs gml:id
            //$ref = $node->addChild("invFeatureAssociation", null, $this->s100Ns);
            
        }
        
        //Print this object ONLY if it has not already been printed
        if (!$isDuplicate)
            $this->printObject($instance->getAllAttributes(), $node, $instance->gmlId); //pass node as parent to children
    }
    
    /**
     * Print a ComplexAttribute
     * @param SimpleXMLElement $parentNode
     * @param CommonS100Type $instance
     */
    private function printComplex($parentNode, $instance)
    {
        //If instance is further a FeatureType or InformationType, it shall be included asa new <imember> with references;
        
        //else
        {
            //add the content of a ComplexAttribute instead of a reference
            $node = $parentNode->addChild(get_class($instance), null, $this->defaultNs); // get the type of the object
            
            //ADD COMPLEX ATTRIBUTES with GML-ID set to null
            $this->printObject($instance->getAllAttributes(), $node, null); //pass node as parent to children
        }
        
    }
    
    /**
     * Print a SimpleAttribute
     * @param SimpleXMLElement $parentNode
     * @param CommonS100Type $instance
     */
    private function printSimple($parentNode, $instance)
    {
        //Add the value of a Geometry
        if (get_class($instance) == 'Geometry')
        {
            $this->printGeometry($parentNode, $instance); //print value in node
            
        }
        else
        {
            //Add the value of a SimpleAttribute
            $parentNode->addChild(get_class($instance), $instance->oPrint(), $this->defaultNs); //print value in node
        }
    }
    /**
     * This is a helper- function to print the <Geometry/> into the parent SimpleXML-node
     * srsData is hardcoded into the function
     * @param SimpleXMLElement $parentNode
     * @param Geometry $instance
     */
    private function printGeometry($parentNode, $instance)
    {
        //get positions and insert into lat/lon
        $positions = $instance->oPrint(true);
        
        //Read type from Geometry
        //Support: SURFACE, LINE, POINT
        switch ($instance->getType())
        {
        
            
        case 'POINT':
           
            $geometry = $parentNode->addChild('geometry', null, $this->defaultNs);
            $surface = $geometry->addChild('pointProperty',null, $this->s100Ns);
            $point = $surface->addChild('Point',null, $this->s100Ns); //XXX correct NS?
            $point->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
            $point->addAttribute('srsDimension', '2');
            $point->addAttribute('srsName', $this->epsg);
            
            //add positions
            $point->addChild('gml:pos',$positions,  $this->gmlNs);
            break;
        
        case 'LINE':
            
            $geometry = $parentNode->addChild('geometry', null, $this->defaultNs);
            $line = $geometry->addChild('lineProperty',null,$this->s100Ns);
            $string = $line->addChild('LineString',null, $this->gmlNs);
            $string->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
            $string->addAttribute('srsDimension', '2');
            $string->addAttribute('srsName', $this->epsg);
            
            //add positions
            $string->addChild('posList',$positions, $this->gmlNs);
            break;
            
        case 'SURFACE':
            
            $geometry = $parentNode->addChild('geometry', null, $this->defaultNs);
            $surface = $geometry->addChild('surfaceProperty',null, $this->s100Ns);
            $polygon = $surface->addChild('Polygon',null, $this->gmlNs);
            $polygon->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
            $polygon->addAttribute('srsDimension', '2');
            $polygon->addAttribute('srsName', $this->epsg);
            $exterior = $polygon->addChild('exterior',null, $this->gmlNs);
            $linear = $exterior->addChild('LinearRing',null, $this->gmlNs);
            
            //add positions
            $linear->addChild('posList',$positions, $this->gmlNs);
            break;
            
        default:
            throw new Exception ("Unsupported Geometry type provided to S100GmlPrinter");
            
        }
         
    }
}
?>