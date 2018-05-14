<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Parses commands
class Cmd_parser
{
    //Parse a command string
    public static function parse($cmd_string)
    {
        $cmd_input = explode("\n",$cmd_string);
        $cmd_str = $sub_cmd = $attr = $value = NULL;
        $cmd_data = array(
            'cmd'=>NULL,
            'sub_cmd'=>NULL,
            'attr'=>NULL,
            'value'=>NULL,
        );

        $cmd_list = array();
        //Check if it is a single command string or multiple command strings
        foreach($cmd_input as $cmd)
        { 
            $cmd = trim(preg_replace('/\s+/',' ',$cmd));//Remove any extra whitespace
            $cmd = strtolower($cmd); //Make the command string all lowercase ~ so any case can be used
            
            //If the command is <=1 meaning /c is the shortest command that can be provided
            if(strlen($cmd) <= 1)
            {  continue;  }

            //Split the command into its various sub-sections
            $cmd = explode(' ',$cmd);
            
            //Set the various sections
            $cmd_str = $cmd[0];
            $sub_cmd = $cmd[1] ?? NULL;
            $attr = $cmd[2] ?? NULL;
            $value = $cmd[3] ?? NULL;

            //Save the various sections of the of the command in an associative array
            $cmd_data['cmd'] = $cmd_str;
            $cmd_data['sub_cmd'] = $sub_cmd;
            $cmd_data['attr'] = $attr;
            $cmd_data['value'] = $value;

            array_push($cmd_list,$cmd_data);
        }

        $is_ok =  is_array($cmd_list) && (count($cmd_list)>0);#if the parse was okay
        $message = $is_ok ? 'Success' : 'Sorry, I did not understand that';

        //TODO: Return the commands with the different sections
        return (array(
            'ok' => $is_ok,
            'commands' => $cmd_list,
            'message' => $message
        ));
    }

    //Get command from command string
    public static function get_cmd($cmd_string)
    {
        $cmd = self::Parse($cmd_string);
        return $cmd['command'] ?? NULL;
    }

    //Get subcommand from command string
    public static function get_sub_cmd($cmd_string)
    {
        $cmd = self::Parse($cmd_string);
        return $cmd['sub_command'] ?? NULL;
    }

    //Get values passed from command string
    public static function get_value($cmd_string)
    {
        $cmd = self::Parse($cmd_string);
        return $cmd['value'] ?? NULL;
    }

    //Get flags passed from command string
/*     public static function get_flags($cmd_string)
    {
        $cmd = self::Parse($cmd_string);
        return $cmd['flags'] ?? NULL;
    } */
}