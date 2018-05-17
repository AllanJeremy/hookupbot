<?php defined('BASEPATH') or exit('No direct script access allowed');

// Profile command related messages
$lang['profile_description'] = "This command allows you to manage your profile. Below is a a list of sub-commands that it has to work with your profile:
/profile start - Starts the profile setup process
/profile info - Returns information about your profile
/profile set [attribute] [value] - sets or updates the value of an attribute. For example /p set age 20 will set the age to 20. Multiple commands can be set through multiple lines
/profile remove [attribute] - removes the value of an attribute
";

$lang['profile_start'] = "I know you want to hookup. Before you do that, we'll need to setup a few things to help you find the best hookups for you.";#Starting the profile query command

//Getting information from the user
$lang['profile_get_phone'] = "Your phone number will be your means of contact once you get a hookup. Note: Your phone number will not be shared with anyone without your explicit confirmation.";

$lang['profile_get_age'] = "Please tell me your age (minimum 18 years)";

$lang['profile_get_gender'] = "Please select your gender";

$lang['profile_get_gender_preference'] = "What is your gender preference for hookups?";

$lang['profile_get_min_age'] = "What is your minimum age for a hookup partner? (Must be 18 or above)";

$lang['profile_get_max_age'] = "What is your maximum age for a hookup partner?";

$lang['profile_get_location'] = "You could share your location to help you find people available more easily";

$lang['profile_get_needs_appreciation'] = "Do you need to be appreciated/paid by your hookup partner for a hookup?";

$lang['profile_get_providing_appreciation'] = "Would you be willing to pay/appreciate your hookup partner?";

//Profile set attribute success or failure message
$lang['profile_attribute_success'] = 'Successfully [action] the [attribute]';#TODO: Move into a library

$lang['profile_attribute_failure'] = 'Failed to [action] the [attribute]';#TODO: Move into a library

$lang['profile_unknown_attribute'] = 'I cannot set the [attribute] attribute as I either do not know of it or I am not allowed to set it.';
