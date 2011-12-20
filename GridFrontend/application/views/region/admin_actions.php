<div id="search_popup">
    <table class="display" id="search_results">
        <thead>
            <tr>
                <th><?php echo lang('sg_region_name'); ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<table>
<?php if ( ! $hg ): ?>
    <tr>
        <th><?php echo lang('sg_region_owner'); ?></th>
        <td><?php echo render_user_link($owner_id); ?> <a href="#" id="search_button"><?php echo lang('sg_region_new_owner'); ?></a></td>
    </tr>
<?php else: ?>
    <tr>
        <th>Unlink Region</th>
        <td><a href="#" id="unlink_button">Go</a></td>
    </tr>
<?php endif; ?>
</table>
<script>

<?php if ( ! $hg ): ?>
$("#search_popup").dialog({
    autoOpen: false, 
    position: ['left', 'top']
});
$("#search_button").click(function(event) {
    $("#search_popup").dialog('open');
    return false;
});
$("#search_results").dataTable({
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": "{site_url}/user/search/change_region_owner",
    "bSort": false,
    "bLengthChange": false,
    "bJQueryUI": true,
    "bRetrieve": true
});

function change_region_owner(uuid) {
	var data = {
		user_id: uuid
	};
	$.post("{site_url}/region/change_region_owner/{scene_id}", data, change_region_success);
}

function change_region_success(data) {
	window.location = "{site_url}/region/view/{scene_id}/admin_actions";
}
<?php endif; ?>

</script>