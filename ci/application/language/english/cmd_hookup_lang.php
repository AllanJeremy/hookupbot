<?php defined('BASEPATH') or exit('No direct script access allowed');

//Hookup specific messages ~ prefixed with hookup_
$lang['hookup_intro'] = "This command deals with managing your hookups as well as hookup pool. Below are some subcommands to work with it."; #TODO: Consider markdown formatting to make the commands bold

$lang['hookup_select_success'] = 'Successfully selected the hookup partner. They have received a notification expressing your interest. If they accept, you can pay a hookup fee for their contact';

$lang['hookup_select_failure'] = 'Failed to select the hookup partner. Either the partner has already been taken or left the hookup pool.';

$lang['hookup_confirm_payment'] = "Please enter your mpesa confirmation code to proceed";

//Hookup pool specific messages ~ prefixed with pool_
$lang['pool_add_success'] = 'Successfully added you to the hookup pool';

$lang['pool_add_failure'] = 'Failed to add you to the hookup pool';

$lang['pool_remove_success'] = 'Successfully removed you from the hookup pool';

$lang['pool_remove_failure'] = 'Failed to remove you from the hookup pool';

$lang['missing_attr'] = 'Missing [attr_name]. Expected [command] [[attr_name]]';#TODO: Move this to general lang file

$lang['record_failed_action'] = 'Could not [action] [record]';#TODO: Move this to general lang file

$lang['pool_find_result'] = "[age]yr [gender] from [location]\nAppreciation:[appreciation]";

$lang['pool_found_matches'] = "Found [count] matches for you in the hookup pool";

$lang['pool_no_matches_found'] = "Sorry, I could not find matches for you based on your search criteria. Please try again later";

$lang['pool_result_details'] = "Age: [age]\nGender : [gender]\nMin preferred age:[min_age]\nMax preferred age:[max_age]\nPreferred gender: [gender_preference] \nLocation: [location]\nAppreciation:[appreciation]\nDetails: [details]";

$lang['pool_select_message'] = 'A [age]yr old [gender] from [location] matched with you and would like to hookup with you';

//Hookup request specific
$lang['request_accepted_message'] = "Congratulations, your hookup request ([age]yr old [gender] from [location]) accepted your request. You may now pay a [amount] hookup fee to [payment_info] and I'll send you her number once you confirm the payment.";

$lang['request_declined_message'] = "Your hookup request ([age]yr old [gender] from [location]) declined your hookup request. You will not be charged anything for this, feel free to check for other hookups in the hookup pool through /find";

$lang['error_generic'] = "An error occurred while trying to [action]";
