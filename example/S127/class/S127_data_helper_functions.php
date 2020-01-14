<?php

include 'class/createFairway.php';

function addFeatureName($name)
{
    $fn = new featureName();
    $fn->name = $name;
    return $fn;
}

function addWktGeometry($wkt)
{
    $geom = new Geometry();
    $geom->addWkt($wkt);
    return $geom;
}

?>