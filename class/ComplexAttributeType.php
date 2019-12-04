<?php

/*
 * The complex attribute is a container of other Simple or Complex attributes.
 * Each attribute must be initialized in the constructor of a subclass.
 * 
 */
abstract class ComplexAttributeType extends CommonS100Type
{
    protected $values = array();
    
    public function __construct(){}
    
    /*
     * Value can be a simple attribute object or value, or a complex attribute object
     */
    public function __set($name, $value)
    {
        $this->setValue($name, $value, true);
    }
    
    //add association to both ends
    public function addAssoc($name, $value)
    {
        $this->setValue($name, $value, false);
    }
    
    private function setValue($name, $value, $crossAddAssoc)
    {
        if (!$this->exists($name))
        {
            throw(new Exception("Illegal attribute name $name in ".get_class($this)));
        }
        
        elseif (count($this->values[$name]['values']) >= $this->values[$name]['maxOccur'])
        {
            throw(new Exception("Upper bound of array elements reached."));
        }
        
        //if object matches required type
        elseif ($value instanceOf $this->values[$name]['type'])
        {       
            //add object to next position in array
            $this->values[$name]['values'][] = $value;
            
            //TODO: Maybe.. 
            // If added object is FeatureType or InformationType it is an association
            // if $crossAddAssoc == true then add also $this into the other object
            // $value->addAssoc($name, $value) -> need to find the attribute in the other onject?
        }
        
        //if we are trying to fill in a simple value, allow values
        elseif (new $this->values[$name]['type']() instanceOf SimpleAttributeType)
        {
            switch(gettype($value))
            {
                //allow only values of type
                case "integer":
                case "double":
                case "string":
                case "boolean":    
                    //cast value into object (object will throw exception if value not allowed)
                    $this->values[$name]['values'][] = new $this->values[$name]['type']($value);
                break;
                default:
                    throw(new Exception("Unable to cast ".gettype($value)." into ". $this->values[$name]['type']));
            }
        }
        else
        {
            throw(new Exception("Cannot add wrong type of object into ".$this->values[$name]['type']));
        }
    }
    
    /*
     * Returns always the object
     */
    public function __get($name)
    {
        //return object if only 1 available
        if ($this->values[$name]['maxOccur'] == 1)
            return $this->values[$name]['values'][0];
        
        //return array,if maxOccur > 1
        return $this->values[$name]['values'];
    }
    
    //only allow specifically set attributes
    private function exists($name)
    {
        return isset($this->values[$name]);
    }
    
    /*
     * Each attribute must be added before it is used. Attributes must be inherited from the CommonS100Type.
     */
    protected function addAttribute($name, $type = null, $minOccur = 0, $maxOccur = 1, $ordered = false)
    {
        if ($this->exists($name))
        {
            throw(new Exception("Attribute already exists"));
        }
        
        $type = ($type == null) ? $name : $type;
        
         //XXX Recursive object- creation uses too much resources.
         //Validate the object as Simple or Complex type
        //if (!new $type() instanceOf SimpleAttributeType && !new $type() instanceOf ComplexAttributeType)
        
        if (!is_subclass_of($type, 'CommonS100Type'))
        {
           throw new Exception("$type is not a valid attribute class");
        }
        
        $this->values[$name]['type'] = $type;
        $this->values[$name]['minOccur'] = $minOccur;
        $this->values[$name]['maxOccur'] = $maxOccur;
        $this->values[$name]['values'] = array();
    }
    
    //return null if everything is ok
    private function hasProblems($k)
    {
        if (count($this->values[$k]['values']) < $this->values[$k]['minOccur'])
            return "Minimum amount of value $k not set in ".get_class($this);
        
        return null;
    }
    
    private function isArray($name)
    {
        return $this->values[$name]['maxOccur'] > 1;
    }
    
    public function oPrint()
    {
       //print name of class
        echo "<ul><li>".get_class($this);
        
        
        foreach($this->values as $name=>$arr)
        {
            //validate 
            $check = $this->hasProblems($name);
            if (  $check != null )
                throw new Exception($check);
                
           echo $this->isArray($name) ? "<ul><li>".$name : '';
           foreach($arr['values'] as $obj)
               {
                   if ($obj instanceOf SimpleAttributeType)
                       echo "<ul><li>".$name . " = " . $obj->oPrint()."</ul>";
                   else
                   {    
                       echo $obj->oPrint();
                   }
               }
           echo $this->isArray($name) ? "</ul>" : '';
               
        }
        echo "</ul>";
    }
    
    public function arrayPrint()
    {
        
       $array = array();
       $baseName = get_class($this);
        foreach($this->values as $name=>$arr)
        {
            //validate
            $check = $this->hasProblems($name);
            if (  $check != null )
                throw new Exception($check);
                
                foreach($arr['values'] as $obj)
                {
                    if ($obj instanceOf SimpleAttributeType)
                    {
                        if ($name != $baseName)
                            if ($this->isArray($name))
                                $array[$baseName][$name][] = $obj->oPrint();
                            else
                                $array[$baseName][$name] = $obj->oPrint();
                        else
                            if ($this->isArray($name))
                                $array[$name][] = $obj->oPrint();
                            else
                                $array[$name] = $obj->oPrint();
                    }
                    else
                    {
                        if ( $this->isArray($name) )
                            $array[$baseName][$name][] = $obj->arrayPrint();
                        else
                            $array[$baseName][$name] = $obj->arrayPrint();
                    }
                }
        }
 
        return $array;
    }
    
    public function printSchema()
    {
        $schema = array();
        foreach($this->values as $k=>$v)
        {
            $schema[] = $v['type']."::".$k."[".$v['minOccur']."..".$v['maxOccur']."]";
        }
        
        return $schema;
    }
}
	
?>