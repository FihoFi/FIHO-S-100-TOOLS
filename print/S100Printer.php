<?php

function getS100class($class)
{
    return $class->oPrint();
}


// function definition to convert array to xml
function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_numeric($key) ){
            $key = 'item'; //.($key+1); //dealing with <0/>..<n/> issues
        }
        if( is_array($value) ) {
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
    }
}

function getS100json($class)
{
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($class->arrayPrint(), JSON_PRETTY_PRINT);
}

function getS100xml($class)
{
    try {
        // initializing or creating array
        $data = $class->arrayPrint();
        
    } catch (Exception $e) {
        exit("Exception in S100Printer: ".$e->getMessage());
    }
    
    
    // creating object of SimpleXMLElement
    $xml_data = new SimpleXMLElement('<?xml version="1.0"?><S100_DataSet></S100_DataSet>');
    
    // function call to convert array to xml
    array_to_xml($data, $xml_data);
    
    
    //load xml as DomDocument
    $xmlDomDoc = new DomDocument('1.0');
    $xmlDomDoc->preserveWhiteSpace = false;
    $xmlDomDoc->formatOutput = true;
    $xmlDomDoc->loadXML($xml_data->saveXML());
    $result = $xmlDomDoc->saveXML();
    
    //remove <item.. and </item tags
    //$result = preg_replace('/<item_(\d+)>/', "/*item $1 begin */", $result);
    //$result = preg_replace('/<\/item_(\d+)>/', "/*item $1 end*/", $result);
    
    return trim($result);
}

//Function prints schema of all S11- based classes
function printS100classes()
{
    $classes = array();
    
    $i=0;
    foreach( get_declared_classes() as $class )
    {
        $reflectionClass = new ReflectionClass($class);
        $fileName = $reflectionClass->getFileName();
        //check if class is of correct type
        if ($reflectionClass->isInstantiable() && $reflectionClass->isSubclassOf("CommonS100Type") )
        {
            //class shall have empty constructor, create instance
            $instance = new $class();
            
            $classes[$i]['family'] = $instance->getFamilyTree();
            $classes[$i]['file'] = $fileName;
            if ($class != "CommonS100Type" && $instance->printSchema() != null)
            {
                $classes[$i]['schema'] = $instance->printSchema();
            }
            $i++;
        }
    }
    
    echo "<table border = 1>";
    foreach ( $classes as $member )
    {   echo "<tr>";
    foreach ($member as $k=>$v)
    {
        switch($k)
        {
            
            case 'file':
                //echo "<td>$v</td>";
                break;
                
            case 'family':
                foreach ($v as $td)
                    echo "<td>$td</td>";
                    break;
                    
            case 'schema':
                echo "<td>";
                foreach ($v as $td)
                    echo $td."<br/>";
                    echo "</td>";
                    break;
        }
    }
    echo "</tr>";
    }
    echo "</table>";
}
?>