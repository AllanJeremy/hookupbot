<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class handles hookups as well as hookup pools
class Hookup_model extends CI_Model
{
    function __construct()
    {
        $this->load->database;
    }

    //Hookup related functions go here

    //TODO: Hookup joins
    private function _hookup_joins()
    {

    }

    //TODO: Hookup Request joins
    private function _hookup_request_joins()
    {

    }

    //TODO: Pool joins
    private function _pool_joins()
    {

    }
    
    //TODO: Add to hookup pool
    public function add_to_pool($data)
    {

    }

    //TODO: Remove from hookup pool
    public function remove_from_pool($user_id=NULL) #if user id is not set, get the current user
    {

    }

    //TODO: Select user from hookup pool
    public function select_user_from_pool($pool_id)
    {

    }

    //TODO: View/get hookup pool matches (for current user)
    public function get_pool_matches($user_id=NULL)#if user id is not set, get the current user
    {

    }

    //TODO: Make hookup request to user in hookup pool
    public function make_hookup_request()
    {

    }

    //TODO: View hookup request
    public function view_hookup_request($request_id)
    {

    }

    //TODO: Confirm hookup request ~ once payment has been made
    public function confirm_hookup_request($request_id)
    {
        
    }
    
    //TODO: Update hookup request status ~ accepted or declined
    private function _update_request_status($request_id,$accepted=FALSE)
    {

    }

    //TODO: Accept hookup request
    public function accept_hookup_request($request_id)
    {
        return $this->_update_request_status($request_id,TRUE);
    }

    //TODO: Decline hookup request
    public function decline_hookup_request($request_id)
    {
        return $this->_update_request_status($request_id,FALSE);
    }

}