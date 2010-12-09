<?php

class Launch extends Controller {

    function Launch()
    {
        parent::Controller();   
    }
    
    function index()
    {
        log_message('debug', "Building a launch document");
        
        $data = array('name' => '', 'region' => '', 'type' => '', 'login_url' => '');
        $capID = null;
        
        if ($this->sg_auth->is_logged_in()) {
            $user_id = $this->sg_auth->get_uuid();
            log_message('debug', "Launch document is for logged in user $user_id");
            
            $user = $this->simiangrid->get_user($user_id);
            if ( ! empty($user))
            {
                log_message('debug', "$user_id is " . $user['Name']);
                $data['name'] = $user['Name'];
            } else {
                log_message('debug', "Failed to resolve $user_id to an account name");
            }
            
            $timeout_seconds = 10 * 60; // Expires in 10 minutes
            $capID = $this->simiangrid->add_capability($user_id, 'login', time() + $timeout_seconds);
        } else {
            log_message('debug', "Launch document is for unauthenticated user");
        }
        
        if ( ! empty($capID)) {
            log_message('debug', "Generated capability $capID for $user_id");
            
            $data['login_url'] = $this->config->item('login_service') . "?cap=$capID";
            $data['type'] = 'capability';
        } else {
            $data['login_url'] = $this->config->item('login_service');
        }
        
        // TODO: Allow region to be passed in as a parameter (sanitize!)
        //$data['region'] = '';
        
        $this->parser->parse('launch', $data);
    }
}
