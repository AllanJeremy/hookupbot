<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller{

    //Initialize bot ~ handles how functions go
    protected function init_bot()
    {
        $this->load->library('commands/cmd_handler');# Load the command handler

        $user_input = $this->telegram->get_user_update();
        $cb_query = &$user_input->callback_query;//Callback query if any
        
        $chat_id = isset($cb_query) ? $cb_query->message->chat->id : $user_input->message->chat->id;//If there is callback data, get the chat id from there
        $msg_text = isset($cb_query) ? $cb_query->data : $user_input->message->text; //If there is callback data, get the message text from the callback query

        //If the message is a reply ~ handle the reply
        $this->load->library('telegram/reply_handler');
        if($this->reply_handler->is_reply())
        {   $cmd_str = $this->reply_handler->get_command_string($msg_text);  }
        else //Otherwise just handle the command normally
        {   $cmd_str = $msg_text;  }
        
        $commands = $this->cmd_parser->parse($cmd_str);
        $this->cmd_handler->handle_command($commands,$cb_query);
    }

    //The dev bot initialization
    function _dev_init_bot($cmd_str='')
    {
        $this->load->library('commands/cmd_handler');# Load the command handler
        //If the message is a reply ~ handle the reply
        $this->load->library('telegram/reply_handler');
        if($this->reply_handler->is_reply())
        {   $cmd_str = $this->reply_handler->get_command_string($msg_text);  }
        
        $commands = $this->cmd_parser->parse($cmd_str);
        $this->cmd_handler->handle_command($commands);
    }
    //Test the view
    function index()
    {
        $dev_cmd_str = CMD_HELP;# Command string used during development

        if(is_dev_environment())
        {
            $this->_dev_init_bot($dev_cmd_str);
        }
        else
        {
            $this->init_bot();
        }
        
        $this->load->view('test');
    }


}