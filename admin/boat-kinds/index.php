<?php
    include "info.php";

    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $columns = array(
         "2" => array("name"=>"boat_kind_id", "title"=>"Κωδικός", "cursor", "visible"),
         "3" => array("name"=>"name", "title"=>"Όνομα", "cursor", "visible"),
         "4" => array("name"=>"is_default", "title"=>"Προεπιλογή", "cursor", "visible", "boolean"),
    );

    if (is_array($_SESSION["states"][$package]["columns"]))
    {
        foreach ($_SESSION["states"][$package]["columns"] as $column => $visible)
        {
            if ($visible == "false")
                unset( $columns[$column][ array_search("visible", $columns[$column]) ] );
            else if (! in_array("visible", $columns[$column]) )
                $columns[ $column ][] = "visible";
        }
    }

    $advSearch = $_SESSION["states"][$package]["advSearch"] ? : array();

    $orderBy = $_SESSION["states"][$package]["orderBy"] ? : "name";
    $orderType = $_SESSION["states"][$package]["orderType"] ? : "dropdown";

    $searchText = $_SESSION["states"][$package]["searchText"] ? : "";
    $searchColumn = $_SESSION["states"][$package]["searchColumn"] ? : "all";
    $pageRecords = $_SESSION["states"][$package]["pageRecords"] ? : "";
    $pagination = $_SESSION["states"][$package]["pagination"] ? : "0";

    $openSearch = $_SESSION["states"][$package]["openSearch"] ? : "false";
?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="../../libs/jquery/jquery-2.1.0.js"></script>

    <link href="../../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../../libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="../../libs/bootbox/bootbox.js"></script>

    <link href="../../libs/searchclear/searchclear.css" rel="stylesheet">

    <link href="../../libs/totop/css/ui.totop.css" rel="stylesheet">
    <script src="../../libs/totop/js/easing.js"></script>
    <script src="../../libs/totop/js/jquery.ui.totop.js"></script>

    <script src="../../libs/pagination/lib/jquery.bootpag.js"></script>

    <link href="../../libs/select/bootstrap-select.css" rel="stylesheet">
    <script src="../../libs/select/bootstrap-select.js"></script>

    <script src="../../libs/datetimepicker/moment.js"></script>
    <link href="../../libs/datetimepicker/datetimepicker.css" rel="stylesheet">
    <script src="../../libs/datetimepicker/datetimepicker.js"></script>

    <script src="../../ajax/functions.js"></script>

    <script src="../../libs/spin/spin.js"></script>
    <script src="../../libs/spin/loader.js"></script>
</head>

