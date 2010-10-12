<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sg_username_check($ci, $username)
{
    $result = false;
    $space_pos = strpos($username, ' ');
    if ( $space_pos !== false ) {
        $first_name = substr($username, 0, $space_pos);
        $last_name = substr($username, $space_pos);
        if ( $ci->form_validation->alpha_dash($first_name) && $ci->form_validation->alpha_dash($first_name) ) {
            $result = true;
        }
    }
    return $result;
}

function sg_username_exists_check($ci, $username)
{
    $result = $ci->simiangrid->get_user_by_name($username);
    if ( $result != null ) {
        return false;
    } else {
        return true;
    }
}

function sg_email_check($ci, $email)
{
    $result = $ci->simiangrid->get_user_by_email($email);
    if ( $result != null ) {
        $ci->form_validation->set_message('email_check', lang('sg_auth_email_exists') );
        return false;
    } else {
        return true;
    }
}

function sg_email_exists($ci, $email)
{
    $result = $ci->simiangrid->get_user_by_email($email);
    if ( $result != null ) {
        return true;
    } else {
        $ci->form_validation->set_message('email_exists', lang('sg_auth_email_not_exist') );
        return false;
    }
}

?>