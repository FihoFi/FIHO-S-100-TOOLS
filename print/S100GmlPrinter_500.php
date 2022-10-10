<?php
/******************************************************************************
*
* Project:  FIHO-S-100-TOOLS
* Purpose:  Generate S-100 based GML- products
* Author:   Stefan Engström / traficom.fi
*
***************************************************************************
*   Copyright (C) 2019 by Stefan Engström / traficom.fi                  *
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

namespace fiho\s100;

/**
 * Class prints the object as GML according to IHO S-100 5.0.0
 * @author Stefan Engstr�m
 *
 */
class S100GmlPrinter_500
{
    //data structure
    private $xml; //holds the SimpleXML object
    private $objectList = array(); //a list of references to the objects printed as $objectList[$gmlId] = SimpleXMLNode
    
    //Construct
    private $rootClass; //GML document root that shall be a subclass of ComplexAttribute
    private $productNs = null;
    private $schemaLocation = null;
    private $rolesNs = null;
    
    //XML init
    private $gmlId; //set by init function
    private $s100Ns = "http://www.iho.int/s100gml/5.0"; // NEW 5.0.0 NS
    private $gmlNs = 'http://www.opengis.net/gml/3.2';
    private $xlinkNs = 'http://www.w3.org/1999/xlink';
    
    //IdentificationData
    private $productIdentifier = null;
    private $productEdition= null;
    
    //IdentifiationData
    private $datasetFileIdentifier = null;
    private $abstract = null;
    private $datasetReferenceDate = null;
    private $datasetTitle = null;
    
    private $datasetPurpose = "base";
    private $updateNumber = 0;
    
    //epsg definition for geometries
    private $epsg = 'urn:ogc:def:crs:EPSG:4326';
    
    private $corner = array();
    
    function __construct ($rootClass, $productNs, $rolesNs, $schemaLocation, $date = null)
    {
        $this->rootClass = $rootClass;
        $this->productNs =  $productNs;
        $this->rolesNs = $rolesNs;
        $this->schemaLocation = $schemaLocation;
        $this->datasetReferenceDate = ($date == null) ? date('Y-m-d') : $date;
    }
 
    public function setProductData($productIdentifier, $productEdition = null)
    {
        $this->productIdentifier = $productIdentifier;
        $this->productEdition = $productEdition;
    }
    
    
    public function setIdentificationData($datasetTitle, $datasetFileIdentifier, $abstract= null)
    {
        $this->datasetTitle = $datasetTitle;
        $this->datasetFileIdentifier = $datasetFileIdentifier;
        $this->abstract = $abstract;
    }
        /**
         * Set the coordinates for Envelope upper corner
         * @param unknown $lat
         * @param unknown $lon
         */
        public function setBoundsUpperCorner($lat, $lon)
        {
            $this->corner['upper']['lat'] = $lat;
            $this->corner['upper']['lon'] = $lon;
        }
        
        /**
         * Set the coordinates for Envelope lower corner
         * @param unknown $lat
         * @param unknown $lon
         */
        public function setBoundsLowerCorner($lat, $lon)
        {
            $this->corner['lower']['lat'] = $lat;
            $this->corner['lower']['lon'] = $lon;
        }
        
        private function addBoundedBy()
        {
        
        $lat1 = $this->corner['lower']['lat'];
        $lon1 = $this->corner['lower']['lon'];
        $lat2 = $this->corner['upper']['lat'];
        $lon2 = $this->corner['upper']['lon'];
            
        $stub =
        "<gml:boundedBy>
        <gml:Envelope srsDimension=\"2\" srsName=\"$this->epsg\">
        <gml:lowerCorner>$lat1 $lon1</gml:lowerCorner>
        <gml:upperCorner>$lat2 $lon2</gml:upperCorner>
        </gml:Envelope>
        </gml:boundedBy>";
        
        return $stub;
        }
        
