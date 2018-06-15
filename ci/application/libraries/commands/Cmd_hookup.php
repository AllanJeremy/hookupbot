<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles hookup related commands
class Cmd_hookup
{
    public $ci;
    public $current_user_id;
    public $current_user;

    function __construct()
    {
        $this->ci = &get_instance();
        
        $this->ci->lang->load('cmd_hookup');# Load the hookup language file
        $this->ci->load->model('hookup_model');# Load appropriate handler for hookups ~ this talks to the model
        $this->ci->load->model('user_model');# Load appropriate handler for hookups ~ this talks to the model

        $this->current_user_id = tg_get_current_user_id();

        $this->current_user = $this->ci->user_model->get_user_data($this->current_user_id);
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd,$callback_query=NULL)
    {
        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
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

        $return_message = NULL;
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
            
            case 'confirm':
                $return_message = $this->get_confirm_payment();
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
                    $return_message = tg_send_message($message,$this->current_user_id,$extras);
                }
            break;

            case 'request':
                //If the pool id has been set
                if($pool_id = $cmd['attr'])
                {  
                    $return_message = $this->request_hookup($pool_id); 
                }
                else
                {
                    $message = tg_parse_msg(lang('missing_attr'),array(
                        'attr_name'=>'pool_id',
                        'command'=>'/request'
                    ));
                    $return_message = tg_send_message($message,$this->current_user_id,$extras);
                }
            break;
            //TODO: Consider DRYing this
            case 'accept': #Accepting a hookup request
                //If the request id is set
                if($request_id = $cmd['attr'])
                {
                    $return_message = $this->accept_hookup_request($request_id);
                }
                else
                {
                    $message = tg_parse_msg(lang('missing_attr'),array(
                        'attr_name'=>'request_id',
                        'command'=>'/hookup accept'
                    ));
                    $return_message = tg_send_message($message,$this->current_user_id,$extras);
                }
            break;
            //TODO: Consider DRYing this
            case 'decline': #Declining a hookup request
                //If the request id is set
                if($request_id = $cmd['attr'])
                {
                    $return_message = $this->decline_hookup_request($request_id);
                }
                else
                {
                    $message = tg_parse_msg(lang('missing_attr'),array(
                        'attr_name'=>'request_id',
                        'command'=>'/hookup decline'
                    ));
                    $return_message = tg_send_message($message,$this->current_user_id,$extras);
                }
            break;
        }

        return $cmd_result;
    }

    /* 
        Functions to handle subcommands will be here
    */
    //Hookup base command
    public function hookup()
    {
        $buttons = array(
            [ 
                tg_inline_button('Find hookups',array('callback_data'=>'/hookup find')),
                tg_inline_button('Join hookup pool',array('callback_data'=>'/hookup add')),
            ],
            [ tg_inline_button('Leave hookup pool',array('callback_data'=>'/hookup remove')) ]
        );
        $extras = array(
            'reply_markup'=>tg_inline_keyboard($buttons)
        );

        $message = lang('hookup_intro');
        return tg_send_message($message,$this->current_user_id,$extras);
    }

    // Returns true if the user can join the hookup pool and false if not
    private function _can_join_pool(string $user_id): bool
    {
        $user = $this->ci->user_model->get_user_data($user_id);
        
        // Conditions that must be true for the user to be able to join 
        $can_join = isset($user);
        $can_join &= isset($user->phone); #Phone has to have been set

        return $can_join;            
    }

    //Add self to hookup pool to hookup pool
    public function add_to_pool($user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        $data = array(
            'hookup_user_id' => $user_id
        );

        $message = '';
        $setup_message = NULL;# Telegram message object to be returned
        $can_join = $this->_can_join_pool($user_id); #If the current user can join hookup pool
        
        if($can_join)
        {
            //Add user to pool
            $add_status = $this->ci->hookup_model->add_to_pool($data);

            //If we successfully to add the user to the hookup pool
            if ($add_status)
            {   $message = lang('pool_add_success');    }
            else
            {   $message = lang('pool_add_failure');    }

            $extras = array(
                'reply_markup'=>tg_reply_keyboard_remove()
            );
        }
        else //If we cannot join the hookup pool. Ask user to setup their profile
        {
            $message = lang('pool_add_failure');
            $this->ci->load->library('commands/cmd_profile');
            $setup_message = $this->ci->cmd_profile->profile_setup($user_id); #Takes care of
        }

        //Send success or failure message
        $success_message = tg_send_message($message,$this->current_user_id,$extras);

        return array(
            'ok' => (bool)$can_join,
            'message' => $can_join ? $success_message : $setup_message
        );
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

        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        return tg_send_message($message,$this->current_user_id,$extras);
    }

    //Find hookups in hookup pool
    public function find_hookups()
    {
        $hookups_found = $this->hookup_model->get_pool_matches()->result_object;

        //If no hookups were found ~ send no hookups found message
        if(!isset($hookups_found))
        {
            return array(
                'ok'=>FALSE,
                'message'=>tg_send_message(lang('pool_no_matches_found'))
            );
        }

        $column1 = $column2 = array();
        
        $count = 0;
        //Hookups found ~ loop through them and display appropriate buttons
        foreach($hookups_found as $hookup)
        {
            $count++;
            
            //[age]yr [gender] from [location]\nAppreciation:[appreciation]
            $button_text = tg_parse_msg(lang('pool_find_result'),array(
                'age'=> $hookup->age,
                'gender'=> $hookup->gender,
                'location'=> $hookup->location_title ?? 'Unknown location',
            ));
            $button_command = '/hookup view '.$hookup->id;
            $button = tg_inline_button($button_text,array(
                'callback_data'=>$button_command
            ));

            //If we aren't in an even number ~ add to column 1
            if($count%2 !== 0)
            {
                array_push($button,$column1);
            }
            else //Otherwise add to column 2
            {
                array_push($button,$column2);
            }
        }

        $message = tg_parse_msg(lang('pool_found_matches'),array(
            'count'=>$count
        ));

        $buttons = array($column1,$column2);
        $extras = array(
            'reply_markup'=>tg_inline_keyboard($buttons)
        );

        return tg_send_message($message,$this->current_user_id,$extras);
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

        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        return tg_send_message($message,$this->current_user_id,$extras);
    }

    public function request_hookup($pool_id)
    {
        $hookup = $this->ci->hookup_model->select_user_from_pool($pool_id)->row_object();

        $status_message = '';
        //If a hookup was found ~ send the hookup a message
        if(isset($hookup))
        {
            //Add the hookup request to the database
            $request_id = $this->ci->hookup_model->make_hookup_request($pool_id,$this->current_user_id);

            //Request hookup ~ generate message to be sent to the hookup requester (current user)
            $status_message = lang('hookup_select_success');
            $message = tg_parse_msg(lang('pool_request_hookup'),array(
                'age'=>$hookup->age,
                'gender'=>$hookup->gender,
                'location'=>$hookup->location
            ));

            $buttons = array(
                [ tg_inline_button('Accept',array('callback_data'=>'/hookup accept '.$pool_id) )],
                [ tg_inline_button('Decline',array('callback_data'=>'/hookup decline '.$pool_id) )]
            );

            $extras = array(
                'reply_markup' => tg_inline_keyboard($buttons)
            );
            //Send a message to the requested hookup partner
            tg_send_message($message,$hookup->hookup_user_id,$extras);#TODO: accept and decline buttons
        }
        else
        {
            $status_message = lang('hookup_select_failure');
        }

        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        return tg_send_message($message,$this->current_user_id,$extras);
    }

    //Handle hookup request acceptance
    private function _handle_hookup_request_accept($request_id,$is_accepted)
    {
        //Get the requester
        $requester = $this->ci->hookup_model->get_hookup_request($request_id)->row_object();
        $status = FALSE;
        $message = '';

        //If is_accepted is true, run accepted hookup request logic
        if($is_accepted)
        {   
            $status = $this->hookup_model->accept_hookup_request($request_id);
        }
        else
        {
            $status = $this->hookup_model->decline_hookup_request($request_id);
        }

        //Name of the message in the lang file that will be displayed ~ declined or accepted message
        $decision_message = $is_accepted ? 'request_accepted_message' : 'request_declined_message';

        //If the hookup request was successful
        if($status && isset($requester))
        {
            $message = tg_parse_msg(lang($decision_message),array(
                'age'=> $requester->age,
                'gender'=> $requester->gender,
                'location'=> $requester->location
            ));
        }
        else //If the hookup request was unsuccessful ~ or we couldn't retrieve the requester
        {
            $message = tg_parse_msg(lang('error_generic'),array(
                'action'=> 'accept the hookup request'#TODO: Move this to lang file for localization
            ));
        }

        //Remove the reply keyboard 
        $extras = array(
            'reply_markup'=>tg_inline_keyboard(
                array(
                    [tg_inline_button('Confirm payment',array('callback_data'=>'/hookup confirm'))]
                )
            )
        );
        // Return a message
        return array(
            'ok' => (bool)$status,
            'message'=>tg_send_message($message,$requester->hookup_user_id,$extras)
        );
    }

    //Accept hookup request
    public function accept_hookup_request($request_id)
    {
        return $this->_handle_hookup_request_accept($request_id,TRUE);#TODO: Add buttons here
    }

    //Decline hookup request
    public function decline_hookup_request($request_id)
    {
        return $this->_handle_hookup_request_accept($request_id,TRUE);
    }

}
