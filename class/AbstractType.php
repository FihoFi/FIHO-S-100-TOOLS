<?php

abstract class AbstractType extends ComplexAttributeType
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function addFeatureBinding()
    {}
    
    public function addInformationBinding()
    {}
    
}
?>