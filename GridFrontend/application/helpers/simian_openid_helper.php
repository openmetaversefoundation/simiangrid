<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function openid_identifier_render($title, $form_url, $button_label)
	{
		$openid_identifier = array(
		    'name'  => 'openid_identifier',
		    'id'    => 'openid_identifier',
		    'size'  => 40
		);
		
		$ci =& get_instance();
		if ( $ci->sg_auth->enabled_openid ) {
			$result = "<fieldset><legend>$title</legend><dl>";
			$result = $result . form_open($form_url);
			$result = $result . form_hidden('action', 'verify');
			$result = $result . "<dt>" . form_label('OpenID', $openid_identifier['id']) . "</dt>";
			$result = $result . '<dd>' . form_input($openid_identifier) . form_submit('submit', $button_label, 'class="button"');
			$result = $result . form_error($openid_identifier['name']) . "</dd>";
			$result = $result . form_close() . "</dl></fieldset>";
			echo $result;
		}
	}
	
	function openid_process_verify($ci, $callback_url)
	{
		$user_id = $ci->input->post('openid_identifier');
		$pape_policy_uris = $ci->input->post('policies');

		if (!$pape_policy_uris) {
		  $pape_policy_uris = array();
		}

		$ci->config->load('openid');
		$sreg_req = $ci->config->item('openid_sreg_required');
		$sreg_opt = $ci->config->item('openid_sreg_optional');
		$ax_req = $ci->config->item('openid_ax_required');
		$ax_opt = $ci->config->item('openid_ax_optional');
		$policy = site_url($ci->config->item('openid_policy'));

		$ci->openid->set_request_to($callback_url);
		$ci->openid->set_trust_root(base_url());
		$ci->openid->set_args(null);
		$ci->openid->set_sreg(true, $sreg_req, $sreg_opt, $policy);
		$ci->openid->set_ax(true, $ax_req, $ax_opt);
		$ci->openid->set_pape(false, $pape_policy_uris);
		$ci->openid->authenticate($user_id);
	}

	function openid_check($ci, $callback_url, &$data)
	{
		if (!isset($data))
	        $data = array();
	    
	    $ci->config->load('openid');
	    
	    $ci->openid->set_request_to($callback_url);
	    $response = $ci->openid->getResponse();

    	switch ($response->status) {
        case Auth_OpenID_CANCEL:
            push_message('error', $ci->lang->line('openid_cancel'), $ci);
            break;
        case Auth_OpenID_FAILURE:
            push_message('error', set_message('openid_failure', $response->message), $ci);
            break;
        case Auth_OpenID_SUCCESS:
            $openid = $response->getDisplayIdentifier();
            $esc_identity = htmlspecialchars($openid, ENT_QUOTES);
            
            $data['openid_identifier'] = $openid;

            $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
            $sreg = $sreg_resp->contents();

            $ax_resp = new Auth_OpenID_AX_FetchResponse();
            $ax = $ax_resp->fromSuccessResponse($response);
            
            if (isset($sreg['email'])) {
                $data['email'] = $sreg['email'];
            }
            if ($ax) {
                if (isset($ax->data['http://axschema.org/contact/email']))
                    $data['email'] = $ax->getSingle('http://axschema.org/contact/email');
                if (isset($ax->data['http://axschema.org/namePerson/first'])) {
					$first_name = $ax->getSingle('http://axschema.org/namePerson/first');
				}
                if (isset($ax->data['http://axschema.org/namePerson/last'])) {
					$last_name = $ax->getSingle('http://axschema.org/namePerson/last');
				} else {
					$last_name = "Avatar";
				}
				
				if ( $first_name != null && $last_name != null ) {
					$data['username'] = "$first_name $last_name";
				}
            }

            return true;
        }
        
        return false;
	}
?>