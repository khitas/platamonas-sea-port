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
        $lastname = trim($_POST["txtLastname"]);
        $firstname = trim($_POST["txtFirstname"]);
        $username = trim($_POST["txtUsername"]);
        $password = trim($_POST["txtPassword"]);
        $repassword = trim($_POST["txtRePassword"]);
        $permission = trim($_POST["spPermission"]);


        if (( ! ctype_digit($id) ) || (! $id)) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if ( $lastname == "" ) { throw new Exception("Το Επώνυμο πρέπει να έχει τιμή", 500);  }
        if ( $firstname == "" ) { throw new Exception("Το Όνομα πρέπει να έχει τιμή", 500);  }
        if ( $username == "" ) { throw new Exception("Το Όνομα Χρήστη πρέπει να έχει τιμή", 500);  }
        if (( $password || $repassword ) && ( $password <> $repassword )) { throw new Exception("O Κωδικός δεν ταιριάζει με την Επαλήθευση", 500);  }
        if (( ! ctype_digit($permission) ) || (! $permission)) { throw new Exception("Πρέπει να επιλέξετε Δικαιώματα", 500);  }

        $sql = "UPDATE users SET
                    ".($password ? "password = ".$db->quote( md5($password) )."," : "")."
                    lastname = ".$db->quote($lastname).",
                    firstname = ".$db->quote($firstname).",
                    permission_id = ".$db->quote($permission)."
                WHERE user_id = ".$db->quote($id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Ο Χρήστης ενημερώθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
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