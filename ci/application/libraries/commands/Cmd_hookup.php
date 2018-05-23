<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles add commands
class Cmd_add
{
    public $ci;
    public $current_user_id;
    public $current_user;

    function __construct()
    {
        $this->ci = &get_instance();
        
        $this->ci->lang->load('cmd_hookup');# Load the hookup language file
        $this->ci->load->helper('telegram/message_parser');# Load the message parser helper
        $this->ci->load->model('hookup_model');# Load appropriate handler for hookups ~ this talks to the model
        $this->ci->load->model('user_model');# Load appropriate handler for hookups ~ this talks to the model

        $this->current_user_id = $this->ci->telegram->get_current_user_id();

        $this->current_user = $this->ci->user_model->get_user_data($this->current_user_id);
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd)
    {
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {   return $this->ci->telegram->send_invalid_cmd_message();   }

        //If the command is the hookup command on its own
        if($cmd['cmd'] == CMD_HOOKUP && !isset($cmd['sub_cmd']))
        {   return $this->hookup();   }
        
        //Switch on the command itself ~ handle the short versions of hookup commands
        switch($cmd['cmd'])
        {
            case CMD_ADD: 
                $cmd['sub_cmd'] = CMD_HOOKUP.' add';
            break;
            case CMD_REMOVE: 
                $cmd['sub_cmd'] = CMD_HOOKUP.' remove';
            break;
            case CMD_FIND: 
                $cmd['sub_cmd'] = CMD_HOOKUP.' find';
            break;
            case CMD_VIEW: 
                $cmd['sub_cmd'] = CMD_HOOKUP.' view';
            break;
            case CMD_SELECT: 
                $cmd['sub_cmd'] = CMD_HOOKUP.' select';
            break;
        }

        //Switch on sub commands 
        switch($cmd['sub_cmd'])
        {
            case 'add': #Add self to hookup pool
                $return_message = $this->add_to_pool();
            break;
            
            case 'remove': #Remove self from hookup pool
                $return_message = $this->remove_from_pool();
            break;
            
            case 'find':
                $return_message = $this->find_hookups();
            break;

            case 'view':
                //If the pool id has been set
                if($pool_id = $cmd['attr'])
                {  
                    $return_message = $this->view_hookup($pool_id); 
                }
                else
                {
                    $message = tg_parse_msg(lang('missing_attr'),array(
                        'attr_name'=>'hookup_id',
                        'command'=>'/view'
                    ));
                    $return_message = $this->ci->telegram->send_message($message);
                }
            break;

            case 'select':
                //If the pool id has been set
                if($pool_id = $cmd['attr'])
                {  
                    $return_message = $this->select_hookup($pool_id); 
                }
                else
                {
                    $message = tg_parse_msg(lang('missing_attr'),array(
                        'attr_name'=>'pool_id',
                        'command'=>'/select'
                    ));
                    $return_message = $this->ci->telegram->send_message($message);
                }
            break;
        }

        $this->ci->telegram->send_message('You used the hookup command punk',TEST_CHAT_ID);#TODO: Remove this ~ only for testing skeleton
        return $return_message;
        
    }

    /* 
        Functions to handle subcommands will be here
    */
    //Hookup base command
    public function hookup()
    {
        $message = lang('hookup_intro');
        return $this->ci->telegram->send_message($message);
    }

    //Add self to hookup pool to hookup pool
    public function add_to_pool()
    {
        $data = array(
            'hookup_user_id' => $this->current_user_id
        );
        $add_status = $this->ci->hookup_model->add_to_pool($data);

        $message = '';
        //If we successfully to add the user to the hookup pool
        if ($add_status)
        {   $message = lang('pool_add_success');    }
        else
        {   $message = lang('pool_add_failure');    }

        return $this->ci->telegram->send_message($message);
    }

    //Remove self from hookup pool
    public function remove_from_pool($pool_id)
    {
        $remove_status = $this->ci->hookup_model->remove_from_pool($this->current_user_id);

        $message = '';
        //If we successfully to remove the user from the hookup pool
        if ($remove_status)
        {   $message = lang('pool_remove_success');    }
        else
        {   $message = lang('pool_remove_failure');    }

        return $this->ci->telegram->send_message($message);
    }

    //Find hookups in hookup pool
    public function find_hookups()
    {
        //TODO: Add implementation ~ once we have buttons
    }

    public function view_hookup($pool_id)
    {
        $hookup = $this->ci->hookup_model->select_user_from_pool($pool_id)->row_object();
        
        $message = '';
        if(isset($hookup))
        {
            $needs_appreciation = $hookup->needs_appreciation ? "\nNeeds appreciation" : '';
            $providing_appreciation = $hookup->providing_appreciation ? "\nProviding appreciation" : '';
            $appreciation = $needs_appreciation.$providing_appreciation ;
            
            $message = tg_parse_msg(lang('pool_result_details'),array(
                'age' => $hookup->age,
                'gender' => $hookup->gender,
                'min_age' => $hookup->min_age,
                'max_age' => $hookup->max_age,
                'gender_preference' => $hookup->gender_preference,
                'location' => $hookup->location_title,
                'appreciation' => $appreciation,
                'details' => $hookup->details
            ));
        }
        else
        {
            $message = tg_parse_msg(lang('record_failed_action'),array(
                'action'=>'view',
                'record'=>'the user'
            ));
        }

        return $this->ci->telegram->send_message($message);
    }

    public function select_hookup($pool_id)
    {
        $hookup = $this->ci->hookup_model->select_user_from_pool($pool_id)->row_object();

        $status_message = '';
        //If a hookup was found ~ send the hookup a message
        if(isset($hookup))
        {
            $status_message = lang('hookup_select_success');
            $message = tg_parse_msg(lang('pool_select_message'),array(
                'age'=>$hookup->age,
                'gender'=>$hookup->gender,
                'location'=>$hookup->location
            ));

            //Send a message to the requested hookup partner
            $this->ci->telegram->send_message($message,$hookup->hookup_user_id);#TODO:Add accept and decline buttons
        }
        else
        {
            $status_message = lang('hookup_select_failure');
        }

        return $this->ci->telegram->send_message($status_message);
    }

}