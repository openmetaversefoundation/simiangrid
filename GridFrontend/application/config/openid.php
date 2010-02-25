<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['openid_storepath'] = 'tmp';
$config['openid_request_to'] = 'auth/check_openid';

$config['openid_sreg_required'] = array();
$config['openid_sreg_optional'] = array('fullname', 'email');

$config['openid_ax_required'] = array(
	'http://axschema.org/contact/email' => 'email',
	'http://axschema.org/namePerson/first' => 'first',
	'http://axschema.org/namePerson/last' => 'last'
);
$config['openid_ax_optional'] = array();
