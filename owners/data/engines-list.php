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
        $sql = "SELECT engine_id FROM owner_engines WHERE owner_id = ".$db->quote($_POST["owner_id"]);
//        echo "<br><br>".$sql."<br><br>";

        $stmt = $db->query( $sql );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ids = "";
        foreach ($rows as $row)
        {
            $ids .= ($ids ? ", " : "") . $row["engine_id"];
        }

        $where .= ($where ? " AND " : "WHERE " ) . "(engines.engine_id in (0" . ($ids ? ", ".$ids : "") .") AND owner_engines.owner_id = ".$db->quote($_POST["owner_id"]).")";
    }

    if ($_POST["skip_owner_id"])
    {
        $sql = "SELECT engine_id FROM owner_engines WHERE owner_id = ".$db->quote($_POST["skip_owner_id"]);
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
              owner_engines.owner_id,
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
            LEFT JOIN owner_engines ON owner_engines.engine_id = engines.engine_id
            LEFT JOIN engine_types ON engines.engine_type_id = engine_types.engine_type_id
            LEFT JOIN engine_kinds ON engines.engine_kind_id = engine_kinds.engine_kind_id
            LEFT JOIN engine_power_types ON engines.engine_power_type_id = engine_power_types.engine_power_type_id
            LEFT JOIN engine_brands ON engines.engine_brand_id = engine_brands.engine_brand_id
            LEFT JOIN engine_status ON engines.engine_status_id = engine_status.engine_status_id "
            .$where. " ORDER BY engines.serial_number ASC";


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