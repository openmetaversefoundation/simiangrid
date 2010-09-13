<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH . '/helpers/facebook.php';

function generate_like_button($url)
{
	$CI =& get_instance();
	if ( $CI->config->item('use_facebook') === true ) {
		echo "<iframe src=\"http://www.facebook.com/plugins/like.php?href=" . urlencode($url) . "&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:450px; height:35px;\" allowTransparency=\"true\"></iframe>";
	}
}

function generate_site_stream()
{
	$CI =& get_instance();
	if ( $CI->config->item('use_facebook') === true ) {
		$domain_name = get_site_host();
		echo "<iframe src=\"http://www.facebook.com/plugins/activity.php?site=$domain_name&amp;width=300&amp;height=300&amp;header=true&amp;colorscheme=light&amp;recommendations=false\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:300px; height:300px;\" allowTransparency=\"true\"></iframe>";
	}
}

function generate_tweet_button($url, $text='')
{
	$CI =& get_instance();
	if ( $CI->config->item('use_twitter') === true ) {
		echo "<a href=\"http://twitter.com/share?url=" . urlencode($url) . "\" class=\"twitter-share-button\" data-count=\"horizontal\">Tweet</a><script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script>";
	}
}

function _get_facebook($ci=null)
{
	if ( $ci == null ) {
		$ci =& get_instance();
	}
	
	$fb = null;
	if ( $ci->config->item('use_facebook') === true ) {
		$data = array (
			'appId' => $ci->config->item('facebook_id'),
			'secret' => $ci->config->item('facebook_secret'),
			'cookie' => true
		);
		$fb = new Facebook($data);
	}
	return $fb;
}
function generate_facebook_stub()
{
	$ci =& get_instance();
	$fb = _get_facebook($ci);
	if ( $fb != null ) {
		$fb_session = $fb->getSession();
		$app_id = $fb->getAppId();
		if ( $fb_session != null ) {
			$session = json_encode($fb_session);
		} else {
			$session = '{}';
		}
echo <<<END
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId   : "$app_id",
      session : $session,
      status  : true,
      cookie  : true,
      xfbml   : true
    });

    // whenever the user logs in, we refresh the page
    FB.Event.subscribe('auth.login', function() {
      window.location.reload();
    });
  };

  (function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
END;
	}
}

function generate_facebook_auth($url) {
	$ci =& get_instance();
	if ( $ci->config->item('use_facebook') ) {
		$app_id = $ci->config->item('facebook_id');
		echo "<a href=\"https://graph.facebook.com/oauth/authorize?client_id=$app_id&redirect_uri=$url&scope=email\"><img src=\"http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif\"/></a>";
	}
}

function process_facebook_verification($code, $url) {
	$auth_token = null;
	$ci =& get_instance();
	$ci->load->library('Curl');
	if ( $ci->config->item('use_facebook') ) {
		$app_id = $ci->config->item('facebook_id');
		$app_secret = $ci->config->item('facebook_secret');
		$auth_url = "https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=$url&client_secret=$app_secret&code=$code";
		$result = $ci->curl->simple_get($auth_url);
		if ( $result != null ) {
			parse_str($result, $result_bits);
			if ( isset($result_bits['access_token']) ) {
				$auth_token = $result_bits['access_token'];
			}
		}
	}
	return $auth_token;
}

function _facebook_graph_get($url_suffix, $token=null, $ci)
{
	$ci->load->library('Curl');
	$url = "https://graph.facebook.com/$url_suffix";
	if ( $token != null ) {
		$url = $url . "?access_token=$token";
	}
	return decode_recursive_json($ci->curl->simple_get($url));
}

function facebook_check($ci, $token, &$data)
{
	if (!isset($data))
        $data = array();
	$result = _facebook_graph_get("me", $token, $ci);

	if ( ! $result ) {
		return false;
	}
	if ( ! empty($result['name']) ) {
		$data['username'] = $result['name'];
	}
	if ( ! empty($result['email']) ) {
		$data['email'] = $result['email'];
	}
	return true;
}

function facebook_get_id($ci, $token)
{
	$result = _facebook_graph_get("me", $token, $ci);
	$fb_id = null;
	if ( !empty($result['id']) ) {
		$fb_id = $result['id'];
	}
	return $fb_id;
}

?>
