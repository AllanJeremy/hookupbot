<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class contains an abstraction to the telegram API
class Telegram
{
    private static $_website = 'https://api.telegram.org/bot';
    private $_access_token;
    protected $api_url;

    //Constructor
    function __construct()
    {
        $this->access_token = '475594525:AAGaQhupVlm_IaRCXzlrLighdqkbUoKWRWw';#Your bot access token
        $this->_set_api_url();
    }
    
    /* PRIVATE FUNCTIONS */
    private function _set_api_url()
    {
        $this->api_url = $this->_website.$this->_access_token.'/';
    }

    //Make a GET request ~ returns response
    protected function get_request($url)
    {
        return file_get_contents($url);
    }
    // ~ consider adding ability to do POST requests
    
    // Parse a list of url parameters into a single url string
    private function _parse_params($params,$is_concat=TRUE)
    {
        $url = '';
        if(!is_array($params))
        {   return $url; }
        
        $count = 0;
        foreach($extra as $param)
        {
            $prefix = '&';
            //If it is the first param and concatenation is disabled ~ then set the prefix to ''
            if($count == 0 && !$is_concat)
            {   $prefix = '';   }

            //Get key name ~ we will use this as the param name in the url
            $key = key($param);

            //If the key is not set start the loop again
            if(!isset($key))
            {   continue;   }

            $value = $param[$key];
            //Check if the value is an array. If it is, pass it as a json object
            if(is_array($value))
            {   $value = json_encode($value);   }

            $url .= $prefix.$key.'='.$value;
            $count++; #increment count here ~ only happens if a valid param was found
        }

        return $url;
    }

    /* PUBLIC FUNCTIONS */
    //Get the message sent by a user
    public function get_user_update()
    {
        return $this->input->raw_input_stream;
    }
    
    //Send a message
    public function send_message($chat_id,$text,$extra=NULL)
    {
        $url = $this->api_url.'sendMessage?';

        $url .= 'chat_id='.$chat_id;
        $url .= '&text='.$text;

        //other_params = array('param_name'=>'param_value')
        if(isset($extra) && is_array($extra) && count($extra)>0)
        {
            $url .= $this->_parse_params($extra);
        }

        //Make the API request
        return $this->get_request($url);
    }

}