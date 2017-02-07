<?php
    include "../includes/settings.php";
    include "../includes/authentication.php";

    $_SESSION["states"][$_POST["package"]]["columns"][ $_POST["column"] ] = $_POST["visible"];
?>