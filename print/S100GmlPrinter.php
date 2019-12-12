<?php

class S100GmlPrinter
{
    //shall be a subclass of ComplexAttribute
    private $rootClass;
    private $xml;
    private $ns = "127";
    private $refCounter = 1;
    
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
                    
                    //If instance is a FeatureType or InformationType, it shall be included as new <imember> with references;
                    if ($instance instanceOf FeatureType || $instance instanceOf InformationType)
                    {
                        //TODO
                        //USE GML ID:s for references
                        //Use rolenames
                        //Always add references as XLINK
                        //Only print feature once (put printed GML ID in array and check)
                        
                        $ns = 'S127_';
                        //add current feature as a new iMember at root- level
                        $newParentNode = $this->xml->addChild('imember');
                        $node = $newParentNode->addChild($ns.get_class($instance)); // get the type of the object
                        
                        //do not add reference fr top-level objects
                        if ($parentNode != $this->xml)
                        {
                            //The current $attribute holds the reference
                            //The current $attribute['name'] holds the rolenames of the association
                            
                            $roles = explode('_', $attribute['name']);
                            
                            $ref = $node->addChild('REF_'.$this->refCounter.'_'.$roles[0]);
                            
                            //The current object was referenced in the parent
                            $ref2 = $parentNode->addChild('REF_'.$this->refCounter.'_'.$roles[1]);
                            $this->refCounter++;
                        }
                    }
                    
                    else
                    {
                        //add the content of a ComplexAttribute instead of a reference
                        $node = $parentNode->addChild($ns.get_class($instance)); // get the type of the object
                    }
                    
                    //ADD COMPLEX ATTRIBUTES
                    $this->printObject($instance->getAllAttributes(), $node); //pass node as parent to children
                }
                
                //SIMPLE
                else
                {   //Add the value of a SimpleAttribute    
                    $node = $parentNode->addChild(get_class($instance), $instance->oPrint()); //print value in node
                }
            }
        }
    }  
}
?>