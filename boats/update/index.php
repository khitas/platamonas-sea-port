<?php
include "../info.php";
$action = "update";

include "../../includes/settings.php";
include "../../includes/authentication.php";

?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="../../libs/jquery/jquery-2.1.0.js"></script>
    <script src="../../libs/jquery/jquery.ui.widget.js"></script>

    <link href="../../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../../libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="../../libs/bootbox/bootbox.js"></script>

    <link href="../../libs/switch/build/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
    <script src="../../libs/switch/build/js/bootstrap-switch.js"></script>

    <link href="../../libs/select/bootstrap-select.css" rel="stylesheet">
    <script src="../../libs/select/bootstrap-select.js"></script>

    <link href='../../libs/magnific/dist/magnific-popup.css' rel='stylesheet prefetch'>
    <script src='../../libs/magnific/dist/jquery.magnific-popup.min.js'></script>

    <link href="../../libs/capty/css/jquery.capty.css" rel="stylesheet">
    <script src="../../libs/capty/js/jquery.capty.min.js"></script>

    <script src="../../libs/datetimepicker/moment.js"></script>
    <link href="../../libs/datetimepicker/datetimepicker.css" rel="stylesheet">
    <script src="../../libs/datetimepicker/datetimepicker.js"></script>

    <script src="../../ajax/functions.js"></script>

    <link href="../../libs/uploader/css/style.css" rel="stylesheet">
    <link href="../../libs/uploader/css/jquery.fileupload.css" rel="stylesheet">

    <script src="../../libs/blueimp/load-image.min.js"></script>
    <script src="../../libs/blueimp/canvas-to-blob.min.js"></script>
    <script src="../../libs/uploader/js/jquery.iframe-transport.js"></script>
    <script src="../../libs/uploader/js/jquery.fileupload.js"></script>
    <script src="../../libs/uploader/js/jquery.fileupload-process.js"></script>
    <script src="../../libs/uploader/js/jquery.fileupload-image.js"></script>
    <script src="../../libs/uploader/js/jquery.fileupload-validate.js"></script>

    <script src="../../libs/spin/spin.js"></script>
    <script src="../../libs/spin/loader.js"></script>

</head>

<script>
$(document).ready(function(){

///= Objects ===========================================================================================================

    $('#cbIsFast').bootstrapSwitch('size', 'medium');
    $('#cbIsFast').bootstrapSwitch('onText', 'ΝΑΙ');
    $('#cbIsFast').bootstrapSwitch('offText', 'ΟΧΙ');
    $('#cbIsFast').bootstrapSwitch('animate', true);


    $('#spBoatPort').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spRegistryType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spAmyenType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spBoatMaterial').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spBoatColor').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spBoatType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spMovementType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spBoatStatus').selectpicker({ size: '3', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spBoatKind').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });
    $('#spOwnerStatus').selectpicker({ size: '3', width:'100%', noneSelectedText : 'επιλέξτε' });

    $('#spOwner').selectpicker({ size: 'auto', noneSelectedText : 'επιλέξτε' });
    $('#spEngine').selectpicker({ size: 'auto', noneSelectedText : 'επιλέξτε' });

    $('#dpRegistryDate').datetimepicker({ pickTime: false });
    $('#dpLicenseExpiredDate').datetimepicker({ pickTime: false });
    $('#dpGetDate').datetimepicker({ pickTime: false });


    $('.with-caption').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        mainClass: 'mfp-with-zoom mfp-img-mobile',
        image: {
            verticalFit: true,
            titleSrc: function(item) {
                return item.el.attr('title') + '<a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">Άνοιγμα</a>';
            }
        },
        zoom: {
            enabled: true
        }
    });

