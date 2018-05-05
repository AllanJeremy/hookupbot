<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cmd_handler
{
    public $ci;
    private static $unknown_cmd_msg = 'I did not understand that command. Please check /help for a list of the commands available.';
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('commands/cmd_parser');
    }

    //Handle command ~ entry point for all commands in this library
    public function handle_command($cmd)
    {
        $ci->telegram->send_message(TEST_CHAT_ID,'cp,,dasdas');
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return $this->ci->telegram->send_message(TEST_CHAT_ID,$cmd['message']);
        }

        //Commands available
        foreach($cmd['commands'] as $cmd)
        {
            $cmd_str = $cmd['cmd'];
            switch($cmd_str)
            {
                case CMD_PROFILE:
                    $this->ci->load->library('commands/cmd_profile');
                    $this->ci->cmd_profile->handle_command($cmd_str);
                break;
                case CMD_FIND:
                    $this->ci->load->library('commands/cmd_find');
                    $this->ci->cmd_find->handle_command($cmd_str);
                break;
                case CMD_ADD:
                    $this->ci->load->library('commands/cmd_add');
                    $this->ci->cmd_add->handle_command($cmd_str);
                break;
                case CMD_SELECT:
                    $this->ci->load->library('commands/cmd_select');
                    $this->ci->cmd_select->handle_command($cmd_str);
                break;
                case CMD_VIEW:
                    $this->ci->load->library('commands/cmd_view');
                    $this->ci->cmd_view->handle_command($cmd_str);
                break;
                case CMD_REMOVE:
                    $this->ci->load->library('commands/cmd_view');
                    $this->ci->cmd_view->handle_command($cmd_str);
                break;
                default: 
                    $this->ci->telegram->send_message(TEST_CHAT_ID,self::$unknown_cmd_msg);
            }
        }
    }
}