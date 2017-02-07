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

    <link href="../libs/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../libs/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet">
    <script src="../libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="../libs/bootbox/bootbox.js"></script>

    <link href="../libs/searchclear/searchclear.css" rel="stylesheet">

    <link href="../libs/totop/css/ui.totop.css" rel="stylesheet">
    <script src="../libs/totop/js/easing.js"></script>
    <script src="../libs/totop/js/jquery.ui.totop.js"></script>

    <script src="../libs/pagination/lib/jquery.bootpag.js"></script>

    <script src="../libs/spin/spin.js"></script>
    <script src="../libs/spin/loader.js"></script>
</head>

<script>
$(document).ready(function(){

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

    setTimeout(function(){
        spinner.stop();
    }, 250);
});
</script>

<style>
    body {
        padding: 0px 20px 20px 20px;
        /*background-color: #eee;*/
    }
</style>

<body>

<?php include "../toolbars/navbar-top.php" ?>

<div class="container">

    <div id="preview"></div>

    <div role="main" class="col-md-12">

        <div class="row"><br><br><br><br></div>

<!--        <div class="row">-->
<!--            <h2 class="page-header">Διαχείριση</h2>-->
<!--            <p class="lead" id="lblTitle">Επιλέξτε ένα από τα παρακάτω Λεξικά</p>-->
<!--        </div>-->

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="../"><span class="glyphicon glyphicon-home"></span></a></li>
                <li><a href=".">Διαχείριση</a></li>
                <li class="active">Λεξικά</li>
            </ol>
        </div>

        <div class="row">
            <div class="list-group">

                <?php
                $sql = "SELECT count(*) as total FROM boat_ports";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["boat-ports"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["boat-ports"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM registry_types";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["registry-types"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["registry-types"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM boat_kinds";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["boat-kinds"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["boat-kinds"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM boat_types";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["boat-types"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["boat-types"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM boat_materials";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["boat-materials"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["boat-materials"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM boat_colors";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["boat-colors"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["boat-colors"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM boat_status";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["boat-status"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["boat-status"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM amyen_types";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["amyen-types"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["amyen-types"]["title"] ?>
                </a>

            </div>
        </div>

        <div class="row">
            <div class="list-group">

                <?php
                $sql = "SELECT count(*) as total FROM engine_power_types";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["engine-power-types"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["engine-power-types"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM engine_types";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["engine-types"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["engine-types"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM movement_types";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["movement-types"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["movement-types"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM engine_kinds";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["engine-kinds"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["engine-kinds"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM engine_status";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["engine-status"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["engine-status"]["title"] ?>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM engine_brands";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["engine-brands"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["engine-brands"]["title"] ?>
                </a>

            </div>
        </div>

        <div class="row">
            <div class="list-group">

                <?php
                $sql = "SELECT count(*) as total FROM owner_status";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["owner-status"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["owner-status"]["title"] ?>
                </a>


            </div>
        </div>

        <div class="row">
            <div class="list-group">

                <?php
                $sql = "SELECT count(*) as total FROM page_records";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["page-records"]["path"] ?>" class="list-group-item">
                    <span class="badge"><?php echo $row["total"] ?></span><?php echo $packages["page-records"]["title"] ?>
                </a>

            </div>
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

</div>

<?php include "../toolbars/navbar-bottom.php" ?>

</body>
</html>