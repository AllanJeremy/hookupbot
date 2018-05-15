<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles profile commands
class Cmd_profile
{
    public $ci;
    public $user_chat_id;
    function __construct()
    {
        $this->user_chat_id = $this->ci->telegram->get_user_update()->message->chat->id;
        $this->ci = &get_instance();
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd_obj)
    {
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return $this->ci->telegram->send_message($this->user_chat_id,$cmd['message']);
        }

        //TODO: Add implementation
        $this->ci->telegram->send_message($this->user_chat_id,'You used the profile command punk');
    }

    /* 
        Functions to handle subcommands will be here
    */
}