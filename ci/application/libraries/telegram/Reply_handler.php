<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reply_handler
{
    public $ci;
    protected $cmd_profile_set;
    
    //Constructor
    function __construct()
    {
        //Commands ~ abstracting them here allows for changing them without disrupting logic
        $this->cmd_profile_set = 'profile set';

        //Get CI instance and load appropriate files
        $this->ci = &get_instance();
        $this->ci->load->model('bot_trace_model');
        $this->ci->lang->load('cmd_profile');

    }

    //Returns the trace if it exists
    private function _trace_exists($user_id)
    {
        $user_id = $user_id ?? tg_get_current_user_id();//If user id is not set ~ use current user
        $trace = $this->ci->bot_trace_model->get_trace_by_user($user_id);

        $response = array(
            'ok' => isset($trace),
            'trace' => $trace
        );
        return  $response;
    }
    
    //Get the appropriate profile set command string ~ convenience function intended to DRY the code
    private function _get_command_set_string($command,$attr,$val)
    {
        return '/'.$command.' '.$attr.' '.$val;
    }

    //[Public use] Returns true if the message is a reply and false if it isn't 
    public function is_reply($user_id=NULL)#Abstracts the private _trace_exists function
    {
        //Message is a reply if there is a trace of it stored, otherwise it is not
        return $this->_trace_exists($user_id)['ok'];
    }

    //Returns the command string used to set an attribute
    public function get_command_string($reply_text,$user_id=NULL)
    {
        $trace_data = $this->_trace_exists($user_id);
        $cmd_str = ''; #The command string we will be using to store the generated command
        
        //If we could not find the trace data
        if(!$trace_data['ok'])
        {   
            return array('ok'=>$trace_data['ok']);   
        }
        
        $trace = &$trace_data['trace'];#get the trace, used to check last message text
        
        //Check what the last trace text was and get a command string based on that
        switch($trace->last_bot_message)
        {
            //Profile related replies
            case lang('profile_get_phone'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'phone',$reply_text);
            break;

            case lang('profile_get_age'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'age',$reply_text);
            break;

            case lang('profile_get_gender'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'gender',$reply_text);
            break;

            case lang('profile_get_gender_preference'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'gender_preference',$reply_text);
            break;

            case lang('profile_get_min_age'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'min_age',$reply_text);
            break;

            case lang('profile_get_location'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'phone',$reply_text);
            break;

            case lang('profile_get_needs_appreciation'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'needs_appreciation',$reply_text);
            break;

            case lang('profile_get_providing_appreciation'):
                $cmd_str = $this->_get_command_set_string($this->cmd_profile_set,'providing_appreciation',$reply_text);
            break;

            //Payment related replies #TODO: Find a way to get a confirm payment reply
            // case lang('payment_confirm_payment'):
            //     $cmd_str = $this->_get_command_set_string('hookup confirm','providing_appreciation',$reply_text);
        }

        $response = array(
            'ok'=> (isset($cmd_str) && !empty($cmd_str)), #Was the response okay
            'cmd_str'=> $cmd_str # The command string that was found
        );

        return $response;
    }

}