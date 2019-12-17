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

class S100GmlPrinter
{
    private $rootClass; //shall be a subclass of ComplexAttribute
    private $xml;
    private $objectList = array(); //a list of references to the objects printed as $objectList[$gmlId] = SimpleXMLNode
    
    //NAMESPACE
    private $s100Ns = "http://www.iho.int/s100gml/1.0";
    private $gmlNs = 'http://www.opengis.net/gml/3.2';
    private $defaultNs = "http://www.traficom.fi";
   
    //set specific to PS
    private $productName = "S12X";
    private $productNs = "http://www.iho.int/S12X/gml/cs0/1.0";
    private $rolesNs = 'http://www.iho.int/s12X/gml/1.0/roles/';
    private $title = "TITLE not set";
    private $abstract = "ABSTRACT not set";
    
    private $epsg = 'urn:ogc:def:crs:EPSG:4326';
   
    function __construct ($rootClass, $productName = null, $productNs = null, $rolesNs = null, $title = null, $abstract=null)
    {
        //Update PS- specific data if given
        $this->productName = ( $productName == null ) ? $this->productName : $productName;
        $this->productNs = ( $productNs == null ) ? $this->productNs : $productNs;
        $this->rolesNs = ( $rolesNs == null ) ? $this->rolesNs : $rolesNs;
        $this->title = ( $title == null ) ? $this->title : $title;
        $this->abstract = ( $abstract == null ) ? $this->abstract : $abstract;
        
        $this->rootClass = $rootClass;
        
        // creating object of SimpleXMLElement
        $doc = new SimpleXMLElement(
            '<?xml version="1.0"?>
            <'.$this->productName.':Dataset 
            xmlns="'.$this->defaultNs.'"
            xmlns:'.$this->productName.'="'.$this->productNs.'"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
            xmlns:gml="http://www.opengis.net/gml/3.2"
            xmlns:S100="'.$this->s100Ns .'"
            xmlns:s100_profile="http://www.iho.int/S-100/profile/s100_gmlProfile"
            xmlns:xlink="http://www.w3.org/1999/xlink">'
            .$this->printEnvelopeString()
            .$this->printMetaInformationString().
            '</'.$this->productName.':Dataset>');
        
        $this->xml = $doc;
    }
        
