<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();

$ci->load->model('user_model');
$user = $ci->user_model->get_user_data(TEST_CHAT_ID,TRUE);

//testing the start command
$ci->load->library('commands/cmd_start');
$ci->cmd_start->start();