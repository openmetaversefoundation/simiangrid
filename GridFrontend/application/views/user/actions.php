<div>

<table>
	<tr>
		<td>Change Password</td>
		<td><input type="submit" id="change_password" value="Change"></input></td>
	</tr>
	<?php if ( $user_id == $this->sg_auth->get_uuid() ): ?>
	<tr>
		<td>Change Language</td>
		<td><span id="user_language"><?php echo lang('sg_lang_' . $language); ?></span></td>
	</tr>
	<?php endif; ?>
	<?php if ( $this->sg_auth->is_admin() ): ?>
	<tr>
		<td><?php echo lang('sg_user_access_level'); ?></td>
		<td><span id="access_level"><?php echo pretty_access($user_data['AccessLevel']); ?></span></td>
	</tr><tr>
		<td>Raw</td>
		<td><input type="submit" id="raw_user_view" value="<?php echo lang('sg_raw'); ?>"></input></td>
	</tr><tr>
		<td><?php echo lang('sg_auth_ban_status'); ?></td>
		<td><span id="ban_status"><?php echo $banned; ?></span></td>
	</tr><tr>
		<td><?php echo lang('sg_auth_validation_status'); ?></td>
		<td><span id="validation_status"><?php echo $validation; ?></span></td>
	</tr>	
	<tr>
		<td><?php echo lang('sg_avatar_reset'); ?></td>
		<td><a href="{site_url}/user/actions/{user_id}/reset_avatar"><?php echo lang('sg_reset'); ?></td>
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

<div id="raw_popup">
<div id="raw_popup_contents"></div>
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
	
	function do_raw_user()
	{
		$("#raw_popup_contents").html("Loading...");
		var url = "<?php echo "$site_url/user/raw/" . $user_id; ?>";
		load_via_post(url, "#raw_popup_contents");
		$("#raw_popup").dialog('open');
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
		
		$("#raw_popup").dialog({autoOpen: false, title:"<?php echo lang('sg_raw'); ?>", modal: true, width: 500, position: 'top' });
		$("#raw_user_view").click(do_raw_user);
		
		$("#access_level").editable("<?php echo "$site_url/user/actions/" . $user_id . '/change_access_level'; ?>", {
			submit : 'OK',
			type : 'select',
			loadurl : "<?php echo "$site_url/user/actions/" . $user_id . '/load_access_level'; ?>"
		});

		$("#user_language").editable("<?php echo "$site_url/user/actions/" . $user_id . '/change_language'; ?>", {
			submit : 'OK',
			type : 'select',
			loadurl : "<?php echo "$site_url/user/actions/" . $user_id . '/load_language'; ?>"
		});
		
		$("#ban_status").editable("<?php echo "$site_url/user/actions/" . $user_id . '/change_ban_status'; ?>", {
			submit : 'OK',
			type : 'select',
			loadurl : "<?php echo "$site_url/user/actions/" . $user_id . '/load_ban_status'; ?>"
		});

		$("#validation_status").editable("<?php echo "$site_url/user/actions/" . $user_id . '/change_validation_status'; ?>", {
			submit : 'OK',
			type : 'select',
			loadurl : "<?php echo "$site_url/user/actions/" . $user_id . '/load_validation_status'; ?>"
		});
	});
</script>
