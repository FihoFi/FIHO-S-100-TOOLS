<?php
//define the path to the classes
define ( 'FIHO_TOOLS_CLASS_PATH', 'class\\');
define ( 'FIHO_TOOLS_PRINT_PATH', 'print\\');

//include the framework- FIHO_TOOLS_CLASSes
include (FIHO_TOOLS_CLASS_PATH.'CommonS100Type.php');
include (FIHO_TOOLS_CLASS_PATH.'SimpleAttributeType.php');
include (FIHO_TOOLS_CLASS_PATH.'EnumerationType.php');
include (FIHO_TOOLS_CLASS_PATH.'CodeListType.php');
include (FIHO_TOOLS_CLASS_PATH.'ComplexAttributeType.php');
include (FIHO_TOOLS_CLASS_PATH.'AbstractType.php');
include (FIHO_TOOLS_CLASS_PATH.'AbstractFeatureType.php');
include (FIHO_TOOLS_CLASS_PATH.'AbstractInformationType.php');
include (FIHO_TOOLS_CLASS_PATH.'AbstractInformationAssociation.php');
include (FIHO_TOOLS_CLASS_PATH.'AbstractFeatureAssociation.php');
include (FIHO_TOOLS_CLASS_PATH.'AbstractRole.php');
include (FIHO_TOOLS_CLASS_PATH.'Geometry.php');
include (FIHO_TOOLS_CLASS_PATH.'valueTypeValidation.php');
include (FIHO_TOOLS_CLASS_PATH.'Exception.php');

include (FIHO_TOOLS_PRINT_PATH.'S100GmlPrinter.php');
?>