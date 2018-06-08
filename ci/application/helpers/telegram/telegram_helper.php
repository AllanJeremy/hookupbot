<?php defined('BASEPATH') or exit('No direct script access allowed');

//Get codeigniter instance
$ci = &get_instance();

//If the telegram library is not loaded, load it
if(!class_exists('Telegram'))
{   $ci->load->library('telegram/telegram'); }

/* 
    MESSAGES
*/
//Send debug message ~ sends to dev chat_id : used for testing
function tg_debug_message(string $message,array $extras=NULL)
{
    $ci = &get_instance();
    return $ci->telegram->debug_message($message,$extras);
}

//Send a message
function tg_send_message($text='generic text',$chat_id=NULL,$extras=NULL)
{
    $ci = &get_instance();
    return $ci->telegram->send_message($message,$chat_id,$extras);
}
//Send invalid command message
function tg_send_invalid_cmd_message($chat_id=NULL)
{
    $ci = &get_instance();
    return $ci->telegram->send_invalid_cmd_message($chat_id);
}

//Find the current user message information
function tg_get_user_update()
{
    $ci = &get_instance();
    return $ci->telegram->get_user_update();
}

//Get the current user ~ user that sent the message
function tg_get_current_user()
{
    $ci = &get_instance();
    return $ci->telegram->get_current_user();
}

//Get current user id ~ returns id if found : false if not 
function tg_get_current_user_id()# added as an abstraction to prevent errors
{
    $ci = &get_instance();
    return is_dev_environment() ? TEST_CHAT_ID : $ci->telegram->get_current_user_id();
}

/* 
    MISCELLANEOUS
*/
//Parse a message that contains placeholder data eg. [attribute] and return the parsed message
function tg_parse_msg(string $message,array $replacements)
{
    //If the replacements are not an array ~ return the message
    if(!is_array($replacements))
    {   return $message;    }

    //Otherwise, loop through the replacements and replace them in the message provided
    foreach ($replacements as $key => $value) 
    {
        $value = (isset($value) && !empty($value)) ? $value : '[Not set]';
        // replace the $key with the value
        $replace_str = '['.(string)$key.']';;# The string to be replaced
        $message = str_replace($replace_str,$value,$message);
    }

    return $message;
}