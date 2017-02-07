<?php
    include "../includes/settings.php";
    include "../includes/authentication.php";

    if ($_POST["advSearch"])
    {
        $split_parameters = explode('&', $_POST["advSearch"]);

        $list = array();
        for($i = 0; $i < count($split_parameters); $i++) {
            $final_split = explode('=', $split_parameters[$i]);
            $list[$final_split[0]] = urldecode($final_split[1]);
        }
        unset($_POST["advSearch"]);
        $_POST["advSearch"] = $list;
    }

    $_SESSION["states"][$_POST["package"]]["columns"] = $_POST["columns"];
    $_SESSION["states"][$_POST["package"]]["advSearch"] = $_POST["advSearch"];
    $_SESSION["states"][$_POST["package"]]["openSearch"] = $_POST["openSearch"];
    $_SESSION["states"][$_POST["package"]]["orderBy"] = $_POST["orderBy"];
    $_SESSION["states"][$_POST["package"]]["orderType"] = $_POST["orderType"];
    $_SESSION["states"][$_POST["package"]]["searchText"] = $_POST["searchText"];
    $_SESSION["states"][$_POST["package"]]["searchColumn"] = $_POST["searchColumn"];
    $_SESSION["states"][$_POST["package"]]["pageRecords"] = $_POST["pageRecords"];
    $_SESSION["states"][$_POST["package"]]["pagination"] = ( ctype_digit($_POST["pagination"]) ? $_POST["pagination"] : 0);
?>