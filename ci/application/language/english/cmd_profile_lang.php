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
$lang['profile_get_phone'] = "Please enter your phone number.

Your phone number will be your means of contact once you get a hookup. Note: Your phone number will not be shared with anyone without your explicit confirmation.";

$lang['profile_get_age'] = "Please tell me your age (minimum 18 years)";

$lang['profile_get_gender'] = "Please select your gender";

$lang['profile_get_gender_preference'] = "What is your gender preference for hookups?";

$lang['profile_get_min_age'] = "What is your minimum age for a hookup partner? (Must be 18 or above)";

$lang['profile_get_max_age'] = "What is your maximum age for a hookup partner?";

$lang['profile_get_location'] = "You could share your location to help you find people available more easily";

$lang['profile_get_needs_appreciation'] = "Do you need to be appreciated/paid by your hookup partner for a hookup?";

$lang['profile_get_providing_appreciation'] = "Would you be willing to pay/appreciate your hookup partner?";

//Profile set attribute success or failure message
$lang['profile_attribute_success'] = "Successfully [action] the [attribute]";

$lang['profile_attribute_failure'] = "Failed to [action] the [attribute]. Invalid attribute or restricted access";

$lang['profile_unknown_attribute'] = "I cannot set the [attribute] attribute as I either do not know of it or I am not allowed to set it.";

$lang['profile_missing_attribute'] = "Please provide an appropriate attribute to set. Expected /profile set [attribute] [value].";

//Profile attribute info
$lang['profile_info_phone'] = "Your phone number is [phone].
This is the number you will use as your account number when making payments. It is also the number that will be sent to a hookup when you approve of their request.";

$lang['profile_info_age'] = "Your age : [age]
Your age determines the kind of people that can find you in the hookup pool.
It is also a confirmation that you are of legal age to use this service.";

$lang['profile_info_gender'] = "Your gender : [gender]
Your gender is used to determine your matches in the hookup pool.";

$lang['profile_info_gender_preference'] = "Your gender preference: [gender_preference]
Your gender preference is used to determine your matches in the hookup pool.";

$lang['profile_info_min_age'] = "Your minimum age: [min_age]
This is the minimum preferred age for a hookup partner.";

$lang['profile_info_max_age'] = "Your maximum age: [max_age]
This is the maximum preferred age for a hookup partner.";

$lang['profile_info_location'] = "Your location : [location]";

$lang['profile_info_needs_appreciation'] = "Would like appreciation: [needs_appreciation]
Whether or not you would like to be appreciated/paid by your hookup partner for a hookup.
May determine matches in hookup pool.";

$lang['profile_info_providing_appreciation'] = "Providing appreciation: [providing_appreciation]
Whether or not you are willing to appreciate/pay your hookup partner for the hookup.
May determine matches in hookup pool.";

$lang['profile_info'] = "Profile info";
$lang['profile_info'] .= "\n".$lang['profile_info_phone'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_age'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_gender'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_gender_preference'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_min_age'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_max_age'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_location'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_needs_appreciation'];
$lang['profile_info'] .= "\n\n".$lang['profile_info_providing_appreciation'];

$lang['profile_info_missing'] = "Sorry, I could not retrieve that information.";
