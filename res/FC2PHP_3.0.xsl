<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:S100FC="http://www.iho.int/S100FC" xmlns:S100Base="http://www.iho.int/S100Base" xmlns:S100CI="http://www.iho.int/S100CI" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:S100FD="http://www.iho.int/S100FD" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.iho.int/S100FC">
	<xsl:output method="text"/>
	<!-- Add javadoc for enumeration and codelist-->
	<xsl:template name="javadoc_enum">
		<xsl:value-of select="S100FC:code"/>=<xsl:value-of select="S100FC:label"/>
		<!--/ <xsl:value-of select="S100FC:definition"/>-->
	</xsl:template>
	<!-- Add init- functions for enumeration and codelist-->
	<xsl:template name="init_enum">
					$this->addValue(<xsl:value-of select="S100FC:code"/>, "<xsl:value-of select="S100FC:label"/>
		<xsl:text>");</xsl:text>
	</xsl:template>
	<!-- Add definition of referenced attribute -->
	<xsl:template name="ref_attribute_description">
		<xsl:variable name="refCode" select="S100FC:attribute/@ref"/>
		<xsl:for-each select="//S100FC:code[text()=$refCode]">
			<xsl:text> </xsl:text>
			<xsl:value-of select="../S100FC:definition"/>
		</xsl:for-each>
	</xsl:template>
	<!-- Add inherited attributes as @property to Feature and Information comments-->
	<xsl:template name="addParentDescription">
		<xsl:variable name="currCode" select="S100FC:code"/>
		<xsl:for-each select="S100FC:attributeBinding">
		* @property <xsl:value-of select="S100FC:attribute/@ref"/>[<xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>..<xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
			<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">*</xsl:if>] <xsl:value-of select="S100FC:attribute/@ref"/> |Defined in <xsl:value-of select="$currCode"/>
		</xsl:for-each>
		<xsl:for-each select="S100FC:featureBinding">
		* @property <xsl:value-of select="S100FC:informationType/@ref"/>
			<xsl:value-of select="S100FC:featureType/@ref"/>[<xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>..<xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
			<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">*</xsl:if>] <xsl:value-of select="S100FC:association/@ref"/>_<xsl:value-of select="S100FC:role/@ref"/>_<xsl:value-of select="S100FC:featureType/@ref"/> |Defined in <xsl:value-of select="$currCode"/>
		</xsl:for-each>
		<xsl:for-each select="S100FC:informationBinding">
		* @property <xsl:value-of select="S100FC:informationType/@ref"/>
			<xsl:value-of select="S100FC:featureType/@ref"/>[<xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>..<xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
			<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">*</xsl:if>] <xsl:value-of select="S100FC:association/@ref"/>_<xsl:value-of select="S100FC:role/@ref"/>_<xsl:value-of select="S100FC:informationType/@ref"/> |Defined in <xsl:value-of select="$currCode"/>
		</xsl:for-each>
		<xsl:variable name="refCode" select="S100FC:superType"/>
		<xsl:for-each select="//S100FC:S100_FC_FeatureType[S100FC:code/text()=$refCode]">
			<xsl:call-template name="addParentDescription"/>
		</xsl:for-each>
		<xsl:for-each select="//S100FC:S100_FC_InformationType[S100FC:code/text()=$refCode]">
			<xsl:call-template name="addParentDescription"/>
		</xsl:for-each>
	</xsl:template>
	<xsl:template match="/S100FC:S100_FC_FeatureCatalogue">
		<xsl:text disable-output-escaping="yes">&lt;?php</xsl:text>
	namespace fiho\s100\<xsl:value-of select="translate(S100FC:product, '-', '')"/>;
	use fiho\S100\ {AbstractFeatureAssociation, AbstractFeatureType, AbstractInformationAssociation, AbstractInformationType, AbstractRole, CodeListType, ComplexAttributeType, EnumerationType, Geometry, SimpleAttributeType};
	define ( 'CURRENT_PS', '<xsl:value-of select="translate(S100FC:product, '-', '')"/>' );
	/**
	* S100 PS Features as PHP, generated by FIHO-S100-TOOLS-FC2PHP
	* Name: <xsl:value-of select="S100FC:name"/>
	* Product: <xsl:value-of select="S100FC:product"/>
	* Version, num.: <xsl:value-of select="S100FC:versionNumber"/>
	* Version, date: <xsl:value-of select="S100FC:versionDate"/>
	*/
	
	//********************************* SIMPLE ATTRIBUTES ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_SimpleAttributes/S100FC:S100_FC_SimpleAttribute">
			<xsl:choose>
				<xsl:when test="S100FC:valueType = 'enumeration'">
			/**
			* Enumeration <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* @property int value
			*/
			class <xsl:value-of select="S100FC:code"/> extends EnumerationType
			{
				/**
				* Enumeration <xsl:value-of select="S100FC:code"/>
				*
				* <xsl:value-of select="S100FC:definition"/>
				* @param int value
				* <xsl:for-each select="S100FC:listedValues/S100FC:listedValue">
				* <xsl:call-template name="javadoc_enum"/>
					</xsl:for-each>
				*/
				 public function __construct($value = null)
				{
					<xsl:for-each select="S100FC:listedValues/S100FC:listedValue">
						<xsl:call-template name="init_enum"/>
					</xsl:for-each>
					parent::__construct($value);
				}
			}
	     </xsl:when>
				<xsl:when test="S100FC:valueType = 'S100_CodeList'">
			/**
			* S100_CodeList <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* @property int value
			*/
			class <xsl:value-of select="S100FC:code"/> extends CodeListType
			{
				/**
				* S100_CodeList <xsl:value-of select="S100FC:code"/>
				*
				* <xsl:value-of select="S100FC:definition"/>
				* @param int value
				* <xsl:for-each select="S100FC:listedValues/S100FC:listedValue">
				* <xsl:call-template name="javadoc_enum"/>
					</xsl:for-each>
				*/
				 public function __construct($value = null)
				{
					<xsl:for-each select="S100FC:listedValues/S100FC:listedValue">
						<xsl:call-template name="init_enum"/>
					</xsl:for-each>
					parent::__construct($value);
				}
			}
	     </xsl:when>
				<xsl:otherwise>
			/**
			* SimpleAttribute <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* @property <xsl:value-of select="S100FC:valueType"/> value
			*/
			class <xsl:value-of select="S100FC:code"/> extends SimpleAttributeType
			{
				public function validate($value)
				{
					return is_<xsl:value-of select="S100FC:valueType"/>($value);
				}
			}
         </xsl:otherwise>
			</xsl:choose>
		</xsl:for-each>
	
	//********************************* COMPLEX ATTRIBUTES ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_ComplexAttributes/S100FC:S100_FC_ComplexAttribute">
		/**
		* ComplexAttribute <xsl:value-of select="S100FC:code"/>
		*
		* <xsl:value-of select="S100FC:definition"/>
		* <xsl:for-each select="S100FC:subAttributeBinding">
		* @property <xsl:value-of select="S100FC:attribute/@ref"/>[<xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>..<xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">*</xsl:if>]  <xsl:value-of select="S100FC:attribute/@ref"/>
			</xsl:for-each>
		*/
		class <xsl:value-of select="S100FC:code"/> extends ComplexAttributeType
		{
			/**
			* ComplexAttribute <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			*<xsl:for-each select="S100FC:subAttributeBinding">
			* @property <xsl:value-of select="S100FC:attribute/@ref"/>[<xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>..<xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">*</xsl:if>]  <xsl:value-of select="S100FC:attribute/@ref"/>
				<xsl:call-template name="ref_attribute_description"/>
			</xsl:for-each>
			*/
			public function __construct()
			{
				parent::__construct();
				
				<xsl:for-each select="S100FC:subAttributeBinding">$this->addAttribute('<xsl:value-of select="S100FC:attribute/@ref"/>', '<xsl:value-of select="S100FC:attribute/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
			}
		}
	</xsl:for-each>

	
	//********************************* FEATURE TYPES ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_FeatureTypes/S100FC:S100_FC_FeatureType">
		/**
		* FeatureType <xsl:value-of select="S100FC:code"/>
		*
		* <xsl:value-of select="S100FC:definition"/>
		* 
		* <xsl:call-template name="addParentDescription"/>
		*/
		<xsl:if test="@isAbstract='true'">abstract</xsl:if> class <xsl:value-of select="S100FC:code"/> extends <xsl:value-of select="S100FC:superType"/>
			<xsl:if test="not(S100FC:superType)">AbstractFeatureType</xsl:if>
		{
		
			/**
			* FeatureType <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* 
			* <xsl:call-template name="addParentDescription"/>
			*/
			public function __construct()
			{
				parent::__construct();
				
				//AttributeBindings
				<xsl:for-each select="S100FC:attributeBinding">$this->addAttribute('<xsl:value-of select="S100FC:attribute/@ref"/>', '<xsl:value-of select="S100FC:attribute/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
				//FeatureBindings
				<xsl:for-each select="S100FC:featureBinding">$this->addFeatureBinding('<xsl:value-of select="S100FC:association/@ref"/>','<xsl:value-of select="S100FC:role/@ref"/>', '<xsl:value-of select="S100FC:featureType/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if> );
				</xsl:for-each>
				//InformationBindings
				<xsl:for-each select="S100FC:informationBinding">$this->addInformationBinding('<xsl:value-of select="S100FC:association/@ref"/>', '<xsl:value-of select="S100FC:role/@ref"/>', '<xsl:value-of select="S100FC:informationType/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
				//Geometry parameters. The null- element is added to array for technical reasons
				$this->setGeometry('<xsl:value-of select="S100FC:featureUseType"/>', array(<xsl:for-each select="S100FC:permittedPrimitives">'<xsl:value-of select="current()"/>', </xsl:for-each>null));
				
			}
			
			
		}
	</xsl:for-each>
	
	
	//********************************* INFORMATION TYPES ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_InformationTypes/S100FC:S100_FC_InformationType">
		/**
		* InformationType <xsl:value-of select="S100FC:code"/>
		*
		* <xsl:value-of select="S100FC:definition"/>
		* 
		* <xsl:call-template name="addParentDescription"/>
		*/
		<xsl:if test="@isAbstract='true'">abstract</xsl:if> class <xsl:value-of select="S100FC:code"/> extends <xsl:value-of select="S100FC:superType"/>
			<xsl:if test="not(S100FC:superType)">AbstractInformationType</xsl:if>
		{
			
			/**
			* InformationType <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* 
			* <xsl:call-template name="addParentDescription"/>
			*/
			public function __construct()
			{
				parent::__construct();
				
				//AttributeBindings
				<xsl:for-each select="S100FC:attributeBinding">$this->addAttribute('<xsl:value-of select="S100FC:attribute/@ref"/>', '<xsl:value-of select="S100FC:attribute/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
				//FeatureBindings
				<xsl:for-each select="S100FC:featureBinding">$this->addFeatureBinding('<xsl:value-of select="S100FC:association/@ref"/>','<xsl:value-of select="S100FC:role/@ref"/>', '<xsl:value-of select="S100FC:featureType/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if> );
				</xsl:for-each>
				//InformationBindings
				<xsl:for-each select="S100FC:informationBinding">$this->addInformationBinding('<xsl:value-of select="S100FC:association/@ref"/>', '<xsl:value-of select="S100FC:role/@ref"/>', '<xsl:value-of select="S100FC:informationType/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
			}
		}
	</xsl:for-each>
	
	//********************************* ROLES ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_Roles/S100FC:S100_FC_Role">
		/**
		* Role
		* <xsl:value-of select="S100FC:definition"/>
		* 
		* <xsl:value-of select="S100FC:remarks"/>
		*/
		class <xsl:value-of select="S100FC:code"/> extends AbstractRole{}
	</xsl:for-each>
	
	//********************************* FeatureAssociations ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_FeatureAssociations/S100FC:S100_FC_FeatureAssociation">
		/**
		* FeatureAssociation
		*
		* <xsl:value-of select="S100FC:definition"/>
		* 
		* <xsl:call-template name="addParentDescription"/>
		*/
		class <xsl:value-of select="S100FC:code"/> extends AbstractFeatureAssociation
		{
			<xsl:for-each select="S100FC:role">
			public $<xsl:value-of select="@ref"/> = null;
			</xsl:for-each>
			
			/**
			* FeatureAssociationType <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* 
			* <xsl:call-template name="addParentDescription"/>
			*/
			public function __construct()
			{
				parent::__construct();
				
				//AttributeBindings
				<xsl:for-each select="S100FC:attributeBinding">$this->addAttribute('<xsl:value-of select="S100FC:attribute/@ref"/>', '<xsl:value-of select="S100FC:attribute/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
			}
			
		}
	</xsl:for-each>
	
	
	//********************************* InformationAssociations ***********************************************************
	<xsl:for-each select="S100FC:S100_FC_InformationAssociations/S100FC:S100_FC_InformationAssociation">
		/**
		* InformationAssociation
		*
		* <xsl:value-of select="S100FC:definition"/>
		*
		* <xsl:call-template name="addParentDescription"/>
		*/
		class <xsl:value-of select="S100FC:code"/> extends AbstractInformationAssociation
		{
			<xsl:for-each select="S100FC:role">
			public $<xsl:value-of select="@ref"/> = null;
			</xsl:for-each>
			
			/**
			* InformationAssociationType <xsl:value-of select="S100FC:code"/>
			*
			* <xsl:value-of select="S100FC:definition"/>
			* 
			* <xsl:call-template name="addParentDescription"/>
			*/
			public function __construct()
			{
				parent::__construct();
				
				//AttributeBindings
				<xsl:for-each select="S100FC:attributeBinding">$this->addAttribute('<xsl:value-of select="S100FC:attribute/@ref"/>', '<xsl:value-of select="S100FC:attribute/@ref"/>', <xsl:value-of select="S100FC:multiplicity/S100Base:lower"/>, <xsl:value-of select="S100FC:multiplicity/S100Base:upper"/>
				<xsl:if test="S100FC:multiplicity/S100Base:upper/@infinite='true'">MAX_OCCUR</xsl:if>);
				</xsl:for-each>
			}
		}
	</xsl:for-each>
		<xsl:text disable-output-escaping="yes">?&gt;</xsl:text>
	</xsl:template>
</xsl:stylesheet>
