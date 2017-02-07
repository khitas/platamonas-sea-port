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
        $engine_id = trim($_POST["spEngine"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if ( ( ! ctype_digit($engine_id) ) || ($engine_id == 0) ) { throw new Exception("Πρέπει να επιλέξετε Μηχανή", 500);  }

        $sql = "INSERT INTO owner_engines SET
                owner_id = ".$db->quote($id).",
                engine_id = ".$db->quote($engine_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );
//        $id = $db->lastInsertId();

        $result["status"] = 200;
        $result["message"] = "Η Μηχανή συνδέθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $id.":".$engine_id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>