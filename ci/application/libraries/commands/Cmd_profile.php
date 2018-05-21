<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles profile commands
class Cmd_profile
{
    public $ci;
    public $user_chat_id;
    private $_editable_attributes;#A list of attributes that can be modified
    protected $current_user_id;#The current user's id

    function __construct()
    {
        $this->ci = &get_instance();
        $this->user_chat_id = $this->ci->telegram->get_user_update()->message->chat->id;
        
        $this->ci->load->model('user_model'); #For setting user records in the database
        $this->ci->lang->load('cmd_profile'); #For getting messages to be sent to the user
        $this->ci->load->helper('telegram/message_parser');# Load the message parser helper

        //Set the list of attributes that can be modified
        $this->_editable_attributes = array(
            'phone',
            'age',
            'gender',
            'gender_preference',
            'min_age',
            'max_age',
            'location',
            'needs_appreciation',
            'providing_appreciation'
        );
        
        //Current user id 
        $this->current_user_id = $this->ci->telegram->get_current_user_id();
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd)
    {
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return $this->ci->telegram->send_invalid_cmd_message();
        }

        $sub_cmd = &$cmd['sub_cmd'];
        // Check if the command has a subcommand ~ if not, run the base profile command
        if (!isset($sub_cmd))
        {   return $this->profile();    }

