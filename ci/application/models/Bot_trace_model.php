<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class handles payment related operations
class Bot_trace_model extends MY_Model
{
    protected $current_user_id;#TODO: Consider adding to MY_Model
    //Constructor
    function __construct()
    {
        parent::__construct();
        $this->current_user_id = $this->telegram->get_current_user_id();
    }

    //Selects and joins that may be needed for the trace table
    private function _trace_joins()
    {
        $this->db->select(TBL_BOT_TRACE.'.*');
        $this->db->from(TBL_BOT_TRACE);
    }

    //Get a bot trace by user id
    public function get_trace_by_user($user_id)
    {   
        $this->_trace_joins();
        $this->db->where(TBL_BOT_TRACE.'.user_id',$user_id);
        return $this->db->get()->row_object();#Debug
    }

    //Set the various bot trace attributes
    public function set_trace($data,$user_id=NULL)
    {
        $user_id = $user_id ?? $this->current_user_id;#If the user_id is not set, use the current user
        //If the data is not an array ~ don't bothe running the query
        if(!array($data))
        {   return FALSE;   }
        
        $set_status = NULL;
        // If the trace exists, update it
        if($this->get_trace_by_user($user_id))
        {
            $set_status = $this->db->update(TBL_BOT_TRACE,$data,array(TBL_BOT_TRACE.'.user_id'=>$user_id));
        }
        else // Otherwise add it
        {
            $set_status = $this->db->insert(TBL_BOT_TRACE,$data);
        }

        return (bool)$set_status;
    }
    
    //Removes the user's bot trace data ~ useful for resetting bot trace values
    public function remove_user_trace($user_id)
    {
        $data = array(
            'last_bot_message_id' => NULL,
            'last_bot_message' => NULL
        );
        return $this->set_trace($data,$user_id);
    }
}
