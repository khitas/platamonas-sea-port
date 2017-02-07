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

        if ($_POST["frmComments"])
        {
            $split_parameters = explode('&', $_POST["frmComments"]);

            for($i = 0; $i < count($split_parameters); $i++) {
                $final_split = explode('=', $split_parameters[$i]);
                $_POST[$final_split[0]] = urldecode($final_split[1]);
            }

            unset($_POST["frmComments"]);
        }

        $id = $_POST["id"];
        $serial_number = trim($_POST["txtSerialNumber"]);
        $engine_kind_id = trim($_POST["spEngineKind"]);
        $engine_type_id = trim($_POST["spEngineType"]);
        $power = trim($_POST["txtPower"]);
        $engine_power_type_id = trim($_POST["spEnginePowerType"]);
        $engine_brand_id = trim($_POST["spEngineBrand"]);
        $engine_status_id = trim($_POST["spEngineStatus"]);
        $comments = trim($_POST["txtComments"]);


        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός της Μηχανής είναι λάθος", 500);  }
        if ( $serial_number == "" ) { throw new Exception("O Σειριακός Αριθμός πρέπει να έχει τιμή", 500);  }

        $sql = "UPDATE engines SET
                    serial_number = ".$db->quote($serial_number).",
                    engine_kind_id = ".($engine_kind_id ? $db->quote($engine_kind_id) : "NULL").",
                    engine_type_id = ".($engine_type_id ? $db->quote($engine_type_id) : "NULL").",
                    power = ".$db->quote($power).",
                    engine_power_type_id = ".($engine_power_type_id ? $db->quote($engine_power_type_id) : "NULL").",
                    engine_brand_id = ".($engine_brand_id ? $db->quote($engine_brand_id) : "NULL").",
                    engine_status_id = ".($engine_status_id ? $db->quote($engine_status_id) : "NULL").",
                    comments = ".$db->quote($comments)."
                WHERE engine_id = ".$db->quote($id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Η Μηχανή ενημερώθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>