<script>
$(document).ready(function(){

//// Objects ===========================================================================================================

    var defaults = {
        containerID: 'toTop', // fading element id
        containerHoverID: 'toTopHover', // fading element hover id
        scrollSpeed: 1200,
        easingType: 'linear'
    };
    $().UItoTop({ easingType: 'easeOutQuart' });


////= Load =============================================================================================================


    function saveListState(pagination, pageRecords, searchText, searchColumn, orderBy, orderType, openSearch, advSearch)
    {
        $.ajax({
            url: '../../ajax/save-list-state.php',
            type: 'POST',
            data: {
                package: '<?php echo $package ?>',
                pagination: (pagination != null ? pagination : $('#pagination').find('.active').text()),
                pageRecords: (pageRecords != null ? pageRecords : $("#page-records").find('.active').attr("data-value")),
                searchText: (searchText != null ? searchText : $("#txtSearch").val()),
                searchColumn: (searchColumn != null ? searchColumn : $("#searchColumns").find('.active').attr("data-value")),
                orderBy: (orderBy != null ? orderBy : $("#maintable").find('.order').parent().attr("data-value")),
                orderType: (orderType != null ? orderType : $("#maintable").find('.order')[0].className.split(' ')[1]),
                openSearch: (openSearch != null ? openSearch : $("#pnlAdvSearch").is( ":visible" )),
                advSearch: (advSearch != null ? advSearch : $('#frm').serialize())
            }
        });
    }


    function saveColumnState(column, visible)
    {
        $.ajax({
            url: '../../ajax/save-column-state.php',
            type: 'POST',
            data: {package:'<?php echo $package ?>', column: column, visible: visible}
        });
    }


    function initialization()
    {
        $.ajax({
            url: '../../admin/page-records/data/list.php',
            type: 'POST',
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $("#page-records").empty();

                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            $("#page-records").append('<li' + (row.is_default == 1 ? ' class="active"' : '') + ' data-value="'+row.name+'"><a href="#">'+row.name+'</a> </li>');
                        });

                        $("#page-records").append('<li class="divider"></li>');
                    }

                    if ( $("#page-records").find('.active').text() )
                        $("#page-records").append('<li data-value="all"><a href="#">Όλες</a> </li>');
                    else
                        $("#page-records").append('<li class="active" data-value="all"><a href="#">Όλες</a> </li>');

                    <?php if ($pageRecords) { ?>
                    $("#page-records li.active").removeClass('active');
                    $("#page-records li[data-value*='<?php echo $pageRecords ?>']").addClass('active');
                    <?php } ?>

                    $("#page-records-value").text( $("#page-records").find('.active').text() );


                    $("#searchColumns li.active").removeClass('active');
                    $("#searchColumns li[data-value*='<?php echo $searchColumn ?>']").addClass('active');

                    $("#txtSearch").val('<?php echo addslashes($searchText) ?>');

                    <?php if ($openSearch == "true") { ?>
                    $('#pnlAdvSearch').collapse('toggle');
                    <?php } ?>

                    $("#txtName").val('<?php echo $advSearch["txtName"] ?>')

                    loadData(<?php echo $pagination ?>);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }
            }
        })
    }


    function loadData(pagination, pageRecords, searchText, searchColumn, orderBy, orderType, advSearch)
    {
        $.ajax({
            url: 'data/list.php',
            type: 'POST',
            async: false,
            data: {
                pagination: (pagination != null ? pagination : $('#pagination').find('.active').text()),
                pageRecords: (pageRecords != null ? pageRecords : $("#page-records").find('.active').attr("data-value")),
                searchText: (searchText != null ? searchText : $("#txtSearch").val()),
                searchColumn: (searchColumn != null ? searchColumn : $("#searchColumns").find('.active').attr("data-value")),
                orderBy: (orderBy != null ? orderBy : $("#maintable").find('.order').parent().attr("data-value")),
                orderType: (orderType != null ? orderType : $("#maintable").find('.order')[0].className.split(' ')[1]),
                advSearch: (advSearch != null ? advSearch : $('#frm').serialize())
            },
            success: function(response){
                var data = JSON.parse(response);

                $("#maintable > tbody:last").children().remove();
                $("#pagination").empty()

                if (data.status == 200)
                {
                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);

                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            var btnEdit = '<td style="width: 80px"><button type="button" value="' + row.boat_kind_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-<?php echo (in_array($User["permission_id"], array(1, 2)) ? "pencil" : "info-sign") ?>"></button></td>';
                            var $btnEdit = $(btnEdit).click(function() {
                                window.location.href = "update/?id=" + row.boat_kind_id;
                            });

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.boat_kind_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-trash"></button></td>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Διαγραφή του "'+row.boat_kind_id+' : '+row.name+'";',
                                    title: "Διαγραφή",
                                    buttons: {
                                        success: {
                                            label: "Διαγραφή",
                                            className: "btn-primary",
                                            callback: function() {
                                                $.ajax({
                                                    type: "POST",
                                                    url: "data/delete.php",
                                                    async: false,
                                                    data: {id:row.boat_kind_id},
                                                    success: function(response){
                                                        var data = JSON.parse(response);

                                                        if (data.status == 200)
                                                        {
                                                            setTimeout(function(){
                                                                $("#btnRefresh").click();
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

                            var $rowdata = $('<tr></tr>');
                            $rowdata.append('<td>' + (id + 1) + '</td>');
                            <?php foreach( $columns as $id => $column ) {
                                    if (in_array("boolean", $column)) { ?>
                            $rowdata.append('<td>' + (row.<?php echo $column["name"] ?> == 1 ? "<span class='glyphicon glyphicon-ok'></span>" : "" ) + '</td>');
                            <?php   } else  { ?>
                            $rowdata.append('<td>' + noNull(row.<?php echo $column["name"] ?>) + '</td>');
                            <?php   } } ?>
                            $rowdata.append($btnEdit);
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $rowdata.append($btnDelete);
                            <?php } ?>

                            $rowdata.dblclick(function() {
                                window.location.href = "update/?id=" + row.boat_kind_id;
                            });

                            $('#maintable > tbody').append($rowdata);
                        });
                    }
                    else
                    {
                        var $rowdata = $('<tr></tr>');
                        $rowdata.append('<td colspan="30" align="center">'+data.message+'</td>');
                        $('#maintable > tbody').append($rowdata);
                    }

                    $("#events-result").attr("class", "alert alert-info");
                    $("#events-result").text(data.message);
                }
                else
                {
                    $("#events-result").attr("class", "alert alert-danger");
                    $("#events-result").text(data.message);
                }

                $("#tableColumns li").each(function() {
                    if ( $(this).find('span').css("display") == "none" )
                        $('#maintable td:nth-child('+$(this).attr("data-value")+'), th:nth-child('+$(this).attr("data-value")+')').hide();
                    else
                        $('#maintable td:nth-child('+$(this).attr("data-value")+'), th:nth-child('+$(this).attr("data-value")+')').show();
                });

                $("#pagination").bootpag({total: data.pages.total, page: data.pages.current, maxVisible: 10});
                $("#pagination-values").text('Εμφάνιση ' + data.records.from + ' έως ' + data.records.to + ' από ' + data.records.total);
            },
            error: function(){
            },
            complete: function() {
            }
        });
    }

////= Buttons ==========================================================================================================


    $("#btnCreate").click(function(){
        window.location.href = "create/";
    });


    $("#btnRefresh").click(function(event){
        saveListState(0);
        loadData(0);
    });


    $("#btnFilters").click(function(event){
        if ( ! $("#pnlAdvSearch").is( ":visible" ) )
        {
            $('#pnlAdvSearch').collapse('toggle');
        }

        saveListState(null, null, null, null, null, null, "true");
    });


    $("#btnPrint").click(function(){
        bootbox.prompt({
            message : "Εκτύπωση",
            title    : "Εκτύπωση",
            inputType : 'select',
            value : 'PrintAll',
            inputOptions : [
                { text : 'Όλες τις εγγραφές', value: 'PrintAll', name: 'PrintAll'},
                { text : 'Αποτελέσματα Αναζήτησης', value: 'PrintSearch', name: 'PrintSearch'},
                { text : 'Τρέχουσα Σελίδα', value: 'PrintPage', name: 'PrintPage'}
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
                    case "PrintAll" :
                        var data = {
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1],
                            print:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('print/?' + str,'_blank');
                    break;
                    case "PrintSearch" :
                        var data = {
                            advSearch:$('#frm').serialize(),
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1],
                            print:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('print/?' + str,'_blank');
                    break;
                    case "PrintPage" :
                        var data = {
                            pagination: $("#pagination").find('.active').attr("data-lp"),
                            pageRecords: $("#page-records").find('.active').attr("data-value"),
                            advSearch:$('#frm').serialize(),
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1],
                            print:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('print/?' + str,'_blank');
                    break;
                }
            }
        });
    });


    $("#btnExport").click(function(event){
        bootbox.prompt({
            message : "Εξαγωγή",
            title    : "Εξαγωγή",
            inputType : 'select',
            value : 'ExportAll',
            inputOptions : [
                { text : 'Όλες τις εγγραφές', value: 'ExportAll', name: 'ExportAll'},
                { text : 'Αποτελέσματα Αναζήτησης', value: 'ExportSearch', name: 'ExportSearch'},
                { text : 'Τρέχουσα Σελίδα', value: 'ExportPage', name: 'ExportPage'}
            ],
            buttons: {
                confirm: {
                    label: "Εξαγωγή",
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
                    case "ExportAll" :
                        var data = {
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1],
                            export:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('export/?' + str,'_blank');
                    break;
                    case "ExportSearch" :
                        var data = {
                            advSearch:$('#frm').serialize(),
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1],
                            export:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('export/?' + str,'_blank');
                    break;
                    case "ExportPage" :
                        var data = {
                            pagination: $("#pagination").find('.active').attr("data-lp"),
                            pageRecords: $("#page-records").find('.active').attr("data-value"),
                            advSearch:$('#frm').serialize(),
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1],
                            export:option
                        }

                        var str = Object.keys(data).map(function(key){
                            return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                        }).join('&');

                        window.open('export/?' + str,'_blank');
                    break;
                }
            }
        });

    });


    $("#page-records").on('click', 'li', function(){
        $("#page-records li.active").removeClass('active');
        $(this).closest('li').addClass('active');
        $("#page-records-value").text( $(this).text() );

        saveListState();
        loadData();
    });


    $("#tableColumns").on('click', 'li', function(){
        $(this).find("span").toggle();

        if ( $(this).find("span").is(':visible') )
            $('#maintable td:nth-child('+$(this).attr("data-value")+'), th:nth-child('+$(this).attr("data-value")+')').show();
        else
            $('#maintable td:nth-child('+$(this).attr("data-value")+'), th:nth-child('+$(this).attr("data-value")+')').hide();

        saveColumnState($(this).attr("data-value"), $(this).find("span").is(':visible'));
    });


    $("#txtSearch").keypress(function(e) {
        if(e.which == 13) {
            saveListState(0);
            loadData(0);
        }
    });


    $("#searchclear").click(function(){
        $("#txtSearch").val('');
        saveListState();
    });


    $("#btnSearch").click(function(){
        saveListState(0);
        loadData(0);
    });


    $("#btnAdvSearch").click(function(){
        saveListState(0);
        loadData(0);
    });


    $("#btnAdvClear").click(function(event){
        $("#txtName").val('');

        saveListState();
    });


    $("#btnAdvCancel").click(function(event){
        $('#pnlAdvSearch').collapse('toggle');

        $("#txtName").val('');

        saveListState(0, null, null, null, null, null, false);

        loadData(0);
    });


    $("#searchColumns").on('click', 'li', function(){
        $("#searchColumns li.active").removeClass('active');
        $(this).closest('li').addClass('active');

        saveListState(0);
        loadData(0);
    });


    $("#tdOrderColumns").on('click', 'th', function(){
        if ($("#maintable").find('.order').parent().attr("data-value") == $(this).attr("data-value"))
        {
            if ($(this).find('.order').hasClass('dropdown'))
                $(this).find('.order').removeClass('dropdown').addClass('dropup');
            else
                $(this).find('.order').removeClass('dropup').addClass('dropdown');
        }
        else
        {
            $("#maintable").find('.order').parent().find('span').remove();
            $(this).append('<span class="order dropdown"><span style="margin: 10px 5px;" class="caret">');
        }

        saveListState(0);
        loadData(0);
    });


    $('#pagination').bootpag({
        total: 0
    }).on("page", function(event, num){
        saveListState(num);
        loadData(num);
    });


    $("#btnReturn").click(function(){
        window.location.href = "../";
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

///= Start Here ========================================================================================================

    spinner.spin( document.getElementById('preview') );

    initialization();

    setTimeout(function(){
        spinner.stop();
    }, 250);

});
</script>

<style>
    body {
        padding: 0px;
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
                <li><a href="../"><?php echo $packages["admin"]["title"] ?></a></li>
                <li><a href="."><?php echo $packages[$package]["title"] ?></a></li>
                <li class="active">Λίστα</li>
            </ol>
        </div>

        <div class="row" style="display: block">
            <div class="alert alert-info" id="events-result">&nbsp;</div>
        </div>

        <div class="row">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <!--Αναζήτηση-->
                    <div class="navbar-form navbar-right">
                        <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                        <button type="button" id="btnCreate" class="btn btn-default glyphicon glyphicon-plus"></button>
                        <?php } ?>
                        <button type="button" id="btnRefresh" class="btn btn-default glyphicon glyphicon-refresh"></button>
                        <button type="button" id="btnFilters" class="btn btn-default glyphicon glyphicon-filter"></button>
                        <button type="button" id="btnPrint" class="btn btn-default glyphicon glyphicon-print"></button>
                        <button type="button" id="btnExport" class="btn btn-default glyphicon glyphicon-export"></button>
                        <!--Καθορισμώς εγγραφών ανα σελίδα-->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span id="page-records-value">Όλες </span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="page-records" role="menu">
                                <li class="active" data-value="all"><a href="#">Όλες</a> </li>
                            </ul>
                        </div>
                        <!--Καθορισμώς ορατών στηλων-->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-th icon-th"></i> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="tableColumns" role="menu" style="max-height: 300px; overflow-y: auto; overflow-x: hidden;">
                            <?php foreach ($columns as $id => $column) { ?>
                                <li data-value="<?php echo $id ?>"><a href="#"><span class="glyphicon glyphicon-ok" style="display:<?php echo (!in_array("visible", $column) ? 'none' : 'inline-block') ?>;"></span> <?php echo $column["title"] ?></a></li>
                            <?php } ?>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <input type="search" name="txtSearch" id="txtSearch" value="<?php echo $_SESSION["states"][$package]["txtSearch"]; ?>" class="form-control" placeholder="">
                            <span id="searchclear" class="glyphicon glyphicon-remove-circle"></span>
                        </div>

                        <div class="btn-group">
                            <button type="button" id="btnSearch" name="btnSearch" class="btn btn-default">Αναζήτηση</button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="searchColumns" role="menu" style="max-height: 300px; overflow-y: auto; overflow-x: hidden;">
                            <?php foreach ($columns as $id => $column) { ?>
                                <li data-value="<?php echo $column["name"] ?>"><a href="#"><?php echo $column["title"] ?></a></li>
                            <?php } ?>
                                <li class="divider" ></li>
                                <li data-value="all" class="active"><a href="#">Όλα τα πεδία</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </nav>
        </div>


        <div class="row">
            <div class="panel panel-default">
                <div id="pnlAdvSearch" class="panel-collapse collapse">
                    <div class="panel-body">
                        <form class="form-horizontal col-sm-12" id="frm" role="form">
                            <fieldset>

                                <div class="form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="txtName" id="txtName" placeholder="Όνομα">
                                    </div>
                                    <div class="col-sm-2"></div>
                                </div>

                                <div class="row"><br></div>

                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-8">
                                        <div class="btn-group btn-group-justified">
                                            <div class="btn-group">
                                                <button type="button" id="btnAdvSearch" class="btn btn-primary btn-block">Αναζήτηση</button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" id="btnAdvClear" class="btn btn-default btn-block">Καθαρισμός</button>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" id="btnAdvCancel" class="btn btn-default btn-block">Ακύρωση</button>
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


        <div class="row">
            <div class="panel panel-default">
                <table class="table table-hover table-striped table-bordered" id="maintable">
                    <thead>
                        <tr style="height: 40px; vertical-align: middle" id="tdOrderColumns">
                            <th style="width: 50px">#</th>

                            <?php foreach ($columns as $id => $column) { ?>
                            <th style="<?php echo (in_array("cursor", $column) ? 'cursor: pointer;' : '') ?> <?php echo (!in_array("visible", $column) ? 'display:none;' : '') ?>" data-value="<?php echo $column["name"] ?>" nowrap><?php echo $column["title"] ?> <?php echo ($column["name"] == $orderBy ? '<span class="order '.$orderType.'"><span style="margin: 10px 5px;" class="caret"></span></span>' : '') ?></th>
                            <?php } ?>
                            <th style="width: 50px" colspan="2">Ενέργειες</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="btn-toolbar" role="toolbar" style="margin: 0;">
                <div class="btn-group pull-left" id="pagination-values" name="pagination-values">Εμφάνιση 0 έως 0 από 0</div>
                <div class="btn-group pull-right">
                    <ul class="pagination bootpag" id="pagination" name="pagination"></ul>
                </div>
            </div>
        </div>

        <div class="row"><br></div>

        <div class="row">
            <div class="btn-group-justified">
                <div class="btn-group">
                    <button type="button" id="btnReturn" class="btn btn-default btn-lg btn-block">Επιστροφή</button>
                </div>
            </div>
        </div>

        <div class="row"><br><br><br><br></div>

    </div>

</div>

<?php include "../../toolbars/navbar-bottom.php" ?>

</body>
</html>