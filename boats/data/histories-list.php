<?php
try
{
    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    $id = $_POST["id"];

    if ( ! ctype_digit($id) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }

    $sql = "SELECT
              boat_history_id,
              boat_id,
              name,
              event_date
            FROM boat_histories
            WHERE boat_id = ".$db->quote($id)."
            ORDER BY event_date DESC";
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = "Βρέθηκαν ".count($rows)." Ενέργειες";
    $result["rows"] = $rows;
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>