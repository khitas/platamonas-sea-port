<?php
try
{
    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    if (!in_array($User["permission_id"], array(1, 2)))
    {
        throw new Exception("Δεν έχετε δικαιώματα πρόσβασης στα δεδομένα", 500);
    }
    else
    {
        if ($_POST["frm"])
        {
            $split_parameters = explode('&', $_POST["frm"]);

            for($i = 0; $i < count($split_parameters); $i++) {
                $final_split = explode('=', $split_parameters[$i]);
                $_POST[$final_split[0]] = urldecode($final_split[1]);
            }

            unset($_POST["frm"]);
        }

        $option = $_POST["option"];
        $txtReportHeader = trim($_POST["txtReportHeader"]);

        $sql = "UPDATE options SET
                    option_value = ".$db->quote($txtReportHeader)."
                WHERE option_name = ".$db->quote($option);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Οι Ρυθμίσεις ενημερώθηκαν".($AppVars["debug"] ? "  SQL >> ".$sql : "");
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>