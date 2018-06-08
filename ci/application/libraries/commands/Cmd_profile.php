<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles profile commands
class Cmd_profile
{
    public $ci;
    private $_editable_attributes;#A list of attributes that can be modified
    protected $current_user_id;#The current user's id

    function __construct()
    {
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
        $this->current_user_id = tg_get_current_user_id();
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd,$callback_query=NULL)
    {
        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return tg_send_invalid_cmd_message();
        }

        $sub_cmd = &$cmd['sub_cmd'];
        // Check if the command has a subcommand ~ if not, run the base profile command
        if (!isset($sub_cmd))
        {   return $this->profile($this->current_user_id);    }

        $return_msg = NULL;//TODO: Set the return message in each scenario
        //Otherwise, there is a subcommand ~ switch on it
        switch($sub_cmd)
        {
            case 'start': #Profile start ~ shows message for starting profile setup
                $this->profile_start($this->current_user_id);
            break;

            case 'setup': #Profile setup ~ sequentially asks user to enter profile details
                $this->profile_setup($this->current_user_id);
            break;

            case 'info': #Profile info ~ starts the queries for questions to be asked
                
                if ($attr = $cmd['attr']) # If we're requesting an attribute's info
                {   $this->profile_attr_info($attr);    }
                else # Profile info
                {   $this->profile_info($this->current_user_id);  }
                
            break;
            
            case 'get': #Get profile attribute
                //Check if the attribute has been provided
                if($attr = $cmd['attr'])
                {
                    $value = $cmd['value'];
                    $function_name = 'get_'.$attr;#Name of the function to be called
                    
                    try //Try calling the corresponding getter function
                    {
                        call_user_func(
                            array($this,$function_name),
                            $this->current_user_id
                        );//Call the exact function needed to set the attribute provided
                    }
                    catch(Exception $e)//Function we tried to call doesn't exist, means invalid attribute get request
                    {
                        $message = tg_parse_msg(lang('profile_attribute_failure'),array(
                            'action'=>'get',
                            'attribute'=>$attr
                        ));
                        tg_send_message($message,$this->current_user_id,$extras);
                    }

                }
                else //No attribute has been provided ~ show appropriate message
                {
                    $message = lang('profile_missing_attribute');
                    tg_send_message($message,$this->current_user_id,$extras);
                }
            break;

            case 'set': #Set profile attribute
                //Check if the attribute has been provided
                if($attr = $cmd['attr'])
                {
                    $value = $cmd['value'];
                    $this->set_attribute($attr,$value);
                }
                else //No attribute has been provided ~ show list of attributes you can set
                {
                    //We want the keyboard buttons to be used to get the attributes ~ which will then allow us to set the attributes as a reply
                    $extras['reply_markup'] = $this->_get_editable_attribute_keyboard('get');
                    tg_send_message($message,$this->current_user_id,$extras);
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
                    $extras['reply_markup'] = $this->_get_editable_attribute_keyboard('remove');
                    tg_send_message($message,$this->current_user_id,$extras);
                }
            break;
        }
        
    }

    // Returns an inline keyboard containing a list of all the editable commands
    private function _get_editable_attribute_keyboard($command)# Convenience function
    {
        $command = '/profile '.$command.' ';# For example /profile set

        $buttons = array(#TODO: Consider using the editable attribute array to dynamically generate this
            [
                tg_inline_button('Phone',array('callback_data'=>$command.'phone')),
                tg_inline_button('Age',array('callback_data'=>$command.' age')),  
            ],
            [
                tg_inline_button('Gender',array('callback_data'=>$command.'gender')),
                tg_inline_button('Gender preference',array('callback_data'=>$command.' gender_preference')),
            ],
            [
                tg_inline_button('Min age',array('callback_data'=>$command.'min_age')),
                tg_inline_button('Max age',array('callback_data'=>$command.'max_age')),
            ],
            [
                tg_inline_button('Location',array('callback_data'=>$command.'location')),
            ],
            [
                tg_inline_button('Needs appreciation',array('callback_data'=>$command.'needs_appreciation')),
                tg_inline_button('Providing appreciation',array('callback_data'=>$command.'providing_appreciation')),
            ],
        );

        return tg_inline_keyboard($buttons);
    }

    /* 
        Functions to handle subcommands will be here
    */
    //Send a message to the user
    private function _send_message_to_user($message,$user_id=NULL,$extras=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        return tg_send_message($message,$user_id,$extras);
    }

    //Profile command with no sub-commands
    public function profile($user_id=NULL)
    {
        $buttons = array(
            [
                tg_inline_button('Setup profile',array('callback_data'=>'/profile setup')),
                tg_inline_button('Profile info',array('callback_data'=>'/profile info'))
            ],
            [
                tg_inline_button('Set attribute',array('callback_data'=>'/profile set')),
                tg_inline_button('Remove attribute',array('callback_data'=>'/profile remove'))
            ]
        );
        $extras = array(
            'reply_markup'=>tg_inline_keyboard($buttons)
        );
        return $this->_send_message_to_user(lang('profile_description'),$user_id,$extras);
    }

    //Profile start
    public function profile_start($user_id=NULL)
    {
        return $this->_send_message_to_user(lang('profile_start'),$user_id);
    }

    //Sequential profile setup
    public function profile_setup()
    {
        //TODO: Add actual implementation
        $user_id = $user_id ?? $this->current_user_id;
        
        $this->ci->load->model('bot_trace_model');
        $trace = $this->ci->bot_trace_model->get_trace_by_user($user_id);

        //TODO: Show the next setup step based on the previous setup step
        $attr_request_msg = $this->get_attribute('phone');
        tg_send_message($attr_request_msg);
    }

    //Profile get_attribute ~ request a user for a certain attribute
    protected function get_attribute($attr,$extras=NULL,$user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;
        
        $message = $message = lang('profile_get_'.$attr);
        $bot_message = tg_send_message($message,$user_id,$extras);#The message sent to the bot
        
        //Data to add to the bot trace table ~ keeping track of the last message
        $trace_data = array(
            'last_bot_message_id' => $bot_message->message_id,
            'last_bot_message' => $message
        );
        
        $this->ci->load->model('bot_trace_model');
        $set_trace_status = $this->ci->bot_trace_model->set_trace($trace_data,$user_id);

        return array(
            'ok' => (bool)$set_trace_status,
            'message' => $bot_message
        );
    }

    /* 
        VARIOUS ATTRIBUTE GETTERS
    */
    //Get the phone attribute
    public function get_phone($user_id=NULL)
    {
        $buttons = [
            array(tg_button('Share Phone',TRUE))
        ];

        $extras = array(
            'reply_markup' => tg_reply_keyboard($buttons)
        );

        return $this->get_attribute('phone',$extras,$user_id);
    }

    //Get the age attribute
    public function get_age($user_id=NULL)
    {
        $extras = array(
            'reply_markup' => tg_force_reply()
        );

        return $this->get_attribute('age',$extras,$user_id);
    }

    //Get the gender attribute
    public function get_gender($user_id=NULL)
    {
        $buttons = array(
            [
                tg_button('Male'),
                tg_button('Female')
            ]
        );
        
        $extras = array(
            'reply_markup' => tg_reply_keyboard($buttons)
        );

        return $this->get_attribute('gender',$extras,$user_id);
    }

    //Get the gender_preference attribute
    public function get_gender_preference($user_id=NULL)
    {
        $buttons = [
            array( tg_button('Male') ),
            array( tg_button('Female') )
        ];

        $extras = array(
            'reply_markup' => tg_reply_keyboard($buttons)
        );

        return $this->get_attribute('gender_preference',$extras,$user_id);
    }

    //Get the min_age attribute
    public function get_min_age($user_id=NULL)
    {
        $extras = array(
            'reply_markup' => tg_force_reply()
        );

        return $this->get_attribute('min_age',$extras,$user_id);
    }

    //Get the max_age attribute
    public function get_max_age($user_id=NULL)
    {
        $extras = array(
            'reply_markup' => tg_force_reply()
        );

        return $this->get_attribute('max_age',$extras,$user_id);
    }
    
    //Get the location attribute
    public function get_location($user_id=NULL)
    {
        $buttons = [
            array(tg_button('Share location',NULL,TRUE)),#Column 1
        ];#Array of arrays containing buttons (rows,columns)

        $extras = array(
            'reply_markup' => tg_reply_keyboard($buttons)
        );

        return $this->get_attribute('location',$extras,$user_id);
    }
    
    //Get the needs_appreciation attribute
    public function get_needs_appreciation($user_id=NULL)
    {
        $buttons = array(
            [
                tg_button('Yes'),
                tg_button('No')
            ]
        );

        $extras = array(
            'reply_markup' => tg_reply_keyboard($buttons)
        );

        return $this->get_attribute('needs_appreciation',$extras,$user_id);        
    }
    
    //Get the providing_appreciation attribute
    public function get_providing_appreciation($user_id=NULL)
    {
        $buttons = array(
            [
                tg_button('Yes'),
                tg_button('No')
            ]
        );

        $extras = array(
            'reply_markup' => tg_reply_keyboard($buttons)
        );

        return $this->get_attribute('providing_appreciation',$extras,$user_id);
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
            return tg_send_message($message,$user_id);
        }
        
        //Set the update data
        $update_data = array($attr => $value);
        $reply_keyboard = NULL;#Set this based on which attribute is being set

        //Add the update data to the database
        $update_status = $this->ci->user_model->set_user_data($update_data,$user_id);
        
        //If the update in the db was successful ~ show success message
        if($update_status)
        {   
            $this->ci->load->model('bot_trace_model');
            //Remove the last message from bot trace ~ since this has already been handled
            $this->ci->bot_trace_model->remove_user_trace($user_id);

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

        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        //Return status and message
        return array(
            'ok' => (bool)$update_status,
            'message' => tg_send_message($message,$user_id,$extras)#TODO: Add reply keyboards
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
            return tg_send_message($message,$user_id);
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
        
        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        $sent_message = tg_send_message($message,$user_id,$extras);# Add reply keyboards
        //Return status and message
        return array(
            'ok' => (bool)$remove_status,
            'message' => $sent_message 
        ); 

    }

    //Profile info ~ displays information about the profile
    public function profile_info($user_id=NULL)
    {
        $this->ci->load->model('user_model');
        
        $user_id = $user_id ?? $this->current_user_id;
        $user = $this->ci->user_model->get_user_data($user_id,TRUE); # Get user and fetch info from it
        
        $message = lang('profile_info_missing'); # If We can't get the user, this message will be used

        //If the user is set, we can get user properties
        if(isset($user))
        {
            $message = tg_parse_msg(lang('profile_info'),array(
                'phone' => $user->phone,
                'age' => $user->age,
                'gender' => $user->gender,
                'gender_preference' => $user->gender_preference,
                'min_age' => $user->min_age,
                'max_age' => $user->max_age,
                'location' => $user->location,
                'needs_appreciation' => $user->needs_appreciation,
                'providing_appreciation' => $user->providing_appreciation
            ));
        }

        return tg_send_message($message,$user_id);
    }

    //Profile attribute info
    public function profile_attr_info($attr,$user_id=NULL)
    {
        $this->ci->load->model('user_model');#Used to get user properties

        $user_id = $user_id ?? $this->current_user_id;
        $user = $this->ci->user_model->get_user_data($user_id,TRUE)->row_object(); # Get user and fetch info from it
        $message = lang('profile_info_missing'); # If We can't get the user, this message will be used

        //If the user is set, we can get user properties
        if(isset($user))
        {
            // Determine which information to set as the message
            switch($attr)
            {
                case 'phone': 
                    $message = tg_parse_msg(lang('profile_info_phone'),array(
                        'phone' => $user->phone
                    ));
                break;
                case 'age': 
                    $message = tg_parse_msg(lang('profile_info_age'),array(
                        'age' => $user->age
                    ));
                break;
                case 'gender': 
                    $message = tg_parse_msg(lang('profile_info_gender'),array(
                        'gender' => $user->gender
                    ));
                break;
                case 'gender_preference': 
                    $message = tg_parse_msg(lang('profile_info_gender_preference'),array(
                        'gender_preference' => $user->gender_preference
                    ));
                break;
                case 'min_age': 
                    $message = tg_parse_msg(lang('profile_info_min_age'),array(
                        'min_age' => $user->min_age
                    ));
                break;
                case 'max_age': 
                    $message = tg_parse_msg(lang('profile_info_max_age'),array(
                        'max_age' => $user->max_age
                    ));
                break;
                case 'location': 
                    $message = tg_parse_msg(lang('profile_info_location'),array(
                        'location' => $user->location
                    ));
                break;
                case 'needs_appreciation': 
                    $message = tg_parse_msg(lang('profile_info_needs_appreciation'),array(
                        'needs_appreciation' => $user->needs_appreciation
                    ));
                break;
                case 'providing_appreciation': 
                    $message = tg_parse_msg(lang('profile_info_providing_appreciation'),array(
                        'providing_appreciation' => $user->providing_appreciation
                    ));
                break;
            }
        }

        $extras = array(
            'reply_markup'=>tg_reply_keyboard_remove()
        );
        return tg_send_message($message,$user_id,$extras);
    }
}