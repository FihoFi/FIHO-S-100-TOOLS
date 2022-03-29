<?php  
/******************************************************************************
*
* Project:  FIHO-S-100-TOOLS
* Purpose:  Generate S-100 based GML- products
* Author:   Stefan Engström / traficom.fi
*
***************************************************************************
*   Copyright (C) 2019 by Stefan Engström / traficom.fi                                 *
*                                                                         *
*   This program is free software; you can redistribute it and/or modify  *
*   it under the terms of the GNU General Public License as published by  *
*   the Free Software Foundation; either version 2 of the License, or     *
*   (at your option) any later version.                                   *
*                                                                         *
*   This program is distributed in the hope that it will be useful,       *
*   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
*   GNU General Public License for more details.                          *
*                                                                         *
*   You should have received a copy of the GNU General Public License     *
*   along with this program; if not, write to the                         *
*   Free Software Foundation, Inc.,                                       *
*   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
***************************************************************************
*/

namespace fiho\s100
{
/*
 * Enumeration is a special case of the simple attribute type.
 * Enumeration values and descriptions are added upon construction.
 */
abstract class EnumerationType extends SimpleAttributeType
{
	protected $enum = array();
	protected $description = null; //additional to value (key), enumerations hold the description
	
	public function __construct($value)
	{
	    parent::__construct($value);
	}
	
	protected function addValue($key, $value)
	{
	    $this->enum[$key] = $value;
	}
	
	//all enums can use the same validation
	protected function validate($value)
	{
	    foreach($this->enum as $k=>$v)
	    {
	        //Only the key of the enumeration is accepted as the value
	        if ($value == $k)
	        {
	            $this->description = $v; //set current description 
	            return true;
	        }
	    }
	    return  false;
	}
	
	/*
	 * Print the object
	 */
	public function oPrint()
	{
	    return $this->description;
	}
}
}
?>