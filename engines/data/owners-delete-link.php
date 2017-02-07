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
        $owner_id = trim($_POST["owner_id"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός της Μηχανής είναι λάθος", 500);  }
        if (( ! ctype_digit($owner_id) ) || ($owner_id == 0) ) { throw new Exception("Ο Κωδικός του Ιδιοκτήτη είναι λάθος", 500);  }

        $sql = "DELETE FROM owner_engines
                WHERE engine_id = ".$db->quote($id)." AND owner_id = ".$db->quote($owner_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Η σύνδεση διαγράφηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
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