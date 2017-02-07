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

        if ( $lastname == "" ) { throw new Exception("Το Επώνυμο πρέπει να έχει τιμή", 500);  }
        if ( $firstname == "" ) { throw new Exception("Το Όνομα πρέπει να έχει τιμή", 500);  }
        if ( $username == "" ) { throw new Exception("Το Όνομα Χρήστη πρέπει να έχει τιμή", 500);  }
        if ( ! $password ) { throw new Exception("Ο Κωδικός πρέπει να έχει τιμή", 500);  }
        if (( $password || $repassword ) && ( $password <> $repassword )) { throw new Exception("O Κωδικός δεν ταιριάζει με την Επαλήθευση", 500);  }
        if (( ! ctype_digit($permission) ) || (! $permission)) { throw new Exception("Πρέπει να επιλέξετε Δικαιώματα", 500);  }

        $sql = "SELECT user_id FROM users WHERE username = ".$db->quote($username);
        $stmt = $db->query( $sql );

        if ( count( $stmt->fetchAll(PDO::FETCH_ASSOC) ) > 0 )
        {
            throw new Exception("Το Όνομα Χρήστη θπάρχει ήδη", 500);
        }

        $sql = "INSERT INTO users SET
                    password = ".$db->quote( md5($password) ).",
                    lastname = ".$db->quote($lastname).",
                    firstname = ".$db->quote($firstname).",
                    permission_id = ".$db->quote($permission).",
                    username = ".$db->quote($username);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Ο Χρήστης δημιουργήθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
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