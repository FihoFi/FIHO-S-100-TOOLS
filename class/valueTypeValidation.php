<?php
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