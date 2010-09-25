<div style="height:100%; width:100%;">
	<table class="display" id="search_results">
		<thead>
			<th>User Name</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script>
    $().ready(function() {
		$("#search_results").dataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "{site_url}/user/search",
			"bSort": false,
			"bLengthChange": false
		});
    });
</script>
