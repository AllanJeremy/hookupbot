<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();
$ci->load->library('commands/cmd_handler');

$user_input = json_decode($ci->telegram->get_user_message());
$commands = is_object($user_input) ? $user_input->message->text : '';
$chat_id = is_object($user_input) ? $user_input->message->chat->id : '';
$msg_text = is_object($user_input) ? $user_input->message->text : '';
// $ci->telegram->send_message($chat_id,'[Ha! it worked] Commands : '.$commands);
// $ci->telegram->send_message(TEST_CHAT_ID,'Chat id : '.$chat_id);

$ci->cmd_handler->handle_command($ci->cmd_parser->parse($msg_text));

$handled = $ci->cmd_handler->handle_command($parsed);

//Local testing
echo '<b>Parsed message:</b> <br>';
var_dump($parsed);
echo '<br><b>Handled message:</b> <br>';
var_dump($handled);