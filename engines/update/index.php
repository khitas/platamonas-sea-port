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

    $('#spEngineType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Τύπος' });
    $('#spEngineKind').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Είδος' });
    $('#spEnginePowerType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Μονάδα' });
    $('#spEngineBrand').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Μάρκα' });
    $('#spEngineStatus').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Κατάσταση' });

    $('#spBoat').selectpicker({ size: 'auto', noneSelectedText : 'επιλέξτε' });
    $('#spOwner').selectpicker({ size: 'auto', noneSelectedText : 'επιλέξτε' });

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

                    $("#txtSerialNumber").val(data.row.serial_number)
                    $('#spEngineKind').selectpicker('val', data.row.engine_kind_id);
                    $('#spEngineType').selectpicker('val', data.row.engine_type_id);
                    $("#txtPower").val(data.row.power)
                    $('#spEnginePowerType').selectpicker('val', data.row.engine_power_type_id);
                    $('#spEngineBrand').selectpicker('val', data.row.engine_brand_id);
                    $('#spEngineStatus').selectpicker('val', data.row.engine_status_id);
                    $("#txtComments").val(data.row.comments);

                    <?php if (!in_array($User["permission_id"], array(1, 2))) { ?>
                    $("#txtSerialNumber").prop('readonly', true);
                    $('#spEngineKind').prop('disabled',true);
                    $('#spEngineType').prop('disabled',true);
                    $("#txtPower").prop('readonly', true);
                    $('#spEnginePowerType').prop('disabled',true);
                    $('#spEngineBrand').prop('disabled',true);
                    $('#spEngineStatus').prop('disabled',true);
                    $("#txtComments").prop('readonly', true);
                    <?php } ?>

                    <?php if (!in_array($User["permission_id"], array(1, 2))) { ?>
                    $("#btnEngineType").prop('disabled',true);
                    $("#btnEngineKind").prop('disabled',true);
                    $("#btnEnginePowerType").prop('disabled',true);
                    $("#btnEngineBrand").prop('disabled',true);
                    $("#btnEngineStatus").prop('disabled',true);
                    <?php } ?>

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text("<?php echo (in_array($User["permission_id"], array(1, 2)) ? "Ενημέρωση Μηχανής" : "Προβολή Μηχανής") ?>");
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

    function loadEngineTypes(selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["engine-types"]["parent"].$packages["engine-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                selected = (selected ? selected : 0);

                if (data.status == 200)
                {
                    $('#spEngineType').empty();

                    $('#spEngineType').append( '<option data-divider="true"></option>');
                    $('#spEngineType').append( '<option value="0">επιλέξτε</option>');
                    $('#spEngineType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spEngineType').append( '<option value="'+row.engine_type_id+'">'+row.name+'</option>');

                        if (row.engine_type_id == selected)
                        {
                            $('#spEngineType').selectpicker('val', row.engine_type_id);
                        }
                    });

                    $('#spEngineType').selectpicker('refresh');

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


    function loadEngineKinds(selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["engine-kinds"]["parent"].$packages["engine-kinds"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                selected = (selected ? selected : 0);

                if (data.status == 200)
                {
                    $('#spEngineKind').empty();

                    $('#spEngineKind').append( '<option data-divider="true"></option>');
                    $('#spEngineKind').append( '<option value="0">επιλέξτε</option>');
                    $('#spEngineKind').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spEngineKind').append( '<option value="'+row.engine_kind_id+'">'+row.name+'</option>');

                        if (row.engine_kind_id == selected)
                        {
                            $('#spEngineKind').selectpicker('val', row.engine_kind_id);
                        }
                    });

                    $('#spEngineKind').selectpicker('refresh');

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


    function loadEnginePowerTypes(selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["engine-power-types"]["parent"].$packages["engine-power-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                selected = (selected ? selected : 0);

                if (data.status == 200)
                {
                    $('#spEnginePowerType').empty();

                    $('#spEnginePowerType').append( '<option data-divider="true"></option>');
                    $('#spEnginePowerType').append( '<option value="0">επιλέξτε</option>');
                    $('#spEnginePowerType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spEnginePowerType').append( '<option value="'+row.engine_power_type_id+'">'+row.name+'</option>');

                        if (row.engine_power_type_id == selected)
                        {
                            $('#spEnginePowerType').selectpicker('val', row.engine_power_type_id);
                        }
                    });

                    $('#spEnginePowerType').selectpicker('refresh');

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


    function loadEngineBrands(selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["engine-brands"]["parent"].$packages["engine-brands"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                selected = (selected ? selected : 0);

                if (data.status == 200)
                {
                    $('#spEngineBrand').empty();

                    $('#spEngineBrand').append( '<option data-divider="true"></option>');
                    $('#spEngineBrand').append( '<option value="0">επιλέξτε</option>');
                    $('#spEngineBrand').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spEngineBrand').append( '<option value="'+row.engine_brand_id+'">'+row.name+'</option>');

                        if (row.engine_brand_id == selected)
                        {
                            $('#spEngineBrand').selectpicker('val', row.engine_brand_id);
                        }
                    });

                    $('#spEngineBrand').selectpicker('refresh');

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


    function loadEngineStatus(selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["engine-status"]["parent"].$packages["engine-status"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                selected = (selected ? selected : 0);

                if (data.status == 200)
                {
                    $('#spEngineStatus').empty();

                    $('#spEngineStatus').append( '<option data-divider="true"></option>');
                    $('#spEngineStatus').append( '<option value="0">επιλέξτε</option>');
                    $('#spEngineStatus').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spEngineStatus').append( '<option value="'+row.engine_status_id+'">'+row.name+'</option>');

                        if (row.engine_status_id == selected)
                        {
                            $('#spEngineStatus').selectpicker('val', row.engine_status_id);
                        }
                    });

                    $('#spEngineStatus').selectpicker('refresh');

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

////= Link-Boats =======================================================================================================

    function initBoats()
    {
        $('#spBoat').empty();

        $('#spBoat').append( '<option data-divider="true"></option>');
        $('#spBoat').append( '<option value="0">Σκάφος</option>');
        $('#spBoat').append( '<option data-divider="true"></option>');

        $('#spBoat').selectpicker('val', 0);

        $('#spBoat').selectpicker('refresh');
    }

    function loadBoats(selected)
    {
        $.ajax({
            type: "POST",
            url: "../data/boats-list.php",
            async: false,
            data: {skip_engine_id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoat').empty();

                    $('#spBoat').append( '<option data-divider="true"></option>');
                    $('#spBoat').append( '<option value="0">Σκάφος</option>');
                    $('#spBoat').append( '<option data-divider="true"></option>');

                    $('#spBoat').selectpicker('val', 0);

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoat').append(
                            '<option value="'+row.boat_id+'" data-subtext="'+
//                                'Α.Δ.Τ.:' + noNull(row.adt) + ', ' +
//                                'Α.Φ.Μ.:' + noNull(row.afm) + '">' +
                                '">' +
                                noNull(row.name) +
                                '</option>');

                        if (row.boat_id == selected)
                        {
                            $('#spBoat').selectpicker('val', row.boat_id);
                        }
                    });

                    $('#spBoat').selectpicker('refresh');

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


    function loadEngineBoats()
    {
        $.ajax({
            type: "POST",
            url: "../data/boats-list.php",
            async: false,
            data: {engine_id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                $("#tblBoats > tbody:last").children().remove();

                if (data.status == 200)
                {
                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnEditBoatLink = '<td style="width: 80px"><button type="button" value="' + row.boat_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-edit"></button></td>';
                            var $btnEditBoatLink = $(btnEditBoatLink).click(function() {

                                $('#btnReloadBoats').attr('disabled', 'disabled');

                                $('#spBoat').empty();

                                $('#spBoat').append( '<option data-divider="true"></option>');

                                $('#spBoat').append(
                                    '<option value="'+row.boat_id+'" data-subtext="'+
//                                'Α.Δ.Τ.:' + noNull(row.adt) + ', ' +
//                                'Α.Φ.Μ.:' + noNull(row.afm) + '">' +
                                        '">' +
                                        noNull(row.name) +
                                        '</option>');

                                $('#spBoat').append( '<option data-divider="true"></option>');
                                $('#spBoat').selectpicker('val', row.boat_id);
                                $('#spBoat').selectpicker('refresh');

                                $('#btnAddBoat').text('Ενημέρωση');
                                $('#btnAddBoat').attr('data-value', 'update');

                                if ( ! $("#pnlBoats").is( ":visible" ) )
                                {
                                    $('#pnlBoats').collapse('toggle');
                                }
                            });
                            <?php } ?>

                            var btnEdit = '<td style="width: 80px"><button type="button" value="' + row.boat_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-<?php echo (in_array($User["permission_id"], array(1, 2)) ? "pencil" : "info-sign") ?>"></button></td>';
                            var $btnEdit = $(btnEdit).click(function() {
                                window.open("../../<?php echo $packages["boats"]["parent"].$packages["boats"]["path"] ?>update/?id=" + row.boat_id, '_blank');
                            });

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.boat_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-log-out"></button></td>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Αποσύνδεση του Σκάφους "'+row.boat_id + ' : ' + row.name+'" από τη Μηχανή;',
                                    title: "Αποσύνδεση",
                                    buttons: {
                                        success: {
                                            label: "Αποσύνδεση",
                                            className: "btn-primary",
                                            callback: function() {
                                                if ( $("#pnlBoats").is( ":visible" ) )
                                                {
                                                    $("#btnCancelBoat").click();
                                                }

                                                $.ajax({
                                                    type: "POST",
                                                    url: "../data/boats-delete-link.php",
                                                    async: false,
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', boat_id:row.boat_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            initBoats();
                                                            loadEngineBoats();
                                                            loadEngineHistories();

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
                            $rowdata.append('<td>' + noNull(row.boat_id) + '</td>');
                            $rowdata.append('<td>' + noNull(row.name) + '</td>');

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $rowdata.append($btnEditBoatLink);
                            $rowdata.append($btnDelete);
                            <?php } ?>

                            $rowdata.append($btnEdit);

                            $rowdata.dblclick(function() {
                                window.open("../../<?php echo $packages["boats"]["parent"].$packages["boats"]["path"] ?>update/?id=" + row.boat_id, '_blank');
                            });

                            $('#tblBoats > tbody').append($rowdata);
                        });
                    }
                    else
                    {
                        var $rowdata = $('<tr></tr>');
                        $rowdata.append('<td colspan="12" align="center">'+data.message+'</td>');
                        $('#tblBoats > tbody').append($rowdata);
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

////= Link-Boats-Buttons ===============================================================================================

    $("#btnReloadEngineBoats").click(function(){
        loadEngineBoats();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadBoats").click(function(){
        loadBoats( $('#spBoat').selectpicker('val') );
    });


    $("#btnCreateBoat").click(function(){
        window.open('../../<?php echo $packages["engines"]["parent"].$packages["engines"]["path"] ?>create/','_blank');
    });


    $("#btnOpenBoat").click(function(){
        $('#btnReloadBoats').removeAttr("disabled");

        initBoats();

        $('#btnAddBoat').text('Σύνδεση');
        $('#btnAddBoat').attr('data-value', 'create');

        if ( ! $("#pnlBoats").is( ":visible" ) )
        {
            $('#pnlBoats').collapse('toggle');
        }
    });


    $("#btnAddBoat").click(function(){
        if ($(this).attr("data-value") == "update")
        {
            $.ajax({
                type: "POST",
                url: "../data/boats-update-link.php",
                async: false,
                data: $('#frmBoats').serialize(),
                success: function(response){

                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initBoats();
                        loadEngineBoats();
                        loadEngineHistories();

                        $("#btnCancelBoat").click();

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
                url: "../data/boats-create-link.php",
                async: false,
                data: $('#frmBoats').serialize(),
                success: function(response){
                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initBoats();
                        loadEngineBoats();
                        loadEngineHistories();

                        $("#btnCancelBoat").click();

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


    $("#btnCancelBoat").click(function(){
        $('#btnReloadBoats').removeAttr("disabled");

        initBoats();

        $('#btnAddBoat').text('Σύνδεση');
        $('#btnAddBoat').attr('data-value', 'create');

        $('#pnlBoats').collapse('toggle');

    });
    <?php } ?>

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

    function loadOwners(selected)
    {
        $.ajax({
            type: "POST",
            url: "../data/owners-list.php",
            async: false,
            data: {skip_engine_id:'<?php echo $_REQUEST["id"] ?>'},
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

                        if (row.owner_id == selected)
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

    function loadEngineOwners()
    {
        $.ajax({
            type: "POST",
            url: "../data/owners-list.php",
            async: false,
            data: {engine_id:'<?php echo $_REQUEST["id"] ?>'},
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
                                    message: 'Αποσύνδεση του Ιδιοκτήτη "'+ row.owner_id + ' : ' + row.lastname+' '+row.firstname+'" από το Σκάφος;',
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
                                                            loadEngineOwners();
                                                            loadEngineHistories();

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
                            $rowdata.append('<td>' + noNull(row.afm) + '</td>');
                            $rowdata.append('<td>' + noNull(row.phone) + '</td>');
                            $rowdata.append('<td>' + noNull(row.mobile) + '</td>');

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


    $("#btnReloadEngineOwners").click(function(){
        loadEngineOwners();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadOwners").click(function(){
        loadOwners( $('#spOwner').selectpicker('val') );
    });


    $("#btnCreateOwner").click(function(){
        window.open('../../<?php echo $packages["owners"]["parent"].$packages["owners"]["path"] ?>create/','_blank');
    });


    $("#btnOpenOwners").click(function(){
        $('#btnReloadOwners').removeAttr("disabled");

        initOwners();

        $('#btnAddOwner').text('Σύνδεση');
        $('#btnAddOwner').attr('data-value', 'create');

        if ( ! $("#pnlOwners").is( ":visible" ) )
        {
            $('#pnlOwners').collapse('toggle');
        }
    });


    $("#btnAddOwner").click(function(){
        if ($(this).attr("data-value") == "update")
        {
            $.ajax({
                type: "POST",
                url: "../data/owners-update-link.php",
                data: $('#frmOwners').serialize(),
                success: function(response){

                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initBoats();
                        loadEngineBoats();
                        loadEngineHistories();

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
                        loadEngineOwners();
                        loadEngineHistories();

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


    $("#btnCancelOwner").click(function(){
        $('#btnReloadOwners').removeAttr("disabled");

        initOwners();

        $('#btnAddOwner').text('Σύνδεση');
        $('#btnAddOwner').attr('data-value', 'create');

        $('#pnlOwners').collapse('toggle');

    });
    <?php } ?>

////= Link-Images ======================================================================================================

    function loadEngineImages()
    {
        $.ajax({
            type: "POST",
            url: "../data/images-list.php",
            async: false,
            data: {id:'<?php echo $_REQUEST["id"] ?>'},
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
                            var btnDelete = '<button type="button" value="' + row.engine_image_id + '" class="btn btn-default btn-block">Διαγραφή</button>';
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
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', engine_image_id:row.engine_image_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            loadEngineImages();

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

    $("#btnReloadEngineImages").click(function(){
        loadEngineImages();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnOpenImages").click(function(){
        if ( ! $("#pnlImages").is( ":visible" ) )
        {
            $('#pnlImages').collapse('toggle');
        }
    });


    $("#btnCancelImage").click(function(){
        $("#files").empty();

        $('#progress .progress-bar').css('width', 0);

        $('#pnlImages').collapse('toggle');
    });

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
        if (data.result.status == 200)
        {
            setTimeout(function(){
                $("#btnCancelImage").click();

                loadEngineImages();
            }, 1000);

            $("#events-result").attr("class", "alert alert-success");
            $("#events-result").text(data.result.message);
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

    function loadEngineHistories()
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
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.engine_history_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-trash"></button></td>';
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
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', engine_history_id:row.engine_history_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            loadEngineHistories();

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
                            $rowdata.append('<td>' + noNull(row.engine_history_id) + '</td>');
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

    $("#btnReloadEngineHistories").click(function(){
        loadEngineHistories();
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
                    $("#btnSave").attr("disabled", "disabled");
                    $("#btnSaveCreate").attr("disabled", "disabled");
                    $("#btnPrint").attr("disabled", "disabled");
                    $("#btnDelete").attr("disabled", "disabled");
                    $("#btnReturn").attr("disabled", "disabled");

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
                    $("#btnSave").attr("disabled", "disabled");
                    $("#btnSaveCreate").attr("disabled", "disabled");
                    $("#btnPrint").attr("disabled", "disabled");
                    $("#btnDelete").attr("disabled", "disabled");
                    $("#btnReturn").attr("disabled", "disabled");

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
    $("#btnEngineType").click(function(event){
        loadEngineTypes( $('#spEngineType').selectpicker('val') )
    });


    $("#btnEngineKind").click(function(event){
        loadEngineKinds( $('#spEngineKind').selectpicker('val') )
    });


    $("#btnEnginePowerType").click(function(event){
        loadEnginePowerTypes( $('#spEnginePowerType').selectpicker('val') )
    });


    $("#btnEngineBrand").click(function(event){
        loadEngineBrands( $('#spEngineBrand').selectpicker('val') )
    });


    $("#btnEngineStatus").click(function(event){
        loadEngineStatus( $('#spEngineStatus').selectpicker('val') )
    });
    <?php } ?>


    $("#btnPrint").click(function(){
        bootbox.prompt({
            message : "Εκτύπωση",
            title    : "Εκτύπωση",
            inputType : 'select',
            value : 'PrintInfos',
            inputOptions : [
                { text : 'Στοιχεία Μηχανής', value: 'PrintInfos', name: 'PrintInfos'},
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
                            async: false,
                            data: {id:<?php echo $_REQUEST["id"] ?>},
                            success: function(response){
                                var data = JSON.parse(response);

                                if (data.status == 200)
                                {
                                    setTimeout(function(){
                                        window.location.href = "../";
                                    }, 1500);

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

////Start Here =========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadEngineTypes();
    loadEngineKinds();
    loadEnginePowerTypes();
    loadEngineBrands();
    loadEngineStatus();

    initBoats();
    loadEngineBoats();

    initOwners();
    loadEngineOwners();

    loadEngineImages();

    loadEngineHistories();

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
                <li class="active"><a href="#infos" role="tab" data-toggle="tab">Στοιχεία Μηχανής</a></li>
                <li><a href="#boats" role="tab" data-toggle="tab">Σκάφη</a></li>
                <li><a href="#owners" role="tab" data-toggle="tab">Ιδιοκτήτες</a></li>
                <li><a href="#images" role="tab" data-toggle="tab">Φωτογραφίες</a></li>
                <li><a href="#history" role="tab" data-toggle="tab">Ιστορικό</a></li>
                <li><a href="#comments" role="tab" data-toggle="tab">Σχόλια</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active" id="infos">
                    <div class="row">

                        <div class="row"><br></div>

                        <form class="form-horizontal col-sm-12" id="frm" role="form">
                            <fieldset>
                                <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;"><span style="color: red">*</span> Σειριακός Αριθμός :</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="txtSerialNumber" id="txtSerialNumber">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Τύπος :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spEngineType" name="spEngineType" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnEngineType" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Είδος :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spEngineKind" name="spEngineKind" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnEngineKind" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Δύναμη :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtPower" id="txtPower">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Μονάδα :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spEnginePowerType" name="spEnginePowerType" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnEnginePowerType" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Μάρκα :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spEngineBrand" name="spEngineBrand" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnEngineBrand" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Κατάσταση :</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <select id="spEngineStatus" name="spEngineStatus" class="selectpicker" data-live-search="true"></select>
                                            <span class="input-group-btn">
                                                <button id="btnEngineStatus" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                </div>


                <div class="tab-pane" id="boats">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="table-responsive col-sm-12">
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <a class="navbar-brand" href="javascript:void(0)"><?php echo $packages["boats"]["title"] ?></a>
                                    </div>
                                    <div class="navbar-form navbar-right">
                                        <button id="btnReloadEngineBoats" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                        <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                                            <button id="btnOpenBoat" class="btn btn-default " type="button"><span class="glyphicon glyphicon-log-in"></span></button>
                                            <button id="btnCreateBoat" class="btn btn-default " type="button"><span class="glyphicon glyphicon-plus"></span></button>
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
                                <div id="pnlBoats" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form class="form-horizontal col-sm-12" id="frmBoats" role="form">
                                            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">
                                            <fieldset>

                                                <div class="form-group">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <select id="spBoat" name="spBoat" data-width="auto" class="selectpicker" data-live-search="true"></select>
                                                            <span class="input-group-btn">
                                                                <button id="btnReloadBoats" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                                                                <button type="button" id="btnAddBoat" name="btnAddBoat" class="btn btn-primary btn-block">Σύνδεση</button>
                                                            </div>
                                                            <div class="btn-group">
                                                                <button type="button" id="btnCancelBoat" name="btnCancelBoat" class="btn btn-default btn-block">Ακύρωση</button>
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-hover table-striped table-bordered" id="tblBoats" name="tblBoats">
                                        <thead>
                                        <tr style="height: 40px; vertical-align: middle" id="tdOrderColumns">
                                            <th style="width: 50px">#</th>
                                            <th style="cursor: pointer;<?php echo ($cols[2] == "false" ? ' display:none;' : '') ?>" data-value="id">Κωδικός <?php echo ($field == "id" ? $order : '') ?></th>
                                            <th style="cursor: pointer;<?php echo ($cols[3] == "false" ? ' display:none;' : '') ?>" data-value="name">Όνομα <?php echo ($field == "name" ? $order : '') ?></th>
                                            <th style="width: 50px" colspan="3">Ενέργειες</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                                        <button id="btnReloadEngineOwners" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <select id="spOwner" name="spOwner" data-width="auto" class="selectpicker" data-live-search="true"></select>
                                                            <span class="input-group-btn">
                                                                <button id="btnReloadOwners" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                                    <th style="cursor: pointer;<?php echo ($cols[5] == "false" ? ' display:none;' : '') ?>" data-value="name">Α.Φ.Μ. <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[6] == "false" ? ' display:none;' : '') ?>" data-value="name">Τηλέφωνο <?php echo ($field == "name" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[6] == "false" ? ' display:none;' : '') ?>" data-value="name">Κινητό <?php echo ($field == "name" ? $order : '') ?></th>
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
                                        <button id="btnReloadEngineImages" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                                        <button id="btnReloadEngineHistories" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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