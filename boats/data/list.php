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
        $where[] = "boats.boat_id = " . $db->quote($_POST["id"]);
    }

    if ( ($_POST["searchColumn"] == "id") && ($_POST["searchText"]) ) {
        if (( ! ctype_digit($_POST["searchText"]) ) || ($_POST["searchText"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "boats.boat_id = " . $_POST["searchText"];
    }

    if ( ( ($_POST["searchColumn"] == "name") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "boat_port") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boat_ports.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "registry_type") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "registry_types.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "registry_number") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.registry_number like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "registry_date_gr") ) && ($_POST["searchText"]) ) {
        list($day, $month, $year) = explode('/', $_POST["searchText"]);
        $where[] = "boats.registry_date = " . $db->quote($year."/".$month."/".$day);
    }

    if ( ( ($_POST["searchColumn"] == "amyen_type") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "amyen_types.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "amyen_number") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.amyen_number like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "dds") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.dds like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "dsp") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.dsp like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "length") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.length like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "width") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.width like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "height") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.height like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "builder") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.builder like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "boat_color") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boat_colors.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "boat_material") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boat_materials.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "boat_type") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boat_types.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "license_expired_date_gr") ) && ($_POST["searchText"]) ) {
        list($day, $month, $year) = explode('/', $_POST["searchText"]);
        $where[] = "boats.license_expired_date = " . $db->quote($year."/".$month."/".$day);
    }

    if ( ( ($_POST["searchColumn"] == "boat_status") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boat_status.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "movement_type") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "movement_types.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "boat_kind") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boat_kinds.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ($_POST["searchColumn"] == "count_engines") && ($_POST["searchText"]) ) {
        $where[] = "tmp_boat_engines.count_engines = " . $db->quote($_POST["searchText"]);
    }

    if ( ($_POST["searchColumn"] == "count_owners") && ($_POST["searchText"]) ) {
        $where[] = "tmp_boat_owners.count_owners = " . $db->quote($_POST["searchText"]);
    }

    if ( ( ($_POST["searchColumn"] == "comments") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "boats.comments like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ($_POST["searchColumn"] == "is_fast") && ($_POST["searchText"]) ) {
        $is_fast = toBool($search);
        $where[] = "boats.is_fast" . ($is_fast ? " = " . $is_fast : " is NULL");
    }

    if ( count($where) ) $where = array( "(". implode(" OR ", $where) .")" );



    if ( $_POST["txtName"] ) {
        $where[] = "boats.name like " . $db->quote("%".$_POST["txtName"]."%");
    }

    if ( $_POST["spBoatPort"] ) {
        $where[] = "boats.boat_port_id = " . $db->quote($_POST["spBoatPort"]);
    }

    if ( $_POST["spRegistryType"] ) {
        $where[] = "boats.registry_type_id = " . $db->quote($_POST["spRegistryType"]);
    }

    if ( $_POST["txtRegistryNumber"] ) {
        $where[] = "boats.registry_number like = " . $db->quote("%".$_POST["txtRegistryNumber"]."%");
    }

    if ( $_POST["txtRegistryDate"] ) {
        list($day, $month, $year) = explode('/', $_POST["txtRegistryDate"]);
        $where[] = "boats.registry_date = " . $db->quote($year."/".$month."/".$day);
    }

    if ( $_POST["spAmyenType"] ) {
        $where[] = "boats.amyen_type_id = " . $db->quote($_POST["spAmyenType"]);
    }

    if ( $_POST["txtAmyenNumber"] ) {
        $where[] = "boats.amyen_number like " . $db->quote("%".$_POST["txtAmyenNumber"]."%");
    }

    if ( $_POST["txtDDS"] ) {
        $where[] = "boats.dds like " . $db->quote("%".$_POST["txtDDS"]."%");
    }

    if ( $_POST["txtDSP"] ) {
        $where[] = "boats.dsp like = " . $db->quote("%".$_POST["txtDSP"]."%");
    }

    if ( $_POST["txtLength"] ) {
        $where[] = "boats.length like " . $db->quote("%".$_POST["txtLength"]."%");
    }

    if ( $_POST["txtWidth"] ) {
        $where[] = "boats.width like " . $db->quote("%".$_POST["txtWidth"]."%");
    }

    if ( $_POST["txtHeight"] ) {
        $where[] = "boats.height like " . $db->quote("%".$_POST["txtHeight"]."%");
    }

    if ( $_POST["txtBuilder"] ) {
        $where[] = "boats.builder like " . $db->quote("%".$_POST["txtBuilder"]."%");
    }

    if ( $_POST["spBoatColor"] ) {
        $where[] = "boat_colors.boat_color_id = " . $db->quote($_POST["spBoatColor"]);
    }

    if ( $_POST["spBoatMaterial"] ) {
        $where[] = "boats.boat_material_id = " . $db->quote($_POST["spBoatMaterial"]);
    }

    if ( $_POST["spBoatType"] ) {
        $where[] = "boats.boat_type_id = " . $db->quote($_POST["spBoatType"]);
    }

    if ( $_POST["txtLicenseExpiredDate"] ) {
        list($day, $month, $year) = explode('/', $_POST["txtLicenseExpiredDate"]);
        $where[] = "boats.license_expired_date = " . $db->quote($year."/".$month."/".$day);
    }

    if ( $_POST["spBoatStatus"] ) {
        $where[] = "boats.boat_status_id = " . $db->quote($_POST["spBoatStatus"]);
    }

    if ( $_POST["spMovementType"] ) {
        $where[] = "boats.movement_type_id = " . $db->quote($_POST["spMovementType"]);
    }

    if ( $_POST["spBoatKind"] ) {
        $where[] = "boat_kinds.boat_kind_id = " . $db->quote($_POST["spBoatKind"]);
    }

    if ( $_POST["txtComments"] ) {
        $where[] = "boats.comments like " . $db->quote("%".$_POST["txtComments"]."%");
    }

    if ( $_POST["cbIsFast"] ) {
        $is_fast = toBool($_POST["cbIsFast"]);
        if (isset($is_fast)) {
            $where[] = "boats.is_fast" . ($is_fast ? " = " . $is_fast : " is NULL");
        }
    }

    $where = ( count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "");


    switch ( trim($_POST["orderBy"]) )
    {
        case "id" : $sortby = "boats.boat_id"; break;
        case "boat_id" : $sortby = "boats.boat_id"; break;
        case "name" : $sortby = "boats.name"; break;
        case "boat_port" : $sortby = "boat_ports.name"; break;
        case "registry_type" : $sortby = "registry_types.name"; break;
        case "registry_number" : $sortby = "boats.registry_number"; break;
        case "registry_date_gr" : $sortby = "boats.registry_date"; break;
        case "amyen_type" : $sortby = "amyen_types.name"; break;
        case "amyen_number" : $sortby = "boats.amyen_number" ; break;
        case "dds" : $sortby = "boats.dds"; break;
        case "dsp" : $sortby = "boats.dsp"; break;
        case "length" : $sortby = "boats.length"; break;
        case "width" : $sortby = "boats.width"; break;
        case "height" : $sortby = "boats.height"; break;
        case "builder" : $sortby = "boats.builder"; break;
        case "boat_color" : $sortby = "boat_colors.name"; break;
        case "boat_material" : $sortby = "boat_materials.name"; break;
        case "boat_type" : $sortby = "boat_types.name"; break;
        case "license_expired_date_gr" : $sortby = "boats.license_expired_date"; break;
        case "boat_status" : $sortby = "boat_status.name"; break;
        case "movement_type" : $sortby = "movement_types.name"; break;
        case "boat_kind" : $sortby = "boat_kinds.name"; break;
        case "count_engines" : $sortby = "tmp_boat_engines.count_engines"; break;
        case "count_owners" : $sortby = "tmp_boat_owners.count_owners"; break;
        case "is_fast" : $sortby = "boats.is_fast"; break;
        case "comments" : $sortby = "boats.comments"; break;
        default : $sortby = "boats.name"; break;
    }


    switch ( trim($_POST["orderType"]) )
    {
        case "dropdown" : $sorttype = "ASC"; break;
        case "dropup" : $sorttype = "DESC"; break;
        default : $sorttype = "ASC"; break;
    }

    $order =  "ORDER BY ".$sortby." ".$sorttype;

    $sql = "SELECT count(*) as total
            FROM boats
            LEFT JOIN boat_ports ON boats.boat_port_id = boat_ports.boat_port_id
            LEFT JOIN registry_types ON boats.registry_type_id = registry_types.registry_type_id
            LEFT JOIN amyen_types ON boats.amyen_type_id = amyen_types.amyen_type_id
            LEFT JOIN boat_colors ON boats.boat_color_id = boat_colors.boat_color_id
            LEFT JOIN boat_materials ON boats.boat_material_id = boat_materials.boat_material_id
            LEFT JOIN boat_types ON boats.boat_type_id = boat_types.boat_type_id
            LEFT JOIN boat_status ON boats.boat_status_id = boat_status.boat_status_id
            LEFT JOIN movement_types ON boats.movement_type_id = movement_types.movement_type_id
            LEFT JOIN boat_kinds ON boats.boat_kind_id = boat_kinds.boat_kind_id
            LEFT JOIN ( SELECT count(*) as count_engines, boat_id FROM boat_engines GROUP BY boat_id ) as tmp_boat_engines ON boats.boat_id = tmp_boat_engines.boat_id
            LEFT JOIN ( SELECT count(*) as count_owners, boat_id FROM boat_owners GROUP BY boat_id ) as tmp_boat_owners ON boats.boat_id = tmp_boat_owners.boat_id
            ".$where;
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);



     $sql = "SELECT
                boats.boat_id,
                boats.name,
                boat_ports.boat_port_id,
                boat_ports.name as boat_port,
                registry_types.registry_type_id,
                registry_types.name as registry_type,
                boats.registry_number,
                concat(registry_types.name, ' ', boats.registry_number) as registry_type_number,
                boats.registry_date,
                DATE_FORMAT(boats.registry_date,'%d-%m-%Y') as registry_date_gr,
                amyen_types.amyen_type_id,
                amyen_types.name as amyen_type,
                boats.amyen_number,
                concat(amyen_types.name, ' ', boats.amyen_number) as amyen_type_number,
                boats.dds,
                boats.dsp,
                boats.length,
                boats.width,
                boats.height,
                boats.builder,
                boat_colors.boat_color_id,
                boat_colors.name as boat_color,
                boat_colors.code as boat_color_code,
                boat_materials.boat_material_id,
                boat_materials.name as boat_material,
                boat_types.boat_type_id,
                boat_types.name as boat_type,
                boats.is_fast,
                boats.license_expired_date,
                DATE_FORMAT(boats.license_expired_date,'%d-%m-%Y') as license_expired_date_gr,
                boat_status.boat_status_id,
                boat_status.name as boat_status,
                movement_types.movement_type_id,
                movement_types.name as movement_type,
                boat_kinds.boat_kind_id,
                boat_kinds.name as boat_kind,
                ifnull(tmp_boat_engines.count_engines, 0) as count_engines,
                ifnull(tmp_boat_owners.count_owners, 0) as count_owners,
                DATEDIFF(boats.license_expired_date, now()) AS license_days,
                DATEDIFF(boats.license_expired_date, now()) >= 0 AS license_active,
                (CASE
                    WHEN boats.license_expired_date is null THEN 'Δεν υπάρχουν πληροφορίες για την Α.Ε.Π.'
                    WHEN DATEDIFF(boats.license_expired_date, now()) = -2 THEN 'Η Α.Ε.Π. έληξε προχθές'
                    WHEN DATEDIFF(boats.license_expired_date, now()) = -1 THEN 'Η Α.Ε.Π. έληξε χθές'
                    WHEN DATEDIFF(boats.license_expired_date, now()) < 0 THEN concat('Η Α.Ε.Π. έληξε πρίν από ', abs(DATEDIFF(boats.license_expired_date, now())), ' ημέρες')
                    WHEN DATEDIFF(boats.license_expired_date, now()) = 0 THEN 'Η Α.Ε.Π. λήγει σήμερα'
                    WHEN DATEDIFF(boats.license_expired_date, now()) = 1 THEN 'Η Α.Ε.Π. λήγει αύριο'
                    WHEN DATEDIFF(boats.license_expired_date, now()) = 2 THEN 'Η Α.Ε.Π. λήγει μεθαύριο'
                    ELSE concat('Η Α.Ε.Π. λήγει σε ', DATEDIFF(boats.license_expired_date, now()), ' ημέρες')
                END) AS license_message,
                boats.comments
            FROM boats
            LEFT JOIN boat_ports ON boats.boat_port_id = boat_ports.boat_port_id
            LEFT JOIN registry_types ON boats.registry_type_id = registry_types.registry_type_id
            LEFT JOIN amyen_types ON boats.amyen_type_id = amyen_types.amyen_type_id
            LEFT JOIN boat_colors ON boats.boat_color_id = boat_colors.boat_color_id
            LEFT JOIN boat_materials ON boats.boat_material_id = boat_materials.boat_material_id
            LEFT JOIN boat_types ON boats.boat_type_id = boat_types.boat_type_id
            LEFT JOIN boat_status ON boats.boat_status_id = boat_status.boat_status_id
            LEFT JOIN movement_types ON boats.movement_type_id = movement_types.movement_type_id
            LEFT JOIN boat_kinds ON boats.boat_kind_id = boat_kinds.boat_kind_id
            LEFT JOIN ( SELECT count(*) as count_engines, boat_id FROM boat_engines GROUP BY boat_id ) as tmp_boat_engines ON boats.boat_id = tmp_boat_engines.boat_id
            LEFT JOIN ( SELECT count(*) as count_owners, boat_id FROM boat_owners GROUP BY boat_id ) as tmp_boat_owners ON boats.boat_id = tmp_boat_owners.boat_id "
            .$where." ".$order." ".$pagination;
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