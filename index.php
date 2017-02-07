<?php
    include "info.php";

    include "includes/settings.php";
    include "includes/authentication.php";
?>
<!DOCTYPE html>
<html lang="gr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $AppVars["AppName"]; ?></title>

    <script src="libs/jquery/jquery-2.1.0.js"></script>

    <link rel="stylesheet" href="libs/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="libs/bootstrap/dist/css/bootstrap-theme.css">
    <script src="libs/bootstrap/dist/js/bootstrap.js"></script>

    <script src="libs/bootbox/bootbox.js"></script>

    <script src="libs/spin/spin.js"></script>
    <script src="libs/spin/loader.js"></script>

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

    ///= Start Here ========================================================================================================

    spinner.spin( document.getElementById('preview') );

    setTimeout(function(){
        spinner.stop();
    }, 250);

});
</script>

<style>
    body {
        padding: 0px;
        /*background-color: #eee;*/
    }
</style>

<body>

<?php include "toolbars/navbar-top.php" ?>

<div class="container">

    <div id="preview"></div>

    <div role="main" class="col-md-12">

        <div class="row"><br><br><br></div>

        <div class="row">
            <h2 class="page-header"><?php echo $packages["home"]["title"] ?></h2>
<!--            <p class="lead" id="lblTitle">Επιλέξτε μία από τις παρακάτω κατηγορίες</p>-->
        </div>

        <div class="row">
            <div class="list-group">
<?php
    $sql = "SELECT count(*) as total FROM boats";
    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
                <a href="<?php echo $packages["boats"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["boats"]["title"] ?></h4>
                    <p class="list-group-item-text">Βρέθηκαν <?php echo $row["total"] ?> <?php echo $packages["boats"]["title"] ?></p>
                </a>

<?php
    $sql = "SELECT count(*) as total FROM owners";
    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
                <a href="<?php echo $packages["owners"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["owners"]["title"] ?></h4>
                    <p class="list-group-item-text">Βρέθηκαν <?php echo $row["total"] ?> <?php echo $packages["owners"]["title"] ?></p>
                </a>

                <?php
                $sql = "SELECT count(*) as total FROM engines";
                $stmt = $db->query( $sql );
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["engines"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["engines"]["title"] ?></h4>
                    <p class="list-group-item-text">Βρέθηκαν <?php echo $row["total"] ?> <?php echo $packages["engines"]["title"] ?></p>
                </a>

            </div>
        </div>

        <div class="row">
            <div class="list-group">

                <a href="<?php echo $packages["admin"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["admin"]["title"] ?></h4>
                    <p class="list-group-item-text">Διαχείριση όλων των Λεξικών</p>
                </a>

                <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                <a href="<?php echo $packages["options"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["options"]["title"] ?></h4>
                    <p class="list-group-item-text">Καθορίστε τις ρυθμίσεις της εφαρμογής</p>
                </a>
                <?php } ?>

                <?php if (in_array($User["permission_id"], array(1, 2))) { ?>
                <?php
                    include "users/info.php";
                    $sql = "SELECT count(*) as total FROM users";
                    $stmt = $db->query( $sql );
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <a href="<?php echo $packages["users"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["users"]["title"] ?></h4>
                    <p class="list-group-item-text">Βρέθηκαν <?php echo $row["total"] ?> <?php echo $packages["users"]["title"] ?></p>
                </a>
                <?php } ?>

            </div>
        </div>

        <div class="row">
            <div class="list-group">
                <a href="<?php echo $packages["profile"]["path"] ?>" class="list-group-item">
                    <h4 class="list-group-item-heading"><?php echo $packages["profile"]["title"] ?></h4>
                    <p class="list-group-item-text">Διαχείριση του Προφίλ</p>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="list-group">
                <a href="logout/" class="list-group-item">
                    <h4 class="list-group-item-heading">Έξοδος</h4>
                    <p class="list-group-item-text">Έξοδος από την εφαρμογή</p>
                </a>
            </div>
        </div>

        <div class="row"><br><br><br></div>

    </div>

</div>

<?php include "toolbars/navbar-bottom.php" ?>

</body>
</html>