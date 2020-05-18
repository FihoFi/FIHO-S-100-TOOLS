<?php 

abstract class AbstractType extends ComplexAttributeType
{
    //GML ID
    public $gmlId = null;
    
    public $role = null;
    
    public function __construct()
    {
        parent::__construct();
        
        //GENERATE ID
        $this->gmlId = 'fiho.'.get_class($this).'.'.CommonS100Type::nextId();
    }
    /**
     * 
     * @param string $association
     * @param string $role
     * @param string $type
     * @param number $minOccur
     * @param number $maxOccur
     * @param boolean $ordered
     */
    public function addFeatureBinding($associationClass, $role, $associatedType, $minOccur = 0, $maxOccur = 1, $ordered = false)
    {
        $name = $associationClass.'_'.$role.'_'.$associatedType; //create name
        
        $this->role = new $role; //instantiate the role for test
        
        $assoc = new $associationClass();
        //IF Association has attributes, use linking
        if ($assoc->hasAttributes())
        {
            //Add the associationClass instead of final type
            $this->addAttribute($name, $associationClass, $minOccur, $maxOccur, $ordered);
            //Add flag of allowed type
            $this->attributes[$name]['associatedType'] = $associatedType; //check isset()?//Add the associationClass instead of final type
        }
        //If no attributes, use direct association (most objects)
        else
        {
            $this->addAttribute($name, $associatedType, $minOccur, $maxOccur, $ordered);
        }
    }
    
    /**
     *
     * @param string $association
     * @param string $role
     * @param string $type
     * @param number $minOccur
     * @param number $maxOccur
     * @param boolean $ordered
     */
    public function addInformationBinding($associationClass, $role, $associatedType, $minOccur = 0, $maxOccur = 1, $ordered = false)
    {
        $this->addFeatureBinding($associationClass, $role, $associatedType, $minOccur, $maxOccur, $ordered);
    }
}
?>