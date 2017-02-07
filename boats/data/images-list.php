<?php
try
{
    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    $id = $_POST["id"];

    if ( ! ctype_digit($id) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }

    $sql = "SELECT
              boat_image_id,
              boat_id,
              name
            FROM boat_images
            WHERE boat_id = ".$db->quote($id);
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = "Βρέθηκαν ".count($rows)." Φωτογραφίες";
    $result["rows"] = $rows;
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>