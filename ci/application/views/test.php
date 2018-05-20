<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();
$ci->load->library('commands/cmd_handler');

$user_input = $ci->telegram->get_user_update();
$chat_id = is_object($user_input) ? $user_input->message->chat->id : '';
$msg_text = is_object($user_input) ? $user_input->message->text : '';

//If the message is a reply ~ handle the reply
$ci->load->library('telegram/reply_handler');
if($ci->reply_handler->is_reply())
{   $cmd_str = $ci->reply_handler->get_command_string($msg_text);  }
else //Otherwise just handle the command normally
{   $cmd_str = $msg_text;  }

$commands = $ci->cmd_parser->parse($cmd_str);
$ci->cmd_handler->handle_command($commands);
