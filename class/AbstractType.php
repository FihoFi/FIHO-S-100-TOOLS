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
abstract class AbstractType extends ComplexAttributeType
{
    //GML ID
    public $gmlId = null;
    
    public $role = null;
    
    public function __construct()
    {
        parent::__construct();
        
        //GENERATE ID
        $this->gmlId = str_replace('\\', '.', get_class($this)).'.'.CommonS100Type::nextId();
    }
    /**
     * 
     * @param string $association
     * @param string $role
     * @param string $type
     * @param number $minOccur
     * @param number $maxOccur
     * @param boolean $ordered
     */
    public function addFeatureBinding($associationClass, $role, $associatedType, $minOccur = 0, $maxOccur = 1, $ordered = false)
    {
        $name = $associationClass.'_'.$role.'_'.$associatedType; //create name
        
        $nsRole = 'fiho\\s100\\'.$role;
        $this->role = new $nsRole; //instantiate the role for test
        
        $nsAssoc = 'fiho\\s100\\'.$associationClass;
        $assoc = new $nsAssoc;
        //IF Association has attributes, use linking
        if ($assoc->hasAttributes())
        {
            //Add the associationClass instead of final type
            $this->addAttribute($name, $associationClass, $minOccur, $maxOccur, $ordered);
            //Add flag of allowed type
            $this->attributes[$name]['associatedType'] = $associatedType; //check isset()?//Add the associationClass instead of final type
        }
        //If no attributes, use direct association (most objects)
        else
        {
            $this->addAttribute($name, $associatedType, $minOccur, $maxOccur, $ordered);
        }
    }
    
    /**
     *
     * @param string $association
     * @param string $role
     * @param string $type
     * @param number $minOccur
     * @param number $maxOccur
     * @param boolean $ordered
     */
    public function addInformationBinding($associationClass, $role, $associatedType, $minOccur = 0, $maxOccur = 1, $ordered = false)
    {
        $this->addFeatureBinding($associationClass, $role, $associatedType, $minOccur, $maxOccur, $ordered);
    }
}
}
?>