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

class FeatureType extends AbstractFeatureType
{
    
}

class editionNumber extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_numeric($value);
    }
}
class issueDate extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}
class graphic extends ComplexAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class classification extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class copyright extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class dataTypeVersion extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class purpose extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class verticalDatum extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class price extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class chartNumber extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class compilationScale extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class producerCode extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class producerNation extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class specificUsage extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class frameDimensions extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class printInformation extends ComplexAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class datasetName extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class updateDate extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class updateNumber extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class productSpecification extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class content extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class publicationNumber extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

class onlineDescription extends SimpleAttributeType
{
    public function validate($value)
    {
        return is_text($value);
    }
}

//include the generated FC for S-123 
include 'S128FC.php';

//Set the value to use for undefined upper bound as in [1..*]
define('MAX_OCCUR', 9999);


//define the root-object and 
class S128Catalogue extends FeatureType
{
        public function __construct()
    {
        $this->addAttribute('services', 'AbstractFeatureType', 1, MAX_OCCUR );
    }
}

?>