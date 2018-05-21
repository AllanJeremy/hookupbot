<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    //User related functions go here
    protected function _select_user_query($include_phone=FALSE,$include_id=TRUE)
    {
        $select = '';
        if($include_id)
        { $select .= TBL_USERS.'.id,'; }
        
        $select .= TBL_USERS.'.is_bot,';
        $select .= TBL_USERS.'.first_name,';
        $select .= TBL_USERS.'.last_name,';
        $select .= TBL_USERS.'.username,';
        $select .= TBL_USERS.'.language_code,';
        $select .= TBL_USERS.'.age,';
        $select .= TBL_USERS.'.gender,';
        $select .= TBL_USERS.'.latitude,';
        $select .= TBL_USERS.'.longitude,';
        $select .= TBL_USERS.'.location_title,';
        $select .= TBL_USERS.'.location_address,';
        $select .= TBL_USERS.'.gender_preference,';
        $select .= TBL_USERS.'.min_age,';
        $select .= TBL_USERS.'.max_age,';
        $select .= TBL_USERS.'.needs_appreciation,';
        $select .= TBL_USERS.'.providing_appreciation,';
        $select .= TBL_USERS.'.details,';
        $select .= TBL_USERS.'.is_banned,';
        $select .= TBL_USERS.'.date_joined';

        if($include_phone)
        {   $select .= ','.TBL_USERS.'.phone' ;}

        return $select;
    }
}