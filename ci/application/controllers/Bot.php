<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller{

    //Initialize bot ~ handles how functions go
    protected function init_bot()
    {
        $this->load->library('commands/cmd_handler');# Load the command handler

        $user_input = $this->telegram->get_user_update();
        $chat_id = is_object($user_input) ? $user_input->message->chat->id : '';
        $msg_text = is_object($user_input) ? $user_input->message->text : '';
        
        //If the message is a reply ~ handle the reply
        $this->load->library('telegram/reply_handler');
        if($this->reply_handler->is_reply($msg_text))
        {   $cmd_str = $this->reply_handler->get_command_string($msg_text);  }
        else //Otherwise just handle the command normally
        {   $cmd_str = $msg_text;  }
        
        $commands = $this->cmd_parser->parse($cmd_str);
        $this->cmd_handler->handle_command($commands);
    }

    //Test the view
    function index()
    {
        $this->init_bot();
        $this->load->view('test');
    }


}