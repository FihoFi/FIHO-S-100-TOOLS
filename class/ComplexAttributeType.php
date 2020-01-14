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

/*
 * The complex attribute is a container of other Simple or Complex attributes.
 * Each attribute must be initialized in the constructor of a subclass.
 * 
 */
abstract class ComplexAttributeType extends CommonS100Type
{
    protected $attributes = array();
    
    public function __construct()
    {
        parent::__construct();
    }
    
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
        
        elseif (count($this->attributes[$name]['instances']) >= $this->attributes[$name]['maxOccur'])
        {
            throw(new Exception("Upper bound of array elements reached."));
        }
        
        //if object matches required type
        elseif ($value instanceOf $this->attributes[$name]['type'])
        {       
            //add object to next position in array
            $this->attributes[$name]['instances'][] = $value;
            
            //TODO: Maybe.. 
            // If added object is FeatureType or InformationType it is an association
            // if $crossAddAssoc == true then add also $this into the other object
            // $value->addAssoc($name, $value) -> need to find the attribute in the other onject?
        }
        
        //if we are trying to fill in a simple value, allow values
        elseif (new $this->attributes[$name]['type']() instanceOf SimpleAttributeType)
        {
            switch(gettype($value))
            {
                //allow only values of type
                case "integer":
                case "double":
                case "string":
                case "boolean":    
                    //cast value into object (object will throw exception if value not allowed)
                    $this->attributes[$name]['instances'][] = new $this->attributes[$name]['type']($value);
                break;
                default:
                    throw(new Exception("Unable to cast ".gettype($value)." into ". $this->attributes[$name]['type']));
            }
        }
        else
        {
            throw(new Exception("Cannot add wrong type of object into ".$this->attributes[$name]['type']));
        }
    }
    
    /*
     * Returns always the object
     */
    public function __get($name)
    {
        //return object if only 1 available
        if ($this->attributes[$name]['maxOccur'] == 1)
            return $this->attributes[$name]['instances'][0];
        
        //return array,if maxOccur > 1
        return $this->attributes[$name]['instances'];
    }
    
    //only allow specifically set attributes
    private function exists($name)
    {
        return isset($this->attributes[$name]);
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
        
        $this->attributes[$name]['name'] = $name;
        $this->attributes[$name]['type'] = $type;
        $this->attributes[$name]['minOccur'] = $minOccur;
        $this->attributes[$name]['maxOccur'] = $maxOccur;
        $this->attributes[$name]['instances'] = array();
    }
   
    /**
     * Check whether any attributes are set.
     * @return boolean
     */
    public function hasAttributes()
    {
        return count($this->attributes) > 0;
    }
    /**
     * Return all attributes, and reorder such that GEOMETRY comes last
     * @throws Exception
     * @return array
     */
    public function getAllAttributes()
    {
       //Reorder such that Geometry goes last
       if (isset($this->attributes['Geometry']))
       {
           $geometry = array_shift($this->attributes); // remove first item
           $this->attributes['Geometry'] = $geometry; // add as last item
       }
       
        
        //Iterate all attributes in current instance
        foreach($this->attributes as $attribute)
        {
            //Validate attribute and throw exception if needed
            $check = $this->validateAttribute($attribute['name']);
            if (  $check != null )
                throw new Exception($check);
        }
        
        return $this->attributes;
    }
    
    //return null if everything is ok
    private function validateAttribute($attributeName)
    {
        if (count($this->attributes[$attributeName]['instances']) < $this->attributes[$attributeName]['minOccur'])
            return "Minimum amount of value $attributeName not set in ".get_class($this);
        
        return null;
    }
}
	
?>