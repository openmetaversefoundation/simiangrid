
<h2>About</h2>

<table><tr><td>
<p><a href="http://code.google.com/p/openmetaverse/wiki/SimianGrid">SimianGrid</a>
<?php echo lang('sg_about_blurb'); ?>
</p>
<ul>
<?php if ( $this->sg_auth->is_admin() ): ?>
<li><a title='menu_admin' href="{site_url}/admin"><?php echo lang('sg_admin'); ?></a></li>
<?php endif; ?>
</ul>
</td><td>
<?php
	generate_site_stream();
?>
</td></tr></table>