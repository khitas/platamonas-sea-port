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

        $lastname = trim($_POST["txtLastName"]);
        $firstname = trim($_POST["txtFirstName"]);
        $fathername = trim($_POST["txtFatherName"]);
        $adt = trim($_POST["txtADT"]);
        $afm = trim($_POST["txtAFM"]);
        $address = trim($_POST["txtAddress"]);
        $phone = trim($_POST["txtPhone"]);
        $mobile = trim($_POST["txtMobile"]);
        $comments = trim($_POST["txtComments"]);

        if ( ! $lastname ) { throw new Exception("Το Επώνυμο πρέπει να έχει τιμή", 500);  }
        if ( ! $firstname ) { throw new Exception("Το Όνομα πρέπει να έχει τιμή", 500);  }

        $sql = "INSERT INTO owners SET
                    lastname = ".$db->quote($lastname).",
                    firstname = ".$db->quote($firstname).",
                    fathername = ".$db->quote($fathername).",
                    adt = ".$db->quote($adt).",
                    afm = ".$db->quote($afm).",
                    address = ".$db->quote($address).",
                    phone = ".$db->quote($phone).",
                    mobile = ".$db->quote($mobile).",
                    comments = ".$db->quote($comments);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );
        $id = $db->lastInsertId();

        $result["status"] = 200;
        $result["message"] = "Ο Ιδιοκτήτης δημιουργήθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
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