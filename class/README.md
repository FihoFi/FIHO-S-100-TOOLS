# Class

$this->addAttribute('AuthorityContact_theAuthority', 'Authority', 0, MAX_OCCUR);

//Role and arcrole can be exploded from attribute_name

Association needs;

The rolename as tag 
<S127:ContactDetails> ===> <theAuthority>
<theAuthority xlink:href="#CP.AUTORI.PILOT.CIPA" xlink:arcrole="http://www.iho.int/s127/gml/1.0/roles/authorityContact"/>

	//ADD ATTRIBUTE TO PermissionType
	<S100FC:attributeBinding sequential="false">
	<S100FC:multiplicity>
	<S100Base:lower>1</S100Base:lower>
	<S100Base:upper xsi:nil="false" infinite="false">1</S100Base:upper>
	</S100FC:multiplicity>
	<S100FC:permittedValues>
	<S100FC:value>1</S100FC:value>
	<S100FC:value>2</S100FC:value>
	<S100FC:value>3</S100FC:value>
	<S100FC:value>4</S100FC:value>
	<S100FC:value>5</S100FC:value>
	<S100FC:value>6</S100FC:value>
	</S100FC:permittedValues>
	<S100FC:attribute ref="categoryOfRelationship"/>
	</S100FC:attributeBinding>
