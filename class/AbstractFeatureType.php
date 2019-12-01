<?php

abstract class AbstractFeatureType extends ComplexAttributeType
{
    public function __construct()
    {
        $this->addAttribute('Geometry', 'Geometry', 0, MAX_OCCUR);
    }
}

?>