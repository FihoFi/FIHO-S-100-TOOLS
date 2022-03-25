<?php   
/******************************************************************************
*
* Project:  FIHO-S-100-TOOLS
* Purpose:  Generate S-100 based GML- products
* Author:   Stefan Engström / traficom.fi
*
***************************************************************************
*   Copyright (C) 2019 by Stefan Engström / traficom.fi                  *
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

/**
 * @property Geometry[0..*] Geometry | The Geometry
 */
abstract class AbstractFeatureType extends AbstractType
{
        /**
     * @property Geometry[0..*] Geometry | The Geometry
     */
    public function __construct()
    {
        parent::__construct();
        
        //Geometry must be added last, and only one instance
        $this->addAttribute('Geometry', 'Geometry', 0, MAX_OCCUR);
    }
    
    public function setGeometry($featureUseType, $permittedPrimitives)
    {
        $this->attributes['Geometry']['featureUseType'] = $featureUseType;
        $this->attributes['Geometry']['permittedPrimitives'] = $permittedPrimitives;
    }
}

?>