<?php

class S100GmlPrinter
{
    //shall be a subclass of ComplexAttribute
    private $rootClass;
    private $xml;
    private $ns = "127";
    
    function __construct ($class)
    {
        $this->rootClass = $class;
        
        // creating object of SimpleXMLElement
        $this->xml = new SimpleXMLElement('<?xml version="1.0"?><S100_DataSet></S100_DataSet>');
    }
    
    function printStructure()
    { 
    
        /*
            $this->attributes[$name]['name'] = $name;
            $this->attributes[$name]['type'] = $type;
            $this->attributes[$name]['minOccur'] = $minOccur;
            $this->attributes[$name]['maxOccur'] = $maxOccur;
            $this->attributes[$name]['instances'] = array();
        */
        
        $this->printObject($this->rootClass->getAllAttributes(), $this->xml);
        
        //load xml as DomDocument
        $xmlDomDoc = new DomDocument('1.0');
        $xmlDomDoc->preserveWhiteSpace = false;
        $xmlDomDoc->formatOutput = true;
        $xmlDomDoc->loadXML($this->xml->saveXML());
        $result = $xmlDomDoc->saveXML();
        return trim($result);
    }
    
    function printObject($attributes, $parentNode)
    {
       
        foreach($attributes as $attribute)
        {   
            /*
                $attribute['name'] = $name;
                $attribute['type'] = $type;
                $attribute['minOccur'] = $minOccur;
                $attribute['maxOccur'] = $maxOccur;
                $attribute['instances'] = array();
            */
            foreach($attribute['instances'] as $instance)
            {
                
                //if Complex attribute, no final values
               if ($instance instanceOf ComplexAttributeType)
                {
                    $ns = '';
                    $baseType = $attribute['type'];
                    //featuretype or informationtype
                    if ($baseType == 'FeatureType' || $baseType == 'InformationType')
                    {
                        //$parentNode = $parentNode->addChild('imember');
                        $ns = 'S127_';
                    }
                    $node = $parentNode->addChild($ns.get_class($instance)); // get the type of the object
                    $this->printObject($instance->getAllAttributes(), $node); //pass node as parent to children
                }
                
                else
                {       
                    $node = $parentNode->addChild(get_class($instance), $instance->oPrint()); //print value in node
                }
            }
        }
    }  
}
?>