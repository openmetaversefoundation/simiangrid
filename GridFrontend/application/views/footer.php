
<?php if ( ! isset($this->simple_page) || ! $this->simple_page ):?>
</div>

</div>

<div id="footer">
<small>Website theme provided by <a href="http://www.openmetaverse.org/">Open Metaverse Foundation</a> and
<a href="http://go-psp-go.info" title="Psp Go">Odkazy</a>, &copy; 2009</small>

<p id="footerlinks">
<!-- <a href="#">Link 1</a> --> 
<!-- <a href="#" class="lastlink">Link 2</a> -->
</p>
</div>

</div>

<script type="text/javascript">
$().ready(function() {
	var url_base = "<?php echo site_url('about/tooltip/'); ?>";
	$("[title!='']").each(function(i) {
		var rel = $(this).attr('title');
		$(this).qtip({
			content : {
				url: url_base + "/" + rel,
				text: '...'
			},
		    show: 'mouseover',
		    hide: 'mouseout'
		});
		$(this).attr('title', '');
	});
});
</script>

</body>
</html>

<?php endif; ?>