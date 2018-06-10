<?php defined('BASEPATH') OR exit('No direct script access allowed');

//This class handles user related operations
class User_model extends MY_Model
{
    
    function __construct()
    {
        parent::__construct();
    }
    
    //User selects & joins
    private function _user_joins($include_phone=FALSE)
    {
        $select = $this->_select_user_query($include_phone);
        $this->db->select($select);
        $this->db->from(TBL_USERS);
    }

    //Joins for the user images
    private function _user_images_joins($include_phone=FALSE)
    {
        $select = TBL_USER_IMAGES.'.*,';
        $select .= $this->_select_user_query($include_phone,FALSE);
        
        $this->db->select($select);
        $this->db->from(TBL_USER_IMAGES);
        $this->db->join(TBL_USERS,TBL_USER_IMAGES.'.user_id = '.TBL_USERS.'.id');
    }

    //Get user data ~ returns false if the user doesn't exist
    public function get_user_data($user_id,$include_phone=FALSE)
    {
        $this->_user_joins($include_phone);
        $this->db->where(TBL_USERS.'.id',$user_id);
        return $this->db->get()->row_object();
    }

    //Get user images
    public function get_user_images($user_id,$include_phone=FALSE)
    {
        $this->_user_images_joins($include_phone);
        $this->db->where(TBL_USER_IMAGES.'.user_id',$user_id);
        return $this->db->get();
    }

    //Update/insert user data
    public function set_user_data($data,$user_id=NULL)
    {
        //If the data is not an array (invalid assumed in this case) ~ return false
        if(!is_array($data))
        {   return FALSE;   }

        $user_id = $user_id ?? $data['id'];//Try getting the user id from the data array
        $user_id = $user_id ?? tg_get_current_user_id();#assume current user id if none provided
        $this->db->select(TBL_USERS.'.id');#Only select id for faster queries
        $this->db->from(TBL_USERS);
        $this->db->where(TBL_USERS.'.id',$user_id);
        $user = $this->db->get()->row_object();

        //Reset the query builder so we can build new queries 
        $this->db->reset_query();#Note : not sure if get() resets query builder hence this
        $op_result = NULL;#Operation result for the operation we performed ~ insert/update

        //If the user exists ~ update it
        if(isset($user))
        {
            $op_result = $this->db->update(TBL_USERS,$data,array(TBL_USERS.'.id' => $user_id));
        }
        else //The user does not exist, add/insert it
        {   
            $op_result = $this->db->insert(TBL_USERS,$data);
        }
        
        return $op_result;
    }

    //Remove user data 
    public function remove_user_data($user_id,$columns=NULL)
    {
        $remove_status = TRUE;
        //If there are extra columns provided, removes specific data(columns) from user
        if(is_array($columns))
        {
            $this->db->where(TBL_USERS.'.id',$user_id);
            foreach($columns as $col)
            {
                $this->db->set($col,NULL);
                $remove_status &= $this->db->update(TBL_USERS);
                $this->db->where($col,'*');
            }
        }
        else
        {   $remove_status = FALSE;   }

        return $remove_status;
    }

    //Remove user image
    public function remove_user_image($image_id)
    {
        $this->db->where(TBL_USER_IMAGES.'.file_id',$image_id);
        return $this->db->delete(TBL_USER_IMAGES);
    }
}