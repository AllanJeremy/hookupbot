<?php defined('BASEPATH') or exit('No direct script access allowed');

//Hookup specific messages ~ prefixed with hookup_
$lang['hookup_intro'] = "This command deals with managing your hookups as well as hookup pool
/hookup add ~ adds you to hookup pool (/add can be used in its place)
/hookup remove ~ removes you from the hookup pool (/remove can be used in its place)
/hookup find ~ finds potential hookup partners that match your criteria (/find can be used in its place)
/hookup view [pool_id] ~ shows the profile of the hookup partner with a pool id of [pool_id]
/hookup select [pool_id] ~ selects the hookup with the pool id of [pool_id]. Sends a message expressing your interest to hookup with them
"; #TODO: Consider markdown formatting to make the commands bold

$lang['hookup_select_success'] = 'Successfully selected the hookup partner. They have received a notification expressing your interest. If they accept, you can pay a hookup fee for their contact';

$lang['hookup_select_failure'] = 'Failed to select the hookup partner. Either the partner has already been taken or left the hookup pool.';

//Hookup pool specific messages ~ prefixed with pool_
$lang['pool_add_success'] = 'Successfully added you to the hookup pool';

$lang['pool_add_failure'] = 'Failed to add you to the hookup pool';

$lang['pool_remove_success'] = 'Successfully removed you from the hookup pool';

$lang['pool_remove_failure'] = 'Failed to remove you from the hookup pool';

$lang['missing_attr'] = 'Missing [attr_name]. Expected [command] [[attr_name]]';#TODO: Move this to general lang file

$lang['pool_find_result'] = "[age]yr [gender] from [location]\nAppreciation:[appreciation]";

$lang['pool_result_detail'] = "Age: [age]\nGender : [gender]\nMin preferred age:[min_age]\nMax preferred age:[max_age]\nPreferred gender: [gender_preference] \nLocation: [location]\nAppreciation:[appreciation]\nDetails: [details]";
