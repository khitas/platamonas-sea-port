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

        $id = $_POST["id"];
        $amyen_type_id = trim($_POST["spAmyenType"]);
        $boat_kind_id = trim($_POST["spBoatKind"]);
        $boat_status_id = trim($_POST["spBoatStatus"]);
        $boat_type_id = trim($_POST["spBoatType"]);
        $boat_color_id = trim($_POST["spBoatColor"]);
        $boat_material_id = trim($_POST["spBoatMaterial"]);
        $movement_type_id = trim($_POST["spMovementType"]);
        $boat_port_id = trim($_POST["spBoatPort"]);
        $registry_type_id = trim($_POST["spRegistryType"]);
        $amyen_number = trim($_POST["txtAmyenNumber"]);
        $builder = trim($_POST["txtBuilder"]);
        $dds = trim($_POST["txtDDS"]);
        $dsp = trim($_POST["txtDSP"]);
        $height = trim($_POST["txtHeight"]);
        $length = trim($_POST["txtLength"]);
        $name = trim($_POST["txtName"]);
        $registry_number = trim($_POST["txtRegistryNumber"]);
        $width = trim($_POST["txtWidth"]);
        $license_expired_date = trim($_POST["txtLicenseExpiredDate"]);
        $registry_date = trim($_POST["txtRegistryDate"]);
        $is_fast = ($_POST["cbIsFast"] == "on" ? 1 : 0);
        $comments = trim($_POST["txtComments"]);

        if ( ( ! ctype_digit($id) ) || ( $id == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        if ( $name == "" ) { throw new Exception("Το Όνομα πρέπει να έχει τιμή", 500);  }

        if ($license_expired_date)
        {
            list($day, $month, $year) = explode('/', $license_expired_date);
            $license_expired_date = $year."/".$month."/".$day;
        }

        if ($registry_date)
        {
            list($day, $month, $year) = explode('/', $registry_date);
            $registry_date = $year."/".$month."/".$day;
        }

        $sql = "UPDATE boats SET
                    name = ".$db->quote($name).",
                    boat_port_id = ".($boat_port_id ? $db->quote($boat_port_id) : "NULL").",
                    registry_type_id = ".($registry_type_id ? $db->quote($registry_type_id) : "NULL").",
                    registry_number = ".$db->quote($registry_number).",
                    registry_date = ".($registry_date ? $db->quote($registry_date) : "NULL").",
                    amyen_type_id = ".($amyen_type_id ? $db->quote($amyen_type_id) : "NULL").",
                    amyen_number = ".$db->quote($amyen_number).",
                    dds = ".$db->quote($dds).",
                    dsp = ".$db->quote($dsp).",
                    length = ".$db->quote($length).",
                    width = ".$db->quote($width).",
                    height = ".$db->quote($height).",
                    boat_color_id = ".($boat_color_id ? $db->quote($boat_color_id) : "NULL").",
                    boat_material_id = ".($boat_material_id ? $db->quote($boat_material_id) : "NULL").",
                    boat_type_id = ".($boat_type_id ? $db->quote($boat_type_id) : "NULL").",
                    license_expired_date = ".($license_expired_date ? $db->quote($license_expired_date) : "NULL").",
                    boat_status_id = ".($boat_status_id ? $db->quote($boat_status_id) : "NULL").",
                    movement_type_id = ".($movement_type_id ? $db->quote($movement_type_id) : "NULL").",
                    builder = ".$db->quote($builder).",
                    boat_kind_id = ".($boat_kind_id ? $db->quote($boat_kind_id) : "NULL").",
                    is_fast = ".($is_fast ? $db->quote($is_fast) : "NULL" ).",
                    comments = ".$db->quote($comments)."
                WHERE boat_id = ".$db->quote($id);
        //echo "<br><br>".$sql."<br><br>";

        $db->query( $sql );

        $result["status"] = 200;
        $result["message"] = "Το Σκάφος ενημερώθηκε".($AppVars["debug"] ? "  SQL >> ".$sql : "");
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