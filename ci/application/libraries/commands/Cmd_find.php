<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles find commands
class Cmd_find
{
    public $ci;
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->telegram->debug_message('[Cmd_start] Find command handler loaded');
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd)
    {
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return $this->ci->telegram->debug_message($cmd['message']);
        }

        //TODO: Add implementation
        $this->ci->telegram->debug_message('You used the find command punk');
    }

    /* 
        Functions to handle subcommands will be here
    */
}