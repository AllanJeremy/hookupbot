<?php defined('BASEPATH') or exit('No direct script access allowed');

class Telegram
{
    public $ci;
    private static $access_token = '475594525:AAGaQhupVlm_IaRCXzlrLighdqkbUoKWRWw';
    private static $website = 'https://api.telegram.org/bot';
    protected $api_url;

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
        foreach($params as $param)
        {
            $prefix = '&';
            //If it is the first param and concatenation is disabled ~ then set the prefix to ''
            if($count == 0 && !$is_concat)
            {   $prefix = ''; }

            //Get the key name ~ we will use this as the param name in the url
            $key = key($param);

            //If the key is not set start again
            if(!isset($key))
            {   continue;   }

            $value = $param[$key];
            //Check if the value is an array. If it is pass it as a json object
            if(is_array($value))
            {   $value = json_encode($value); }
            
            $url .= $prefix.$key.'='.$value;
            $count++;#increment count here ~ only happens if a valid param was found
        }

        return $url;
    }

    //Send message
    public function send_message($chat_id,$text,$extras=NULL)
    {
        $url = $this->api_url.'sendMessage?';

        $url .= 'chat_id='.$chat_id;
        $url .= '&text='.$text;

        if(isset($extras) && is_array($extras))
        {
            $url .= $this->_parse_params($extras);
        }

        return $this->_get_request($url);
    }

}