<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();
$ci->load->library('commands/cmd_handler');

$user_input = $ci->telegram->get_user_update();
$chat_id = is_object($user_input) ? $user_input->message->chat->id : '';
$msg_text = is_object($user_input) ? $user_input->message->text : '';
$commands = $ci->cmd_parser->parse($msg_text);
$ci->cmd_handler->handle_command($commands);