        /**
         * Object is needed by S-131 XSD
         * @return string
         */
        private function addDatasetIdentification()
        {
            $stub =
            "<S100:DatasetIdentificationInformation>
                <S100:encodingSpecification>S-100 Part 10b</S100:encodingSpecification>
                <S100:encodingSpecificationEdition>1.0</S100:encodingSpecificationEdition>
                <S100:productIdentifier>$this->productIdentifier</S100:productIdentifier>
                <S100:productEdition>$this->productEdition</S100:productEdition>
                <S100:applicationProfile>1.0</S100:applicationProfile>
                <S100:datasetFileIdentifier>$this->datasetFileIdentifier</S100:datasetFileIdentifier>
                <S100:datasetTitle>$this->datasetTitle</S100:datasetTitle>
                <S100:datasetReferenceDate>$this->datasetReferenceDate</S100:datasetReferenceDate>
                <S100:datasetLanguage>eng</S100:datasetLanguage>
                <S100:datasetAbstract>$this->abstract</S100:datasetAbstract>
                <S100:datasetTopicCategory>transportation</S100:datasetTopicCategory>
                <S100:datasetPurpose>$this->datasetPurpose</S100:datasetPurpose>
                <S100:updateNumber>$this->updateNumber</S100:updateNumber>
            </S100:DatasetIdentificationInformation>";
            
            return $stub;
        }
        
        function initializeXML()
        {
            $this->gmlId = 'FIHO.GML.'.uniqid();
            
            $stub =
            '<?xml version="1.0"?>'.
            '<Dataset
            {$this->schemaLocation}
            xmlns="{$this->productNs}"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns:gml="http://www.opengis.net/gml/3.2"
            xmlns:S100="{$this->s100Ns}"
            xmlns:s100_profile="http://www.iho.int/S-100/profile/s100_gmlProfile"
            xmlns:xlink="{$this->xlinkNs}"
            gml:id="{$this->gmlId}">'.
            $this->addBoundedBy().
            $this->addDatasetIdentification().
            '</Dataset>';
            
            $stub = str_replace('{$this->schemaLocation}', $this->schemaLocation, $stub);
            $stub = str_replace('{$this->productNs}', $this->productNs, $stub);
            $stub = str_replace('{$this->s100Ns}', $this->s100Ns, $stub);
            $stub = str_replace('{$this->xlinkNs}', $this->xlinkNs, $stub);
            $stub = str_replace('{$this->gmlId}', $this->gmlId, $stub);
            
            // creating object of SimpleXMLElement
            $doc = new \SimpleXMLElement($stub);
            
            $this->xml = $doc;
            //add <members> container required by 5.0.0
            $this->membersNode = $this->xml->addChild('members', null, $this->productNs);
            
        }
        /**
         * Basic function for printing the S-100 dataset as GML. 
         * The root object to use is passed into the constructor.  
         * @return string The full GML- document as a string
         */
        function printGML()
        { 
            //create XML with root- object into $this->xml
            $this->initializeXML();
            
            //reset objectlist thtat keeps track of objects that are already printed
            $this->objectList = array();
            
            //The function uses the attributes- array of the rootClass
            $this->printObject($this->rootClass->getAllAttributes(), $this->membersNode);
            
            /*
             $this->attributes[$name]['name'] = $name;
             $this->attributes[$name]['type'] = $type;
             $this->attributes[$name]['minOccur'] = $minOccur;
             $this->attributes[$name]['maxOccur'] = $maxOccur;
             $this->attributes[$name]['instances'] = array();
             */
            
            //Prettyprint XML- document
            $xmlDomDoc = new \DomDocument('1.0');
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
            //$gmlString = str_replace('xmlns="'.$this->defaultNs.'"', '', $gmlString);
            
            return trim($gmlString);
        }
        
        /**
         * This is an recursive function, creating the GML- structure as a SimpleXML- document
         * @param ArrayObject $attributes array of the calling "parent"
         * @param SimpleXMLElement $parentNode the XML- node of the calling "parent"
         */
        function printObject($attributes, $parentNode)
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
                
