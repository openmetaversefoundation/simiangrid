<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	function get_site_title()
	{
		$CI =& get_instance();
		if ( $CI->config->item('grid_name_short') == null ) {
			return "SimianGrid";
		} else {
			return $CI->config->item('grid_name');
		}
	}
	
	function get_site_host()
	{
		$url_bits = explode('/', get_base_url());
		$domain_name = $url_bits[2];
		return $domain_name;
	}
	
	function get_site_path()
	{
		$url_bits = explode('/', get_base_url());
		$site_path = implode('/', array_slice($url_bits, 3));
		return $site_path;
	}
	
	function get_base_url()
	{
		$url = base_url();
		$check = substr($url, count($url) - 2, count($url) - 1);
		if ( $check == '/' ) {
			return $url;
		} else {
			return $url . '/';
		}
	}
	
	function push_message($message, $type='info', $ci=null)
	{
		if ( $type != 'info' && $type != 'error' ) {
			return;
		}
		if ( $ci == null ) {
			$ci =& get_instance();
		}
		$messages = $ci->session->userdata('flash_messages');
		if ( $messages == null ) {
			$messages = array();
		}
		$message_data = array(
			'type' => $type,
			'text' => $message
		);
		array_push($messages, $message_data);
		$ci->session->set_flashdata('messages', $messages);
	}
	
	function messages_render()
	{
		$ci =& get_instance();
		$render = "<ul>";
		
		$messages = $ci->session->flashdata('messages');
		if ( $messages != null ) {
			foreach ( $messages as $message_data ) {
				$type = $message_data['type'];
				$message = $message_data['text'];
				$id = "message_$type";
				if ( $type == "info" ) {
					$type = 'information';
				}
				$image = "<img src=\"" . "static/images/dialog-$type.png" . "\"></img>";
				$render = "$render<li class=\"flash_message\" id=\"$id\">$image $message</li>";
			}
		}
		
		$render = "$render</ul>";
		echo $render;
		echo <<< END
<script type="text/javascript">
	$().ready(function() {
		setTimeout(function() {
			$(".flash_message").hide('explode', {}, 1000);
		}, 5000);
	});
</script>
END;
	}

	function set_message($msg, $val = '', $sub = '%s')
    {
        return str_replace($sub, $val, lang($msg));
    }

	function json_access_levels($selected=null)
	{
		$ci =& get_instance();
		$access_levels = $ci->sg_auth->access_level_map();
		if ( $selected != null ) {
			$access_levels['selected'] = $selected;
		}
		echo json_encode($access_levels);
	}
	
	function get_stylesheet()
	{
		$ci =& get_instance();
		$user_id = $ci->sg_auth->get_uuid();
		$stylesheet = null;
		if ( $user_id != null ) {
			$stylesheet = $ci->user_settings->get_style($user_id);
		}
		if ( $stylesheet == null ) {
			$stylesheet = $ci->config->item('default_style');
			if ( ! $stylesheet ) {
				$stylesheet = "default";
			}
		}
		return $stylesheet;
	}
	
	function pretty_style($style){
		$ci =& get_instance();
		$styles = $ci->config->item('style_list');
		if ( empty($styles[$style]) ) {
			return $style;
		}
		return $styles[$style];
	}

	function render_stylesheet()
	{
		$stylesheet = get_stylesheet();
		echo <<< END
<link id="main" rel="stylesheet" href="static/styles/$stylesheet/style.css" type="text/css" media="screen"/>
<link id="jquery_ui" rel="stylesheet" href="static/styles/$stylesheet/jquery-ui.css" type="text/css" media="screen"/>
<link id="jquery_qtip" rel="stylesheet" href="static/styles/$stylesheet/jquery.qtip.css" type="text/css" media="screen"/>
END;
	}
	
	function json_style_list()
	{
		$ci =& get_instance();
		$styles = $ci->config->item('style_list');
		if ( $styles != null ) {
			$stylesheet = get_stylesheet();
			if ( $stylesheet != null ) {
				$styles['selected'] = $stylesheet;
			}
		} else {
			$styles = array('default' => 'Default');
		}
		echo json_encode($styles);
	}
	
	function render_user_link($user_id)
	{
		$ci =& get_instance();
		$user = $ci->simiangrid->get_user($user_id);
		$result = '';
		if ( $ci->sg_auth->is_searchable($user_id) ) {
			$result = anchor(site_url("user/view/$user_id"), $user['Name']);
		} else {
			$result = $user['Name'];
		}
		echo $result;
	}
	
	function render_region_link($region_id)
	{
		$ci =& get_instance();
		$region = $ci->simiangrid->get_scene($region_id);
		echo anchor(site_url("region/info/$region_id"), $region['Name']);
	}
	
	function pretty_access($level)
	{
		$ci =& get_instance();
		$levels = $ci->sg_auth->access_level_map();
		if ( !empty($levels[$level]) ) {
			return $levels[$level];
		} else {
			return $level;
		}
	}

?>
