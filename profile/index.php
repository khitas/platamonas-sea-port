<?php
    include "info.php";

    include "../includes/settings.php";
    include "../includes/authentication.php";

    $_REQUEST["id"] = $User["user_id"];
?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="../libs/jquery/jquery-2.1.0.js"></script>

    <script src="../libs/bootbox/bootbox.js"></script>

    <link href="../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../libs/bootstrap/dist/js/bootstrap.js"></script>

    <link href="../libs/switch/build/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
    <script src="../libs/switch/build/js/bootstrap-switch.js"></script>

    <link href="../libs/select/bootstrap-select.css" rel="stylesheet">
    <script src="../libs/select/bootstrap-select.js"></script>

    <link href='../libs/magnific/dist/magnific-popup.css' rel='stylesheet prefetch'>
    <script src='../libs/magnific/dist/jquery.magnific-popup.min.js'></script>


    <script src="../libs/spin/spin.js"></script>
    <script src="../libs/spin/loader.js"></script>

</head>

<script>
$(document).ready(function(){

///= Objects ===========================================================================================================

    $('#spPermission').selectpicker({size: 'auto', width:'100%', noneSelectedText : 'επιλέξτε' });

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
            url: "../<?php echo $packages["users"]["parent"].$packages["users"]["path"] ?>data/list.php",
            async: false,
            data: {id:'<?php echo $_REQUEST["id"] ?>'},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    data.row = data.rows[0];

                    $("#txtLastname").val(data.row.lastname);
                    $("#txtFirstname").val(data.row.firstname);
                    $("#txtUsername").val(data.row.username);
                    $("#txtPassword").val('');
                    $("#txtRePassword").val('');
                    $('#spPermission').selectpicker('val', data.row.permission_id);

                    $("#txtUsername").prop('readonly', true);
                    $('#spPermission').prop('disabled',true);

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text("<?php echo "Ενημέρωση Χρήστη" ?>");
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

    function loadPermissions(selected)
    {
        $.ajax({
            type: "POST",
            url: "../admin/permissions/data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spPermission').empty();

                    $('#spPermission').append( '<option data-divider="true"></option>');
                    $('#spPermission').append( '<option value="0">Δικαιώματα</option>');
                    $('#spPermission').append( '<option data-divider="true"></option>');

                    $('#spPermission').selectpicker('val', 0);

                    $.each(data.rows, function(id, row)
                    {
                        $('#spPermission').append( '<option value="'+row.permission_id+'">'+row.name+'</option>');

                        if (row.permission_id == selected)
                        {
                            $('#spPermission').selectpicker('val', row.permission_id);
                        }
                    });

                    $('#spPermission').selectpicker('refresh');

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
            url: "../<?php echo $packages["users"]["parent"].$packages["users"]["path"] ?>data/update-user.php",
            async: false,
            data: $('#frm').serialize(),
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $("#btnSave").attr("disabled", "disabled");
                    $("#btnPrint").attr("disabled", "disabled");
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
                { text : 'Στοιχεία Χρήστη', value: 'PrintInfos', name: 'PrintInfos'},
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
                        var data = {
                            id:'<?php echo $_REQUEST["id"] ?>',
                            print:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('../<?php echo $packages["users"]["parent"].$packages["users"]["path"] ?>print/infos.php?' + str,'_blank');
                        break;
                }
            }
        });
    });

////Start Here =========================================================================================================

    spinner.spin( document.getElementById('preview') );

    loadPermissions();

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
                <li><a href="../"><?php echo $packages[$package]["title"] ?></a></li>
                <li class="active">Ενημέρωση</li>
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
                        <label class="col-sm-2 control-label"><span style="color: red">*</span> Επώνυμο :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="txtLastname" id="txtLastname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red">*</span> Όνομα :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="txtFirstname" id="txtFirstname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red">*</span> Όνομα Χρήστη :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="txtUsername" id="txtUsername" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Κωδικός :</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="txtPassword" id="txtPassword">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Επαλήθευση :</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="txtRePassword" id="txtRePassword">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span style="color: red">*</span> Δικαιώματα :</label>
                        <div class="col-sm-10">
                            <select id="spPermission" name="spPermission" class="selectpicker" data-live-search="true"></select>
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
                    <button type="button" id="btnPrint" class="btn btn-default btn-lg btn-block">Εκτύπωση</button>
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