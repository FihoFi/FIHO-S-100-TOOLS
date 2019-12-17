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

/*
 * Validation functions for the different ValueTypes. These functions can be called in the individual SimpleType::validate($value) function
 */

function is_text($value)
{
    return is_string($value);
}

function is_boolean($value)
{
    return is_bool($value);
}

function is_time($value)
{
    //allow colon
    $pattern = '/^([01]?[0-9]|2[0-3]):?[0-5][0-9](:?[0-5][0-9])?/';
    return (preg_match($pattern, $value) == 1);
}

function is_URL($value)
{
    //XXX accept any string
    return is_string($value);
}

?>