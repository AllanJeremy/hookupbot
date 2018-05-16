<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles add commands
class Cmd_add
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
            return $this->ci->telegram->send_message($cmd['message'],TEST_CHAT_ID);
        }

        //TODO: Add implementation
        $this->ci->telegram->send_message('You used the add command punk',TEST_CHAT_ID);
    }

    /* 
        Functions to handle subcommands will be here
    */
}