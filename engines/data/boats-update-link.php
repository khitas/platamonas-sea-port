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
        $boat_id = trim($_POST["spBoat"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός της Μηχανής είναι λάθος", 500);  }
        if ( ( ! ctype_digit($boat_id) ) || ($boat_id == 0) ) { throw new Exception("Πρέπει να επιλέξετε Σκάφος", 500);  }

        $sql = "UPDATE boat_engines SET
                engine_id = ".$db->quote($id).",
                boat_id = ".$db->quote($boat_id)."
                WHERE engine_id = ".$db->quote($id)." AND boat_id = ".$db->quote($boat_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );
//        $id = $db->lastInsertId();

        $result["status"] = 200;
        $result["message"] = "Η σύνδεση ενημερώθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $id.":".$boat_id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>