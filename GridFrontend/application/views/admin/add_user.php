<?php

$username = array(
    'name'  => 'username',
    'id'    => 'username',
    'size'  => 30,
    'value' =>  set_value('username'),
    'title' => 'register_username'
);

$password = array(
    'name'  => 'password',
    'id'    => 'password',
    'size'  => 30,
    'value' => set_value('password')
);

$confirm_password = array(
    'name'  => 'password_confirm',
    'id'    => 'password_confirm',
    'size'  => 30,
    'value' => set_value('password_confirm')
);

$email = array(
    'name'  => 'email',
    'id'    => 'email',
    'maxlength' => 80,
    'size'  => 30,
    'value' => set_value('email'),
    'title' => 'register_email'
);

?>

<fieldset>
<dl>
    <dt><?php echo form_label(lang('sg_name'), $username['id']);?></dt>
    <dd>
        <?php echo form_input($username)?>
        <div id='username_error'></div>
    </dd>
    
    <dt><?php echo form_label(lang('sg_auth_appearance'), 'avatar_type');?></dt>
    <dd>
        <?php
            $options = array();
            $options['DefaultAvatar'] = 'Default Avatar';
            foreach ($this->config->item('extra_avatar_types') as $lbl => $val)
                $options[$val] = $lbl;
        ?>
        <?php echo form_dropdown('avatar_type', $options, 'DefaultAvatar', 'id="avatar_type"')?>
        <div id='avatar_type_error'></div>
    </dd>

    <dt><?php echo form_label(lang('sg_password'), $password['id']);?></dt>
    <dd>
        <?php echo form_password($password)?>
        <div id='password_error'></div>
    </dd>

    <dt><?php echo form_label(lang('sg_password_confirm'), $confirm_password['id']);?></dt>
    <dd>
        <?php echo form_password($confirm_password);?>
    </dd>

    <dt><?php echo form_label(lang('sg_email'), $email['id']); ?></dt>
    <dd>
        <?php echo form_input($email); ?>
        <div id='email_error'></div>
    </dd>

    <dt></dt>
    <div id="submit_button">
    <dd><?php echo form_submit('create_user', lang('sg_admin_add_user'), 'class="button"');?></dd>
    </div>
    <div id="confirmation_link">
    </div>
</dl>
</fieldset>

<div id="create_error">
</div>

<script type='text/javascript'>

    function data_from_form()
    {
        $("#username_error").html('');
        $("#password_error").html('');
        $("#avatar_type_error").html('');
        $("#email_error").html('');
        var user = Object;
        user.username = $("input[name='username']").val();
        user.password = $("input[name='password']").val();
        user.password_confirm = $("input[name='password_confirm']").val();
        user.email = $("input[name='email']").val();
        user.avatar_type = $("input[name='avatar_type']").val();
        return user;
    }
    
    function form_from_data(data)
    {
        if ( data.username_error != undefined ) {
            $("#username_error").html(data.username_error);
        }
        if ( data.avatar_type_error != undefined ) {
            $("#avatar_type_error").html(data.avatar_type_error);
        }
        if ( data.password_error != undefined ) {
            $("#password_error").html(data.password_error);
        }
        if ( data.email_error != undefined ) {
            $("#email_error").html(data.email_error);
        }
        $("input[name='username']").val(data.username);
        $("input[name='password']").val(data.password);
        $("input[name='password_confirm']").val(data.password_confirm);
        $("input[name='avatar_type']").val(data.avatar_type);
        $("input[name='email']").val(data.email);
    }
    
    function form_clear()
    {
        $("input[name='username']").val('');
        $("input[name='password']").val('');
        $("input[name='password_confirm']").val('');
        $("input[name='avatar_type']").val('');
        $("input[name='email']").val('');
        $("#username_error").html('');
        $("#password_error").html('');
        $("#avatar_type_error").html('');
        $("#email_error").html('');
        
    }
    
    function handle_user_success(data)
    {
        $("#submit_button").hide();
        $("#confirmation_link").html(
            '<a href="{site_url}/admin/add_user">Add Another</a><br/>' +
            'Go to user <a href="{site_url}/user/view/' + data.user_id + '">' + data.username + '</a>'
        );
        $("#confirmation_link").show();
    }

    function create_user_success(data, textStatus, XMLHttpRequest)
    {
        if ( data.success ) {
            handle_user_success(data);
        } else {
            form_from_data(data);
        }
    }

    function do_create_user(event) {
        var user_data = data_from_form();
        var ajax_request = {
            url : "{site_url}/admin/add_user/form",
            dataType : 'json',
            type : 'POST',
            success : create_user_success,
            error : handle_load_error("#create_user_status"),
            data : user_data
        };
        $.ajax(ajax_request);
    }

    $("input[name='create_user']").click(do_create_user);
    $("#create_error").dialog({ autoOpen: false, modal: true });
</script>