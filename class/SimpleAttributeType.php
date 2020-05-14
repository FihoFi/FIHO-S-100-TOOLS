<?php 
/******************************************************************************
*
* Project:  FIHO-S-100-TOOLS
* Purpose:  Generate S-100 based GML- products
* Author:   Stefan Engström / traficom.fi
*
***************************************************************************
*   Copyright (C) 2019 by Stefan Engström / traficom.fi                   *
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

 /*
  * The simple type is the basic building- block of all attributes
  * Simple types have only one value, and a specific validation function
  * Common get and set functions are used, but value can also be passed in constructor
  */
abstract class SimpleAttributeType extends CommonS100Type
{
    protected $value = null;
    
    /*
     * Constructor accepts the optional value for easy use of the function 
     */
    public function __construct($value = null)
    {
        if ($value !== null)
            $this->__set('value', $value);    
    }
    
    /*
     * Set the value as $simpleAttribute->value = "test"
     */
    public function __set($name, $value)
    {
        if ($this->validate($value))
        {
            $this->value = $value;
        }
        else
        {
            throw(new Exception("Illegal simple value"));
        }
    }
    
    /*
     * Get the value as $simpleAttribute->value
     */
    public function __get($value)
    {
        return $this->$value;
    }
   
    /*
     * Print the object
     */
    public function oPrint()
    {
        //print true / false for boolean
        if(is_boolean($this->value))
            return $this->value ? 'true' : 'false';
        
        return $this->value;
    }
}