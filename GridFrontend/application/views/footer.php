</div>

<div id="footer" class="ui-widget ui-corner-bottom">
<div style="float: left;">
SimianGrid is provided by the <a href="http://www.openmetaverse.org/">Open Metaverse Foundation</a>
</div>
<div style="float: right;">
<?php render_style_selector(); ?>
</div>
</div>

</div>

</div>

<script type="text/javascript">

	$().ready(function() {
<?php if ( $this->config->item('enable_tooltips') ): ?>
		scan_tooltips("<?php echo site_url('about/tooltip/'); ?>");
<?php endif; ?>
	});
</script>

</body>
</html>