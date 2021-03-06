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
        $owner_image_id = trim($_POST["owner_image_id"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if (( ! ctype_digit($owner_image_id) ) || ($owner_image_id == 0) ) { throw new Exception("Πρέπει να επιλέξετε μια Εικόνα", 500);  }

        $sql = "SELECT name FROM owner_images
                WHERE owner_image_id = ".$db->quote($owner_image_id);
        //echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "DELETE FROM owner_images
                WHERE owner_image_id = ".$db->quote($owner_image_id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        unlink($row["name"]);

        $result["status"] = 200;
        $result["message"] = "Η Φωτογραφία διαγράφηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
        $result["id"] = $owner_image_id;
    }
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>