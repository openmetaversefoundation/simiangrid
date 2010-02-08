<?php if ($this->dx_auth->is_logged_in()): ?>
Welcome, <?php echo $this->dx_auth->get_name(); ?>
<?php endif; ?>
