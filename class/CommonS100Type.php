<?php
/*
 * Base class for all S-100 types
 */
abstract class CommonS100Type
{
    public function __construct(){}
    
    public function getFamilyTree()
    {
        $ancestors = array();
        $ancestors[] = get_class($this);
        $current = $this;
        
        while ( ($parent = get_parent_class($current)) != "")
        {   
            $ancestors[] = $parent;
            $current = $parent;
        }
        
        return array_reverse($ancestors);
        
    }
    
    public function printSchema()
    {
        return null;
    }
}

?>