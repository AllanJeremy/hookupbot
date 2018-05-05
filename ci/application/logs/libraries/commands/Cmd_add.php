<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Handles add to hookup pool related commands
class Cmd_add
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
        $this->ci->telegram->send_message('540434472','Handling add to hookup pool command');
    }

    /* 
        Every subcommand will have its own function here
    */
}