                //iterate all instances (as populated in this product)
                foreach($attribute['instances'] as $instance)
                {
                    if (false)
                    {
                        //dummy
                    }
                    
                    
                    //print association class with attributes
                    elseif ( ($instance instanceOf AbstractInformationAssociation || $instance instanceOf AbstractFeatureAssociation) && 
                        $instance->hasAttributes())
                    {
                    
                        $this->printAssociationWithAttributes($parentNode, $attribute, $instance);
                       
                    }
                    
                    
                    //Print feature / information object
                    elseif ($instance instanceOf AbstractType)
                    {
                        //print feature and set as new parent object
                        $newParent = $this->printFeature($parentNode, $attribute, $instance);
                        
                        //if associationClass, print also the referenced feature
                        if($instance instanceOf AbstractInformationAssociation ||
                            $instance instanceOf AbstractFeatureAssociation)
                        {
                            //use same "attribute parameters" as parent, but the associated object as instance
                            $this->printFeature($newParent, $attribute, $instance->associatedType);
                        }
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
         * Strip namespace from classname before using as tag
         * @param string $classWithNs
         * @return string
         */
        private function stripNamespace($classWithNs)
        {
            $pos = strrpos($classWithNs, '\\');
            return substr($classWithNs, $pos+1);
        }
        
        /**
         * Printing association with attributes is a special case. Printing is inverted such that the associatedType is printed first, 
         * and association with attributes as an extension to the referene tag.
         * @param unknown $parentNode
         * @param unknown $attribute
         * @param unknown $instance
         * @throws Exception
         */
        private function printAssociationWithAttributes($parentNode, $attribute, $instance)
        {
            // check that there is an associatedType set
            if ( $instance->associatedType == null)
            {
                $name = get_class($instance);
                throw new Exception("$name : Association with attributes must have associatedType set.");
            }
            
            //print inverted: first print the underlying associatedType diretly into the current parent feature
            //NOTE: the actual feature is also printed, but the rference is returned as $newParent
            $gmlReferenceTag = $this->printFeature($parentNode, $attribute, $instance->associatedType, true);
            
            //print the association node inline if the association has attributes
            $associationTag = $gmlReferenceTag->addChild($this->stripNamespace(get_class($instance)), null, $this->productNs);
            //print the content into the node
            $this->printObject($instance->getAllAttributes(), $associationTag);
        }
        
        /**
         * Print a FeatureType or InformationType
         * @param unknown $parentNode
         * @param unknown $attribute
         * @param unknown $instance
         * @param boolean $returnReference set this to true to return the xlink- reference node instead of the actual node
         * @return NULL|mixed
         */
        private function printFeature($parentNode, $attribute, $instance, $returnReference = false)
        {
            
            $node = null;
            $refNode = null;
            
            //Check if this feature was referenced elsewhere, and is already printed
            $isDuplicate = isset($this->objectList[$instance->gmlId]);
            
            //use duplicate as node instead of printing new
            if ($isDuplicate)
            {
                //SET NODE
                //use the existing node (referenced in objectList) if already printed to XML
                $node = $this->objectList[$instance->gmlId];
            }
            else
            {
               
                //SET NODE
                //add new node into <members>
                $node = $this->membersNode->addChild($this->stripNamespace(get_class($instance)), null,  $this->productNs);
                $node->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
                
                //add the new node to list, and reference list to not print again
                $this->objectList[$instance->gmlId] = $node;
            }
            
            
            //do not add reference for top-level objects
            if ($parentNode != $this->membersNode)
            {
                //The current $attribute holds the reference
                //The current $attribute['name'] holds the rolenames of the association
                //<theContactDetails xlink:href="#CP.CONDET.PILOT.AMP" xlink:arcrole="http://www.iho.int/s127/gml/1.0/roles/authorityContact"/>
                
                //the attribute name holds the rolenames separated by  underscore
                $rolenames = explode('_', $attribute['name']);
                
                //Assume additional underscore only in middle attribute-name
                $rolename = $rolenames[1];
                if(count($rolenames) == 4)
                    $rolename .= "_".$rolenames[2];
                
                $xlink_href = "#".$instance->gmlId;
                $xlink_role = $this->rolesNs;
                
                $xlink_needed = true; //assume needed
                
                //check if this XLINK was already added
                foreach($parentNode->children() as $tag=>$child)
                {
                    //child already exists
                   if ($tag == $rolename)
                   {
                       //check if attribute value is same
                       foreach($child->attributes('xlink', true) as $attr=>$val)
                       {
                           
                           if($attr == "href" && $val == $xlink_href)
                           {
                               $xlink_needed = false;
                               break;
                           }
                           
                       }
                   }
                }
                    
                //add ref to parent
                if ($xlink_needed)
                {
                    $ref = $parentNode->addChild($rolename, null, $this->productNs);
                    $ref->addAttribute('xlink:href', $xlink_href, $this->xlinkNs);
                    $ref->addAttribute('xlink:arcrole', $xlink_role.$rolenames[1], $this->xlinkNs);
                    
                    $refNode = $ref;
                }
            }
            
            //Print this object ONLY if it has not already been printed
            if (!$isDuplicate)
            {
                $this->printObject($instance->getAllAttributes(), $node); //pass node as parent to children
            }
            
            //return the reference as "current node and next parent" if association with attributes
            return $returnReference ? $refNode : $node;
        }
        
        /**
         * Print a ComplexAttribute
         * @param SimpleXMLElement $parentNode
         * @param CommonS100Type $instance
         */
        private function printComplex($parentNode, $instance)
        {
            //add the node for a ComplexAttribute
            $node = $parentNode->addChild($this->stripNamespace(get_class($instance)), null, $this->productNs);
            
            //print the content into the node
            $this->printObject($instance->getAllAttributes(), $node);
        }
        
        /**
         * Print a SimpleAttribute
         * @param SimpleXMLElement $parentNode
         * @param CommonS100Type $instance
         */
        private function printSimple($parentNode, $instance)
        {
            //Add the value of a Geometry
            if (get_class($instance) == 'fiho\\s100\\Geometry')
            {
                $this->printGeometry($parentNode, $instance); //print value in node
            }
            else
            {
                $simpleNode = null;
                
                //special printing of S100_TruncatedDate according to S-131 XSD
                if (isset($instance->valueType) && $instance->valueType == "S100_TruncatedDate")
                {
                    $simpleNode = $parentNode->addChild($this->stripNamespace(get_class($instance)), null, $this->productNs); //print node only
                    $simpleNode->addChild('date', $instance->oPrint(), $this->s100Ns);
                }
                else
                {
                    //Add the value of a SimpleAttribute (or Enumeration)
                    $simpleNode = $parentNode->addChild($this->stripNamespace(get_class($instance)), $instance->oPrint(), $this->productNs); //print value in node
                }
                //add also attribute code=X if enum / codelist
                if ($instance instanceOf EnumerationType)
                {
                    $simpleNode->addAttribute("code", $instance->cPrint());
                }
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
               
                $geometry = $parentNode->addChild('geometry', null, $this->productNs);
                $surface = $geometry->addChild('pointProperty',null, $this->s100Ns);
                $point = $surface->addChild('Point',null, $this->s100Ns); //XXX correct NS?
                $point->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
                $point->addAttribute('srsDimension', '2');
                $point->addAttribute('srsName', $this->epsg);
                
                //add positions
                $point->addChild('gml:pos',$positions,  $this->gmlNs);
                break;
            
            case 'LINE':
               
                //23.12.2020 print LINE as CURVE
                $geometry = $parentNode->addChild('geometry', null, $this->productNs);
                $curveProp = $geometry->addChild('S100:curveProperty',null,$this->s100Ns);
                $curve = $curveProp->addChild('S100:Curve',null, $this->s100Ns);
                    $curve->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
                    $curve->addAttribute('srsDimension', '2');
                    $curve->addAttribute('srsName', $this->epsg);
                $curveSeg = $curve->addChild('segments',null,$this->gmlNs);
                $line = $curveSeg->addChild('LineStringSegment',null,$this->gmlNs);
                 
                //add positions
                $line->addChild('posList',$positions, $this->gmlNs);
                break;
                          
            case 'SURFACE':
                
                $geometry = $parentNode->addChild('geometry', null, $this->productNs);
                $surface = $geometry->addChild('surfaceProperty',null, $this->s100Ns);
                //$polygon = $surface->addChild('Polygon',null, $this->gmlNs);
                $polygon = $surface->addChild('Surface',null, $this->s100Ns);
                $polygon->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
                $polygon->addAttribute('srsDimension', '2');
                $polygon->addAttribute('srsName', $this->epsg);
                $patches = $polygon->addChild('patches', null, $this->gmlNs);
                $polygonPatch = $patches->addChild('PolygonPatch', null, $this->gmlNs);
                
                $exterior = $polygonPatch->addChild('exterior',null, $this->gmlNs);
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