<?php defined('BASEPATH') OR exit('No direct script access allowed');

//A central class for channeling all commands ~ calls on the appropriate command handlers
class Cmd_handler
{
    protected $ci;
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library('commands/cmd_parser');
    }

    public function handle_command($cmd_obj)
    {
        $commands = (is_array($cmd_obj['commands'])) ? $cmd_obj['commands'] : NULL;//Set commands variable
        $user_msg = $this->ci->telegram->get_user_message();
        
        //Telegram
        $this->ci->telegram->send_message('540434472',('User message object : '.json_encode($user_msg)));
        //If the command was not ok ~ send a message and return 
        if(!$cmd_obj['ok'] || !isset($commands))
        {
            $chat_id = 'chat_id';#TODO: Set this to the current chat_id
            $this->ci->telegram->send_message($chat_id,$cmd_obj['message']);
        }

        //Parse commands
        foreach($commands as $cmd)
        {
            //Handle the various command types
            switch($cmd['cmd'])
            {
                case CMD_PROFILE:#Profile command
                    $this->ci->load->library('commands/cmd_profile');
                    $this->ci->cmd_profile->handle_command($cmd);
                break;

                case CMD_FIND:#Find hookup command
                    $this->ci->load->library('commands/cmd_find');
                    $this->ci->cmd_find->handle_command($cmd);
                break;

                case CMD_ADD:#Add to hookup pool command
                    $this->ci->load->library('commands/cmd_add');
                    $this->ci->cmd_add->handle_command($cmd);
                break;

                case CMD_SELECT:#Select hookup command
                    $this->ci->load->library('commands/cmd_select');
                    $this->ci->cmd_select->handle_command($cmd);
                break;

                case CMD_VIEW:#View single hookup command
                    $this->ci->load->library('commands/cmd_view');
                    $this->ci->cmd_view->handle_command($cmd);
                break;

                case CMD_REMOVE:#Remove from hookup pool command
                    $this->ci->load->library('commands/cmd_remove');
                    $this->ci->cmd_profile->handle_command($cmd);
                break;
            }
        }
    }
}
