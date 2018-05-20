<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reply_handler
{
    public $ci;
    //Arrays containing the list of possible last bot messages whose reply should trigger certain commands
    public $reply_profile_set; #Reply that triggers profile set
    public $reply_payment; #Reply that triggers payment validation

    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model('bot_trace_model');
        $this->reply_profile_set = array(
            lang('profile_get_phone'),
            lang('profile_get_age'),
            lang('profile_get_gender'),
            lang('profile_get_gender_preference'),
            lang('profile_get_min_age'),
            lang('profile_get_max_age'),
            lang('profile_get_location'),
            lang('profile_get_needs_appreciation'),
            lang('profile_get_providing_appreciation')
        );
    }


    //Returns true if the response should be processed as a reply to a query by the bot
    public function is_reply($user_id=NULL)
    {
        return (
            $this->get_profile_set_reply()['ok']//TODO: use  || for additional checks
        );
    }
    
    //Get the appropriate profile set command string ~ convenience function intended to DRY the code
    private function _get_profile_set_string($attr,$val)
    {
        return '/profile set '.$attr.' '.$val;
    }

    //Returns the command string used to set a profile attribute
    public function get_profile_set_reply($reply_text,$user_id=NULL)
    {
        $trace = $this->ci->bot_trace_model->get_trace_by_user($user_id);
        $is_ok = in_array($trace->last_bot_message,$this->reply_profile_set);

        $response = array(
            'ok' => $is_ok
        );

        if(!$is_ok)
        {   return $response;   }

        //Switch on what kind of reply it is and use /profile set appropriately
        $cmd_str = '';
        switch($reply_text)
        {
            case lang('profile_get_phone'):
                $cmd_str = $this->_get_profile_set_string('phone',$reply_text);
            break;
            case lang('profile_get_age'):
                $cmd_str = $this->_get_profile_set_string('age',$reply_text);
            break;
            case lang('profile_get_gender'):
                $cmd_str = $this->_get_profile_set_string('gender',$reply_text);
            break;
            case lang('profile_get_gender_preference'):
                $cmd_str = $this->_get_profile_set_string('gender_preference',$reply_text);
            break;
            case lang('profile_get_min_age'):
                $cmd_str = $this->_get_profile_set_string('min_age',$reply_text);
            break;
            case lang('profile_get_location'):
                $cmd_str = $this->_get_profile_set_string('phone',$reply_text);
            break;
            case lang('profile_get_needs_appreciation'):
                $cmd_str = $this->_get_profile_set_string('needs_appreciation',$reply_text);
            break;
            case lang('profile_get_providing_appreciation'):
                $cmd_str = $this->_get_profile_set_string('providing_appreciation',$reply_text);
            break;
        }

        $response['cmd_str'] = $cmd_str;

        return $response;
    }


    //Gets the command string
    public function get_command_string($reply_text,$user_id=NULL)
    {
        $cmd_str = '';#Command string
        //If it is a profile set reply
        if($reply = $this->get_profile_set_reply($user_id) && $reply['ok'])
        {   $cmd_str = $reply['cmd_str'];   }

        return $cmd_str;
    }

}