////= Load =============================================================================================================

    function loadData()
    {
        $.ajax({
            type: "POST",
            url: "../data/list.php",
            async: false,
            data: {id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    data.row = data.rows[0];

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text("<?php echo (in_array($User["permission_id"], array(1, 2)) ? "Ενημέρωση Σκάφους" : "Προβολή Σκάφους") ?>");

                    $("#txtName").val(data.row.name);
                    $('#spBoatKind').selectpicker('val', data.row.boat_kind_id);
                    $('#dpRegistryDate').data("DateTimePicker").setDate(data.row.registry_date_gr);
                    $('#spBoatPort').selectpicker('val', data.row.boat_port_id);
                    $('#spRegistryType').selectpicker('val', data.row.registry_type_id);
                    $("#txtRegistryNumber").val(data.row.registry_number);
                    $("#txtDDS").val(data.row.dds);
                    $('#spAmyenType').selectpicker('val', data.row.amyen_type_id);
                    $("#txtAmyenNumber").val(data.row.amyen_number);
                    $("#txtDSP").val(data.row.dsp);
                    $('#spBoatMaterial').selectpicker('val', data.row.boat_material_id);
                    $('#spBoatColor').selectpicker('val', data.row.boat_color_id);
                    $('#spBoatType').selectpicker('val', data.row.boat_type_id);
                    $('#spMovementType').selectpicker('val', data.row.movement_type_id);
                    $("#txtLength").val(data.row.length);
                    $("#txtWidth").val(data.row.width);
                    $("#txtHeight").val(data.row.height);
                    $("#txtBuilder").val(data.row.builder);
                    $('#cbIsFast').bootstrapSwitch('state', (data.row.is_fast == 1 ? true : false));
                    $('#spMovementType').selectpicker('val', data.row.movement_type_id);
                    $('#spBoatStatus').selectpicker('val', data.row.boat_status_id);
                    $('#dpLicenseExpiredDate').data("DateTimePicker").setDate(data.row.license_expired_date_gr);
                    $("#txtComments").val(data.row.comments);

                    <?php if (!in_array($User["permission_id"], array(1, 2))) { ?>
                    $("#txtName").prop('readonly', true);
                    $('#spBoatKind').prop('disabled',true);
                    $('#dpRegistryDate').data("DateTimePicker").disable();
                    $('#spBoatPort').prop('disabled',true);
                    $('#spRegistryType').prop('disabled',true);
                    $("#txtRegistryNumber").prop('readonly', true);
                    $("#txtDDS").prop('readonly', true);
                    $('#spAmyenType').prop('disabled',true);
                    $("#txtAmyenNumber").prop('readonly', true);
                    $("#txtDSP").prop('readonly', true);
                    $('#spBoatMaterial').prop('disabled',true);
                    $('#spBoatColor').prop('disabled',true);
                    $('#spBoatType').prop('disabled',true);
                    $('#spMovementType').prop('disabled',true);
                    $("#txtLength").prop('readonly', true);
                    $("#txtWidth").prop('readonly', true);
                    $("#txtHeight").prop('readonly', true);
                    $("#txtBuilder").prop('readonly', true);
                    $('#cbIsFast').bootstrapSwitch('readonly', true);
                    $('#spMovementType').prop('disabled',true);
                    $('#spBoatStatus').prop('disabled',true);
                    $('#dpLicenseExpiredDate').data("DateTimePicker").disable();
                    $("#txtComments").prop('readonly', true);
                    <?php } ?>

                    <?php if (!in_array($User["permission_id"], array(1, 2))) { ?>
                    $("#btnReloadBoatKinds").prop('disabled',true);
                    $("#btnReloadBoatPorts").prop('disabled',true);
                    $("#btnReloadRegistryTypes").prop('disabled',true);
                    $("#btnReloadAmyenTypes").prop('disabled',true);
                    $("#btnReloadBoatMaterials").prop('disabled',true);
                    $("#btnReloadBoatColors").prop('disabled',true);
                    $("#btnReloadBoatTypes").prop('disabled',true);
                    $("#btnReloadMovementTypes").prop('disabled',true);
                    $("#btnReloadBoatStatus").prop('disabled',true);
                    <?php } ?>

                    $("#events-aep-value").text(data.row.license_message);

                    if (data.row.license_active == 0) {
                        $("#events-aep").attr("class", "alert alert-danger");
                    } else if (data.row.license_active == 1) {
                        $("#events-aep").attr("class", "alert alert-success");
                    } else {
                        $("#events-aep").attr("class", "alert alert-info");
                    }

                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

////= Dictionaries =====================================================================================================

    function loadBoatKinds($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["boat-kinds"]["parent"].$packages["boat-kinds"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatKind').empty();

                    $('#spBoatKind').append( '<option data-divider="true"></option>');
                    $('#spBoatKind').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatKind').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatKind').append( '<option value="'+row.boat_kind_id+'">'+row.name+'</option>');

                        if (row.boat_kind_id == $selected)
                        {
                            $('#spBoatKind').selectpicker('val', row.boat_kind_id);
                        }
                    });

                    $('#spBoatKind').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadBoatPorts($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["boat-ports"]["parent"].$packages["boat-ports"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatPort').empty();

                    $('#spBoatPort').append( '<option data-divider="true"></option>');
                    $('#spBoatPort').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatPort').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatPort').append( '<option value="'+row.boat_port_id+'">'+row.name+'</option>');

                        if (row.boat_port_id == $selected)
                        {
                            $('#spBoatPort').selectpicker('val', row.boat_port_id);
                        }
                    });

                    $('#spBoatPort').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadRegistryTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["registry-types"]["parent"].$packages["registry-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spRegistryType').empty();

                    $('#spRegistryType').append( '<option data-divider="true"></option>');
                    $('#spRegistryType').append( '<option value="0">επιλέξτε</option>');
                    $('#spRegistryType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spRegistryType').append( '<option value="'+row.registry_type_id+'">'+row.name+'</option>');

                        if (row.registry_type_id == $selected)
                        {
                            $('#spRegistryType').selectpicker('val', row.registry_type_id);
                        }
                    });

                    $('#spRegistryType').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadAmyenTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["amyen-types"]["parent"].$packages["amyen-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spAmyenType').empty();

                    $('#spAmyenType').append( '<option data-divider="true"></option>');
                    $('#spAmyenType').append( '<option value="0">επιλέξτε</option>');
                    $('#spAmyenType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spAmyenType').append( '<option value="'+row.amyen_type_id+'">'+row.name+'</option>');

                        if (row.amyen_type_id == $selected)
                        {
                            $('#spAmyenType').selectpicker('val', row.amyen_type_id);
                        }
                    });

                    $('#spAmyenType').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadBoatMaterials($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["boat-materials"]["parent"].$packages["boat-materials"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatMaterial').empty();

                    $('#spBoatMaterial').append( '<option data-divider="true"></option>');
                    $('#spBoatMaterial').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatMaterial').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatMaterial').append( '<option value="'+row.boat_material_id+'">'+row.name+'</option>');

                        if (row.boat_material_id == $selected)
                        {
                            $('#spBoatMaterial').selectpicker('val', row.boat_material_id);
                        }
                    });

                    $('#spBoatMaterial').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadBoatColors($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["boat-colors"]["parent"].$packages["boat-colors"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatColor').empty();

                    $('#spBoatColor').append( '<option data-divider="true"></option>');
                    $('#spBoatColor').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatColor').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatColor').append( '<option value="'+row.boat_color_id+'">'+row.name+'</option>');

                        if (row.boat_color_id == $selected)
                        {
                            $('#spBoatColor').selectpicker('val', row.boat_color_id);
                        }
                    });

                    $('#spBoatColor').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadBoatTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["boat-types"]["parent"].$packages["boat-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatType').empty();

                    $('#spBoatType').append( '<option data-divider="true"></option>');
                    $('#spBoatType').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatType').append( '<option value="'+row.boat_type_id+'">'+row.name+'</option>');

                        if (row.boat_type_id == $selected)
                        {
                            $('#spBoatType').selectpicker('val', row.boat_type_id);
                        }
                    });

                    $('#spBoatType').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadMovementTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["movement-types"]["parent"].$packages["movement-types"]["path"] ?>data/list.php",
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spMovementType').empty();

                    $('#spMovementType').append( '<option data-divider="true"></option>');
                    $('#spMovementType').append( '<option value="0">επιλέξτε</option>');
                    $('#spMovementType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spMovementType').append( '<option value="'+row.movement_type_id+'">'+row.name+'</option>');

                        if (row.movement_type_id == $selected)
                        {
                            $('#spMovementType').selectpicker('val', row.movement_type_id);
                        }
                    });

                    $('#spMovementType').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadBoatStatus($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["boat-status"]["parent"].$packages["boat-status"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatStatus').empty();

                    $('#spBoatStatus').append( '<option data-divider="true"></option>');
                    $('#spBoatStatus').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatStatus').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatStatus').append( '<option value="'+row.boat_status_id+'">'+row.name+'</option>');

                        if (row.boat_status_id == $selected)
                        {
                            $('#spBoatStatus').selectpicker('val', row.boat_status_id);
                        }
                    });

                    $('#spBoatStatus').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }


    function loadOwnerStatus($selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["owner-status"]["parent"].$packages["owner-status"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spOwnerStatus').empty();

                    $('#spOwnerStatus').append( '<option data-divider="true"></option>');
                    $('#spOwnerStatus').append( '<option value="0">Κατάσταση</option>');
                    $('#spOwnerStatus').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spOwnerStatus').append( '<option value="'+row.owner_status_id+'">'+row.name+'</option>');

                        if (row.owner_status_id == $selected)
                        {
                            $('#spOwnerStatus').selectpicker('val', row.owner_status_id);
                        }
                    });

                    $('#spOwnerStatus').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

