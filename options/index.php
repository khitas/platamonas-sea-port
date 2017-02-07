<?php
include "info.php";

include "../includes/settings.php";
include "../includes/authentication.php";

if (!in_array($User["permission_id"], array(1, 2))) header( 'Location: ../no-perms/' );

?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="../libs/jquery/jquery-2.1.0.js"></script>
    <script src="../libs/jquery/jquery.ui.widget.js"></script>

    <link href="../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="../libs/bootbox/bootbox.js"></script>

    <link href="../libs/switch/build/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
    <script src="../libs/switch/build/js/bootstrap-switch.js"></script>

    <link href="../libs/select/bootstrap-select.css" rel="stylesheet">
    <script src="../libs/select/bootstrap-select.js"></script>

    <link href='../libs/magnific/dist/magnific-popup.css' rel='stylesheet prefetch'>
    <script src='../libs/magnific/dist/jquery.magnific-popup.min.js'></script>

    <link href="../libs/capty/css/jquery.capty.css" rel="stylesheet">
    <script src="../libs/capty/js/jquery.capty.min.js"></script>

    <script src="../libs/datetimepicker/moment.js"></script>
    <link href="../libs/datetimepicker/datetimepicker.css" rel="stylesheet">
    <script src="../libs/datetimepicker/datetimepicker.js"></script>

    <script src="../ajax/functions.js"></script>

    <script src="../libs/spin/spin.js"></script>
    <script src="../libs/spin/loader.js"></script>

</head>

<script>
$(document).ready(function(){

///= Objects ===========================================================================================================

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
            url: "data/list.php",
            async: false,
            data: {option:'report_header'},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    data.row = data.rows[0];

                    $("#txtReportHeader").val(data.row.option_value);
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
            url: "data/update.php",
            async: false,
            data: {frm:$('#frmReports').serialize()},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
//                    setTimeout(function(){
//                        window.location.href = "../"
//                    }, 1000);
//
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

////Start Here =========================================================================================================

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

<?php include "../toolbars/navbar-top.php" ?>

<div class="container">

    <div id="preview"></div>

    <div role="main" class="col-md-12">

        <div class="row"><br><br><br><br></div>

        <!--        <div class="row">-->
        <!--            <h2 class="page-header">--><?php //echo $packages[$package]["title"] ?><!--</h2>-->
        <!--        </div>-->

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="../"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href=""><?php echo $packages[$package]["title"] ?></a></li>
                <li class="active"><?php echo (in_array($User["permission_id"], array(1, 2)) ? 'Ενημέρωση' :'Προβολή' ) ?></li>
            </ol>
        </div>

        <div class="row" style="display: block">
            <div class="alert alert-info" id="events-result">&nbsp;</div>
        </div>

        <div class="row">
        <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#reports" role="tab" data-toggle="tab">Εκτυπώσεις</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">

                <div class="tab-pane active" id="reports">

                    <div class="row"><br></div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <form class="form-horizontal col-sm-12" id="frmReports" role="form">
                                        <input type="hidden" name="option" id="option" value="report_header">
                                        <fieldset>
                                            <div class="form-group">
                                                <textarea id="txtReportHeader" name="txtReportHeader" class="form-control" rows="10" autofocus></textarea>
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

<?php include "../toolbars/navbar-bottom.php" ?>

</body>
</html>