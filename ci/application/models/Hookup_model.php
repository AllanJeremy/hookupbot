<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class handles hookups as well as hookup pools
class Hookup_model extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    //Hookup related functions go here

    // Hookup joins
    private function _hookup_joins($include_phone=FALSE)
    {
        $select = TBL_HOOKUPS.'.*,';
        
        $select .= TBL_HOOKUP_REQUESTS.'.user_id,'; 
        $select .= TBL_HOOKUP_REQUESTS.'.hookup_id,'; 
        $select .= TBL_HOOKUP_REQUESTS.'.is_accepted,'; 
        $select .= TBL_HOOKUP_REQUESTS.'.date_requested,'; 

        $select .= TBL_PAYMENTS.'.*';

        $this->db->select($select);
        $this->db->from(TBL_HOOKUPS);
        $this->db->join(TBL_HOOKUP_REQUESTS,TBL_HOOKUPS.'.request_id = '.TBL_HOOKUP_REQUESTS.'.id');
        $this->db->join(TBL_PAYMENTS,TBL_HOOKUPS.'.payment_id = '.TBL_PAYMENTS.'.id');
    }

    // Hookup Request joins
    private function _hookup_request_joins()
    {
        $select = TBL_HOOKUP_REQUESTS.'.*,';
        $select .= TBL_POOL.'.id,';
        $select .= TBL_POOL.'.is_taken,';
        $select .= TBL_POOL.'.date_joined';
        
        $this->db->select($select);
        $this->db->from(TBL_HOOKUP_REQUESTS);
        $this->db->join(TBL_POOL,TBL_HOOKUP_REQUESTS.'.hookup_id = '.TBL_POOL.'.hookup_user_id');
    }

    // Pool joins
    private function _pool_joins()
    {
        $select = TBL_POOL.'.*,';
        $select .= $this->_select_user_query();

        $this->db->select($select);
        $this->db->from(TBL_POOL);
        $this->db->join(TBL_USERS,TBL_POOL.'.hookup_user_id = '.TBL_USERS.'.id');
    }
    
    // Add to hookup pool
    public function add_to_pool($data)
    {
        return is_array($data) ? $this->db->insert(TBL_POOL,$data) : FALSE;
    }

    // Remove from hookup pool
    public function remove_from_pool($user_id) #if user id is not set, get the current user
    {
        $this->db->where(TBL_POOL.'.hookup_user_id',$user_id);
        return isset($user_id) ? $this->db->delete(TBL_POOL) : FALSE;
    }

    // Select user from hookup pool
    public function select_user_from_pool($pool_id)
    {
        $this->db->where(TBL_POOL.'.id',$pool_id);
        $this->_pool_joins();
        return isset($pool_id) ? $this->db->get() : FALSE;
    }

    //TODO: View/get hookup pool matches (for current user) ~ retrive all records other than the user him/herself
    public function get_pool_matches($user_id=NULL)#if user id is not set, get the current user
    {

    }

    // Make hookup request to user in hookup pool
    public function make_hookup_request($requester_id,$hookup_pool_id)
    {
        $this->db->set(TBL_HOOKUP_REQUESTS.'.user_id',$request_id);
        $this->db->set(TBL_HOOKUP_REQUESTS.'.hookup_id',$hookup_pool_id);
        $add_request = isset($requester_id,$hookup_pool_id) ? $this->db->insert(TBL_HOOKUP_REQUESTS) : FALSE;

        //Set the is_taken status to true in the hookup pool
        $this->db->reset_query();
        $this->db->where(TBL_POOL.'.id',$hookup_pool_id);
        $this->db->set(TBL_POOL.'.is_taken',TRUE);
        $update_pool = isset($requester_id,$hookup_pool_id) ? $this->db->update(TBL_POOL) : FALSE;

        return (bool)($add_request && $update_pool);
    }

    // View hookup request
    public function view_hookup_request($request_id)
    {
        $this->_hookup_request_joins();
        $this->db->where(TBL_HOOKUP_REQUESTS.'.id',$request_id);
        return isset($request_id) ? $this->db->get() : FALSE;
    }

    //Returns true if the hookup request exists and false if it doesn't
    private function _hookup_request_exists($request_id)
    {
        $request = $this->view_hookup_request($request_id); #Get the hookup request
        $request_exists = ($request != FALSE) ? (count($request->result_array())>0) : $request;
        return $request_exists;
    }
    // Confirm hookup request ~ once payment has been made. Add to hookups table
    public function confirm_hookup_request($request_id)
    {
        $this->db->set(TBL_HOOKUPS.'.request_id',$request_id);
        
        // Check whether the request exists or not and decide if we need to update or insert
        $status = $this->_hookup_request_exists($request_id) ? $this->db->update(TBL_HOOKUPS) : $this->db->insert(TBL_HOOKUPS);
        return $status; 
    }
    
    // Update hookup request status ~ accepted or declined
    private function _update_request_status($request_id,$accepted=FALSE)
    {
        $this->db->where(TBL_HOOKUP_REQUESTS.'.id',$request_id);
        $this->db->set(TBL_HOOKUP_REQUESTS.'.is_accepted',(bool)$accepted);
        
        return isset($request_id) ? $this->db->update(TBL_HOOKUP_REQUESTS) : FALSE;
    }

    // Accept hookup request
    public function accept_hookup_request($request_id)
    {
        // Confirm the hookup request once the hookup_user accepts the request
        $confirm_request = $this->confirm_hookup_request($request_id);
        return $confirm_request && $this->_update_request_status($request_id,TRUE);
    }

    // Decline hookup request
    public function decline_hookup_request($request_id)
    {
        return $this->_update_request_status($request_id,FALSE);
    }

}