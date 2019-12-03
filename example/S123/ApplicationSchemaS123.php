<?php 

//define the path to the classes
define ( 'CLASS_PATH', '../../class/');

//include the framework- classes
include (CLASS_PATH.'CommonS100Type.php');
include (CLASS_PATH.'SimpleAttributeType.php');
include (CLASS_PATH.'EnumerationType.php');
include (CLASS_PATH.'CodeListType.php');
include (CLASS_PATH.'ComplexAttributeType.php');
include (CLASS_PATH.'AbstractFeatureType.php');
include (CLASS_PATH.'AbstractInformationType.php');
include (CLASS_PATH.'AbstractInformationAssociation.php');
include (CLASS_PATH.'AbstractFeatureAssociation.php');
include (CLASS_PATH.'AbstractRole.php');
include (CLASS_PATH.'Geometry.php');
include (CLASS_PATH.'valueTypeValidation.php');

//FIX issues manually HERE **********************************
//Add attribute missing from generated FC
class QualityOfTemporalVariation extends SimpleAttributeType{}

//include the generated FC for S-123 
include 'S123FC.php';

//Set the value to use for undefined upper bound as in [1..*]
define('MAX_OCCUR', 9999);

//define the root-object and 
class S123MarineRadioServices extends FeatureType
{
        public function __construct()
    {
        $this->addAttribute('services', 'FeatureType', 1, MAX_OCCUR );
    }
}


?>