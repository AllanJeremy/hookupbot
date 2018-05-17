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
        $this->user_chat_id = $this->ci->telegram->get_user_update()->message->chat->id;
        $this->ci = &get_instance();
        $this->ci->load->model('user_model'); #For setting user records in the database
        $this->ci->lang->load('cmd_profile'); #For getting messages to be sent to the user

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
    //Profile command with no sub-commands
    public function profile()
    {
        $message = lang('profile_description');
        return $this->ci->telegram->send_message($message);
    }

    //Profile start
    public function profile_start($user_id=NULL)
    {
        $message = lang('profile_start');
        return $this->ci->telegram->send_message($message);
    }

    //Profile get_attribute ~ request a user for a certain attribute
    public function get_attribute($attr,$user_id=NULL)
    {
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
        return $this->ci->telegram->send_message($message);
    }

    //Profile set_attribute
    public function profile_set_attr($attr,$value,$user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        $message = NULL;#Message we will show to the user
        //If the value to set the attribute was not provided or was empty or is not an editable attribute ~ show error
        if (!isset($value) || empty($value) || !in_array($attr,$this->_editable_attributes))
        {  
            $message = 'Missing value for '.$attr;#TODO: Change to use lang('profile_attribute_failure') or equivalent. Also : Add parser for this ~ consider localization while doing so
            return $this->ci->telegram->send_message($message);
        }
        
        //Set the update data
        $update_data = array($attr => $value);
        $reply_keyboard = NULL;#Set this based on which attribute is being set

        //Add the update data to the database
        $update_status = $this->ci->user_model->set_user_data($update_data,$user_id);
        
        //If the update in the db was successful ~ show success message
        if($update_status)
        {   $message = lang('profile_attribute_success');   }

        else //Otherwise show failure message
        {   $message = lang('profile_attribute_failure');   }

        //Return status and message
        return array(
            'ok' => (bool)$update_status,
            'message' => $this->ci->telegram->send_message($message)#TODO: Add reply keyboards
        ); 
    }
    
    //Profile remove attribute
    public function profile_remove_attr($attr,$user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        $message = NULL;
        if(!in_array($attr,$this->_editable_attributes))
        {
            $message = lang('profile_unknown_attribute');
            return $this->ci->telegram->send_message($message);
        }

        $remove_status = $this->ci->user_model->remove_user_data($user_id,array($attr));
       
        //Return status and message
        return array(
            'ok' => (bool)$remove_status,
            'message' => $this->ci->telegram->send_message($message)#TODO: Add reply keyboards
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