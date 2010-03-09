
<h2>Welcome to SimianGrid</h2>

<p>
<?php if ($this->dx_auth->is_logged_in()): ?>
Hello, <?php echo $this->dx_auth->get_name(); ?>
<?php endif; ?>
</p>
