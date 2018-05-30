<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();

$ci->load->model('user_model');
$user = $ci->user_model->get_user_data(TEST_CHAT_ID,TRUE);
// var_dump($user);

//Test the hookup model
/* $ci->load->model('hookup_model');

$data = array(
    'hookup_user_id'=>TEST_CHAT_ID
);
$ci->hookup_model->add_to_pool($data); */

//Test the telegram helper
// $ci->load->library('commands/cmd_ho');
// $ci->cmd_profile->profile(TEST_CHAT_ID);
$buttons = [
    array(tg_button('Share Phone',TRUE))
];

$extras = array(
    'reply_markup' => tg_reply_keyboard($buttons)
);

tg_debug_message('testing reply keyboard',$extras);