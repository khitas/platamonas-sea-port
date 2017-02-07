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
        $("#events-result").attr("class", "alert alert-info");
        $("#events-result").text("Δημιουργία Μηχανής");
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

                        if (selected > 0)
                        {
                            if ((row.engine_type_id == selected))
                            {
                                $('#spEngineType').selectpicker('val', row.engine_type_id);
                            }
                        }
                        else
                        {
                            if (row.is_default == 1)
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

                        if ((row.engine_kind_id == selected) ||  (row.is_default == 1))
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

                        if ((row.engine_power_type_id == selected) || (row.is_default == 1))
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

                        if ((row.engine_brand_id == selected) ||  (row.is_default == 1))
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

                        if ((row.engine_status_id == selected) ||  (row.is_default == 1))
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

////=Start Here ========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadEngineTypes();
    loadEngineKinds();
    loadEnginePowerTypes();
    loadEngineBrands();
    loadEngineStatus();

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
            <div class="alert alert-info" id="events-result">Δημιουργία Μηχανής</div>
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


                <div class="tab-pane" id="οςνερσ">
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