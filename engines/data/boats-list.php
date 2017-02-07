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
        $sql = "SELECT boat_id FROM boat_engines WHERE engine_id = ".$db->quote($_POST["engine_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["boat_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "(boats.boat_id in (0" . ($ids ? ", ".$ids : "") .") AND boat_engines.engine_id = ".$db->quote($_POST["engine_id"]).")";
    }

    if ($_POST["skip_engine_id"])
    {
        $sql = "SELECT boat_id FROM boat_engines WHERE engine_id = ".$db->quote($_POST["skip_engine_id"]);
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
              boat_engines.engine_id,
              boats.boat_id,
              boats.name
            FROM boats
            LEFT JOIN boat_engines ON boat_engines.boat_id = boats.boat_id
            ".$where." ORDER BY boats.name ASC";

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