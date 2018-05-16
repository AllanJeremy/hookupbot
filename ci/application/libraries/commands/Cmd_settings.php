<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles add commands
class Cmd_settings
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
            return $this->ci->telegram->debug_message($cmd['message']);
        }

        //TODO: Add implementation
        $this->ci->telegram->debug_message('You used the settings command punk');
    }

    /* 
        Functions to handle subcommands will be here
    */
}