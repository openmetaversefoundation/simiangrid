<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function username_check($username)
{
	$result = false;
	$space_pos = strpos($username, ' ');
	if ( $space_pos !== false ) {
		$first_name = substr($username, 0, $space_pos);
		$last_name = substr($username, $space_pos);
		if ( $this->form_validation->alpha_dash($first_name) && $this->form_validation->alpha_dash($first_name) ) {
			$result = true;
		}
	}
	return $result;
}

function username_exists_check($username)
{
	$result = $this->simiangrid->get_user_by_name($username);
	if ( $result != null ) {
		return false;
	} else {
		return true;
	}
}

function email_check($email)
{
	$result = $this->simiangrid->get_user_by_email($email);
	if ( $result != null ) {
		$this->form_validation->set_message('email_check', lang('sg_auth_email_exists') );
		return false;
	} else {
		return true;
	}
}

function email_exists($email)
{
	$result = $this->simiangrid->get_user_by_email($email);
	if ( $result != null ) {
		return true;
	} else {
		$this->form_validation->set_message('email_exists', lang('sg_auth_email_not_exist') );
		return false;
	}
}

?>