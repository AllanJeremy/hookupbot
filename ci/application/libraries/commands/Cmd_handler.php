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
        // $this->ci->telegram->send_message(TEST_CHAT_ID,json_encode($cmd));
        //If the command was not okay print the error message
        if( !is_array($cmd['commands']) || !isset($cmd['commands']) || !$cmd['ok'])
        {
            return $this->ci->telegram->send_message(TEST_CHAT_ID,self::$unknown_cmd_msg);
        }
        
        //Commands available
        foreach($cmd['commands'] as $cmd)
        {
            $cmd_str = $cmd['cmd'];
            echo '<br>Command string :'.$cmd_str;
            switch($cmd_str)
            {
                case CMD_START:
                    $this->ci->load->library('commands/cmd_start');
                    $this->ci->cmd_start->handle_command($cmd);
                break;
                case CMD_SETTINGS:
                    $this->ci->load->library('commands/cmd_settings');
                    $this->ci->cmd_settings->handle_command($cmd);
                break;
                case CMD_HELP:
                    $this->ci->load->library('commands/cmd_help');
                    $this->ci->cmd_help->handle_command($cmd);
                break;
                case CMD_PAYMENT:
                    $this->ci->load->library('commands/cmd_payment');
                    $this->ci->cmd_payment->handle_command($cmd);
                break;
                case CMD_PROFILE:
                    $this->ci->load->library('commands/cmd_profile');
                    $this->ci->cmd_profile->handle_command($cmd);
                break;
                case CMD_FIND:
                    $this->ci->load->library('commands/cmd_find');
                    $this->ci->cmd_find->handle_command($cmd);
                break;
                case CMD_ADD:
                    $this->ci->load->library('commands/cmd_add');
                    $this->ci->cmd_add->handle_command($cmd);
                break;
                case CMD_SELECT:
                    $this->ci->load->library('commands/cmd_select');
                    $this->ci->cmd_select->handle_command($cmd);
                break;
                case CMD_VIEW:
                    $this->ci->load->library('commands/cmd_view');
                    $this->ci->cmd_view->handle_command($cmd);
                break;
                case CMD_REMOVE:
                    $this->ci->load->library('commands/cmd_view');
                    $this->ci->cmd_view->handle_command($cmd);
                break;
                // default: 
                //     $this->ci->telegram->send_message(TEST_CHAT_ID,self::$unknown_cmd_msg);
            }
        }
    }
}