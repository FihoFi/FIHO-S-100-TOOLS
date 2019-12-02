# FIHO-S-100-TOOLS

Tools related to S-100 data model testing and product development. The aim of this project is to create a framework for creating S-12X datastructures using a programming langage and IDE. PHP is used here, and the framework is intended for use within the Eclipse IDE. The generated files contain PHPDoc- style comments, that provide some degree of intelligent code completion and code hinting when imported as a PHP- project in Eclipse.

## Getting started?
Currently documentation is sparse. The best way forward is to first read this README, and then check out the directory 'example'.

## Install?
The project should be straightforward to set up on any PHP- enabled webeserver. It has been developed on Apache with PHP 5.3. Simply copy all the directories to a folder under your webroot, and point your browser to a subfolder within the 'example'- directory. 

## Setup of the project

1. PHP- classes reflecting basic S-100 structure (FeatureType, ComplexAttributeType etc)
2. XSL- translation of the S-100 Feature catalog generates the datamodel as PHP- classes
3. Additional data-validation and product creation using the framework and classes as regular PHP.
4. A printer- function is used to print out the datamodel for verification.

## Structure of the classes in directory 'class'

* **CommonS100Type** - base class for all S-100 types
  * **SimpleAttributeType extends CommonS100Type** - basic building block of all attributes
    * **EnumerationType extends SimpleAttributeType** - allowed enumeration values are added upon construction
      *  **CodeListType extends EnumerationType** - functionally similar to the EnumerationType
  * **ComplexAttributeType extends CommonS100Type** - basic container for ALL complex objects
    * **AbstractFeatureType extends ComplexAttributeType** - Geometry added by default
    * **AbstractInformationType extends ComplexAttributeType**- simple extension of the  ComplexAttributeType
  * **AbstractInformationAssociation extends CommonS100Type** - simple placeholder implementation
  * **AbstractFeatureAssociation extends CommonS100Type** - simple placeholder implementation
  * **AbstractRole extends CommonS100Type** - simple placeholder implementation
  * **Geometry extends CommonS100Type** - simple placeholder, holds a Geometry as WKT
 * valueTypeValidation.php - this file holds public functions for validating SimpleValues.  
  
## Structure of the classes in directory 'res'

The resource directory holds the XSLT- translation script that is used to tarnslate the machine-readable S100 FeatureCatalogue into PHP- classes. The result is a single PHP- file containing the data model, with suclasses, extending the classes in class- directory.

## Structure of the classes in directory 'print'

Currently this directory holds a single PHP-file S100Printer.php with public functions for data output.
_It is intended that these functions are encapsulated into a class later._
The functions will accept a $class - parameter, which should refer to a CommonS100ype- based class.
The oPrint() function of the referred class is used to retreive the data as an array.

## Structure of the classes in directory 'example'
This directory holds a working sample, using the S-127 Feature catalogue. 

## Version 0.0.1
TBD
