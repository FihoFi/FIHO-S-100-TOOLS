<?php 
namespace fiho\s100\testS122;

//include the framework- classes
include '..\\..\\include.inc';

//include the generated FC for S-122 
include 'S122FC.php';

//Set the value to use for undefined upper bound as in [1..*]
define('MAX_OCCUR', 9999);

//define the root-object and 
class S122MarineProtectedAreas extends FeatureType
{
        public function __construct()
    {
        $this->addAttribute('services', 'FeatureType', 1, MAX_OCCUR );
    }
}
?>