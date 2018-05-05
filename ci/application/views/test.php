<?php defined('BASEPATH') OR exit('No direct script access allowed');

$ci = &get_instance();
$user_input = json_decode($ci->telegram->get_user_message());
$commands = $user_input->message->text;
$chat_id = $user_input->message->chat->id;

// $ci->telegram->send_message($chat_id,'[Ha! it worked] Commands : '.$commands);
// $ci->telegram->send_message(TEST_CHAT_ID,'Chat id : '.$chat_id);

$ci->load->library('commands/cmd_handler');
$ci->cmd_handler->handle_command($ci->cmd_parser->parse());
/*$some_val = $ci->cmd_parser->parse('/profile info
/p 
/
/p set age 20
/select hookup 21234');

$message = file_get_contents("php://input");

$url = 'https://api.telegram.org/bot475594525:AAGaQhupVlm_IaRCXzlrLighdqkbUoKWRWw/';

$contents = file_get_contents($url.'sendMessage?chat_id=540434472&text='.json_decode($message).'&reply_markup={"keyboard":[["Male"],["Female"]],"resize_keyboard":true,"one_time_keyboard":true}&reply_to_message_id=6');

print_r($contents);

echo '<p><b>Command parser result : </b><br/>';
var_dump($some_val);
echo '</p>';

echo '<p><b>Message : </b><br/>';
var_dump($message);
echo '</p>';
 */