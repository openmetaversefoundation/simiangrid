<?php

$openid_identifier = array(
    'name'  => 'openid_identifier',
    'id'    => 'openid_identifier',
    'size'  => 40
);

?>

<h3><?php echo lang('sg_user_identities'); ?></h3>

<?php echo $this->table->generate(); ?>

<?php if ( $uuid == $this->sg_auth->get_uuid() ):?>

<?php if ( ! $has_facebook ): ?>
<?php generate_facebook_auth("$site_url/user/identities/" . $uuid . '/add_facebook'); ?>
<?php endif; ?>

<?php if ( ! $has_openid ): ?>
<?php openid_identifier_render("Add OpenID", "$site_url/user/identities/" . $uuid . '/add_openid', "Add"); ?>
<?php endif; ?>

<?php endif; ?>
