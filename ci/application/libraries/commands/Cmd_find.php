<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles find commands
class Cmd_find
{
    public $ci;
    function __construct()
    {
        $this->ci = &get_instance();
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd)
    {
        //If the command was not okay print the error message
        if(!$cmd['ok'] || !is_array($cmd['commands']))
        {
            return $this->ci->telegram->send_message(TEST_CHAT_ID,$cmd['message']);
        }

        //TODO: Add implementation
        $this->ci->telegram->send_message(TEST_CHAT_ID,'You used the find command punk');
    }

    /* 
        Functions to handle subcommands will be here
    */
}