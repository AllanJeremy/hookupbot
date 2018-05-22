<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles add commands
class Cmd_add
{
    public $ci;
    public $current_user_id;
    function __construct()
    {
        $this->ci = &get_instance();
        
        $this->ci->lang->load('cmd_hookup');# Load the hookup language file
        $this->ci->load->helper('telegram/message_parser');# Load the message parser helper
        $this->ci->load->library('hookups/hookup_handler');# Load appropriate handler for hookups ~ this talks to the model

        $this->current_user_id = $this->ci->telegram->get_current_user_id();
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
        //TODO: Add implementation
    }

    //Remove self from hookup pool
    public function remove_from_pool()
    {
        //TODO: Add implementation
    }

    //Find hookups in hookup pool
    public function find_hookups()
    {
        //TODO: Add implementation
    }

    public function view_hookup($pool_id)
    {
        //TODO: Add implementation
    }

    public function select_hookup($pool_id)
    {
        //TODO: Add implementation
    }

}