    /**
     * Basic function for printing the S-100 dataset as GML. 
     * The root object to use is passed into the constructor.  
     * @return string The full GML- document as a string
     */
    function printStructure()
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
        return trim($result);
    }
    
    /**
     * This is an recursive function, cereating the GML- structure as a SimpleXML- document
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
               
               //if the instance is a Complex attribute, it has no final values
               if ($instance instanceOf ComplexAttributeType)
               {
                    //If instance is a FeatureType or InformationType, it shall be included asa new <imember> with references;
                    if ($instance instanceOf FeatureType || $instance instanceOf InformationType)
                    {
                        //Check if this feature was referenced elsewhere, and already printed
                        if (isset($this->objectList[$parentGmlId]))
                        {
                            //use existing node if already printed to XML
                            $node = $this->objectList[$parentGmlId];
                           
                        }
                        else
                        {
                            //add current feature as a new iMember at root- level
                            $newParentNode = $this->xml->addChild('imember', null, $this->defaultNs);
                            $node = $newParentNode->addChild(get_class($instance), null, $this->gmlNs); // get the type of the object
                            $node->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
                            
                            //add node to list
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
                            
                            //ref
                            $ref = $parentNode->addChild($rolenames[1], null, $this->defaultNs);
                            $ref->addAttribute('xlink:href', $xlink_href, 'xlink');
                            $ref->addAttribute('xlink:arcrole', $xlink_role.$rolenames[0], 'xlink');
                            
                            //XXX flag this objects gml:id to be the referenced in the referenced gml:id 
                        }
                        
                        //ADD COMPLEX ATTRIBUTES and pass GML-ID of parent
                        $this->printObject($instance->getAllAttributes(), $node, $instance->gmlId); //pass node as parent to children
                    }
                    
                    else
                    {
                        //add the content of a ComplexAttribute instead of a reference
                        $node = $parentNode->addChild(get_class($instance), null, $this->defaultNs); // get the type of the object
                        
                        //ADD COMPLEX ATTRIBUTES with GML-ID set to null
                        $this->printObject($instance->getAllAttributes(), $node, null); //pass node as parent to children
                    }
                    
                    
                }
                
                //SIMPLE
                else
                {   
                    //Add the value of a Geometry
                    if (get_class($instance) == 'Geometry')
                    {
                        $node = $this->printGeometry($parentNode, $instance); //print value in node
                        
                    }
                    else
                    {
                        //Add the value of a SimpleAttribute    
                        $node = $parentNode->addChild(get_class($instance), $instance->oPrint(), $this->defaultNs); //print value in node
                    }
                }
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
        //get positions and inert into lat/lon
        $positions = $instance->oPrint(true);
        
        //Read type from Geometry
        //Support: SURFACE, LINE, POINT
        switch ($instance->getType())
        {
        
            
        case 'POINT':
           
            $geometry = $parentNode->addChild('Geometry', null, $this->defaultNs);
            $surface = $geometry->addChild('pointProperty',null, $this->s100Ns);
            $point = $surface->addChild('Point',null, $this->gmlNs);
            $point->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
            $point->addAttribute('srsDimension', '2');
            $point->addAttribute('srsName', $this->epsg);
            
            //add positions
            $point->addChild('gml:pos',$positions,  $this->gmlNs);
            break;
        
        case 'LINE':
            
            $geometry = $parentNode->addChild('Geometry', null, $this->defaultNs);
            $line = $geometry->addChild('lineProperty',null,$this->s100Ns);
            $string = $line->addChild('LineString',null, $this->gmlNs);
            $string->addAttribute('gml:id', $instance->gmlId, $this->gmlNs);
            $string->addAttribute('srsDimension', '2');
            $string->addAttribute('srsName', $this->epsg);
            
            //add positions
            $string->addChild('posList',$positions, $this->gmlNs);
            break;
            
        case 'SURFACE':
            
            $geometry = $parentNode->addChild('Geometry', null, $this->defaultNs);
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
    
    //TODO
    function printSchemaString()
    {
        return '<?xml-model href="http://10.90.192.19/schema/S100/S127/0.2/20180824/S127.sch" type="application/xml" schematypens="http://purl.oclc.org/dsdl/schematron"?>';
    }
    
    function printEnvelopeString()
    {
        return 
        '<gml:boundedBy><gml:Envelope srsName="'.$this->epsg.'" srsDimension="2">
        <gml:lowerCorner>15 55</gml:lowerCorner>
        <gml:upperCorner>30 65</gml:upperCorner>
        </gml:Envelope></gml:boundedBy>';
    }
    
    //TODO
    function printMetaInformationString()
    {
        
        return
        '<DatasetIdentificationInformation>
        <S100:encodingSpecification>S-100 Part 10b</S100:encodingSpecification>
        <S100:encodingSpecificationEdition>1.0</S100:encodingSpecificationEdition>
        <S100:productIdentifier>'.$this->productName.'</S100:productIdentifier>
        <S100:productEdition>0.0.0 - TEST</S100:productEdition>
        <S100:applicationProfile/>
        <S100:datasetFileIdentifier>12X_ABCDE</S100:datasetFileIdentifier>
        <S100:datasetTitle>'.$this->title.'</S100:datasetTitle>
        <S100:datasetReferenceDate>'.date("Y-m-d").'</S100:datasetReferenceDate>
        <S100:datasetLanguage>en</S100:datasetLanguage>
        <S100:datasetAbstract>'.$this->abstract.'</S100:datasetAbstract>
        <S100:datasetTopicCategory>transportation</S100:datasetTopicCategory>
    </DatasetIdentificationInformation>
    <DatasetStructureInformation>
        <S100:datasetCoordOriginX>0.0</S100:datasetCoordOriginX>
        <S100:datasetCoordOriginY>0.0</S100:datasetCoordOriginY>
        <S100:datasetCoordOriginZ>0.0</S100:datasetCoordOriginZ>
        <S100:coordMultFactorX>1</S100:coordMultFactorX>
        <S100:coordMultFactorY>1</S100:coordMultFactorY>
        <S100:coordMultFactorZ>1</S100:coordMultFactorZ>
    </DatasetStructureInformation>';
    }
    
    //TODO
    function printQualityString()
    {
    	return '
    		<member>
		<S127:QualityOfNonBathymetricData gml:id="JS.1">
			<S100:featureObjectIdentifier>
				<S100:agency>JS</S100:agency>
				<S100:featureIdentificationNumber>110056</S100:featureIdentificationNumber>
				<S100:featureIdentificationSubdivision>1</S100:featureIdentificationSubdivision>
			</S100:featureObjectIdentifier>
			<categoryOfTemporalVariation>Unlikely to Change</categoryOfTemporalVariation>
			<sourceIndication>
				<categoryOfAuthority>maritime</categoryOfAuthority>
				<countryName>Jussland</countryName>
				<reportedDate><date>2018-01-01</date></reportedDate>
				<source>Jussland Hydrographic Office</source>
			</sourceIndication>
			<geometry><S100:surfaceProperty><gml:Surface gml:id="SURFACE001" srsDimension="2" srsName="urn:ogc:def:crs:EPSG::4326" >
				<gml:patches>
					<gml:PolygonPatch>
						<gml:exterior><gml:LinearRing><gml:posList>-29.0000000 58.0000000 -29.0000000 79.0000000 -41.0000000 79.0000000 -41.0000000 58.0000000 -29.0000000 58.0000000</gml:posList></gml:LinearRing></gml:exterior>
					</gml:PolygonPatch>
				</gml:patches>
			</gml:Surface></S100:surfaceProperty>
			</geometry>
		</S127:QualityOfNonBathymetricData>
	</member>';
    }
    
    //TODO
    function printDataCoverage()
    {
    	return
    	'	<member>
		<S127:DataCoverage gml:id="JS.0">
			<S100:featureObjectIdentifier>
				<S100:agency>JS</S100:agency>
				<S100:featureIdentificationNumber>110037</S100:featureIdentificationNumber>
				<S100:featureIdentificationSubdivision>1</S100:featureIdentificationSubdivision>
			</S100:featureObjectIdentifier>
			<maximumDisplayScale>999</maximumDisplayScale>
			<minimumDisplayScale>14999999</minimumDisplayScale>
			<geometry><S100:surfaceProperty><gml:Surface gml:id="SURFACE000" srsDimension="2" srsName="urn:ogc:def:crs:EPSG::4326" >
				<gml:patches>
					<gml:PolygonPatch>
						<gml:exterior><gml:LinearRing><gml:posList>-29.0000000 58.0000000 -29.0000000 79.0000000 -41.0000000 79.0000000 -41.0000000 58.0000000 -29.0000000 58.0000000</gml:posList></gml:LinearRing></gml:exterior>
				</gml:PolygonPatch>
				</gml:patches>
			</gml:Surface></S100:surfaceProperty>
			</geometry>
		</S127:DataCoverage>
	</member>
    	';
    }
}
?>