////= Link-Owners ======================================================================================================


    function initOwners()
    {
        $('#spOwner').empty();

        $('#spOwner').append( '<option data-divider="true"></option>');
        $('#spOwner').append( '<option value="0">Ιδιοκτήτης</option>');
        $('#spOwner').append( '<option data-divider="true"></option>');

        $('#spOwner').selectpicker('val', 0);

        $('#spOwner').selectpicker('refresh');
    }

    function loadOwners($selected)
    {
        $.ajax({
            type: "POST",
            url: "../data/owners-list.php",
            async: false,
            data: {skip_boat_id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spOwner').empty();

                    $('#spOwner').append( '<option data-divider="true"></option>');
                    $('#spOwner').append( '<option value="0">Ιδιοκτήτης</option>');
                    $('#spOwner').append( '<option data-divider="true"></option>');

                    $('#spOwner').selectpicker('val', 0);

                    $.each(data.rows, function(id, row)
                    {
                        $('#spOwner').append(
                            '<option value="'+row.owner_id+'" data-subtext="'+
                                'Α.Δ.Τ.:' + noNull(row.adt) + ', ' +
                                'Α.Φ.Μ.:' + noNull(row.afm) + '">' +
                                noNull(row.lastname)+' '+noNull(row.firstname) +
                                '</option>');

                        if (row.owner_id == $selected)
                        {
                            $('#spOwner').selectpicker('val', row.owner_id);
                        }
                    });

                    $('#spOwner').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

    function loadBoatOwners()
    {
        $.ajax({
            type: "POST",
            url: "../data/owners-list.php",
            async: false,
            data: {boat_id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                $("#tblOwners > tbody:last").children().remove();

                if (data.status == 200)
                {
                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnEditOwnerLink = '<td style="width: 80px"><button type="button" value="' + row.owner_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-edit"></button></td>';
                            var $btnEditOwnerLink = $(btnEditOwnerLink).click(function() {

                                $('#btnReloadOwners').attr('disabled', 'disabled');

                                $('#spOwner').empty();

                                $('#spOwner').append( '<option data-divider="true"></option>');

                                $('#spOwner').append(
                                    '<option value="'+row.owner_id+'" data-subtext="'+
                                        'Α.Δ.Τ. : ' + noNull(row.adt) + ', ' +
                                        'Α.Φ.Μ. : ' + noNull(row.afm) + '">' +
                                        noNull(row.lastname)+' '+noNull(row.firstname) +
                                        '</option>');

                                $('#spOwner').append( '<option data-divider="true"></option>');
                                $('#spOwner').selectpicker('val', row.owner_id);
                                $('#spOwner').selectpicker('refresh');

                                $('#dpGetDate').data("DateTimePicker").setDate(row.get_date_gr);

                                $('#txtPercent').val(row.percent);

                                $('#spOwnerStatus').selectpicker('val', row.owner_status_id);
                                $('#spOwnerStatus').selectpicker('refresh')

                                $('#btnAddOwner').text('Ενημέρωση');
                                $('#btnAddOwner').attr('data-value', 'update');

                                if ( ! $("#pnlOwners").is( ":visible" ) )
                                {
                                    $('#pnlOwners').collapse('toggle');
                                }
                            });
                            <?php } ?>

                            var btnEdit = '<td style="width: 80px"><button type="button" value="' + row.owner_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-<?php echo (in_array($User["permission_id"], array(1, 2)) ? "pencil" : "info-sign") ?>"></button></td>';
                            var $btnEdit = $(btnEdit).click(function() {
                                window.open("../../<?php echo $packages["owners"]["parent"].$packages["owners"]["path"] ?>update/?id=" + row.owner_id, '_blank');
                            });

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.owner_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-log-out"></button></td>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Αποσύνδεση του Ιδιοκτήτη "'+row.lastname+' '+row.firstname+'" από το Σκάφος;',
                                    title: "Αποσύνδεση",
                                    buttons: {
                                        success: {
                                            label: "Αποσύνδεση",
                                            className: "btn-primary",
                                            callback: function() {
                                                if ( $("#pnlOwners").is( ":visible" ) )
                                                {
                                                    $("#btnCancelOwner").click();
                                                }

                                                $.ajax({
                                                    type: "POST",
                                                    url: "../data/owners-delete-link.php",
                                                    async: false,
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', owner_id:row.owner_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            initOwners();
                                                            loadBoatOwners();
                                                            loadBoatHistories();

                                                            $("#events-result").attr("class", "alert alert-success");
                                                            $("#events-result").text(data.message);

                                                        }
                                                        else
                                                        {
                                                            $("#events-result").attr("class", "alert alert-danger");
                                                            $("#events-result").text(data.message);
                                                        }
                                                    },
                                                    error: function(){
                                                        alert("Σφάλμα");
                                                    }
                                                });
                                            }
                                        },
                                        cancel: {
                                            label: "Ακύρωση",
                                            className: "btn-default"
                                        }
                                    }
                                });

                            });
                            <?php } ?>

                            var $rowdata = $('<tr></tr>');
                            $rowdata.append('<td>' + (id + 1) + '</td>');
                            $rowdata.append('<td>' + row.owner_id + '</td>');
                            $rowdata.append('<td>' + noNull(row.lastname) + '</td>');
                            $rowdata.append('<td>' + noNull(row.firstname) + '</td>');
                            $rowdata.append('<td>' + noNull(row.adt) + '</td>');
                            $rowdata.append('<td>' + noNull(row.phone) + '</td>');
                            $rowdata.append('<td>' + noNull(row.owner_status) + '</td>');
                            $rowdata.append('<td>' + noNull(row.get_date_gr) + '</td>');
                            $rowdata.append('<td>' + noNull(row.percent) + '</td>');

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $rowdata.append($btnEditOwnerLink);
                            $rowdata.append($btnDelete);
                            <?php } ?>
                            $rowdata.append($btnEdit);

                            $rowdata.dblclick(function() {
                                window.open("../../<?php echo $packages["owners"]["parent"].$packages["owners"]["path"] ?>update/?id=" + row.owner_id, '_blank');
                            });

                            $('#tblOwners > tbody').append($rowdata);
                        });
                    }
                    else
                    {
                        var $rowdata = $('<tr></tr>');
                        $rowdata.append('<td colspan="12" align="center">'+data.message+'</td>');
                        $('#tblOwners > tbody').append($rowdata);
                    }

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

