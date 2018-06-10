<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles start commands
class Cmd_start
{
    public $ci;
    protected $cmd_profile_setup, $cmd_start_info;
    protected $btn_txt_profile_setup, $btn_txt_start_info;

    function __construct()
    {
        //Commands referenced in buttons
        $this->cmd_profile_setup = '/profile start';
        $this->cmd_start_info = '/start info';

        //Text of the buttons
        $this->btn_txt_profile_setup = 'Get started';
        $this->btn_txt_start_info = 'More info';

        $this->ci = &get_instance();#get CI instance since we don't have access to CI in libraries
        
        //Load appropriate stuff 
        $this->ci->lang->load('cmd_start');
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd,$callback_query=NULL)#Handles ONE command at a time
    {
       
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return tg_debug_message($cmd['message']);
        }

        //Check the command for a subcommand ~ if none was provided ~ run the start command
        $sub_cmd = &$cmd['sub_cmd'];
        if(!isset($sub_cmd))
        {  return $this->start();   }

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
    public function start()
    {
        $this->ci->load->model('user_model');
        $user = $this->ci->telegram->get_current_user();#Current user

        if(!isset($user))
        {   return FALSE;   }
        
        
        //Set base user data
        $data = array(
            'id' => $user->id,
            'is_bot' => $user->is_bot,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'language_code' => $user->language_code
        );
        
        
        //The message to send to the user
        $message = lang('start_intro');
        $set_status = $this->ci->user_model->set_user_data($data);
        $extras = array(
            'parse_mode'=>'Markdown',
            'reply_markup'=> tg_reply_keyboard_remove()
        );
        
        //Buttons for the start message
        $buttons = array(
            [ 
                tg_inline_button($this->btn_txt_profile_setup,array('callback_data'=>$this->cmd_profile_setup)),
                tg_inline_button($this->btn_txt_start_info,array('callback_data'=>$this->cmd_start_info))
            ]
        );
        $extras = array(
            'reply_markup'=>tg_inline_keyboard($buttons)
        );

        $message_status = tg_send_message($message,tg_get_current_user_id(),$extras);;

        return array(
            'ok'=> (bool)$set_status, #Whether the records were set correctly in the database
            'message'=> $message_status
        );
    }

    //Handle start info command
    protected function start_info()
    {
        $message = lang('start_details');

        $buttons = array(
            [ tg_inline_button($this->btn_txt_profile_setup,array('callback_data'=>$this->cmd_profile_setup)) ]
        );
        $extras = array(
            'reply_markup'=>tg_inline_keyboard($buttons)
        );
        return tg_send_message($message,tg_get_current_user_id(),$extras);
    }
}