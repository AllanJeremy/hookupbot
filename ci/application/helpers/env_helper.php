<?php defined('BASEPATH') or exit('No direct script access allowed');

//Returns true if the environment is development environment & vice versa
function is_dev_environment()
{
    return ($_SERVER['HTTP_HOST'] == 'localhost');
}

//Returns true if the environment is production environment & vice versa
function is_production_environment()
{
    return !is_dev_environment();
}