////= Link-Owners-Buttons ==============================================================================================

    $("#btnReloadBoatOwners").click(function(){
        loadBoatOwners();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadOwners").click(function(){
        loadOwners( $('#spOwner').selectpicker('val') );
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadOwnerStatus").click(function(){
        loadOwnerStatus( $('#spOwnerStatus').selectpicker('val') );
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCreateOwner").click(function(){
        window.open('../../<?php echo $packages["owners"]["parent"].$packages["owners"]["path"] ?>create/','_blank');
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnOpenOwners").click(function(){
        $('#btnReloadOwners').removeAttr("disabled");

        initOwners();

        $('#dpGetDate').data("DateTimePicker").setDate('');

        $('#txtPercent').val('');

        $('#spOwnerStatus').selectpicker('val', 0);
        $('#spOwnerStatus').selectpicker('refresh')

        $('#btnAddOwner').text('Σύνδεση');
        $('#btnAddOwner').attr('data-value', 'create');

        if ( ! $("#pnlOwners").is( ":visible" ) )
        {
            $('#pnlOwners').collapse('toggle');
        }
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnAddOwner").click(function(){
        if ($(this).attr("data-value") == "update")
        {
            $.ajax({
                type: "POST",
                url: "../data/owners-update-link.php",
                async: false,
                data: $('#frmOwners').serialize(),
                success: function(response){

                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initOwners();
                        loadBoatOwners();
                        loadBoatHistories();

                        $("#btnCancelOwner").click();

                        $("#events-result").attr("class", "alert alert-success");
                        $("#events-result").text(data.message);
                    }
                    else
                    {
                        $("#events-result").attr("class", "alert alert-danger");
                        $("#events-result").text(data.message);
                    }
                },
                error: function(){
                    alert("Σφάλμα");
                }
            });
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "../data/owners-create-link.php",
                async: false,
                data: $('#frmOwners').serialize(),
                success: function(response){
                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initOwners();
                        loadBoatOwners();
                        loadBoatHistories();

                        $("#btnCancelOwner").click();

                        $("#events-result").attr("class", "alert alert-success");
                        $("#events-result").text(data.message);
                    }
                    else
                    {
                        $("#events-result").attr("class", "alert alert-danger");
                        $("#events-result").text(data.message);
                    }
                },
                error: function(){
                    alert("Σφάλμα");
                }
            });
        }
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCancelOwner").click(function(){
        $('#btnReloadOwners').removeAttr("disabled");

        initOwners();

        $('#dpGetDate').data("DateTimePicker").setDate('');

        $('#txtPercent').val('');

        $('#btnAddOwner').text('Σύνδεση');
        $('#btnAddOwner').attr('data-value', 'create');

        $('#pnlOwners').collapse('toggle');

    });
    <?php } ?>

////= Link-Engines =====================================================================================================

    function initEngines()
    {
        $('#spEngine').empty();

        $('#spEngine').append( '<option data-divider="true"></option>');
        $('#spEngine').append( '<option value="0">Μηχανή</option>');
        $('#spEngine').append( '<option data-divider="true"></option>');

        $('#spEngine').selectpicker('val', 0);

        $('#spEngine').selectpicker('refresh');
    }

    function loadEngines($selected)
    {
        $.ajax({
            type: "POST",
            url: "../data/engines-list.php",
            async: false,
            data: {skip_boat_id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spEngine').empty();

                    $('#spEngine').append( '<option data-divider="true"></option>');
                    $('#spEngine').append( '<option value="0">Μηχανή</option>');
                    $('#spEngine').append( '<option data-divider="true"></option>');

                    $('#spEngine').selectpicker('val', 0);

                    $.each(data.rows, function(id, row)
                    {
                        $('#spEngine').append(
                            '<option value="'+row.engine_id+'" data-subtext="'+
                                row.engine_brand + ' : ' +
                                row.engine_type + ' : ' +
                                row.engine_kind + ' : ' +
                                row.power + '   ' +
                                row.engine_power_type +'">'+
                                row.serial_number+
                                '</option>');

                        if (row.engine_id == $selected)
                        {
                            $('#spEngine').selectpicker('val', row.engine_id);
                        }
                    });

                    $('#spEngine').selectpicker('refresh');

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }


    function loadBoatEngines()
    {
        $.ajax({
            type: "POST",
            url: "../data/engines-list.php",
            async: false,
            data: {boat_id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                $("#tblEngines > tbody:last").children().remove();

                if (data.status == 200)
                {
                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnEditEngineLink = '<td style="width: 80px"><button type="button" value="' + row.engine_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-edit"></button></td>';
                            var $btnEditEngineLink = $(btnEditEngineLink).click(function() {

                                $('#btnReloadEngines').attr('disabled', 'disabled');

                                $('#spEngine').empty();

                                $('#spEngine').append( '<option data-divider="true"></option>');

                                $('#spEngine').append(
                                    '<option value="'+row.engine_id+'" data-subtext="'+
                                        'Μάρκα : ' + noNull(row.engine_brand) + ', ' +
                                        'Τύπος : ' + noNull(row.engine_type) + ', ' +
                                        'Είδος : ' + noNull(row.engine_kind) + ', ' +
                                        'Δύναμη : ' + noNull(row.power) + ' ' + noNull(row.engine_power_type) + '">' +
                                        noNull(row.serial_number) +
                                        '</option>');

                                $('#spEngine').append( '<option data-divider="true"></option>');
                                $('#spEngine').selectpicker('val', row.engine_id);
                                $('#spEngine').selectpicker('refresh');

                                $('#btnAddEngine').text('Ενημέρωση');
                                $('#btnAddEngine').attr('data-value', 'update');

                                if ( ! $("#pnlEngines").is( ":visible" ) )
                                {
                                    $('#pnlEngines').collapse('toggle');
                                }
                            });
                            <?php } ?>

                            var btnEdit = '<td style="width: 80px"><button type="button" value="' + row.engine_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-<?php echo (in_array($User["permission_id"], array(1, 2)) ? "pencil" : "info-sign") ?>"></button></td>';
                            var $btnEdit = $(btnEdit).click(function() {
                                window.open("../../<?php echo $packages["engines"]["parent"].$packages["engines"]["path"] ?>update/?id=" + row.engine_id, '_blank');
                            });

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.engine_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-log-out"></button></td>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Αποσύνδεση της Μηχανής "'+row.serial_number+'" από το Σκάφος;',
                                    title: "Αποσύνδεση",
                                    buttons: {
                                        success: {
                                            label: "Αποσύνδεση",
                                            className: "btn-primary",
                                            callback: function() {
                                                if ( $("#pnlEngines").is( ":visible" ) )
                                                {
                                                    $("#btnCancelEngine").click();
                                                }

                                                $.ajax({
                                                    type: "POST",
                                                    url: "../data/engines-delete-link.php",
                                                    async: false,
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', engine_id:row.engine_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            initEngines();
                                                            loadBoatEngines();
                                                            loadBoatHistories();

                                                            $("#events-result").attr("class", "alert alert-success");
                                                            $("#events-result").text(data.message);
                                                        }
                                                        else
                                                        {
                                                            $("#events-result").attr("class", "alert alert-danger");
                                                            $("#events-result").text(data.message);
                                                        }
                                                    },
                                                    error: function(){
                                                        alert("Σφάλμα");
                                                    }
                                                });
                                            }
                                        },
                                        cancel: {
                                            label: "Ακύρωση",
                                            className: "btn-default"
                                        }
                                    }
                                });

                            });
                            <?php } ?>

                            var $rowdata = $('<tr></tr>');
                            $rowdata.append('<td>' + (id + 1) + '</td>');
                            $rowdata.append('<td>' + noNull(row.engine_id) + '</td>');
                            $rowdata.append('<td>' + noNull(row.serial_number) + '</td>');
                            $rowdata.append('<td>' + noNull(row.engine_type) + '</td>');
                            $rowdata.append('<td>' + noNull(row.engine_kind) + '</td>');
                            $rowdata.append('<td>' + noNull(row.power) + '</td>');
                            $rowdata.append('<td>' + noNull(row.engine_power_type) + '</td>');
                            $rowdata.append('<td>' + noNull(row.engine_brand) + '</td>');
                            $rowdata.append('<td>' + noNull(row.engine_status) + '</td>');

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $rowdata.append($btnEditEngineLink);
                            $rowdata.append($btnDelete);
                            <?php } ?>

                            $rowdata.append($btnEdit);

                            $rowdata.dblclick(function() {
                                window.open("../../<?php echo $packages["engines"]["parent"].$packages["engines"]["path"] ?>update/?id=" + row.engine_id, '_blank');
                            });

                            $('#tblEngines > tbody').append($rowdata);
                        });
                    }
                    else
                    {
                        var $rowdata = $('<tr></tr>');
                        $rowdata.append('<td colspan="12" align="center">'+data.message+'</td>');
                        $('#tblEngines > tbody').append($rowdata);
                    }

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

