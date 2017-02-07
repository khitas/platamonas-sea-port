<?php
include "../info.php";
$action = "create";

include "../../includes/settings.php";
include "../../includes/authentication.php";

if (!in_array($User["permission_id"], array(1, 2))) header( 'Location: ../../no-perms/' );

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

    $('#dpRegistryDate').datetimepicker({ pickTime: false });
    $('#dpLicenseExpiredDate').datetimepicker({ pickTime: false });

////= Load =============================================================================================================

    function loadData()
    {
        $("#events-result").attr("class", "alert alert-info");
        $("#events-result").text("Δημιουργία Σκάφους");
    }

////= Dictionaries =====================================================================================================

    function loadBoatKinds(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spBoatKind').empty();

                    $('#spBoatKind').append( '<option data-divider="true"></option>');
                    $('#spBoatKind').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatKind').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatKind').append( '<option value="'+row.boat_kind_id+'">'+row.name+'</option>');

                        if ((row.boat_kind_id == selected) ||  (row.is_default == 1))
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

    function loadBoatPorts(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spBoatPort').empty();

                    $('#spBoatPort').append( '<option data-divider="true"></option>');
                    $('#spBoatPort').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatPort').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatPort').append( '<option value="'+row.boat_port_id+'">'+row.name+'</option>');

                        if ((row.boat_port_id == selected) ||  (row.is_default == 1))
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

    function loadRegistryTypes(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spRegistryType').empty();

                    $('#spRegistryType').append( '<option data-divider="true"></option>');
                    $('#spRegistryType').append( '<option value="0">επιλέξτε</option>');
                    $('#spRegistryType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spRegistryType').append( '<option value="'+row.registry_type_id+'">'+row.name+'</option>');

                        if ((row.registry_type_id == selected) ||  (row.is_default == 1))
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

    function loadAmyenTypes(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spAmyenType').empty();

                    $('#spAmyenType').append( '<option data-divider="true"></option>');
                    $('#spAmyenType').append( '<option value="0">επιλέξτε</option>');
                    $('#spAmyenType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spAmyenType').append( '<option value="'+row.amyen_type_id+'">'+row.name+'</option>');

                        if ((row.amyen_type_id == selected) ||  (row.is_default == 1))
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

    function loadBoatMaterials(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spBoatMaterial').empty();

                    $('#spBoatMaterial').append( '<option data-divider="true"></option>');
                    $('#spBoatMaterial').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatMaterial').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatMaterial').append( '<option value="'+row.boat_material_id+'">'+row.name+'</option>');

                        if ((row.boat_material_id == selected) ||  (row.is_default == 1))
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

    function loadBoatColors(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spBoatColor').empty();

                    $('#spBoatColor').append( '<option data-divider="true"></option>');
                    $('#spBoatColor').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatColor').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatColor').append( '<option value="'+row.boat_color_id+'">'+row.name+'</option>');

                        if ((row.boat_color_id == selected) ||  (row.is_default == 1))
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

    function loadBoatTypes(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spBoatType').empty();

                    $('#spBoatType').append( '<option data-divider="true"></option>');
                    $('#spBoatType').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatType').append( '<option value="'+row.boat_type_id+'">'+row.name+'</option>');

                        if ((row.boat_type_id == selected) ||  (row.is_default == 1))
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

    function loadMovementTypes(selected)
    {
        $.ajax({
            type: "POST",
            url: "../../<?php echo $packages["movement-types"]["parent"].$packages["movement-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spMovementType').empty();

                    $('#spMovementType').append( '<option data-divider="true"></option>');
                    $('#spMovementType').append( '<option value="0">επιλέξτε</option>');
                    $('#spMovementType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spMovementType').append( '<option value="'+row.movement_type_id+'">'+row.name+'</option>');

                        if ((row.movement_type_id == selected) ||  (row.is_default == 1))
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

    function loadBoatStatus(selected)
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
                    $("#events-result").attr("class", "alert alert-info");

                    $('#spBoatStatus').empty();

                    $('#spBoatStatus').append( '<option data-divider="true"></option>');
                    $('#spBoatStatus').append( '<option value="0">επιλέξτε</option>');
                    $('#spBoatStatus').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatStatus').append( '<option value="'+row.boat_status_id+'">'+row.name+'</option>');

                        if ((row.boat_status_id == selected) ||  (row.is_default == 1))
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

////= Buttons ==========================================================================================================

    $("#btnSave").click(function(){
        $.ajax({
            type: "POST",
            url: "../data/create.php",
            async: false,
            data: {frm:$('#frm').serialize(), frmComments:$('#frmComments').serialize()},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $("#btnSave").attr("disabled", "disabled");
                    $("#btnReturn").attr("disabled", "disabled");

                    setTimeout(function(){
                        window.location.href = "../update/?id=" + data.id
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

////=Start Here ========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadBoatKinds();
    loadBoatPorts();
    loadRegistryTypes();
    loadAmyenTypes();
    loadBoatMaterials();
    loadBoatColors();
    loadBoatTypes();
    loadMovementTypes();
    loadBoatStatus();

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
                <li class="active">Δημιουργία</li>
            </ol>
        </div>

        <div class="row" style="display: block">
            <div class="alert alert-info" id="events-result">Δημιουργία Σκάφους</div>
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

                        <form class="form-horizontal col-sm-12" id="frm" role="form">
                            <fieldset>
                                <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="white-space:nowrap;"><span style="color: red">*</span> Όνομα :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="txtName" id="txtName" autofocus>
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
                    <div class="row">

                        <div class="row"><br></div>

                        <form class="form-horizontal col-sm-12">
                            <fieldset>
                                <div class="form-group">
                                    <div class="table-responsive col-sm-12">
                                        <div class="well">Πρέπει πρώτα να κάνετε Αποθήκευση</div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>


                <div class="tab-pane" id="engines">
                    <div class="row">
                        <div class="row"><br></div>
                        <form class="form-horizontal col-sm-12">
                            <fieldset>
                                <div class="form-group">
                                    <div class="table-responsive col-sm-12">
                                        <div class="well">Πρέπει πρώτα να κάνετε Αποθήκευση</div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>


                <div class="tab-pane" id="images">
                    <div class="row">
                        <div class="row"><br></div>
                        <form class="form-horizontal col-sm-12">
                            <fieldset>
                                <div class="form-group">
                                    <div class="table-responsive col-sm-12">
                                        <div class="well">Πρέπει πρώτα να κάνετε Αποθήκευση</div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div class="tab-pane" id="history">
                    <div class="row">
                        <div class="row"><br></div>
                        <form class="form-horizontal col-sm-12">
                            <fieldset>
                                <div class="form-group">
                                    <div class="table-responsive col-sm-12">
                                        <div class="well">Πρέπει πρώτα να κάνετε Αποθήκευση</div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
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
                <div class="btn-group">
                    <button type="button" id="btnSave" class="btn btn-primary btn-lg btn-block">Αποθήκευση</button>
                </div>
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