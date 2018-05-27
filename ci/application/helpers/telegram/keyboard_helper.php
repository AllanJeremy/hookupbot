<?php defined('BASEPATH') or exit('No direct script access allowed');

//Get codeigniter instance
$ci = &get_instance();

//If the telegram library is not loaded, load it
if(!class_exists('Keyboard'))
{   $ci->load->library('telegram/keyboard'); }

/* 
    KEYBOARD FUNCTIONS
*/
//Inline keyboard ~ returns an inline keyboard object
function tg_inline_keyboard(array $buttons)
{
    $ci = &get_instance();
    return $ci->keyboard->inline_keyboard($buttons);
}

//Reply keyboard ~ returns a reply keyboard object
function tg_reply_keyboard(array $buttons,bool $resize_keyboard=TRUE,bool $one_time_keyboard=FALSE, bool $selective=FALSE)
{
    $ci = &get_instance();
    return $ci->keyboard->reply_keyboard($buttons,$resize_keyboard,$one_time_keyboard,$selective);
}

//Reply keyboard remove ~ returns a reply_keyboard remove object
function tg_reply_keyboard_remove(bool $should_remove=TRUE,bool $selective=FALSE)
{
    $ci = &get_instance();
    return $ci->keyboard->reply_keyboard_remove($should_remove,$selective);
}

//Force reply ~ returns a force reply object
function tg_force_reply(bool $should_force=TRUE,bool $selective=FALSE)
{
    $ci = &get_instance();
    return $ci->keyboard->force_reply($should_force,$selective);
}

//Inline keyboard button ~ returns a single inline keyboard button
function tg_inline_button(string $text,array $optional)
{
    $ci = &get_instance();
    return $ci->keyboard->inline_button($text,$optional);
}

//Keyboard button ~ returns a single keyboard button
function tg_button(string $text,bool $request_contact=NULL,bool $request_location=NULL)
{
    $ci = &get_instance();
    return $ci->keyboard->button($text,$request_contact,$request_location);
}