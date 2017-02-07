<?php
    include "../info.php";
    $action = "create";

    include "../../../includes/settings.php";
    include "../../../includes/authentication.php";

if (!in_array($User["permission_id"], array(1, 2))) header( 'Location: ../../no-perms/' );

?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="../../../libs/jquery/jquery-2.1.0.js"></script>

    <script src="../../../libs/bootbox/bootbox.js"></script>

    <link href="../../../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../../../libs/bootstrap/dist/js/bootstrap.js"></script>

    <link href="../../../libs/switch/build/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
    <script src="../../../libs/switch/build/js/bootstrap-switch.js"></script>

    <script src="../../../libs/spin/spin.js"></script>
    <script src="../../../libs/spin/loader.js"></script>
</head>

<script>
$(document).ready(function(){

////= Objects ==========================================================================================================

    $('#cbIsDefault').bootstrapSwitch('size', 'medium');
    $('#cbIsDefault').bootstrapSwitch('onText', 'ΝΑΙ');
    $('#cbIsDefault').bootstrapSwitch('offText', 'ΟΧΙ');
    $('#cbIsDefault').bootstrapSwitch('animate', true);

////= Load =============================================================================================================

    function loadData()
    {
        $("#events-result").attr("class", "alert alert-info");
        $("#events-result").text("Δημιουργία");
    }


////= Buttons ==========================================================================================================

    $("#btnSave").click(function(){
        $.ajax({
            type: "POST",
            url: "../data/create.php",
            async: false,
            data: $('#frm').serialize(),
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    setTimeout(function(){
                       window.location.href = "../";
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

    $("#btnSaveCreate").click(function(){
        $.ajax({
            type: "POST",
            url: "../data/create.php",
            async: false,
            data: $('#frm').serialize(),
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

<?php include "../../../toolbars/navbar-top.php" ?>

<div class="container">

    <div id="preview"></div>

    <div role="main" class="col-md-12">

        <div class="row"><br><br><br><br></div>

<!--        <div class="row">-->
<!--            <h2 class="page-header">--><?php //echo $packages[$package]["title"] ?><!--</h2>-->
<!--        </div>-->

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="../../../"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href="../../"><?php echo $packages["admin"]["title"] ?></a></li>
                <li><a href="../"><?php echo $packages[$package]["title"] ?></a></li>
                <li class="active">Δημιουργία</li>
            </ol>
        </div>

        <div class="row" style="display: block">
            <div class="alert alert-info" id="events-result">&nbsp;</div>
        </div>

        <div class="row">
            <form class="form-horizontal" id="frm" role="form">
                <fieldset>
                    <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST["id"]?>">
                    <div class="form-group">
                        <label for="txtName" class="col-sm-2 control-label"><span style="color: red">*</span> Όνομα :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="txtName" id="txtName" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="txtName" class="col-sm-2 control-label">Χρώμα :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="txtCode" id="txtCode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cbIsDefault" class="col-sm-2 control-label">Προεπιλογή :</label>
                        <div class="col-sm-10">
                            <input id="cbIsDefault" name="cbIsDefault" type="checkbox">
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <div class="row"><br></div>

        <div class="row">
            <div class="btn-group btn-group-justified">

                <div class="btn-group">
                    <button type="button" id="btnSave" class="btn btn-primary btn-lg btn-block">Αποθήκευση</button>
                </div>

                <div class="btn-group">
                    <button type="button" id="btnSaveCreate" class="btn btn-default btn-lg btn-block">Αποθήκευση & Νέα</button>
                </div>

                <div class="btn-group">
                    <button type="button" id="btnReturn" class="btn btn-default btn-lg btn-block">Επιστροφή</button>
                </div>

            </div>
        </div>


        <div class="row"><br><br><br></div>

    </div>

</div>

<?php include "../../../toolbars/navbar-bottom.php" ?>


</body>
</html>