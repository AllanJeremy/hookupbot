<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class handles payment related operations
class Payment_model extends CI_Model
{
    function __construct()
    {
        $this->load->database;
    }

    //Payment related functions go here
    //TODO: Check if payment made is valid
    
    //TODO: Add payment information to database
    public function add_payment_info($data)
    {

    }

    //TODO: Update payment information in database
    public function update_payment_info($id,$data)
    {

    }

    //TODO: View payment informatiion
    public function view_payment_info($payment_id)
    {

    }

    //TODO: Remove payment information from database
    public function remove_payment_info($payment_id)
    {
        
    }
}