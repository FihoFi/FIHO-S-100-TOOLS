# GML- printer

	• FeatureType and InformationType shall be printed as a <imember>
	• Some of these types are not top level objects in the PHP-structure, but referenced as attributes
	• Features and Informationtypes shall have a mandatory ID (to be used for gml)
	• GML association "arcrole" needs a url "namespace" specific for the PS / version


	In Dataset
	Iterate all feature/informationtypes
		print feature/informationtype to array key=id value = <imember>…</imember>
		Iterate all attributes in feature/informationtype
			If normal attribute
				print XML
			If associated feature/informationtype
				print association
					read GML ID
					explode attribute name into arcrole and role
					read GML ID from associated object
					print association tag
					//print association to associated object?
					flag associated feature/informationtype for printing  
					
	
	//array can be sorted alphabetically
	Result array[Type]['ID']['<imember>..</imember>']
	Result array[Type]['ID']['<imember>..</imember>']
	Result array[Type]['ID']['<imember>..</imember>']
