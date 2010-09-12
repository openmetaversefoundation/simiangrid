<h3><?php echo lang('sg_actions'); ?> </h3>

<div>

<ul>
	<li><input type="submit" id="change_password" value="Change Password"></input></li>
	<li><?php echo lang('sg_user_style'); ?> : <span id="style_selection"><?php echo $this->stylesheet; ?></span></li>
	<?php if ( $this->sg_auth->is_admin() ): ?>
	<li><?php echo lang('sg_user_access_level'); ?> : <span id="access_level"><?php echo pretty_access($this->user_data['AccessLevel']); ?></span></li>
	<li><input type="submit" id="raw_user_view" value="<?php echo lang('sg_raw'); ?>"></input></li>
	<li><?php echo lang('sg_auth_ban_status'); ?> : <span id="ban_status"><?php echo $this->banned; ?></span></li>
	<li><?php echo lang('sg_auth_validation_status'); ?> : <span id="validation_status"><?php echo $this->validation; ?></span></li>
	<?php endif; ?>
<ul>

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

<script src="static/javascript/jquery.jeditable.mini.js" type="text/javascript" ></script>
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
	            url : <?php echo "\"" . site_url('user/actions/' . $this->user_id . '/change_password') . "\""; ?>,
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
		var url = <?php echo "\"" . site_url('user/raw/' . $this->user_id) . "\""; ?>;
		real_load_via_post(url, "#raw_popup_contents");
		$("#raw_popup").dialog('open');
	}
	
	function post_style_change(value, settings)
	{
		if ( value != '' ) {
			$("link[media='screen']").attr("href", "static/styles/" + value + "/style.css");
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
		
		$("#raw_popup").dialog({autoOpen: false, title:"<?php echo lang('sg_raw'); ?>", modal: true, width: 500, position: 'top' });
		$("#raw_user_view").click(do_raw_user);
		
		$("#access_level").editable(<?php echo "\"" . site_url('user/actions/' . $this->user_id . '/change_access_level') . "\""; ?>, {
			submit : 'OK',
			tooltip : "<?php echo lang('sg_click_change'); ?>",
			type : 'select',
			data : <?php json_access_levels($this->user_data['AccessLevel']); ?>
		});
		$("#style_selection").editable(<?php echo "\"" . site_url('user/actions/' . $this->user_id . '/style_selection') . "\""; ?>, {
			submit : 'OK',
			tooltip : "<?php echo lang('sg_click_change'); ?>",
			type : 'select',
			data : <?php json_style_list(); ?>,
			callback : post_style_change
		});
		
		var ban_data = {
			'true' : "<?php echo lang('sg_auth_banned'); ?>",
			'false' : "<?php echo lang('sg_auth_not_banned'); ?>"
		};
		$("#ban_status").editable(<?php echo "\"" . site_url('user/actions/' . $this->user_id . '/change_ban_status') . "\""; ?>, {
			submit : 'OK',
			tooltip : "<?php echo lang('sg_click_change'); ?>",
			type : 'select',
			data : ban_data
		});

		var validation_data = {
			'true' : "<?php echo lang('sg_auth_validated'); ?>",
			'false' : "<?php echo lang('sg_auth_not_validated'); ?>"
		};
		$("#validation_status").editable(<?php echo "\"" . site_url('user/actions/' . $this->user_id . '/change_validation_status') . "\""; ?>, {
			submit : 'OK',
			tooltip : "<?php echo lang('sg_click_change'); ?>",
			type : 'select',
			data : validation_data
		});
	});
</script>
