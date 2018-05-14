<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles start commands
class Cmd_start
{
    public $ci;
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->lang('cmd_start_messages');
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd)
    {
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return $this->ci->telegram->send_message(TEST_CHAT_ID,$cmd['message']);
        }

        //Check the command for a subcommand ~ if none was provided ~ run the start command
        $sub_cmd = &$cmd['sub_command'];
        if(!isset($sub_cmd))
        {   return $this->start(); }

        //If a subcommand was found ~ switch on it for supported sub-commands
        $cmd_result = NULL;#Result of executing a command
        switch($sub_cmd)
        {
            case 'info':#TODO: convert to constant
                $cmd_result = $this->start_info();
            break;
            default: # Return I did not understand that command
                $cmd_result = $this->ci->telegram->send_invalid_cmd_message();
        }

        return $cmd_result;
    }

    /* 
        Functions to handle subcommands will be here
    */
    //Handle the start command
    protected function start()
    {
        $message = $this->lang->line('start_intro');
        return $this->ci->telegram->send_message(NULL,$message);#TODO: Add buttons
    }

    //Handle start info command
    protected function start_info()
    {
        $message = $this->lang->line('start_details');
        return $this->ci->telegram->send_message(NULL,$message);#TODO: Add buttons
    }
}