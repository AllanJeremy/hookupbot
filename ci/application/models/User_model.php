<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class handles user related operations
class User_model extends CI_Model
{
    function __construct()
    {
        $this->load->database;
    }

    //User related functions go here

    //TODO : Add user data
    public function add_user($data)
    {

    }
    
    //TODO : Update user data
    public function update_user($user_id,$data)
    {

    }

    //TODO : Get user data
    public function get_user_data($user_id)
    {

    }

    //TODO : Remove user data ~ removes specific data(columns) from user
    public function remove_user_data($user_id,$data)
    {

    }
}