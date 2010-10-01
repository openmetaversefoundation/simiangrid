<div>

<table>
	<tr>
		<td>Change Password</td>
		<td><input type="submit" id="change_password" value="Change"></input></td>
	</tr>
	<?php if ( $user_id == $my_uuid || $this->sg_auth->is_admin() ): ?>
	<tr>
		<td>Search Visibility</td>
		<td><span id="search_visibility"><?php echo $search_visibility; ?></span></td>
	</tr>
	<tr>
		<td>Change Language</td>
		<td><span id="user_language"><?php echo lang('sg_lang_' . $language); ?></span></td>
	</tr>
	<?php endif; ?>
</table>

<div>

<div id="change_password_popup">
	<form>
		<?php echo lang('sg_password'); ?> <input type="password" size="20" id="password"></input>
		<?php echo lang('sg_password_confirm'); ?> <input type="password" size="20" id="password_confirm"></input>
	</form>
	<input type="submit" id="change_password_button" value="Change"></input>
</div>

<div id="password_status_popup">
	<span id="password_status"></span>
</div>

<script src="{base_url}/static/javascript/jquery.jeditable.mini.js" type="text/javascript" ></script>
<script type="text/javascript">
	var password_popup_visible = false;

	function password_popup(message)
	{
		$("#password_status").html(message);
		$("#password_status_popup").dialog('open');
	}

	function change_password_callback(data, textStatus, XMLHttpRequest)
	{
		if ( data && data.success ) {
			password_popup("<?php echo lang('sg_password_success'); ?>");
		} else {
			password_popup("<?php echo lang('sg_password_error'); ?>");
		}
		$("#change_password_popup").dialog('close');
	}

	function do_change_password()
	{
		var password = $("#password").val();
		var password_confirm = $("#password_confirm").val();
		if ( password_confirm == password ) {
			var data = {
				password : password
			};
			var request = {
	            url : "<?php echo "$site_url/user/actions/" . $user_id . '/change_password'; ?>",
	            dataType : 'json',
	            type : 'POST',
	            success : change_password_callback,
	            error : change_password_callback,
				data : data
	        };
	        $.ajax(request);
		} else {
			password_popup("<?php echo lang('sg_password_match'); ?>");
		}
	}

	$().ready(function() {
		$("#change_password_popup").dialog({ autoOpen: false, title: "<?php echo lang('sg_password_change'); ?>", modal: true });
		$("#change_password").click(function() {
			if ( ! password_popup_visible ) {
				$("#password").val('');
				$("#password_confirm").val('');
				$("#change_password_popup").dialog('open');
			}
		});
		$("#change_password_button").click(do_change_password);
		$("#password_status_popup").dialog({ autoOpen: false, modal: true });

		$("#user_language").editable("<?php echo "$site_url/user/actions/" . $user_id . '/change_language'; ?>", {
			submit : 'OK',
			type : 'select',
			loadurl : "<?php echo "$site_url/user/actions/" . $user_id . '/load_language'; ?>"
		});
		$("#search_visibility").editable("<?php echo "$site_url/user/actions/" . $user_id . '/change_search_flag'; ?>", {
			submit : 'OK',
			type : 'select',
			loadurl : "<?php echo "$site_url/user/actions/" . $user_id . '/load_search_flag'; ?>"
		});
	});

</script>
