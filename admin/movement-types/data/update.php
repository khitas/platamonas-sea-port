<?php
try
{
    include "../../../includes/settings.php";
    include "../../../includes/authentication.php";

    $result = array();

    if (!in_array($User["permission_id"], array(1, 2)))
    {
        throw new Exception("Δεν έχετε δικαιώματα πρόσβασης στα δεδομένα", 500);
    }
    else
    {
        $id = $_POST["id"];
        $name = trim($_POST["txtName"]);
        $is_default = ($_POST["cbIsDefault"] == "on" ? 1 : 0);

        if ( ! ctype_digit($id) ) { throw new Exception("Ο Κωδικός είναι λάθος", 500);  }
        if ( $name == "" ) { throw new Exception("Το Όνομα πρέπει να έχει τιμή", 500);  }

        if ($is_default)
        {
            $sql = "UPDATE movement_types SET is_default = NULL";
            //echo "<br><br>".$sql."<br><br>";
            $db->query( $sql );
        }

        $sql = "UPDATE movement_types SET
                name = ".$db->quote($name).",
                is_default = ".($is_default ? $db->quote($is_default) : "NULL" )."
                WHERE movement_type_id = ".$db->quote($id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Η εγγραφή ενημερώθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
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