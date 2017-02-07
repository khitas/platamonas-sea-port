<?php
    include "../includes/settings.php";
    include "../includes/authentication.php";

    $package = $_POST["package"];
    $obj = $_POST["obj"];
    $item = $_POST["item"];
    $value = $_POST["value"];

    if ($item)
        $_SESSION["states"][$package][$obj][$item] = $value;
    else
        $_SESSION["states"][$package][$obj] = $value;
?>