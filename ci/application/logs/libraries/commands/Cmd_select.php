<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Handles select a hookup related commands
class Cmd_select
{
    protected $ci;
    function __construct()
    {
        $this->ci = &get_instance();
    }

    //Handle the various commands
    public function handle_command($cmd)
    {
        //TODO: Add implementation
        $this->ci->telegram->send_message('540434472','Handling select a hookup command');
    }

    /* 
        Every subcommand will have its own function here
    */
}