////= Link-Engines-Buttons =============================================================================================

    $("#btnReloadBoatEngines").click(function(){
        loadBoatEngines();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadEngines").click(function(){
        loadEngines( $('#spEngine').selectpicker('val') );
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCreateEngine").click(function(){
        window.open('../../<?php echo $packages["engines"]["parent"].$packages["engines"]["path"] ?>create/','_blank');
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnOpenEngines").click(function(){
        $('#btnReloadEngines').removeAttr("disabled");

        initEngines();

        $('#btnAddEngine').text('Σύνδεση');
        $('#btnAddEngine').attr('data-value', 'create');

        if ( ! $("#pnlEngines").is( ":visible" ) )
        {
            $('#pnlEngines').collapse('toggle');
        }
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnAddEngine").click(function(){
        if ($(this).attr("data-value") == "update")
        {
            $.ajax({
                type: "POST",
                url: "../data/engines-update-link.php",
                async: false,
                data: $('#frmEngines').serialize(),
                success: function(response){

                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initEngines();
                        loadBoatEngines();
                        loadBoatHistories();

                        $("#btnCancelEngine").click();

                        $("#events-result").attr("class", "alert alert-success");
                        $("#events-result").text(data.message);
                    }
                    else
                    {
                        $("#events-result").attr("class", "alert alert-danger");
                        $("#events-result").text(data.message);
                    }
                },
                error: function(){
                    alert("Σφάλμα");
                }
            });
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "../data/engines-create-link.php",
                async: false,
                data: $('#frmEngines').serialize(),
                success: function(response){
                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initEngines();
                        loadBoatEngines();
                        loadBoatHistories();

                        $("#btnCancelEngine").click();

                        $("#events-result").attr("class", "alert alert-success");
                        $("#events-result").text(data.message);
                    }
                    else
                    {
                        $("#events-result").attr("class", "alert alert-danger");
                        $("#events-result").text(data.message);
                    }
                },
                error: function(){
                    alert("Σφάλμα");
                }
            });
        }
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCancelEngine").click(function(){
        $('#btnReloadEngines').removeAttr("disabled");

        initEngines();

        $('#btnAddEngine').text('Σύνδεση');
        $('#btnAddEngine').attr('data-value', 'create');

        $('#pnlEngines').collapse('toggle');

    });
    <?php } ?>

////= Link-Images ======================================================================================================

    function loadBoatImages()
    {
        $.ajax({
            type: "POST",
            url: "../data/images-list.php",
            data: {id:'<?php echo $_REQUEST["id"] ?>'},
            async: false,
            success: function(response){
                var data = JSON.parse(response);

                $("#tblImages").empty();

                if (data.status == 200)
                {
                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<button type="button" value="' + row.boat_image_id + '" class="btn btn-default btn-block">Διαγραφή</button>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Διαγραφή της Φωτογραφίας;',
                                    title: "Διαγραφή",
                                    buttons: {
                                        success: {
                                            label: "Διαγραφή",
                                            className: "btn-primary",
                                            callback: function() {
                                                if ( $("#pnlImages").is( ":visible" ) )
                                                {
                                                    $("#btnCancelImage").click();
                                                }

                                                $.ajax({
                                                    type: "POST",
                                                    url: "../data/images-delete.php",
                                                    async: false,
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', boat_image_id:row.boat_image_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            loadBoatImages();
                                                            loadBoatHistories();

                                                            $("#events-result").attr("class", "alert alert-success");
                                                            $("#events-result").text(data.message);
                                                        }
                                                        else
                                                        {
                                                            $("#events-result").attr("class", "alert alert-danger");
                                                            $("#events-result").text(data.message);
                                                        }
                                                    },
                                                    error: function(){
                                                        alert("Σφάλμα");
                                                    }
                                                });
                                            }
                                        },
                                        cancel: {
                                            label: "Ακύρωση",
                                            className: "btn-default"
                                        }
                                    }
                                });

                            });
                            <?php } ?>

                            var $thumbnail = $(
                                '<div class="thumbnail">' +
                                '   <a href="'+row.name+'" data-source="'+row.name+'" class="with-caption image-link">' +
                                '       <img id="boatImage" class="fix" src="'+row.name+'" alt="Μεγέθυνση">' +
                                '   </a>' +
                                '</div>'
                            );

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $thumbnail.append($btnDelete);
                            <?php } ?>

                            var $rowdatacol = $('<div class="col-sm-12"></div>');
                            $rowdatacol.append($thumbnail);

                            var $rowdata = $('<div class="row"></div>');
                            $rowdata.append($rowdatacol);

                            $('#tblImages').append($rowdata);
                        });
                    }
                    else
                    {
                    }

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }

            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }

