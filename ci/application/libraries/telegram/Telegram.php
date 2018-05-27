<?php defined('BASEPATH') or exit('No direct script access allowed');

class Telegram
{
    public $ci;
    private static $access_token = '475594525:AAGaQhupVlm_IaRCXzlrLighdqkbUoKWRWw';
    private static $website = 'https://api.telegram.org/bot';
    protected $api_url;

    protected static $invalid_cmd_message = '[telegram library]I did not understand that command. Use /help for a list of supported commands';# Message shown when an invalid command is provided

    function __construct()
    {
        $this->ci = &get_instance();
        $this->_set_api_url();
    }

    // Set the api url
    private function _set_api_url()
    {
        $this->api_url = self::$website.self::$access_token.'/';
    }

    //Get request
    private function _get_request($url)
    {
        return file_get_contents($url);
    }

    // Parse params and return a url string with the concatenated (in some cases) params
    private function _parse_params($params,$is_concat=TRUE)
    {
        $url='';
        // If the params provided are not an array ~ assume no params provided
        if(!is_array($params))
        {   return $url;    }

        $count = 0;
        foreach($params as $key=>$value)
        {
            $prefix = '&';
            //If it is the first param and concatenation is disabled ~ then set the prefix to ''
            if($count == 0 && !$is_concat)
            {   $prefix = ''; }

            //If the key is not set start again
            if(!isset($key))
            {   continue;   }


            //Check if the value is an array. If it is pass it as a json object
            if(is_array($value))
            {   $value = json_encode($value); }
            
            $url .= $prefix.$key.'='.$value;
            $count++;#increment count here ~ only happens if a valid param was found
        }

        return $url;
    }

    //Find the current user message information
    public function get_user_update()
    {
        return json_decode(file_get_contents('php://input'));
    }
    
    //Get the current user ~ user that sent the message
    public function get_current_user()
    {
        return $this->get_user_update()->message->from ?? NULL;
    }

    //Get current user id ~ returns id if found : false if not 
    public function get_current_user_id()# added as an abstraction to prevent errors
    {
        $user = $this->get_current_user();
        return isset($user) ? $user->id : NULL;
    }

    //Send message
    public function send_message($text='generic text',$chat_id=NULL,$extras=NULL)
    {
        $chat_id = $chat_id ?? $this->get_current_user_id();
        $url = $this->api_url.'sendMessage?';

        $url .= 'chat_id='.$chat_id;

        #Format the text so that it is url friendly : otherwise messages with spaces won't send correctly ~ fixed issue #02 on github
        $text = urlencode($text);
        $url .= '&text='.$text;

        if(isset($extras) && is_array($extras))
        {
            $url .= $this->_parse_params($extras);
        }

        return $this->_get_request($url);
    }

    //Send invalid command message
    public function send_invalid_cmd_message($chat_id=NULL)
    {
        $chat_id = $chat_id ?? $this->get_current_user_id();
        return $this->send_message(self::$invalid_cmd_message,$chat_id);
    }

    //Send debug message ~ sends to dev chat_id : used for testing
    public function debug_message($message,$extras=NULL)
    {
        $message = is_array($message) ? json_encode($message) : $message;
        return $this->send_message($message,TEST_CHAT_ID,$extras);
    }
}