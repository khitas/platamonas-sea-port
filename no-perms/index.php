<?php
include "info.php";

include "../includes/settings.php";
include "../includes/authentication.php";
?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="../libs/jquery/jquery-2.1.0.js"></script>

    <link href="../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" >
    <link href="../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet" >
    <script src="../libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="../libs/bootbox/bootbox.js"></script>
</head>

<script>
$(document).ready(function(){

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

    $(document).ready(function(){

        $("#btnReturn").click(function(){
            window.location.href = "../";
        });

    });

});
</script>

<style>
    body {
        padding: 0px;
        /*background-color: #eee;*/
    }
</style>

<body>

<?php include "../toolbars/navbar-top.php" ?>

<div class="container">

    <div role="main" class="col-md-1"></div>

    <div role="main" class="col-md-10">

        <div class="row"><br><br><br><br></div>

        <div class="row" style="display: block">
            <div class="alert alert-danger" id="events-result">Δεν έχετε δικαιώματα πρόσβασης στη σελίδα</div>
        </div>

        <div class="row">
            <div class="btn-group-justified">
                <div class="btn-group">
                    <button type="button" id="btnReturn" class="btn btn-default btn-lg btn-block">Επιστροφή</button>
                </div>
            </div>
        </div>

        <div class="row"><br><br><br></div>

    </div>

    <div role="main" class="col-md-1"></div>

</div>

<?php include "../toolbars/navbar-bottom.php" ?>

</body>
</html>