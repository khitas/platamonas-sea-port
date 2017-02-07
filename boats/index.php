<?php
    include "info.php";

    include "../includes/settings.php";
    include "../includes/authentication.php";

    $columns = array(
        "2" => array("name"=>"boat_id", "title"=>"Κωδικός", "cursor", "visible"),
        "3" => array("name"=>"name", "title"=>"Όνομα", "cursor", "visible"),
        "4" => array("name"=>"boat_kind", "title"=>"Είδος", "cursor"),
        "5" => array("name"=>"boat_port", "title"=>"Λιμένας", "cursor"),
        "6" => array("name"=>"registry_type", "title"=>"Τύπος Εγγραφής", "cursor"),
        "7" => array("name"=>"registry_number", "title"=>"Αρ. Εγγραφής", "cursor"),
        "8" => array("name"=>"registry_type_number", "title"=>"Αριθμός Εγγραφής", "cursor", "visible"),
        "9" => array("name"=>"registry_date_gr", "title"=>"Ημ. Εγγραφής", "cursor"),
        "10" => array("name"=>"amyen_type", "title"=>"Τύπος Α.Μ.Υ.Ε.Ν.", "cursor"),
        "11" => array("name"=>"amyen_number", "title"=>"Αρ. Α.Μ.Υ.Ε.Ν.", "cursor"),
        "12" => array("name"=>"amyen_type_number", "title"=>"Αριθμός Α.Μ.Υ.Ε.Ν.", "cursor", "visible"),
        "13" => array("name"=>"dds", "title"=>"Δ.Δ.Σ.", "cursor"),
        "14" => array("name"=>"dsp", "title"=>"Δ.Σ.Π.", "cursor"),
        "15" => array("name"=>"boat_material", "title"=>"Υλικό Κατασκευής", "cursor"),
        "16" => array("name"=>"boat_color", "title"=>"Χρώμα", "cursor"),
        "17" => array("name"=>"boat_type", "title"=>"Τύπος", "cursor"),
        "18" => array("name"=>"movement_type", "title"=>"Κίνηση", "cursor"),
        "19" => array("name"=>"length", "title"=>"Μήκος", "cursor"),
        "20" => array("name"=>"width", "title"=>"Πλάτος", "cursor"),
        "21" => array("name"=>"height", "title"=>"Ύψος", "cursor"),
        "22" => array("name"=>"builder", "title"=>"Κατασκευαστής", "cursor"),
        "23" => array("name"=>"boat_status", "title"=>"Κατάσταση", "cursor"),
        "24" => array("name"=>"license_expired_date_gr", "title"=>"Α.Ε.Π.", "cursor", "visible" ),
        "25" => array("name"=>"count_engines", "title"=>"Μηχανές", "cursor", "visible" ),
        "26" => array("name"=>"count_owners", "title"=>"Ιδιοκτήτες", "cursor", "visible" ),
        "27" => array("name"=>"is_fast", "title"=>"Ταχύπλοο", "cursor", "boolean"),
        "28" => array("name"=>"comments", "title"=>"Σχόλια", "cursor"),
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

    <script src="../libs/jquery/jquery-2.1.0.js"></script>

    <link href="../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="../libs/bootbox/bootbox.js"></script>

    <link href="../libs/searchclear/searchclear.css" rel="stylesheet">

    <link href="../libs/totop/css/ui.totop.css" rel="stylesheet">
    <script src="../libs/totop/js/easing.js"></script>
    <script src="../libs/totop/js/jquery.ui.totop.js"></script>

    <script src="../libs/pagination/lib/jquery.bootpag.js"></script>

    <link href="../libs/select/bootstrap-select.css" rel="stylesheet">
    <script src="../libs/select/bootstrap-select.js"></script>

    <script src="../libs/datetimepicker/moment.js"></script>
    <link href="../libs/datetimepicker/datetimepicker.css" rel="stylesheet">
    <script src="../libs/datetimepicker/datetimepicker.js"></script>

    <script src="../ajax/functions.js"></script>

    <script src="../libs/spin/spin.js"></script>
    <script src="../libs/spin/loader.js"></script>

</head>
<script>
$(document).ready(function(){

////= Objects ==========================================================================================================

    $('#spBoatPort').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Λιμένας' });
    $('#spRegistryType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Τύπος Εγγραφής' });
    $('#spAmyenType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Τύπος Α.Μ.Υ.Ε.Ν.' });
    $('#spBoatMaterial').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Υλικό Κατασκευής' });
    $('#spBoatColor').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Χρώμα' });
    $('#spBoatType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Τύπος' });
    $('#spMovementType').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Κίνηση' });
    $('#spBoatStatus').selectpicker({ size: '3', width:'100%', noneSelectedText : 'Κατάσταση' });
    $('#spBoatKind').selectpicker({ size: 'auto', width:'100%', noneSelectedText : 'Είδος' });

    $('#dpRegistryDate').datetimepicker({ pickTime: false });
    $('#dpLicenseExpiredDate').datetimepicker({ pickTime: false });


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
            type: 'POST',
            url: '../ajax/save-list-state.php',
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
            type: 'POST',
            url: '../ajax/save-column-state.php',
            data: {package:'<?php echo $package ?>', column: column, visible: visible}
        });
    }


    function loadData(pagination, pageRecords, searchText, searchColumn, orderBy, orderType, advSearch)
    {
        $.ajax({
            type: 'POST',
            url: 'data/list.php',
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
                    if (data.rows.length > 0)
                    {
                        $.each(data.rows, function(id, row)
                        {
                            var btnPrintInfos = '<td style="width: 80px"><button type="button" value="' + row.boat_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-print"></button></td>';
                            var $btnPrintInfos = $(btnPrintInfos).click(function() {
                                bootbox.prompt({
                                    message : "Εκτύπωση",
                                    title    : "Εκτύπωση",
                                    inputType : 'select',
                                    value : 'PrintInfos',
                                    inputOptions : [
                                        { text : 'Στοιχεία Σκάφους', value: 'PrintInfos', name: 'PrintInfos'},
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
                                                var data = {id:row.boat_id, print:option};

                                                var str = Object.keys(data).map(function(key){
                                                    return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                                                }).join('&');

                                                window.open('print/infos.php?' + str,'_blank');
                                                break;
                                            case "PrintExtends" :
                                                var data = {id:row.boat_id, print:option};

                                                var str = Object.keys(data).map(function(key){
                                                    return encodeURIComponent(key) + '=' + encodeURIComponent(data[key]);
                                                }).join('&');

                                                window.open('print/extends.php?' + str,'_blank');
                                                break;
                                        }
                                    }
                                });

                            });

                            var btnEdit = '<td style="width: 80px"><button type="button" value="' + row.boat_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-<?php echo (in_array($User["permission_id"], array(1, 2)) ? "pencil" : "info-sign") ?>"></button></td>';
                            var $btnEdit = $(btnEdit).click(function() {
                                window.location.href = "update/?id=" + row.boat_id;
                            });

                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            var btnDelete = '<td style="width: 80px"><button type="button" value="' + row.boat_id + '" class="btn btn-default btn-lg btn-block glyphicon glyphicon-trash"></button></td>';
                            var $btnDelete = $(btnDelete).click(function() {
                                bootbox.dialog({
                                    message: 'Διαγραφή του Σκάφους "'+row.boat_id+' : '+row.name+'";',
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
                                                    data: {id:row.boat_id},
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
                            $rowdata.append($btnPrintInfos);
                            $rowdata.append($btnEdit);
                            <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                            $rowdata.append($btnDelete);
                            <?php } ?>

                            $rowdata.dblclick(function() {
                                window.location.href = "update/?id=" + row.boat_id;
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


    function initialization()
    {
        $.ajax({
            url: '../admin/page-records/data/list.php',
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

                    $("#txtName").val('<?php echo $advSearch["txtName"] ?>');
                    $('#spBoatKind').selectpicker('val', '<?php echo $advSearch["spBoatKind"]?:0 ?>');
                    $('#dpRegistryDate').data("DateTimePicker").setDate('<?php echo $advSearch["txtRegistryDate"] ?>');
                    $('#spBoatPort').selectpicker('val', '<?php echo $advSearch["spBoatPort"]?:0 ?>');
                    $('#spRegistryType').selectpicker('val', '<?php echo $advSearch["spRegistryType"]?:0 ?>');
                    $("#txtRegistryNumber").val('<?php echo $advSearch["txtRegistryNumber"] ?>');
                    $("#txtDDS").val('<?php echo $advSearch["txtDDS"] ?>');
                    $('#spAmyenType').selectpicker('val', '<?php echo $advSearch["spAmyenType"]?:0 ?>');
                    $("#txtAmyenNumber").val('<?php echo $advSearch["txtAmyenNumber"] ?>');
                    $("#txtDSP").val('<?php echo $advSearch["txtDSP"] ?>');
                    $('#spBoatMaterial').selectpicker('val', '<?php echo $advSearch["spBoatMaterial"]?:0 ?>');
                    $('#spBoatColor').selectpicker('val', '<?php echo $advSearch["spBoatColor"]?:0 ?>');
                    $('#spBoatType').selectpicker('val', '<?php echo $advSearch["spBoatType"]?:0 ?>');
                    $('#spMovementType').selectpicker('val', '<?php echo $advSearch["spMovementType"]?:0 ?>');
                    $("#txtLength").val('<?php echo $advSearch["txtLength"] ?>');
                    $("#txtWidth").val('<?php echo $advSearch["txtWidth"] ?>');
                    $("#txtHeight").val('<?php echo $advSearch["txtHeight"] ?>');
                    $("#txtBuilder").val('<?php echo $advSearch["txtBuilder"] ?>');
                    $('input:radio[name=cbIsFast][value=<?php echo $advSearch["cbIsFast"] ?>]').click();
                    $('#spMovementType').selectpicker('val', '<?php echo $advSearch["spMovementType"]?:0 ?>');
                    $('#spBoatStatus').selectpicker('val', '<?php echo $advSearch["spBoatStatus"]?:0 ?>');
                    $('#dpLicenseExpiredDate').data("DateTimePicker").setDate('<?php echo $advSearch["txtLicenseExpiredDate"] ?>');
                    $("#txtComments").val('<?php echo $advSearch["txtComments"] ?>');

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


    function loadBoatKinds($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["boat-kinds"]["parent"].$packages["boat-kinds"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                $selected = ($selected ? $selected : 0);

                if (data.status == 200)
                {
                    $('#spBoatKind').empty();

                    $('#spBoatKind').append( '<option data-divider="true"></option>');
                    $('#spBoatKind').append( '<option value="0">Είδος</option>');
                    $('#spBoatKind').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatKind').append( '<option value="'+row.boat_kind_id+'">'+row.name+'</option>');

                        if (row.boat_kind_id == $selected)
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

    function loadBoatPorts($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["boat-ports"]["parent"].$packages["boat-ports"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatPort').empty();

                    $('#spBoatPort').append( '<option data-divider="true"></option>');
                    $('#spBoatPort').append( '<option value="0">Λιμένας</option>');
                    $('#spBoatPort').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatPort').append( '<option value="'+row.boat_port_id+'">'+row.name+'</option>');

                        if (row.boat_port_id == $selected)
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

    function loadRegistryTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["registry-types"]["parent"].$packages["registry-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spRegistryType').empty();

                    $('#spRegistryType').append( '<option data-divider="true"></option>');
                    $('#spRegistryType').append( '<option value="0">Τύπος Εγγραφής</option>');
                    $('#spRegistryType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spRegistryType').append( '<option value="'+row.registry_type_id+'">'+row.name+'</option>');

                        if (row.registry_type_id == $selected)
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

    function loadAmyenTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["amyen-types"]["parent"].$packages["amyen-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spAmyenType').empty();

                    $('#spAmyenType').append( '<option data-divider="true"></option>');
                    $('#spAmyenType').append( '<option value="0">Τύπος Α.Μ.Υ.Ε.Ν.</option>');
                    $('#spAmyenType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spAmyenType').append( '<option value="'+row.amyen_type_id+'">'+row.name+'</option>');

                        if (row.amyen_type_id == $selected)
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

    function loadBoatMaterials($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["boat-materials"]["parent"].$packages["boat-materials"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatMaterial').empty();

                    $('#spBoatMaterial').append( '<option data-divider="true"></option>');
                    $('#spBoatMaterial').append( '<option value="0">Υλικό Κατασκευής</option>');
                    $('#spBoatMaterial').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatMaterial').append( '<option value="'+row.boat_material_id+'">'+row.name+'</option>');

                        if (row.boat_material_id == $selected)
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

    function loadBoatColors($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["boat-colors"]["parent"].$packages["boat-colors"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatColor').empty();

                    $('#spBoatColor').append( '<option data-divider="true"></option>');
                    $('#spBoatColor').append( '<option value="0">Χρώμα</option>');
                    $('#spBoatColor').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatColor').append( '<option value="'+row.boat_color_id+'">'+row.name+'</option>');

                        if (row.boat_color_id == $selected)
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

    function loadBoatTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["boat-types"]["parent"].$packages["boat-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatType').empty();

                    $('#spBoatType').append( '<option data-divider="true"></option>');
                    $('#spBoatType').append( '<option value="0">Τύπος</option>');
                    $('#spBoatType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatType').append( '<option value="'+row.boat_type_id+'">'+row.name+'</option>');

                        if (row.boat_type_id == $selected)
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

    function loadMovementTypes($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["movement-types"]["parent"].$packages["movement-types"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spMovementType').empty();

                    $('#spMovementType').append( '<option data-divider="true"></option>');
                    $('#spMovementType').append( '<option value="0">Κίνηση</option>');
                    $('#spMovementType').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spMovementType').append( '<option value="'+row.movement_type_id+'">'+row.name+'</option>');

                        if (row.movement_type_id == $selected)
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

    function loadBoatStatus($selected)
    {
        $.ajax({
            type: "POST",
            url: "../<?php echo $packages["boat-status"]["parent"].$packages["boat-status"]["path"] ?>data/list.php",
            async: false,
            data: {},
            success: function(response){
                var data = JSON.parse(response);

                if (data.status == 200)
                {
                    $('#spBoatStatus').empty();

                    $('#spBoatStatus').append( '<option data-divider="true"></option>');
                    $('#spBoatStatus').append( '<option value="0">Κατάσταση</option>');
                    $('#spBoatStatus').append( '<option data-divider="true"></option>');

                    $.each(data.rows, function(id, row)
                    {
                        $('#spBoatStatus').append( '<option value="'+row.boat_status_id+'">'+row.name+'</option>');

                        if (row.boat_status_id == $selected)
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
                            sortby: $("#maintable").find('.order').parent().attr("data-value"),
                            sorttype: $("#maintable").find('.order')[0].className.split(' ')[1]
                        }
                        window.open('export/?' + str,'_blank');
                        break;
                    case "ExportSearch" :
                        var data = {
                            advSearch:$('#frm').serialize(),
                            orderBy: $("#maintable").find('.order').parent().attr("data-value"),
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1]
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
                            orderType: $("#maintable").find('.order')[0].className.split(' ')[1]
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
        $('#spBoatKind').selectpicker('val',0);
        $('#dpRegistryDate').data("DateTimePicker").setDate('');
        $('#spBoatPort').selectpicker('val', 0);
        $('#spRegistryType').selectpicker('val', 0);
        $("#txtRegistryNumber").val('');
        $("#txtDDS").val('');
        $('#spAmyenType').selectpicker('val', 0);
        $("#txtAmyenNumber").val('');
        $("#txtDSP").val('');
        $('#spBoatMaterial').selectpicker('val', 0);
        $('#spBoatColor').selectpicker('val', 0);
        $('#spBoatType').selectpicker('val', 0);
        $('#spMovementType').selectpicker('val', 0);
        $("#txtLength").val('');
        $("#txtWidth").val('');
        $("#txtHeight").val('');
        $("#txtBuilder").val('');
        $('input:radio[name=cbIsFast][value=none]').click();
        $('#spMovementType').selectpicker('val', 0);
        $('#spBoatStatus').selectpicker('val', 0);
        $('#dpLicenseExpiredDate').data("DateTimePicker").setDate('');
        $("#txtComments").val('');

        saveListState();
    });


    $("#btnAdvCancel").click(function(event){
        $('#pnlAdvSearch').collapse('toggle');

        $("#txtName").val('');
        $('#spBoatKind').selectpicker('val',0);
        $('#dpRegistryDate').data("DateTimePicker").setDate('');
        $('#spBoatPort').selectpicker('val', 0);
        $('#spRegistryType').selectpicker('val', 0);
        $("#txtRegistryNumber").val('');
        $("#txtDDS").val('');
        $('#spAmyenType').selectpicker('val', 0);
        $("#txtAmyenNumber").val('');
        $("#txtDSP").val('');
        $('#spBoatMaterial').selectpicker('val', 0);
        $('#spBoatColor').selectpicker('val', 0);
        $('#spBoatType').selectpicker('val', 0);
        $('#spMovementType').selectpicker('val', 0);
        $("#txtLength").val('');
        $("#txtWidth").val('');
        $("#txtHeight").val('');
        $("#txtBuilder").val('');
        $('input:radio[name=cbIsFast][value=none]').click();
        $('#spMovementType').selectpicker('val', 0);
        $('#spBoatStatus').selectpicker('val', 0);
        $('#dpLicenseExpiredDate').data("DateTimePicker").setDate('');
        $("#txtComments").val('');

        saveListState(0, null, null, null, null, null, false);

        loadData(0);
    });


    $("#searchColumns").on('click', 'li', function(){
        $("#searchColumns li.active").removeClass('active');
        $(this).closest('li').addClass('active');

        saveListState(0);
        loadData(0);
    });


    $("#btnBoatKind").click(function(event){
        loadBoatKinds( $('#spBoatKind').selectpicker('val') )
    });

    $("#btnRegistryType").click(function(event){
        loadRegistryTypes( $('#spRegistryType').selectpicker('val') )
    });

    $("#btnBoatPort").click(function(event){
        loadBoatPorts( $('#spBoatPort').selectpicker('val') )
    });

    $("#btnBoatMaterial").click(function(event){
        loadBoatMaterials( $('#spBoatMaterial').selectpicker('val') )
    });

    $("#btnAmyenType").click(function(event){
        loadAmyenTypes( $('#spAmyenType').selectpicker('val') )
    });

    $("#btnBoatColor").click(function(event){
        loadBoatColors( $('#spBoatColor').selectpicker('val') )
    });

    $("#btnBoatType").click(function(event){
        loadBoatTypes( $('#spBoatType').selectpicker('val') )
    });

    $("#btnMovementType").click(function(event){
        loadMovementTypes( $('#spMovementType').selectpicker('val') )
    });

    $("#btnBoatStatus").click(function(event){
        loadBoatStatus( $('#spBoatStatus').selectpicker('val') )
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

    loadBoatKinds();
    loadBoatPorts();
    loadRegistryTypes();
    loadAmyenTypes();
    loadBoatMaterials();
    loadBoatColors();
    loadBoatTypes();
    loadMovementTypes();
    loadBoatStatus();

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

<?php include "../toolbars/navbar-top.php" ?>

<div class="container">

    <div id="preview"></div>

    <div role="main" class="col-md-12">

        <div class="row"><br><br><br><br></div>

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="../"><span class="glyphicon glyphicon-home"></span></a></li>
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
            <div class="panel-group">
                <div class="panel panel-default">
                    <div id="pnlAdvSearch" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="form-horizontal col-sm-12" id="frm" role="form">
                                <fieldset>

                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtName" id="txtName" placeholder="Όνομα">
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spBoatKind" name="spBoatKind" class="selectpicker" data-live-search="true"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnBoatKind" name="btnBoatKind" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtDDS" id="txtDDS" placeholder="Δ.Δ.Σ.">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spRegistryType" name="spRegistryType" class="selectpicker" data-live-search="true"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnRegistryType" name="btnRegistryType" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtRegistryNumber" id="txtRegistryNumber" placeholder="Αριθμός Εγγραφής">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtDSP" id="txtDSP" placeholder="Δ.Σ.Π.">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spBoatPort" name="spBoatPort" class="selectpicker" data-live-search="true"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnBoatPort" name="btnBoatPort" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class='input-group date' id='dpRegistryDate' data-date-format="DD/MM/YYYY">
                                                <input type='text' name="txtRegistryDate" id="txtRegistryDate" class="form-control" placeholder="Ημ. Εγγραφής">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spBoatMaterial" name="spBoatMaterial" class="selectpicker" data-live-search="false"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnBoatMaterial" name="btnBoatMaterial" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spAmyenType" name="spAmyenType" class="selectpicker" data-live-search="false"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnAmyenType" name="btnAmyenType" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtAmyenNumber" id="txtAmyenNumber" placeholder="Α.Μ.Υ.Ε.Ν.">
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spBoatColor" name="spBoatColor" class="selectpicker" data-live-search="false"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnBoatColor" name="btnBoatColor" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spBoatType" name="spBoatType" class="selectpicker" data-live-search="false"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnBoatType" name="btnBoatType" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spMovementType" name="spMovementType" class="selectpicker" data-live-search="false"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnMovementType" name="btnMovementType" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                <label class="btn btn-default">
                                                    <input type="radio" id="cbIsFast" name="cbIsFast" value="none" checked> Ταχύπλοο
                                                </label>
                                                <label class="btn btn-default">
                                                    <input type="radio" id="cbIsFast" name="cbIsFast" value="on"> ΝΑΙ
                                                </label>
                                                <label class="btn btn-default">
                                                    <input type="radio" id="cbIsFast" name="cbIsFast" value="off"> ΟΧΙ
                                                </label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtBuilder" id="txtBuilder" placeholder="Κατασκευαστής">
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select id="spBoatStatus" name="spBoatStatus" class="selectpicker" data-live-search="false"></select>
                                                <span class="input-group-btn">
                                                    <button id="btnBoatStatus" name="btnBoatStatus" class="btn btn-default " type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class='input-group date' id='dpLicenseExpiredDate' data-date-format="DD/MM/YYYY">
                                                <input type='text' name="txtLicenseExpiredDate" id="txtLicenseExpiredDate" class="form-control" placeholder="Α.Ε.Π.">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtLength" id="txtLength" placeholder="Μήκος">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtWidth" id="txtWidth" placeholder="Πλάτος">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="txtHeight" id="txtHeight" placeholder="Ύψος">
                                        </div>
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
                            <th style="width: 50px" colspan="3">Ενέργειες</th>
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

<?php include "../toolbars/navbar-bottom.php" ?>

</body>
</html>