<?php
try
{
    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    $result = array();
    $where = "";


    if ($_POST["id"])
    {
        if (( ! ctype_digit($_POST["id"]) ) || ($_POST["id"] == 0) ) { throw new Exception("Ο Κωδικός του Ιδιοκτήτη είναι λάθος", 500);  }
    }

    if ($_POST["owner_id"])
    {
        if (( ! ctype_digit($_POST["owner_id"]) ) || ($_POST["owner_id"] == 0) ) { throw new Exception("Ο Κωδικός του Ιδιοκτήτη είναι λάθος", 500);  }
    }

    if ($_POST["skip_owner_id"])
    {
        if (( ! ctype_digit($_POST["skip_owner_id"]) ) || ($_POST["skip_owner_id"] == 0) ) { throw new Exception("Ο Κωδικός του Ιδιοκτήτη είναι λάθος", 500);  }
    }



    if ($_POST["owner_id"])
    {
        $sql = "SELECT boat_id FROM boat_owners WHERE owner_id = ".$db->quote($_POST["owner_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["boat_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "(boats.boat_id in (0" . ($ids ? ", ".$ids : "") .") AND boat_owners.owner_id = ".$db->quote($_POST["owner_id"]).")";
    }

    if ($_POST["skip_owner_id"])
    {
        $sql = "SELECT boat_id FROM boat_owners WHERE owner_id = ".$db->quote($_POST["skip_owner_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["boat_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "boats.boat_id not in (0" . ($ids ? ", ".$ids : "") .")";
    }


    $sql = "SELECT
                boats.boat_id,
                boats.name,
                boat_owners.get_date,
                DATE_FORMAT(boat_owners.get_date,'%d-%m-%Y') as get_date_gr,
                boat_owners.percent,
                owner_status.owner_status_id,
                owner_status.name as owner_status
            FROM boats
            LEFT JOIN boat_owners ON boat_owners.boat_id = boats.boat_id
            LEFT JOIN owner_status ON boat_owners.owner_status_id = owner_status.owner_status_id "
            .$where." ORDER BY boats.name ASC";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = (count($rows) > 0 ? "Βρέθηκαν ".count($rows)." ".$packages["boats"]["title"] : "Δεν βρέθηκαν ".$packages["boats"]["title"]).($AppVars["debug"] ? "  SQL >> ".$sql : "");
    $result["rows"] = $rows;
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>