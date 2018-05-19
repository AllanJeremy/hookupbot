<?php defined('BASEPATH') or exit('No direct script access allowed');

class Keyboard
{
    public $ci;

    //Constructor
    function __construct()
    {
        $this->ci = &get_instance();
    }
}