<?php defined('BASEPATH') or exit('No direct script access allowed');

class Keyboard
{
    public $ci;

    //Constructor
    function __construct()
    {
        $this->ci = &get_instance();
    }
    /* 
        HELPER FUNCTIONS
    */

    //Set an option 
    public function set_opt($param_name,$value)
    {
        return  json_encode(array(
            $param_name => value
        ));
    }
    
    /* 
        KEYBOARDS
    */
    //Inline keyboard ~ returns an inline keyboard object
    public function inline_keyboard(array $buttons)
    {
        //TODO: Add implementation
    }

    //Reply keyboard ~ returns a reply keyboard object
    public function reply_keyboard(array $buttons)
    {
        //TODO: Add implementation
    }

    //Reply keyboard remove ~ returns a reply_keyboard remove object
    public function reply_keyboard_remove(bool $should_remove=TRUE)
    {
        //TODO: Add implementation
    }
    
    //Force reply ~ returns a force reply object
    public function force_reply(bool $should_force=TRUE)
    {
        //TODO: Add implementation
    }
   /* 
        KEYBOARD BUTTONS
    */
    //Inline keyboard button ~ returns a single inline keyboard button
    public function inline_button(string $text,array $optional)
    {#Optional must be exactly one item. If not, only the first item will be considered
        //TODO: Add implementation
    }

    //Keyboard button ~ returns a single keyboard button
    public function button(string $text,array $optional)
    {#Optional must be exactly one item. If not, only the first item will be considered
        //TODO: Add implementation
    }
}