////= Link-Images-Buttons ==============================================================================================


    $("#btnReloadBoatImages").click(function(){
        loadBoatImages();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnOpenImages").click(function(){
        if ( ! $("#pnlImages").is( ":visible" ) )
        {
            $('#pnlImages').collapse('toggle');
        }
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCancelImage").click(function(){
        $("#files").empty();

        $('#progress .progress-bar').css('width', 0);

        $('#pnlImages').collapse('toggle');
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    var url = '../data/images-upload.php?id=<?php echo $_REQUEST["id"] ?>';
    var uploadButton = $('<button/>')
        .addClass('btn btn-primary btn-block')
        .prop('disabled', true)
        .text('Processing...')
        .on('click', function () {
            var $this = $(this),
                data = $this.data();
            $this
                .off('click')
                .text('Abort')
                .on('click', function () {
                    $this.remove();
                    data.abort();
                });
            data.submit().always(function () {
                $this.remove();
            });
        });

    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 120,
        previewMaxHeight: 120,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        $("#files").empty();
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>')
                .append($('<span/>').text(file.name));
            if (!index) {
                node
                    .append('<br>')
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('Ανέβασμα')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        console.log(data);
        if (data.result.status == 200)
        {
            setTimeout(function(){
                $("#btnCancelImage").click();

                loadBoatImages();
                loadBoatHistories();

                $("#events-result").attr("class", "alert alert-success");
                $("#events-result").text(data.result.message);
            }, 1000);
        }
        else
        {
            $("#events-result").attr("class", "alert alert-danger");
            $("#events-result").text(data.result.message);
        }
    }).on('fileuploadfail', function (e, data) {
        $("#events-result").attr("class", "alert alert-danger");
        $("#events-result").text(data.result.message);
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    <?php } ?>

////= Link-Histories ===================================================================================================

    function loadBoatHistories()
    {
        $.ajax({
            type: "POST",
            url: "../data/histories-list.php",
            async: false,
            data: {id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                $("#tblHistories > tbody:last").children().remove();

                if (data.status == 200)
                {
                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.boat_history_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-trash"></button></td>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Διαγραφή της ενέργειας του Ιστορικού;',
                                    title: "Διαγραφή",
                                    buttons: {
                                        success: {
                                            label: "Διαγραφή",
                                            className: "btn-primary",
                                            callback: function() {
                                                $.ajax({
                                                    type: "POST",
                                                    url: "../data/histories-delete.php",
                                                    async: false,
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', boat_history_id:row.boat_history_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            loadBoatHistories();

                                                            $("#events-result").attr("class", "alert alert-success");
                                                            $("#events-result").text(data.message);
                                                        }
                                                        else
                                                        {
                                                            $("#events-result").attr("class", "alert alert-danger");
                                                            $("#events-result").text(data.message);
                                                        }
                                                    },
                                                    error: function(){
                                                        alert("Σφάλμα");
                                                    }
                                                });
                                            }
                                        },
                                        cancel: {
                                            label: "Ακύρωση",
                                            className: "btn-default"
                                        }
                                    }
                                });

                            });
                            <?php } ?>

                            var $rowdata = $('<tr></tr>');
                            $rowdata.append('<td>' + (id + 1) + '</td>');
                            $rowdata.append('<td>' + noNull(row.boat_history_id) + '</td>');
                            $rowdata.append('<td>' + noNull(row.name) + '</td>');
                            $rowdata.append('<td>' + noNull(row.event_date) + '</td>');

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $rowdata.append($btnDelete);
                            <?php } ?>

                            $('#tblHistories > tbody').append($rowdata);
                        });
                    }
                    else
                    {
                        var $rowdata = $('<tr></tr>');
                        $rowdata.append('<td colspan="5" align="center">'+data.message+'</td>');
                        $('#tblHistories > tbody').append($rowdata);
                    }

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    }


////= Link-Histories-Buttons ===========================================================================================

    $("#btnReloadBoatHistories").click(function(){
        loadBoatHistories();
    });


////= Buttons ==========================================================================================================

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnSave").click(function(){
        $.ajax({
            type: "POST",
            url: "../data/update.php",
            async: false,
            data: {frm:$('#frm').serialize(), frmComments:$('#frmComments').serialize()},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    setTimeout(function(){
                        window.location.href = "../"
                    }, 1000);

                    $("#events-result").attr("class", "alert alert-success");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnSaveCreate").click(function(){
        $.ajax({
            type: "POST",
            url: "../data/update.php",
            async: false,
            data: {frm:$('#frm').serialize(), frmComments:$('#frmComments').serialize()},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    setTimeout(function(){
                        window.location.href = "../create/";
                    }, 1000);

                    $("#events-result").attr("class", "alert alert-success");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            },
            error: function(){
                alert("Σφάλμα");
            }
        });
    });
    <?php } ?>


    $("#btnReturn").click(function(){
        window.location.href = "../"
    });


    $("#btnAbout").click(function() {
        bootbox.dialog({
            message: "Πληροφορίες Εφαρμογής",
            title: "Σχετικά",
            buttons: {
                cancel: {
                    label: "Επιστροφή",
                    className: "btn-default"
                }
            }
        });
    });


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadBoatKinds").click(function(){
        loadBoatKinds( $('#spBoatKind').selectpicker('val') );
    });


    $("#btnReloadBoatPorts").click(function(){
        loadBoatPorts( $('#spBoatPort').selectpicker('val') );
    });


    $("#btnReloadRegistryTypes").click(function(){
        loadRegistryTypes( $('#spRegistryType').selectpicker('val') );
    });


    $("#btnReloadAmyenTypes").click(function(){
        loadAmyenTypes( $('#spAmyenType').selectpicker('val') );
    });


    $("#btnReloadBoatMaterials").click(function(){
        loadBoatMaterials( $('#spBoatMaterial').selectpicker('val') );
    });


    $("#btnReloadBoatColors").click(function(){
        loadBoatColors( $('#spBoatColor').selectpicker('val') );
    });


    $("#btnReloadBoatTypes").click(function(){
        loadBoatTypes( $('#spBoatType').selectpicker('val') );
    });


    $("#btnReloadMovementTypes").click(function(){
        loadMovementTypes( $('#spMovementType').selectpicker('val') );
    });


    $("#btnReloadBoatStatus").click(function(){
        loadBoatStatus( $('#spBoatStatus').selectpicker('val') );
    });
    <?php } ?>


    $("#btnPrint").click(function(){
        bootbox.prompt({
            message : "Εκτύπωση",
            title    : "Εκτύπωση",
            inputType : 'select',
            value : 'PrintInfos',
            inputOptions : [
                { text : 'Στοιχεία Σκάφους', value: 'PrintInfos', name: 'PrintInfos'},
                { text : 'Όλα τα στοιχεία', value: 'PrintAll', name: 'PrintAll'}
            ],
            buttons: {
                confirm: {
                    label: "Εκτύπωση",
                    className: "btn-primary"
                },
                cancel: {
                    label: "Ακύρωση",
                    className: "btn-default"
                }
            },
            callback : function(option) {
                switch (option)
                {
                    case "PrintInfos" :
                        var data = {id:<?php echo $_REQUEST["id"] ?>, print:option};

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('../print/infos.php?' + str,'_blank');
                        break;
                    case "PrintExtends" :
                        var data = {id:<?php echo $_REQUEST["id"] ?>, print:option};

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('../print/extends.php?' + str,'_blank');
                        break;
                }
            }
        });
    });


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnDelete").click(function(){
        bootbox.dialog({
            message: 'Διαγραφή του Σκάφους;',
            title: "Διαγραφή",
            buttons: {
                success: {
                    label: "Διαγραφή",
                    className: "btn-primary",
                    callback: function() {
                        $.ajax({
                            type: "POST",
                            url: "../data/delete.php",
                            data: {id:<?php echo $_REQUEST["id"] ?>},
                            success: function(response){
                                var data = JSON.parse(response);

                                if (data.status == 200)
                                {
                                    $("#events-result").attr("class", "alert alert-success");
                                    $("#events-result").text(data.message);

                                    setTimeout(function(){
                                        window.location.href = "../";
                                    }, 1500);
                                }
                                else
                                {
                                    $("#events-result").attr("class", "alert alert-danger");
                                    $("#events-result").text(data.message);
                                }
                            },
                            error: function(){
                                alert("Σφάλμα");
                            }
                        });
                    }
                },
                cancel: {
                    label: "Ακύρωση",
                    className: "btn-default"
                }
            }
        });
    });
    <?php } ?>

