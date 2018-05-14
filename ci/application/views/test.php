<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();
$ci->load->library('commands/cmd_handler');

$user_input = json_decode($ci->telegram->get_user_message());
$chat_id = is_object($user_input) ? $user_input->message->chat->id : '';
$msg_text = is_object($user_input) ? $user_input->message->text : '';
$commands = $ci->cmd_parser->parse($msg_text);

$ci->cmd_handler->handle_command($commands);

//Testing lang
$ci->lang->load('cmd_start');
$stuff = lang('start_intro');
telegram_debug('[debugging lang] : hm');
