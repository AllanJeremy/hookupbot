<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles start commands
class Cmd_start
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
        if( !is_array($cmd))
        {
            return $this->ci->telegram->send_message(TEST_CHAT_ID,$cmd['message']);
        }

        //TODO: Add implementation
        $this->ci->telegram->send_message(TEST_CHAT_ID,'You used the start command punk');
    }

    /* 
        Functions to handle subcommands will be here
    */
}