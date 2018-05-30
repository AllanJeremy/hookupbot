<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cmd_handler
{
    public $ci;
    private static $unknown_cmd_msg = '[Cmd handler] I did not understand from cmd_handler.';
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('commands/cmd_parser');
    }

    //Handle command ~ entry point for all commands in this library
    public function handle_command($cmd)
    {
        // tg_debug_message(json_encode($cmd));
        //If the command was not okay print the error message
        if( !is_array($cmd['commands']) || !isset($cmd['commands']) || !$cmd['ok'])
        {
            return tg_debug_message('I did not understand that command, please check /help for a list of available commands.');
        }
        
        $handled = NULL;
        // tg_debug_message($test_msg.(class_exists('Cmd_start')));
        //Commands available
        foreach($cmd['commands'] as $cmd)
        {
            $cmd_str = $cmd['cmd'];
            // echo '<br>Command string :'.$cmd_str;
            
            switch($cmd_str)
            {
                case CMD_START:
                    $this->ci->load->library('commands/cmd_start');
                    $handled = $this->ci->cmd_start->handle_command($cmd);
                break;
                
                case CMD_SETTINGS:
                    $this->ci->load->library('commands/cmd_settings');
                    $handled = $this->ci->cmd_settings->handle_command($cmd);
                break;
                
                case CMD_HELP:
                    $this->ci->load->library('commands/cmd_help');
                    $handled = $this->ci->cmd_help->handle_command($cmd);
                break;
                
                case CMD_PAYMENT:
                    $this->ci->load->library('commands/cmd_payment');
                    $handled = $this->ci->cmd_payment->handle_command($cmd);
                break;
                
                case CMD_PROFILE:#Profile 
                    $this->ci->load->library('commands/cmd_profile');
                    $handled = $this->ci->cmd_profile->handle_command($cmd);
                break;

                case CMD_ADD:#Add self to hookup pool
                case CMD_FIND:#Find matching members in hookup pool
                case CMD_SELECT:#Select a hookup from hookup pool
                case CMD_VIEW:#View single hookup profile from hookup pool
                case CMD_REMOVE:#Remove self from hookup pool
                case CMD_HOOKUP:#Manage hookups
                    $this->ci->load->library('commands/cmd_hookup');
                    $handled = $this->ci->cmd_hookup->handle_command($cmd);
                break;
            
                // default: 
                //     tg_debug_message(self::$unknown_cmd_msg);
            }
        }

        return $handled;
    }
}