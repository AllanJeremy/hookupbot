<?php defined('BASEPATH') or exit('No direct script access allowed');

class Keyboard
{
    /* 
        HELPER FUNCTIONS
    */
    //Helper functions will go here
    /* 
        KEYBOARDS
    */
    //Inline keyboard ~ returns an inline keyboard object
    public function inline_keyboard(array $buttons)
    {
        $keyboard = array(
            'inline_keyboard' => json_encode($buttons)
        );
        return $keyboard;
    }

    //Reply keyboard ~ returns a reply keyboard object
    public function reply_keyboard(array $buttons,bool $resize_keyboard=TRUE,bool $one_time_keyboard=TRUE, bool $selective=FALSE)
    {
        $keyboard = array(
            'keyboard' => $buttons,
            'resize_keyboard' => (bool)$resize_keyboard,
            'one_time_keyboard' => (bool)$one_time_keyboard,
            'selective' => (bool)$selective,
        );

        return json_encode($keyboard);
    }

    //Reply keyboard remove ~ returns a reply_keyboard remove object
    public function reply_keyboard_remove(bool $should_remove=TRUE,bool $selective=FALSE)
    {
        $keyboard = array(
            'remove_keyboard' => (bool)$should_remove,
            'selective' => (bool)$selective,
        );   
        return json_encode($keyboard);
    }
    
    //Force reply ~ returns a force reply object
    public function force_reply(bool $should_force=TRUE,bool $selective=FALSE)
    {
        $keyboard = array(
            'force_reply' => (bool)$should_force,
            'selective' => (bool)$selective
        );
        return json_encode($keyboard);
    }

    /* 
        KEYBOARD BUTTONS
    */
    //Inline keyboard button ~ returns a single inline keyboard button
    public function inline_button(string $text,array $optional)
    {#Optional must be exactly one item. If not, only the first item will be considered
        $button = array(
          'text' => $text  
        );

        //If an optional property has been provided ~ read the first one and add it as an attribute to the button
        foreach($optional as $key=>$value) #This prevents unnecessary setting of optionals
        {
            $button[$key] = $value;
            break;//Ensures only the first value was checked
        }
        
        return $button;
    }

    //Keyboard button ~ returns a single keyboard button
    public function button(string $text,bool $request_contact=NULL,bool $request_location=NULL)
    {
        $button = array(
            'text' => $text
        );   

        if(isset($request_contact))
        {   
            $button['request_contact'] = (bool)$request_contact;
        }
        else if(isset($request_location))
        {
            $button['request_location'] = (bool)$request_location;
        }

        return $button;
    }
}