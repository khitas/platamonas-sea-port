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

////= Objects ==========================================================================================================

    $('#spOwnerStatus').selectpicker({ size: '3', width:'100%', noneSelectedText : 'Κατάσταση' });

    $('#spBoat').selectpicker({ size: 'auto', noneSelectedText : 'Σκάφος' });
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

                    $("#txtLastName").val(data.row.lastname);
                    $("#txtFirstName").val(data.row.firstname);
                    $("#txtFatherName").val(data.row.fathername);
                    $("#txtADT").val(data.row.adt);
                    $("#txtAFM").val(data.row.afm);
                    $("#txtAddress").val(data.row.address);
                    $("#txtPhone").val(data.row.phone);
                    $("#txtMobile").val(data.row.mobile);
                    $("#txtComments").val(data.row.comments);

                    <?php if (!in_array($User["permission_id"], array(1, 2))) { ?>
                    $("#txtLastName").prop('readonly', true);
                    $("#txtFirstName").prop('readonly', true);
                    $("#txtFatherName").prop('readonly', true);
                    $("#txtADT").prop('readonly', true);
                    $("#txtAFM").prop('readonly', true);
                    $("#txtAddress").prop('readonly', true);
                    $("#txtPhone").prop('readonly', true);
                    $("#txtMobile").prop('readonly', true);
                    $("#txtComments").prop('readonly', true);
                    <?php } ?>

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text("<?php echo (in_array($User["permission_id"], array(1, 2)) ? "Ενημέρωση Ιδιοκτήτη" : "Προβολή Ιδιοκτήτη") ?>");
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

    function loadBoats($selected)
    {
        $.ajax({
            type: "POST",
            url: "../data/boats-list.php",
            async: false,
            data: {skip_owner_id:'<?php echo $_REQUEST["id"] ?>'},
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

                        if (row.boat_id == $selected)
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

    function loadOwnerBoats()
    {
        $.ajax({
            type: "POST",
            url: "../data/boats-list.php",
            async: false,
            data: {owner_id:'<?php echo $_REQUEST["id"] ?>'},
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

                                $('#dpGetDate').data("DateTimePicker").setDate(row.get_date_gr);

                                $('#txtPercent').val(row.percent);

                                $('#spOwnerStatus').selectpicker('val', row.owner_status_id);
                                $('#spOwnerStatus').selectpicker('refresh')

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
                                    message: 'Αποσύνδεση του Σκάφους "'+row.name+'" από τον Ιδιοκτήτη;',
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
                                                            loadOwnerBoats();
                                                            loadOwnerHistories();

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
                            $rowdata.append('<td>' + row.boat_id + '</td>');
                            $rowdata.append('<td>' + noNull(row.name) + '</td>');
                            $rowdata.append('<td>' + noNull(row.owner_status) + '</td>');
                            $rowdata.append('<td>' + noNull(row.get_date_gr) + '</td>');
                            $rowdata.append('<td>' + noNull(row.percent) + '</td>');

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

    $("#btnReloadOwnerBoats").click(function(){
        loadOwnerBoats();
    });

    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadBoats").click(function(){
        loadBoats( $('#spBoat').selectpicker('val') );
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnReloadOwnerStatus").click(function(){
        loadOwnerStatus( $('#spOwnerStatus').selectpicker('val') );
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCreateBoat").click(function(){
        window.open('../../<?php echo $packages["owners"]["parent"].$packages["owners"]["path"] ?>create/','_blank');
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnOpenBoats").click(function(){
        $('#btnReloadBoats').removeAttr("disabled");

        initBoats();

        $('#dpGetDate').data("DateTimePicker").setDate('');

        $('#txtPercent').val('');

        $('#spOwnerStatus').selectpicker('val', 0);
        $('#spOwnerStatus').selectpicker('refresh');

        $('#btnAddBoat').text('Σύνδεση');
        $('#btnAddBoat').attr('data-value', 'create');

        if ( ! $("#pnlBoats").is( ":visible" ) )
        {
            $('#pnlBoats').collapse('toggle');
        }
    });
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
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
                        loadOwnerBoats();
                        loadOwnerHistories();

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
//                async: false,
                data: $('#frmBoats').serialize(),
                success: function(response){
                    var data = JSON.parse(response);

                    if (data.status == 200)
                    {
                        initBoats();
                        loadOwnerBoats();
                        loadOwnerHistories();

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
    <?php } ?>


    <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
    $("#btnCancelBoat").click(function(){
        $('#btnReloadBoats').removeAttr("disabled");

        initBoats();

        $('#dpGetDate').data("DateTimePicker").setDate('');

        $('#txtPercent').val('');

        $('#btnAddBoat').text('Σύνδεση');
        $('#btnAddBoat').attr('data-value', 'create');

        $('#pnlBoats').collapse('toggle');

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
            data: {skip_owner_id:'<?php echo $_REQUEST["id"] ?>'},
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


    function loadOwnerEngines()
    {
        $.ajax({
            type: "POST",
            url: "../data/engines-list.php",
            data: {owner_id:'<?php echo $_REQUEST["id"] ?>'},
            async: false,
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
                                    message: 'Αποσύνδεση της Μηχανής "'+row.serial_number+'" από τον Ιδιοκτήτη;',
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
                                                            loadOwnerEngines();
                                                            loadOwnerHistories();

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
                            $rowdata.append('<td>' + noNull(row.power) + ' ' + noNull(row.engine_power_type) + '</td>');
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

    $("#btnReloadOwnerEngines").click(function(){
        loadOwnerEngines();
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
                        loadOwnerEngines();
                        loadOwnerHistories();

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
                        loadOwnerEngines();
                        loadOwnerHistories();

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


    function loadOwnerImages()
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
                            var btnDelete = '<button type="button" value="' + row.owner_image_id + '" class="btn btn-default btn-block">Διαγραφή</button>';
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
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', owner_image_id:row.owner_image_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            loadOwnerImages();
                                                            loadOwnerHistories();

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

    $("#btnReloadOwnerImages").click(function(){
        loadOwnerImages();
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
        if (data.result.status == 200)
        {
            setTimeout(function(){
                $("#btnCancelImage").click();

                loadOwnerImages();
                loadOwnerHistories();

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

    function loadOwnerHistories()
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
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.owner_history_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-trash"></button></td>';
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
                                                    data: {id:'<?php echo $_REQUEST["id"] ?>', owner_history_id:row.owner_history_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            loadOwnerHistories();

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
                            $rowdata.append('<td>' + noNull(row.owner_history_id) + '</td>');
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

    $("#btnReloadOwnerHistories").click(function(){
        loadOwnerHistories();
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


    $("#btnPrint").click(function(){
        bootbox.prompt({
            message : "Εκτύπωση",
            title    : "Εκτύπωση",
            inputType : 'select',
            value : 'PrintInfos',
            inputOptions : [
                { text : 'Στοιχεία Ιδιοκτήτη', value: 'PrintInfos', name: 'PrintInfos'},
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
                                    setTimeout(function(){
                                        window.location.href = "../";
                                    }, 1500);

                                    $("#events-result").attr("class", "alert alert-success");
                                    $("#events-result").text(data.message);
                                }
                                else
                                {
                                    $("#events-result").text(data.message);
                                    $("#events-result").attr("class", "alert alert-danger");
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

////Start Here ========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadOwnerStatus();

    initBoats();
    loadOwnerBoats();

    initEngines();
    loadOwnerEngines();

    loadOwnerImages();

    loadOwnerHistories();

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
                <li class="active"><a href="#infos" role="tab" data-toggle="tab">Στοιχεία Ιδιοκτήτη</a></li>
                <li><a href="#boats" role="tab" data-toggle="tab">Σκάφη</a></li>
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

                        <form class="form-horizontal col-sm-12" id="frm" role="form">
                            <fieldset>
                                <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;"><span style="color: red">*</span> Επώνυμο :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtLastName" id="txtLastName" autofocus>
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;"><span style="color: red">*</span> Όνομα :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtFirstName" id="txtFirstName">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Πατρώνυμο :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtFatherName" id="txtFatherName">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Διεύθυνση :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtAddress" id="txtAddress">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Α.Δ.Τ. :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtADT" id="txtADT">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Α.Φ.Μ. :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtAFM" id="txtAFM">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Τηλέφωνο :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtPhone" id="txtPhone">
                                    </div>
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;">Κινητό :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtMobile" id="txtMobile">
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
                                        <button id="btnReloadOwnerBoats" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                        <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                                            <button id="btnOpenBoats" class="btn btn-default " type="button"><span class="glyphicon glyphicon-log-in"></span></button>
                                            <button id="btnCreateBoat" class="btn btn-default " type="button"><span class="glyphicon glyphicon-plus"></span></button>
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
                                <div id="pnlBoats" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form class="form-horizontal col-sm-12" id="frmBoats" role="form">
                                            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">
                                            <fieldset>

                                                <div class="form-group">
                                                    <div class="col-sm-2"></div>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <select id="spBoat" name="spBoat" data-width="auto" class="selectpicker" data-live-search="true"></select>
                                                            <span class="input-group-btn">
                                                                <button id="btnReloadBoats" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                            <table class="table table-hover table-striped table-bordered" id="tblBoats" name="tblBoats">
                                <thead>
                                <tr style="height: 40px; vertical-align: middle" id="tdOrderColumns">
                                    <th style="width: 50px">#</th>
                                    <th style="cursor: pointer;<?php echo ($cols[2] == "false" ? ' display:none;' : '') ?>" data-value="id">Κωδικός <?php echo ($field == "id" ? $order : '') ?></th>
                                    <th style="cursor: pointer;<?php echo ($cols[3] == "false" ? ' display:none;' : '') ?>" data-value="name">Όνομα <?php echo ($field == "name" ? $order : '') ?></th>
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
                                        <button id="btnReloadOwnerEngines" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                                        <button id="btnReloadOwnerImages" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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
                                        <button id="btnReloadOwnerHistories" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
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