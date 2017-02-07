<?php
try
{
    include "../info.php";

    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    $result = array();

    function date_difference($date1, $date2) {
        $current = $date1;
        $datetime2 = date_create($date2);
        $count = 0;
        while(date_create($current) < $datetime2){
            $current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current)));
            $count++;
        }
        return $count;
    }

    function toBool($val)
    {
        if ( in_array( strtolower($val), array("true", "1", "yes", "on", "ΝΑΙ", "ναί", "ναι") ) )
            return 1;
        else if ( in_array( strtolower($val), array("false", "0", "no", "off", "ΟΧΙ", "όχι", "οχι") ) )
            return 0;
        else
            return null;
    }

    $result = array();
    $where = array();


    $page = ($_POST["pagination"] > 1 ? $_POST["pagination"] -1 : 0);
    $limit = (ctype_digit($_POST["pageRecords"]) ? $_POST["pageRecords"] : 0);
    $start = $page * $limit;
    $pagination = ($limit ? "LIMIT ".$start.", ".$limit : "");


    if ($_POST["advSearch"])
    {
        $split_parameters = explode('&', $_POST["advSearch"]);

        for($i = 0; $i < count($split_parameters); $i++) {
            $final_split = explode('=', $split_parameters[$i]);
            $_POST[$final_split[0]] = str_replace(" ", "%", trim(urldecode($final_split[1])) );
        }

        unset($_POST["advSearch"]);
    }

    $_POST["searchColumn"] = trim( $_POST["searchColumn"] );
    $_POST["searchText"] = str_replace(" ", "%", trim( $_POST["searchText"] ));


    if ( $_POST["id"] ) {
        if (( ! ctype_digit($_POST["id"]) ) || ($_POST["id"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "engines.engine_id = " . $db->quote($_POST["id"]);
    }

    if ( ($_POST["searchColumn"] == "id") && ($_POST["searchText"]) ) {
        if (( ! ctype_digit($_POST["searchText"]) ) || ($_POST["searchText"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "engines.engine_id = " . $_POST["searchText"];
    }

    if ( ( ($_POST["searchColumn"] == "serial_number") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engines.serial_number like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "engine_type") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engine_types.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "engine_kind") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engine_kinds.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "power") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engines.power like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "engine_power_type") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engine_power_types.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "engine_power") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "concat(engines.power, ' ', engine_power_types.name)  like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "engine_brand") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engine_brands.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "engine_status") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engine_status.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "comments") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "engines.comments like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( count($where) ) $where = array( "(". implode(" OR ", $where) .")" );




    if ( $_POST["txtSerialNumber"] ) {
        $where[] = "engines.serial_number like " . $db->quote("%".$_POST["txtSerialNumber"]."%");
    }

    if ( $_POST["spEngineType"] ) {
        $where[] = "engines.engine_type_id = " . $db->quote($_POST["spEngineType"]);
    }

    if ( $_POST["spEngineKind"] ) {
        $where[] = "engines.engine_kind_id = " . $db->quote($_POST["spEngineKind"]);
    }

    if ( $_POST["txtPower"] ) {
        $where[] = "engines.power like " . $db->quote("%".$_POST["txtPower"]."%");
    }

    if ( $_POST["spEnginePowerType"] ) {
        $where[] = "engines.engine_power_type_id = " . $db->quote($_POST["spEnginePowerType"]);
    }

    if ( $_POST["spEngineBrand"] ) {
        $where[] = "engines.engine_brand_id = " . $db->quote($_POST["spEngineBrand"]);
    }

    if ( $_POST["spEngineStatus"] ) {
        $where[] = "engines.engine_status_id = " . $db->quote($_POST["spEngineStatus"]);
    }

    if ( $_POST["txtComments"] ) {
        $where[] = "boats.comments like " . $db->quote("%".$_POST["txtComments"]."%");
    }

    $where = ( count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "");



    switch ( trim($_POST["orderBy"]) )
    {
        case "id" : $sortby = "engines.engine_id"; break;
        case "engine_id" : $sortby = "engines.engine_id"; break;
        case "serial_number" : $sortby = "engines.serial_number"; break;
        case "engine_type" : $sortby = "engine_types.name"; break;
        case "engine_kind" : $sortby = "engine_kinds.name"; break;
        case "power" : $sortby = "engines.power"; break;
        case "engine_power_type" : $sortby = "engine_power_types.name"; break;
        case "engine_power" : $sortby = "concat(engines.power, ' ', engine_power_types.name)"; break;
        case "engine_brand" : $sortby = "engine_brands.name"; break;
        case "engine_status" : $sortby = "engine_status.name" ; break;
        case "comments" : $sortby = "engines.comments"; break;
        default : $sortby = "engines.serial_number"; break;
    }


    switch ( trim($_POST["orderType"]) )
    {
        case "dropdown" : $sorttype = "ASC"; break;
        case "dropup" : $sorttype = "DESC"; break;
        default : $sorttype = "ASC"; break;
    }

    $order =  "ORDER BY ".$sortby." ".$sorttype;

    $sql = "SELECT count(*) as total
            FROM engines
            LEFT JOIN engine_types ON engines.engine_type_id = engine_types.engine_type_id
            LEFT JOIN engine_kinds ON engines.engine_kind_id = engine_kinds.engine_kind_id
            LEFT JOIN engine_power_types ON engines.engine_power_type_id = engine_power_types.engine_power_type_id
            LEFT JOIN engine_brands ON engines.engine_brand_id = engine_brands.engine_brand_id
            LEFT JOIN engine_status ON engines.engine_status_id = engine_status.engine_status_id
            LEFT JOIN ( SELECT count(*) as count_boats, engine_id FROM boat_engines GROUP BY engine_id ) as tmp_engine_boats ON engines.engine_id = tmp_engine_boats.engine_id
            LEFT JOIN ( SELECT count(*) as count_owners, engine_id FROM owner_engines GROUP BY engine_id ) as tmp_engine_owners ON engines.engine_id = tmp_engine_owners.engine_id
            ".$where;
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);



     $sql = "SELECT
                engines.engine_id,
                engines.serial_number,
                engine_types.engine_type_id,
                engine_types.name as engine_type,
                engine_kinds.engine_kind_id,
                engine_kinds.name as engine_kind,
                engine_power_types.engine_power_type_id,
                engine_power_types.name as engine_power_type,
                concat(engines.power, ' ', engine_power_types.name) as engine_power,
                engine_brands.engine_brand_id,
                engine_brands.name as engine_brand,
                engines.power,
                engine_status.engine_status_id,
                engine_status.name as engine_status,
                IFNULL(tmp_engine_boats.count_boats, 0) as count_boats,
                IFNULL(tmp_engine_owners.count_owners, 0) as count_owners,
                engines.comments
            FROM engines
            LEFT JOIN engine_types ON engines.engine_type_id = engine_types.engine_type_id
            LEFT JOIN engine_kinds ON engines.engine_kind_id = engine_kinds.engine_kind_id
            LEFT JOIN engine_power_types ON engines.engine_power_type_id = engine_power_types.engine_power_type_id
            LEFT JOIN engine_brands ON engines.engine_brand_id = engine_brands.engine_brand_id
            LEFT JOIN engine_status ON engines.engine_status_id = engine_status.engine_status_id
            LEFT JOIN ( SELECT count(*) as count_boats, engine_id FROM boat_engines GROUP BY engine_id ) as tmp_engine_boats ON engines.engine_id = tmp_engine_boats.engine_id
            LEFT JOIN ( SELECT count(*) as count_owners, engine_id FROM owner_engines GROUP BY engine_id ) as tmp_engine_owners ON engines.engine_id = tmp_engine_owners.engine_id
            ".$where." ".$order." ".$pagination;
//    echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = ($row["total"] > 0 ? "Βρέθηκαν ".$row["total"]." ".$packages[$package]["title"] : "Δεν βρέθηκαν ".$packages[$package]["title"]).($AppVars["debug"] ? "  SQL >> ".$sql : "");
    $result["rows"] = $rows;
    $result["records"] = array(
        "total" => $row["total"],
        "from" => ($row["total"] > 0 ? ($start + 1) : 0),
        "to" => ($start + count($rows)),
    );
    $result["pages"] = array(
        "total" => ($row["total"] ? ceil($row["total"] / $limit) : 0),
        "current" => ($row["total"] > 0 ? ($page ? ($page + 1) : 1) : 0)
    );
}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>