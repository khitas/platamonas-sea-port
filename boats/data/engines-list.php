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
        $sql = "SELECT engine_id FROM boat_engines WHERE boat_id = ".$db->quote($_POST["boat_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["engine_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "(engines.engine_id in (0" . ($ids ? ", ".$ids : "") .") AND boat_engines.boat_id = ".$db->quote($_POST["boat_id"]).")";
    }

    if ($_POST["skip_boat_id"])
    {
        $sql = "SELECT engine_id FROM boat_engines WHERE boat_id = ".$db->quote($_POST["skip_boat_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["engine_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "engines.engine_id not in (0" . ($ids ? ", ".$ids : "") .")";
    }


    $sql = "SELECT
              boat_engines.boat_id,
              engines.engine_id,
              engines.serial_number,
              engine_types.engine_type_id,
              engine_types.name as engine_type,
              engine_kinds.engine_kind_id,
              engine_kinds.name as engine_kind,
              engine_power_types.engine_power_type_id,
              engine_power_types.name as engine_power_type,
              engine_brands.engine_brand_id,
              engine_brands.name as engine_brand,
              engines.power,
              engine_status.engine_status_id,
              engine_status.name as engine_status
            FROM engines
            LEFT JOIN boat_engines ON boat_engines.engine_id = engines.engine_id
            LEFT JOIN engine_types ON engines.engine_type_id = engine_types.engine_type_id
            LEFT JOIN engine_kinds ON engines.engine_kind_id = engine_kinds.engine_kind_id
            LEFT JOIN engine_power_types ON engines.engine_power_type_id = engine_power_types.engine_power_type_id
            LEFT JOIN engine_brands ON engines.engine_brand_id = engine_brands.engine_brand_id
            LEFT JOIN engine_status ON engines.engine_status_id = engine_status.engine_status_id "
            .$where." ORDER BY engines.serial_number ASC";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = (count($rows) > 0 ? "Βρέθηκαν ".count($rows)." ".$packages["engines"]["title"] : "Δεν βρέθηκαν ".$packages["engines"]["title"]).($AppVars["debug"] ? "  SQL >> ".$sql : "");
    $result["rows"] = $rows;
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>