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
        if (( ! ctype_digit($_POST["id"]) ) || ($_POST["id"] == 0) ) { throw new Exception("Ο Κωδικός του Σκάφους είναι λάθος", 500);  }
    }

    if ($_POST["boat_id"])
    {
        if (( ! ctype_digit($_POST["boat_id"]) ) || ($_POST["boat_id"] == 0) ) { throw new Exception("Ο Κωδικός του Σκάφους είναι λάθος", 500);  }
    }

    if ($_POST["skip_boat_id"])
    {
        if (( ! ctype_digit($_POST["skip_boat_id"]) ) || ($_POST["skip_boat_id"] == 0) ) { throw new Exception("Ο Κωδικός του Σκάφους είναι λάθος", 500);  }
    }



    if ($_POST["boat_id"])
    {
        $sql = "SELECT owner_id FROM boat_owners WHERE boat_id = ".$db->quote($_POST["boat_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["owner_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "(owners.owner_id in (0" . ($ids ? ", ".$ids : "") .") AND boat_owners.boat_id = ".$db->quote($_POST["boat_id"]).")";
    }

    if ($_POST["skip_boat_id"])
    {
        $sql = "SELECT owner_id FROM boat_owners WHERE boat_id = ".$db->quote($_POST["skip_boat_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["owner_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "owners.owner_id not in (0" . ($ids ? ", ".$ids : "") .")";
    }


    $sql = "SELECT
                boat_owners.boat_id,
                owners.owner_id,
                owners.firstname,
                owners.lastname,
                owners.fathername,
                owners.adt,
                owners.afm,
                owners.address,
                owners.phone,
                owners.mobile,
                boat_owners.get_date,
                DATE_FORMAT(boat_owners.get_date,'%d-%m-%Y') as get_date_gr,
                boat_owners.percent,
                owner_status.owner_status_id,
                owner_status.name as owner_status
            FROM owners
            LEFT JOIN boat_owners ON boat_owners.owner_id = owners.owner_id
            LEFT JOIN owner_status ON boat_owners.owner_status_id = owner_status.owner_status_id "
            .$where." ORDER BY owners.lastname ASC, owners.firstname ASC";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = (count($rows) > 0 ? "Βρέθηκαν ".count($rows)." ".$packages["owners"]["title"] : "Δεν βρέθηκαν ".$packages["owners"]["title"]).($AppVars["debug"] ? "  SQL >> ".$sql : "");
    $result["rows"] = $rows;
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>