</div>

</div>

<div id="footer">
<small>Website theme provided by <a href="http://www.openmetaverse.org/">Open Metaverse Foundation</a> and
<a href="http://go-psp-go.info" title="Psp Go">Odkazy</a>, &copy; 2009</small>

<?php render_style_selector(); ?>

<p id="footerlinks">
<!-- <a href="#">Link 1</a> --> 
<!-- <a href="#" class="lastlink">Link 2</a> -->
</p>
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