////Start Here =========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadOwnerStatus();
    loadBoatKinds();
    loadBoatPorts();
    loadRegistryTypes();
    loadAmyenTypes();
    loadBoatMaterials();
    loadBoatColors();
    loadBoatTypes();
    loadMovementTypes();
    loadBoatStatus();

    initOwners();
    loadBoatOwners();

    initEngines();
    loadBoatEngines();

    loadBoatImages();

    loadBoatHistories();

    loadData();

    setTimeout(function(){
        spinner.stop();
    }, 250);

});
</script>

<style>
    body {
        padding: 0px 20px 20px 20px;
    }
</style>

<body>

<?php include "../../toolbars/navbar-top.php" ?>

<div class="container">

    <div id="preview"></div>

    <div role="main" class="col-md-12">

        <div class="row"><br><br><br><br></div>

        <!--        <div class="row">-->
        <!--            <h2 class="page-header">--><?php //echo $packages[$package]["title"] ?><!--</h2>-->
        <!--        </div>-->

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="../../"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="../"><?php echo $packages[$package]["title"] ?></a></li>
                <li class="active"><?php echo (in_array($User["permission_id"], array(1, 2)) ? 'Ενημέρωση' :'Προβολή' ) ?></li>
            </ol>
        </div>

        <div class="row" style="display: block">
            <div class="alert alert-info" id="events-result">&nbsp;</div>
        </div>

        <div class="row">
        <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#infos" role="tab" data-toggle="tab">Στοιχεία Σκάφους</a></li>
                <li><a href="#owners" role="tab" data-toggle="tab">Ιδιοκτήτες</a></li>
                <li><a href="#engines" role="tab" data-toggle="tab">Μηχανές</a></li>
                <li><a href="#images" role="tab" data-toggle="tab">Φωτογραφίες</a></li>
                <li><a href="#history" role="tab" data-toggle="tab">Ιστορικό</a></li>
                <li><a href="#comments" role="tab" data-toggle="tab">Σχόλια</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active" id="infos">
                    <div class="row">

                        <div class="row"><br></div>
                        <div class="col-sm-12">
                            <div id="events-aep" class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <div id="events-aep-value">Α.Ε.Π.</div>
                            </div>
                        </div>

