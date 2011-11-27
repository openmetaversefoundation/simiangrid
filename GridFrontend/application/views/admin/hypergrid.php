<?php

$hg_uri = array(
    'name'  => 'hg_uri',
    'id'    => 'hg_uri',
    'size'  => 40,
    'max_size' => 1024,
    'value' =>  set_value('hg_uri')
);

$region_name = array(
    'name'  => 'region_name',
    'id'    => 'region_name',
    'size'  => 40,
    'max_size' => 255,
    'value' => set_value('region_name')
);

$x = array(
    'name'  => 'x',
    'id'    => 'x',
    'size'  => 5,
    'max_size' => 5,
    'value' => set_value('x')
);

$y = array(
    'name'  => 'y',
    'id'    => 'y',
    'size'  => 5,
    'max_size' => 5,
    'value' => set_value('y')
);

?>
<div id="link_region_popup">
    <fieldset>
    <dl>
        <dt><?php echo form_label(lang('sg_hg_uri'), $hg_uri['id']);?></dt>
        <dd>
            <?php echo form_input($hg_uri)?>
            <div id='hg_uri_error'></div>
        </dd>

        <dt><?php echo form_label(lang('sg_region_name'), $region_name['id']);?></dt>
        <dd>
            <?php echo form_input($region_name)?>
            <div id='region_name_error'></div>
        </dd>

        <dt><?php echo form_label('X', $x['id']);?></dt>
        <dd>
            <?php echo form_input($x);?>
            <div id='y_error'></div>
        </dd>

        <dt><?php echo form_label('Y', $y['id']); ?></dt>
        <dd>
            <?php echo form_input($y); ?>
            <div id='y_error'></div>
        </dd>

        <dt></dt>
        <div id="submit_button">
        <dd><?php echo form_submit('link_region', lang('sg_hg_link_region'), 'class="button"');?></dd>
        </div>
        <div id="confirmation_link">
        </div>
    </dl>
    </fieldset>

    <div id="link_region_error">
    </div>
</div>
<div style="height:100%; width:100%;">
    <ul>
        <li><a id="refresh_list_button">Refresh</a></li>
        <li><a id="link_region_button">Link Region</a></li>
    </ul>
    <table class="display" id="hypergrid_list">
        <thead>
            <tr>
                <th><?php echo lang('sg_hypergrid'); ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>

    var hg_list;
    var hg_link_popup;
    function data_from_form()
    {
        $("#hg_uri_error").html('');
        $("#region_name_error").html('');
        $("#x_error").html('');
        $("#y_error").html('');
        var hg = Object;
        hg.hg_uri = $("input[name='hg_uri']").val();
        hg.region_name = $("input[name='region_name']").val();
        hg.x = $("input[name='x']").val();
        hg.y = $("input[name='y']").val();
        return hg;
    }

    function form_from_data(data)
    {
        if ( data.hg_uri != undefined ) {
            $("#hg_uri_error").html(data.hg_uri_error);
        }
        if ( data.region_name != undefined ) {
            $("#region_name_error").html(data.region_name_error);
        }
        if ( data.x_error != undefined ) {
            $("#x_error").html(data.x_error);
        }
        if ( data.y_error != undefined ) {
            $("#y_error").html(data.y_error);
        }
        $("input[name='hg_uri']").val(data.hg_uri);
        $("input[name='region_name']").val(data.region_name);
        $("input[name='x']").val(data.x);
        $("input[name='y']").val(data.y);
    }

    function form_clear()
    {
        $("input[name='hg_uri']").val('');
        $("input[name='region_name']").val('');
        $("input[name='x']").val('');
        $("input[name='y']").val('');
        $("#hg_uri_error").html('');
        $("#region_name_error").html('');
        $("#x_error").html('');
        $("#y_error").html('');
    
    }

    function handle_link_region_success(data)
    {
        hg_list.fnDraw();
        hg_link_popup.close();
        form_clear();
    }

    function link_region_success(data, textStatus, XMLHttpRequest)
    {
        if ( data.success ) {
            handle_link_region_success(data);
        } else {
            form_from_data(data);
        }
    }

    function do_link_region(event) {
        var data = data_from_form();
        var ajax_request = {
            url : "{site_url}/admin/hypergrid/form",
            dataType : 'json',
            type : 'POST',
            success : link_region_success,
            error : handle_load_error("#link_region_success"),
            data : data
        };
        $.ajax(ajax_request);
    }
    $().ready(function() {
        hg_list = $("#hypergrid_list").dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "{site_url}/admin/hypergrid/list",
            "bSort": false,
            "bLengthChange": false,
            "bJQueryUI": true,
            "bFilter":false
        });
        hg_link_popup = $("#link_region_popup").dialog({ 
            autoOpen: false, 
            position: ['center', 'center'],
            width: 500,
    //        title: "<?php echo lang('sg_hypergrid_link_region'); ?>" 
            title: "Link Region"
        });
        $("#link_region_button").click(function(event) {
            $("#link_region_popup").dialog('open');
            return false;
        });
        $("#refresh_list_button").click(function(event) {
            hg_list.fnDraw();
            return false;
        });
        $("input[name='link_region']").click(do_link_region);
        $("#link_region_error").dialog({ autoOpen: false, modal: true });
    });

</script>
