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

////Objects ============================================================================================================

    $('#spOwnerStatus').selectpicker({ size: '3', width:'100%', noneSelectedText : 'Κατάσταση' });

////= Load =============================================================================================================

    function loadData()
    {
        $("#events-result").attr("class", "alert alert-info");
        $("#events-result").text("Δημιουργία Ιδιοκτήτη");
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

                        if ((row.owner_status_id == $selected) ||  (row.is_default == 1))
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

////Start Here ========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadOwnerStatus();

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
                <li class="active">Δημιουργία</li>
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