<?php
 /*
  * The simple type is the basic building- block of all attributes
  * Simple types have only one value, and a specific validation function
  * Common get and set functions are used, but value can also be passed in constructor
  */
abstract class SimpleAttributeType extends CommonS100Type
{
    protected $value = null;
    
    /*
     * Constructor accepts the optional value for easy use of the function 
     */
    public function __construct($value = null)
    {
        if ($value !== null)
            $this->__set('value', $value);    
    }
    
    /*
     * Set the value as $simpleAttribute->value = "test"
     */
    public function __set($name, $value)
    {
        if ($this->validate($value))
        {
            $this->value = $value;
        }
        else
        {
            throw(new Exception("Illegal simple value"));
        }
    }
    
    /*
     * Get the value as $simpleAttribute->value
     */
    public function __get($value)
    {
        return $this->$value;
    }
   
    /*
     * Print the object
     */
    public function oPrint()
    {
        return $this->value;
    }
}