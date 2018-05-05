<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Handles profile related commands
class Cmd_profile
{
    protected $ci;
    function __construct()
    {
        $this->ci = &get_instance();
    }

    //Handle the various commands
    public function handle_command($cmd)
    {
        //TODO: Add implementation
        echo 'Handling profile command<br><p>';
        var_dump($cmd);
        echo '</p>';
    }

    /* 
        Every subcommand will have its own function here
    */
}