        //Otherwise, there is a subcommand ~ switch on it
        switch($sub_cmd)
        {
            case 'start': #Profile start ~ starts the queries for questions to be asked
                $this->profile_start();
            break;

            case 'info': #Profile info ~ starts the queries for questions to be asked
                
                if ($attr = $cmd['attr']) # If we're requesting an attribute's info
                {   $this->profile_attr_info($attr);    }
                else # Profile info
                {   $this->profile_info();  }
                
            break;
            
            case 'get': #Get profile attribute
                //Check if the attribute has been provided
                if($attr = $cmd['attr'])
                {
                    $value = $cmd['value'];
                    $this->get_attribute($attr);
                }
                else //No attribute has been provided ~ show appropriate message
                {
                    $message = lang('profile_missing_attribute');
                    $this->ci->telegram->send_message($message);
                }
            break;

            case 'set': #Set profile attribute
                //Check if the attribute has been provided
                if($attr = $cmd['attr'])
                {
                    $value = $cmd['value'];
                    $this->set_attribute($attr,$value);
                }
                else //No attribute has been provided ~ show appropriate message
                {
                    $message = lang('profile_missing_attribute');
                    $this->ci->telegram->send_message($message);
                }
            break;

            case 'remove':
                //Check if the attribute has been provided
                if($attr = $cmd['attr'])
                {
                    $this->remove_attribute($attr);
                }
                else //No attribute has been provided ~ show appropriate message
                {
                    $message = lang('profile_missing_attribute');
                    $this->ci->telegram->send_message($message);
                }
            break;
        }
        
    }

    /* 
        Functions to handle subcommands will be here
    */
    //Profile command with no sub-commands
    public function profile()
    {
        $message = lang('profile_description');
        return $this->ci->telegram->send_message($message);
    }

    //Profile start
    public function profile_start($user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        $message = lang('profile_start');
        $start_msg = $this->ci->telegram->send_message($message);

        $this->ci->load->model('bot_trace_model');
        $trace = $this->ci->bot_trace_model->get_trace_by_user($user_id);

        $attr_request_msg = $this->get_attribute('phone');
        $this->ci->telegram->send_message($attr_request_msg);
    }

    //Profile get_attribute ~ request a user for a certain attribute
    public function get_attribute($attr,$user_id=NULL)
    {
        $this->ci->load->model('bot_trace_model');
        
        $trace_data = array(
            'last_bot_message_id' => '',
            'last_bot_message' => ''
        );

        $user_id = $user_id ?? $this->current_user_id;
        $message = NULL;
        //Determine which attribute message to show
        switch($attr)
        {
            case 'phone':
                $message = lang('profile_get_phone');
            break;
                
            case 'age':
                $message = lang('profile_get_age');
            break;

            case 'gender':
                $message = lang('profile_get_gender');
            break;

            case 'gender_preference':
                $message = lang('profile_get_gender_preference');
            break;

            case 'min_age':
                $message = lang('profile_get_min_age');
            break;

            case 'max_age':
                $message = lang('profile_get_max_age');
            break;

            case 'location':
                $message = lang('profile_get_location');
            break;

            case 'needs_appreciation':
                $message = lang('profile_get_needs_appreciation');
            break;

            case 'providing_appreciation':
                $message = lang('profile_get_providing_appreciation');
            break;

            default:
                $message = lang('profile_unknown_attribute');
        }
        $bot_message = $this->ci->telegram->send_message($message);#The message sent to the bot
        
        $trace_data['last_bot_message_id'] = $bot_message->message_id;
        $trace_data['last_bot_message'] = $message;

        $set_trace_status = $this->ci->bot_trace_model->set_trace($trace_data,$user_id);

        return array(
            'ok' => (bool)$set_trace_status,
            'message' => $bot_message
        );
    }

    //Profile set_attribute
    public function set_attribute($attr,$value,$user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        $message = NULL;#Message we will show to the user
        //If the value to set the attribute was not provided or was empty or is not an editable attribute ~ show error
        if (!isset($value) || empty($value) || !in_array($attr,$this->_editable_attributes))
        {  
            $message = tg_parse_msg(lang('profile_attribute_failure'),array(
                'action' => 'set',#TODO: Use lang file for localization of this
                'attribute' => $attr
            ));
            return $this->ci->telegram->send_message($message);
        }
        
        //Set the update data
        $update_data = array($attr => $value);
        $reply_keyboard = NULL;#Set this based on which attribute is being set

        //Add the update data to the database
        $update_status = $this->ci->user_model->set_user_data($update_data,$user_id);
        
        //If the update in the db was successful ~ show success message
        if($update_status)
        {   
            $message = tg_parse_msg(lang('profile_attribute_success'),array(
                'action' => 'set',#TODO: Use lang file for localization of this
                'attribute' => $attr
            ));
        }

        else //Otherwise show failure message
        {  
             $message = tg_parse_msg(lang('profile_attribute_failure'),array(
                 'action' => 'set', #TODO: Use lang file for localization of this
                 'attribute' => $attr
             ));   
        }

        //Return status and message
        return array(
            'ok' => (bool)$update_status,
            'message' => $this->ci->telegram->send_message($message)#TODO: Add reply keyboards
        ); 
    }
    
    //Profile remove attribute
    public function remove_attribute($attr,$user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        $message = NULL;
        if(!in_array($attr,$this->_editable_attributes))
        {
            $message = tg_parse_msg(lang('profile_unknown_attribute'),array(
                'attribute' => $attr
            ));
            return $this->ci->telegram->send_message($message);
        }

        $remove_status = $this->ci->user_model->remove_user_data($user_id,array($attr));
        
        //If it was successfully removed
        if ($remove_status)
        {
            $message = tg_parse_msg(lang('profile_attribute_success'),array(
                'action' => 'removed',#TODO: Use lang file for localization of this
                'attribute' => $attr
            ));
        }
        else
        {
            $message = tg_parse_msg(lang('profile_attribute_failure'),array(
                'action' => 'remove',#TODO: Use lang file for localization of this
                'attribute' => $attr
            ));
        }
       
        $sent_message = $this->ci->telegram->send_message($message);#TODO: Add reply keyboards
        //Return status and message
        return array(
            'ok' => (bool)$remove_status,
            'message' => $sent_message 
        ); 

    }

    //Profile info ~ displays information about the profile
    public function profile_info($user_id=NULL)
    {
        //TODO: Add implementation
    }

    //Profile attribute info
    public function profile_attr_info($attr,$user_id)
    {
        //TODO: Add implementation
    }
}