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
        $this->db->select($this->_select_user_query($include_phone));
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
    
    //Add user data
    public function add_user($data)
    {
        return is_array($data) ? $this->db->insert(TBL_USERS,$data) : FALSE;
    }

    //Update user data
    public function update_user($user_id,$data)
    {
        $this->db->where(TBL_USERS.'.id',$user_id);
        return is_array($data)? $this->db->update(TBL_USERS,$data) : FALSE;
    }

    //Get user images
    public function get_user_images($user_id,$include_phone=FALSE)
    {
        $this->_user_images_joins($include_phone);
        $this->db->where(TBL_USER_IMAGES.'.user_id',$user_id);
        return $this->db->get();
    }

    //Get user data ~ returns false if the user doesn't exist
    public function get_user_data($user_id,$include_phone=FALSE)
    {
        $this->_user_joins($include_phone);
        return $this->db->get();
    }

    //Remove user data 
    public function remove_user_data($user_id,$columns=NULL)
    {
        $this->db->where(TBL_USERS.'.id',$user_id);

        //If there are extra columns provided, removes specific data(columns) from user
        if(is_array($data))
        {
            foreach($columns as $col)
            {
                $this->db->set($col,NULL);
                $this->db->update(TBL_USERS);
                $this->db->where($col,'*');
            }
        }
        else
        {
            return $this->db->delete(TBL_USERS);
        }
    }

    //Remove user image
    public function remove_user_image($image_id)
    {
        $this->db->where(TBL_USER_IMAGS.'.file_id',$image_id);
        return $this->db->delete(TBL_USER_IMAGES);
    }
}