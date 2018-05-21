<?php defined('BASEPATH') or exit('No direct script access allowed');

//Parse a message and return the parsed message
function tg_parse_msg(string $message,array $replacements)
{
    //If the replacements are not an array ~ return the message
    if(!is_array($replacements))
    {   return $message;    }

    //Otherwise, loop through the replacements and replace them in the message provided
    foreach ($replacements as $key => $value) 
    {
        // replace the $key with the value
        $replace_str = '['.(string)$key.']';;# The string to be replaced
        $message = str_replace($replace_str,$value,$message);
    }

    return $message;
}