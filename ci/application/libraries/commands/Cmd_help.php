<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles help commands
class Cmd_help
{
    public $ci;
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->lang->load('cmd_help');
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd,$callback_query=NULL)
    {
        return $this->help(tg_get_current_user_id());
    }

    /* 
        Functions to handle subcommands will be here
    */
    //The basic help function
    public function help($user_id = NULL)
    {
        $user_id = $user_id ?? tg_get_current_user_id();

        $buttons = array(
            [
                tg_inline_button('Profile',array('callback_data'=>'/profile')),
                tg_inline_button('Hookup',array('callback_data'=>'/hookup')),
            ],
            [
                tg_inline_button('Settings',array('callback_data'=>'/settings')),
                tg_inline_button('Bot info',array('callback_data'=>'/info')),
            ],
        );

        $extras = array(
            'reply_markup'=>tg_inline_keyboard($buttons)
        );
        $message = lang('help_intro');
        tg_send_message($message,$user_id,$extras);
    }
}