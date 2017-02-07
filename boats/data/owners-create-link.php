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
        $id = $_POST["id"];
        $owner_id = trim($_POST["spOwner"]);
        $owner_status_id = trim($_POST["spOwnerStatus"]);
        $get_date = trim($_POST["txtGetDate"]);
        $percent = trim($_POST["txtPercent"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if ( ( ! ctype_digit($owner_id) ) || ($owner_id == 0) ) { throw new Exception("Πρέπει να επιλέξετε Ιδιοκτήτη", 500);  }

        if ($get_date)
        {
            list($day, $month, $year) = explode('/', $get_date);
            $get_date = $year."/".$month."/".$day;
        }

        $sql = "INSERT INTO boat_owners SET
                boat_id = ".$db->quote($id).",
                get_date = ".($get_date ? $db->quote($get_date) : "NULL").",
                percent = ".($percent ? $db->quote($percent) : "NULL").",
                owner_status_id = ".($owner_status_id ? $db->quote($owner_status_id) : "NULL").",
                owner_id = ".$db->quote($owner_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );
//        $id = $db->lastInsertId();

        $result["status"] = 200;
        $result["message"] = "Ο Ιδιοκτήτης συνδέθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $id.":".$owner_id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>