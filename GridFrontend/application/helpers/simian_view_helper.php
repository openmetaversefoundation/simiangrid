<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function generateLikeButton($url)
	{
		$CI =& get_instance();
		
/*		<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fracketberries.eghetto.ca%2F%7Erewt%2Fomfgrid%2FSimian%2FGridFrontend%2Findex.php%2Fuser%2Fview%2F825abe54-f356-4af2-8856-393c75cc778f&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
*/
		if ( $CI->config->item('use_facebook') === true ) {
			echo "<iframe src=\"http://www.facebook.com/plugins/like.php?href=" . urlencode($url) . "&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=35\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:450px; height:35px;\" allowTransparency=\"true\"></iframe>";
		}
	}
?>
