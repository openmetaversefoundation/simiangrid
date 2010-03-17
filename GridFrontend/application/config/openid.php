<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| OpenID support
|--------------------------------------------------------------------------
|
| 'openid_enabled' = Determine if OpenID registration and logins should be enabled.
|
*/

$config['openid_enabled'] = TRUE;

/*
|--------------------------------------------------------------------------
| OpenID temporary store
|--------------------------------------------------------------------------
|
| 'openid_storepath' = A relative or absolute filesystem path. This directory must be
|                      writable by the web server.
|
*/

$config['openid_storepath'] = 'tmp';

/*
|--------------------------------------------------------------------------
| OpenID constants
|--------------------------------------------------------------------------
|
| Do not modify anything below this line unless you know what you are doing.
|
*/

$config['openid_sreg_required'] = array();
$config['openid_sreg_optional'] = array('fullname', 'email');

$config['openid_ax_required'] = array(
	'http://axschema.org/contact/email' => 'email',
	'http://axschema.org/namePerson/first' => 'first',
	'http://axschema.org/namePerson/last' => 'last'
);
$config['openid_ax_optional'] = array();
