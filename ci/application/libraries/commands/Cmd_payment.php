<?php defined('BASEPATH') or exit('No direct script access allowed');

//This class handles payment commands
class Cmd_payment
{
    public $ci;
    function __construct()
    {
        $this->ci = &get_instance();
    }

    //Handle commands ~ all commands will start running through this function
    public function handle_command($cmd,$callback_query=NULL)
    {
        //If the command was not okay print the error message
        if( !is_array($cmd))
        {
            return tg_debug_message($cmd['message']);
        }

        //TODO: Add implementation
        tg_debug_message('You used the payment command punk');
    }

    /* 
        Functions to handle subcommands will be here
    */
    //Confirm payment ~ returns true on success and false on failure
    public function confirm_payment($request_id,$payment_id)
    {

    }
}