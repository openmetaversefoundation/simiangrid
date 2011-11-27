<?php

class Admin extends Controller {
    
    var $min_username = 2;
    var $max_username = 20;
    var $min_password = 6;
    var $max_password = 20;

    function Admin()
    {
        parent::Controller();
        $this->load->library('Form_validation');
        $this->load->helper('simian_view_helper');
        $this->load->helper('simian_form_helper');
        
        $this->lang->load('simian_grid', get_language() );
    }
    
    function _admin_home($tab=null)
    {
        $data = array();
        $data['page'] = 'admin';
        if ( $tab != null ) {
            $data['tab']  = $tab;
        } else {
            $data['tab'] = 'maintenance';
        }
        return parse_template('admin/index', $data);
    }

    function index()
    {
        if ( ! $this->sg_auth->is_admin() ) {
            return redirect('about');
        }
        return $this->_admin_home();
    }
    
    function username_check($value)
    {
        return sg_username_check($this, $value);
    }
    
    function username_exists_check($value)
    {
        return sg_username_exists_check($this, $value);
    }
    
    function email_check($value)
    {
        return sg_email_check($this, $value);
    }
    
    function email_exists($value)
    {
        return sg_email_exists($this, $value);
    }

    function add_user($extra=null)
    {
        if ( ! $this->sg_auth->is_admin() ) {
            return redirect('about');
        }
        if ( $extra === null ) {
            return $this->_admin_home('add_user');
        } elseif ( $extra === 'tab' ) {
            $data = array();
            $data['page'] = 'admin';
            return parse_template('admin/add_user', $data, true);
        } elseif ( $extra === 'form' ) {
            $data = array();
            $data['success'] = false;
            $val = $this->form_validation;
            // Set form validation rules    
            $val->set_rules('username', 'User Name', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|callback_username_exists_check');
            $val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[password_confirm]');
            $val->set_rules('password_confirm', 'Confirm Password', 'trim|required|xss_clean');
            $val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
            $val->set_rules('avatar_type', 'Avatar Type', 'trim|xss_clean');
            
            // Run form validation and register user if validation succeeds
            if ($val->run() ) {
                $data['username'] = $val->set_value('username');
                $data['password'] = $val->set_value('password');
                $data['password_confirm'] = $val->set_value('password_confirm');
                $data['email'] = $val->set_value('email');
                $data['avatar_type'] = $val->set_value('avatar_type', 'DefaultAvatar');
                $user_id = $this->sg_auth->register($data['username'], $data['password'], $data['email'], $data['avatar_type']);
                if ( $user_id != null ) {
                    log_message('debug', "Succesfully created new user " . $data['username']);
                    $data['user_id'] = $user_id;
                    $data['success'] = true;
                } else {
                    log_message('warning', "Unable to create new user " . $data['username']);
                }
            } else {
                log_message('debug', "add_user form validation failed");
                $data['username_error'] = form_error('username');
                $data['password_error'] = form_error('password');
                $data['avatar_type_error'] = form_error('avatar_type');
                $data['email_error'] = form_error('email');
            }
            echo json_encode($data, TRUE);
        }
    }

    function maintenance($extra=null)
    {
        if ( ! $this->sg_auth->is_admin() ) {
            return redirect('about');
        }
        if ( $extra === null ) {
            return $this->_admin_home('add_user');
        }
        $data = array();
        $data['page'] = 'admin';
        $data['total_users'] = $this->simiangrid->total_user_count();
        $data['total_scenes'] = $this->simiangrid->total_scene_count();
        return parse_template('admin/maintenance', $data, true);
    }

    function hypergrid($extra=null)
    {
        if ( ! $this->sg_auth->is_admin() ) {
            return redirect('about');
        }
        if ( $extra === null ) {
            return $this->_admin_home('hypergrid');
        } else if ( $extra === 'tab' ) {
            $data = array();
            $data['page'] = 'hypergrid';
            return parse_template('admin/hypergrid', $data, true);
        } else if ( $extra === 'list' ) {
            parse_str($_SERVER['QUERY_STRING'],$_GET);
            $raw_results = $this->simiangrid->get_hyperlinks();
            $search_results = array();
            foreach ( $raw_results as $raw_result ) {
                array_push($search_results, array(anchor('#', $raw_result['name'], array('class'=>'search_result','onclick' => 'hypergrid_popup(\'' . $raw_result['id'] . '\'); return false;'))));
            }
            $search_count = count($search_results);
            $result = array(
                "sEcho" => $_GET['sEcho'],
                "iTotalRecords" => $search_count,
                "iTotalDisplayRecords" => $search_count,
                "aaData" => $search_results
            );
            echo json_encode($result);
            return;
        } elseif ( $extra === 'form' ) {
            $data = array();
            $data['success'] = false;
            $val = $this->form_validation;
            // Set form validation rules    
            $val->set_rules('hg_uri', 'HyperGrid URI', 'trim|required|xss_clean');
            $val->set_rules('region_name', 'Region Name', 'trim|required|xss_clean');
            $val->set_rules('x', 'X', 'trim|required|xss_clean');
            $val->set_rules('y', 'Y', 'trim|required|xss_clean');

            // Run form validation and register user if validation succeeds
            if ($val->run() ) {
                $data['hg_uri'] = $val->set_value('hg_uri');
                $data['region_name'] = $val->set_value('region_name');
                $data['x'] = $val->set_value('x');
                $data['y'] = $val->set_value('y');
                
                if ( $this->simiangrid->link_region($data['hg_uri'], $data['region_name'], $data['x'], $data['y']) ) {
                    log_message('debug', 'Succesfully linked region ' . $data['region_name'] . '@' . $data['hg_uri']);
                    $data['success'] = true;
                } else {
                    log_message('warn', "Unable to link region " . $data['region_name'] . '@' . $data['hg_uri']);
                }
            } else {
                log_message('debug', "link_region form validation failed");
                $data['hg_uri_error'] = form_error('hg_uri');
                $data['link_region_error'] = form_error('link_region');
                $data['x_error'] = form_error('x_type');
                $data['y_error'] = form_error('y');
            }
            echo json_encode($data, TRUE);  
        }
    }
}
