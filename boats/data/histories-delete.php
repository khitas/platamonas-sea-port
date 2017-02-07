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
        $boat_history_id = trim($_POST["boat_history_id"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if (( ! ctype_digit($boat_history_id) ) || ($boat_history_id == 0) ) { throw new Exception("Πρέπει να επιλέξετε μια ενέργεια από το Ιστορικό ", 500);  }

        $sql = "DELETE FROM boat_histories
                WHERE boat_history_id = ".$db->quote($boat_history_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        unlink($row["name"]);

        $result["status"] = 200;
        $result["message"] = "Η Ενέργεια διαγράφηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $boat_history_id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>