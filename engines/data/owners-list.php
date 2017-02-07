<?php
try
{
    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    $result = array();
    $where = "";


    if ($_POST["engine_id"])
    {
        if (( ! ctype_digit($_POST["engine_id"]) ) || ($_POST["engine_id"] == 0) ) { throw new Exception("Ο Κωδικός της Μηχανής είναι λάθος", 500);  }
    }

    if ($_POST["skip_engine_id"])
    {
        if (( ! ctype_digit($_POST["skip_engine_id"]) ) || ($_POST["skip_engine_id"] == 0) ) { throw new Exception("Ο Κωδικός της Μηχανής είναι λάθος", 500);  }
    }



    if ($_POST["engine_id"])
    {
        $sql = "SELECT owner_id FROM owner_engines WHERE engine_id = ".$db->quote($_POST["engine_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["owner_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "owners.owner_id in (0" . ($ids ? ", ".$ids : "") .")";
    }

    if ($_POST["skip_engine_id"])
    {
        $sql = "SELECT owner_id FROM owner_engines WHERE engine_id = ".$db->quote($_POST["skip_engine_id"]);
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
                owners.owner_id,
                owners.firstname,
                owners.lastname,
                owners.fathername,
                owners.adt,
                owners.afm,
                owners.address,
                owners.phone,
                owners.mobile
            FROM owners
            ".$where." ORDER BY owners.lastname ASC, owners.firstname ASC";

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