<!--                        <div class="row"><br></div>-->

                        <form class="form-horizontal col-sm-12" id="frm" role="form">
                            <fieldset>
                                <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Όνομα :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtName" id="txtName">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Είδος :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spBoatKind" name="spBoatKind" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadBoatKinds" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Αριθμός Εγγραφής :</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select id="spRegistryType" name="spRegistryType" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadRegistryTypes" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="txtRegistryNumber" id="txtRegistryNumber">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Λιμένας :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spBoatPort" name="spBoatPort" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadBoatPorts" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Ημ. Εγγραφής :</label>
                                    <div class="col-sm-4">
                                        <div class='input-group date' id='dpRegistryDate' data-date-format="DD/MM/YYYY">
                                            <input type='text' name="txtRegistryDate" id="txtRegistryDate" class="form-control">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time" ></span></span>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Δ.Δ.Σ.&nbsp;:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtDDS" id="txtDDS">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Α.Μ.Υ.Ε.Ν. :</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <select id="spAmyenType" name="spAmyenType" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadAmyenTypes" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="txtAmyenNumber" id="txtAmyenNumber">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Δ.Σ.Π.&nbsp;:</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtDSP" id="txtDSP">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Υλικό Κατασκευής :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spBoatMaterial" name="spBoatMaterial" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadBoatMaterials" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>

                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Χρώμα :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spBoatColor" name="spBoatColor" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadBoatColors" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Τύπος :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spBoatType" name="spBoatType" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadBoatTypes" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Κίνηση :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spMovementType" name="spMovementType" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadMovementTypes" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Μήκος :</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="txtLength" id="txtLength">
                                    </div>

                                    <label class="col-sm-1 control-label" style="white-space:nowrap;">Πλάτος&nbsp;:</label>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="txtWidth" id="txtWidth">
                                    </div>

                                    <label class="col-sm-1 control-label" style="white-space:nowrap;">Ύψος&nbsp;:</label>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="txtHeight" id="txtHeight">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Κατασκευαστής :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtBuilder" id="txtBuilder">
                                    </div>
                                    <label class="col-sm-2 control-label" tyle="white-space:nowrap;">Ταχύπλοο :</label>
                                    <div class="col-sm-4">
                                        <input id="cbIsFast" name="cbIsFast" type="checkbox">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Κατάσταση :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spBoatStatus" name="spBoatStatus" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnReloadBoatStatus" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Α.Ε.Π. :</label>
                                    <div class="col-sm-4">
                                        <div class='input-group date' id='dpLicenseExpiredDate' data-date-format="DD/MM/YYYY">
                                            <input type='text' name="txtLicenseExpiredDate" id="txtLicenseExpiredDate" class="form-control" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>



                <div class="tab-pane" id="owners">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="javascript:void(0)"><?php echo $packages["owners"]["title"] ?></a>
                                    </div>
                                    <div class="navbar-form navbar-right">
                                        <button id="btnReloadBoatOwners" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                        <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                                            <button id="btnOpenOwners" class="btn btn-default " type="button"><span class="glyphicon glyphicon-log-in"></span></button>
                                            <button id="btnCreateOwner" class="btn btn-default " type="button"><span class="glyphicon glyphicon-plus"></span></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                    <div class="row"s>
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div id="pnlOwners" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form class="form-horizontal col-sm-12" id="frmOwners" role="form">
                                            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">
                                            <fieldset>

                                                <div class="form-group">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <select id="spOwner" name="spOwner" data-width="auto" class="selectpicker" data-live-search="true"></select>
                                                            <span class="input-group-btn">
                                                                <button id="btnReloadOwners" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <select id="spOwnerStatus" name="spOwnerStatus" data-width="auto" class="selectpicker" data-live-search="false"></select>
                                                            <span class="input-group-btn">
                                                                <button id="btnReloadOwnerStatus" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-4">
                                                        <div class='input-group date' id='dpGetDate' data-date-format="DD/MM/YYYY">
                                                            <input type='text' name="txtGetDate" id="txtGetDate" class="form-control" placeholder="Ημ. Απόκτησης">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <input type="text" class="form-control" name="txtPercent" id="txtPercent" placeholder="Ποσοστό">
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-8">
                                                        <div class="btn-group btn-group-justified">
                                                            <div class="btn-group">
                                                                <button type="button" id="btnAddOwner" name="btnAddOwner" class="btn btn-primary btn-block">Σύνδεση</button>
                                                            </div>
                                                            <div class="btn-group">
                                                                <button type="button" id="btnCancelOwner" name="btnCancelOwner" class="btn btn-default btn-block">Ακύρωση</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>

                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover table-striped table-bordered" id="tblOwners" name="tblOwners">
                                <thead>
                                <tr style="height: 40px; vertical-align: middle" id="tdOrderColumns">
                                    <th style="width: 50px">#</th>
                                    <th style="cursor: pointer;<?php echo ($cols[2] == "false" ? ' display:none;' : '') ?>" data-value="id">Κωδικός <?php echo ($field == "id" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[3] == "false" ? ' display:none;' : '') ?>" data-value="name">Επώνυμο <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[4] == "false" ? ' display:none;' : '') ?>" data-value="name">Όνομα <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[5] == "false" ? ' display:none;' : '') ?>" data-value="name">Α.Δ.Τ. <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[6] == "false" ? ' display:none;' : '') ?>" data-value="name">Τηλέφωνο <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[7] == "false" ? ' display:none;' : '') ?>" data-value="name">Κατάσταση <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[8] == "false" ? ' display:none;' : '') ?>" data-value="name">Ημ Απόκτησης <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[9] == "false" ? ' display:none;' : '') ?>" data-value="name">Ποσοστό <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="width: 50px" colspan="3">Ενέργειες</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="engines">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="javascript:void(0)"><?php echo $packages["engines"]["title"] ?></a>
                                    </div>
                                    <div class="navbar-form navbar-right">
                                        <button id="btnReloadBoatEngines" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                        <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                                            <button id="btnOpenEngines" class="btn btn-default " type="button"><span class="glyphicon glyphicon-log-in"></span></button>
                                            <button id="btnCreateEngine" class="btn btn-default " type="button"><span class="glyphicon glyphicon-plus"></span></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div id="pnlEngines" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form class="form-horizontal col-sm-12" id="frmEngines" role="form">
                                            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">
                                            <fieldset>

                                                <div class="form-group">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <select id="spEngine" name="spEngine" data-width="auto" class="selectpicker" data-live-search="true"></select>
                                                            <span class="input-group-btn">
                                                                <button id="btnReloadEngines" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-8">
                                                        <div class="btn-group btn-group-justified">
                                                            <div class="btn-group">
                                                                <button type="button" id="btnAddEngine" name="btnAddEngine" class="btn btn-primary btn-block">Σύνδεση</button>
                                                            </div>
                                                            <div class="btn-group">
                                                                <button type="button" id="btnCancelEngine" name="btnCancelEngine" class="btn btn-default btn-block">Ακύρωση</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>

                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover table-striped table-bordered" id="tblEngines" name="tblEngines">
                                <thead>
                                <tr style="height: 40px; vertical-align: middle" id="tdOrderColumns">
                                    <th style="width: 50px">#</th>
                                    <th style="cursor: pointer;<?php echo ($cols[2] == "false" ? ' display:none;' : '') ?>" data-value="id">Κωδικός <?php echo ($field == "id" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[3] == "false" ? ' display:none;' : '') ?>" data-value="name">Σ. Αριθμός <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[4] == "false" ? ' display:none;' : '') ?>" data-value="name">Τύπος <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[5] == "false" ? ' display:none;' : '') ?>" data-value="name">Είδος <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[6] == "false" ? ' display:none;' : '') ?>" data-value="name">Δύναμη <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[7] == "false" ? ' display:none;' : '') ?>" data-value="name">Μονάδα <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[8] == "false" ? ' display:none;' : '') ?>" data-value="name">Μάρκα <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[9] == "false" ? ' display:none;' : '') ?>" data-value="name">Κατάσταση <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="width: 50px" colspan="3">Ενέργειες</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="images">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="javascript:void(0)">Φωτογραφίες</a>
                                    </div>
                                    <div class="navbar-form navbar-right">
                                        <button id="btnReloadBoatImages" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                        <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                                            <button id="btnOpenImages" class="btn btn-default " type="button"><span class="glyphicon glyphicon-plus"></span></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div id="pnlImages" class="panel-collapse collapse">
                                        <div class="panel-body">

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-8">
                                                        <span class="btn btn-default fileinput-button btn-block">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                            <span>επιλογή εικόνας</span>
                                                            <!-- The file input field used as target for the file upload widget -->
                                                            <input id="fileupload" type="file" name="files[]" multiple>
                                                        </span>

                                                        <!-- The global progress bar -->
                                                        <div id="progress" class="progress">
                                                            <div class="progress-bar progress-bar-success"></div>
                                                        </div>
                                                        <!-- The container for the uploaded files -->
                                                        <div id="files" class="files"></div>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-8">
                                                         <button type="button" id="btnCancelImage" name="btnCancelImage" class="btn btn-default btn-block">Ακύρωση</button>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <div id="tblImages"><!--List of Images--></div>

                </div>


                <div class="tab-pane" id="history">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="javascript:void(0)">Ιστορικό</a>
                                    </div>
                                    <div class="navbar-form navbar-right">
                                        <button id="btnReloadBoatHistories" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-hover table-striped table-bordered" id="tblHistories" name="tblHistories">
                                <thead>
                                <tr style="height: 40px; vertical-align: middle" id="tdOrderColumns">
                                    <th style="width: 50px">#</th>
                                    <th style="cursor: pointer;<?php echo ($cols[2] == "false" ? ' display:none;' : '') ?>" data-value="id">Κωδικός <?php echo ($field == "id" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[3] == "false" ? ' display:none;' : '') ?>" data-value="name">Περιγραφή <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[4] == "false" ? ' display:none;' : '') ?>" data-value="name">Ημερομηνία <?php echo ($field == "name" ? $order : '') ?></th>
                                    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                                    <th style="width: 50px" colspan="1">Ενέργειες</th>
                                    <?php } ?>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="comments">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form class="form-horizontal col-sm-12" id="frmComments" role="form">
                                        <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">
                                        <fieldset>
                                            <div class="form-group">
                                                <textarea id="txtComments" name="txtComments" class="form-control" rows="10" autofocus></textarea>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <div class="row"><br></div>

        <div class="row">
            <div class="btn-group btn-group-justified">
                <?php if (in_array($User["permission_id"], array(1, 2)) ? 'disabled' :'' ) { ?>
                <div class="btn-group">
                    <button type="button" id="btnSave" class="btn btn-primary btn-lg btn-block">Αποθήκευση</button>
                </div>
                <?php } ?>
                <?php if (in_array($User["permission_id"], array(1, 2)) ? 'disabled' :'' ) { ?>
                <div class="btn-group">
                    <button type="button" id="btnSaveCreate" class="btn btn-default btn-lg btn-block">Αποθήκευση & Νέα</button>
                </div>
                <?php } ?>
                <div class="btn-group">
                    <button type="button" id="btnPrint" class="btn btn-default btn-lg btn-block">Εκτύπωση</button>
                </div>
                <?php if (in_array($User["permission_id"], array(1, 2)) ? 'disabled' :'' ) { ?>
                <div class="btn-group">
                    <button type="button" id="btnDelete" class="btn btn-default btn-lg btn-block">Διαγραφή</button>
                </div>
                <?php } ?>
                <div class="btn-group">
                    <button type="button" id="btnReturn" class="btn btn-default btn-lg btn-block">Επιστροφή</button>
                </div>
            </div>
        </div>

        <div class="row"><br><br><br></div>

    </div>

</div>

<?php include "../../toolbars/navbar-bottom.php" ?>

</body>
</html>