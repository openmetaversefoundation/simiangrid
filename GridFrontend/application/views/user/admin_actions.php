<div>

<table>
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
<!--
Note that this will actually remove inventory and do other atrocious things to the avatar.
    <tr>
        <td><?php echo lang('sg_avatar_reset'); ?></td>
        <td><a href="{site_url}/user/actions/{user_id}/reset_avatar"><?php echo lang('sg_reset'); ?></td>
    </tr>
-->
</table>

<div>

<div id="raw_popup">
<div id="raw_popup_contents"></div>
</div>

<script src="{base_url}/static/javascript/jquery.jeditable.mini.js" type="text/javascript" ></script>
<script type="text/javascript">
    
    function do_raw_user()
    {
        $("#raw_popup_contents").html("Loading...");
        var url = "<?php echo "$site_url/user/raw/" . $user_id; ?>";
        load_via_post(url, "#raw_popup_contents");
        $("#raw_popup").dialog('open');
    }

    $().ready(function() {
        $("#raw_popup").dialog({autoOpen: false, title:"<?php echo lang('sg_raw'); ?>", modal: true, width: 500, position: 'top' });
        $("#raw_user_view").click(do_raw_user);
        
        $("#access_level").editable("<?php echo "$site_url/user/admin_actions/" . $user_id . '/change_access_level'; ?>", {
            submit : 'OK',
            type : 'select',
            loadurl : "<?php echo "$site_url/user/admin_actions/" . $user_id . '/load_access_level'; ?>"
        });
        
        $("#ban_status").editable("<?php echo "$site_url/user/admin_actions/" . $user_id . '/change_ban_status'; ?>", {
            submit : 'OK',
            type : 'select',
            loadurl : "<?php echo "$site_url/user/admin_actions/" . $user_id . '/load_ban_status'; ?>"
        });

        $("#validation_status").editable("<?php echo "$site_url/user/admin_actions/" . $user_id . '/change_validation_status'; ?>", {
            submit : 'OK',
            type : 'select',
            loadurl : "<?php echo "$site_url/user/admin_actions/" . $user_id . '/load_validation_status'; ?>"
        });
    });
</script>
