<table><tr><td>
<p><a href="http://code.google.com/p/openmetaverse/wiki/SimianGrid">SimianGrid</a>
<?php echo lang('sg_about_blurb'); ?>
</p>
<?php
if ($logged_in)
{
    echo '<p>';
    echo anchor("$site_url/launch", sprintf(lang('sg_about_launch'), $user_data['Name']));
    echo '</p>';
}
?>
</td><td>
<?php
    echo generate_site_stream();
?>
</td></tr></table>