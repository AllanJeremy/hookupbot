<?php defined('BASEPATH') or exit('No direct script access allowed');

function telegram_debug(string $message)
{
    $ci = &get_instance();
    //If the telegram library is not loaded, load it
    if(!class_exists('Telegram'))
    {   $ci->load->library('telegram/telegram'); }

    return $ci->telegram->debug_message($message);
}