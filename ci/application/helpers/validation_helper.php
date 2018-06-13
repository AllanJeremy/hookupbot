<?php defined('BASEPATH') or exit('No direct script access allowed');

// Return true if the attribute was valid and false if it wasnt
function validate_attribute($attr,$val)
{
    $validation_func = 'vaidate_'.$attr;
    return call_user_func($validation_func,$val);
}

// Returns true if phone is valid
function validate_phone($val)
{
    //Check if the phone is within the acceptable length
    $val = (string)$val;
    return (strlen($val)>MAX_PHONE_LENGTH);
}

// Returns true if age is valid
function validate_age($val)
{
    $return_val = NULL;
    try
    {
        $age = abs((int)$age);
        $return_val = ($age >= MIN_VALID_AGE && $age <= MAX_VALID_AGE);
    }
    catch(Exception $e)
    {
        $return_val = FALSE;
    }
    return $return_val;
}

// Returns true if gender is valid
function validate_gender($val)
{
    $val = strtolower($val);
    return ($val == 'male' || $val == 'female');
}

// Returns true if gender_preference is valid
function validate_gender_preference($val)
{
    return validate_gender($val);
}

// Returns true if min_age is valid
function validate_min_age($val)
{
    return validate_age($val);
}

// Returns true if max_age is valid
function validate_max_age($val)
{
    return validate_age($val);
}

// Returns true if location is valid
function validate_location($val)
{
    return TRUE; # TODO: Add implementation
}

// Validates the value of appreciation
function _validate_appreciation($val)
{
    $val = strtolower($val);
    return ($val == 'yes' || $val == 'no');
}

// Returns true if needs_appreciation is valid
function validate_needs_appreciation($val)
{
    return _validate_appreciation($val);
}

// Returns true if providing_appreciation is valid
function validate_providing_appreciation($val)
{
    return _validate_appreciation($val);
}
