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
        { $select .= TBL_USER.'.id,'; }
        
        $select .= TBL_USER.'.is_bot,';
        $select .= TBL_USER.'.first_name,';
        $select .= TBL_USER.'.last_name,';
        $select .= TBL_USER.'.username,';
        $select .= TBL_USER.'.language_code,';
        $select .= TBL_USER.'.age,';
        $select .= TBL_USER.'.gender,';
        $select .= TBL_USER.'.latitude,';
        $select .= TBL_USER.'.longitude,';
        $select .= TBL_USER.'.location_title,';
        $select .= TBL_USER.'.location_address,';
        $select .= TBL_USER.'.gender_preference,';
        $select .= TBL_USER.'.min_age,';
        $select .= TBL_USER.'.max_age,';
        $select .= TBL_USER.'.needs_appreciation,';
        $select .= TBL_USER.'.providing_appreciation,';
        $select .= TBL_USER.'.details,';
        $select .= TBL_USER.'.is_banned,';
        $select .= TBL_USER.'.date_joined';

        if($include_phone)
        {   $select .= TBL_USER.'.phone' ;}

        return $select;
    }
}