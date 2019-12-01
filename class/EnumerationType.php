<?php
/*
 * Enumeration is a special case of the simple attribute type.
 * Enumeration values and descriptions are added upon construction.
 */
abstract class EnumerationType extends SimpleAttributeType
{
	protected $enum = array();
	protected $description = null; //additional to value (key), enumerations hold the description
	
	public function __construct($value)
	{
	    parent::__construct($value);
	}
	
	protected function addValue($key, $value)
	{
	    $this->enum[$key] = $value;
	}
	
	//all enums can use the same validation
	protected function validate($value)
	{
	    foreach($this->enum as $k=>$v)
	    {
	        //Only the key of the enumeration is accepted as the value
	        if ($value == $k)
	        {
	            $this->description = $v; //set current description 
	            return true;
	        }
	    }
	    return  false;
	}
	
	/*
	 * Print the object
	 */
	public function oPrint()
	{
	    return $this->value .'-'. $this->description;
	}
}


?>