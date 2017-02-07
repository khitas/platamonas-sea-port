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


////Search =============================================================================================================


    if ( $_POST["id"] ) {
        if (( ! ctype_digit($_POST["id"]) ) || ($_POST["id"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "owners.owner_id = " . $db->quote($_POST["id"]);
    }

    if ( ($_POST["searchColumn"] == "id") && ($_POST["searchText"]) ) {
        if (( ! ctype_digit($_POST["searchText"]) ) || ($_POST["searchText"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "owners.owner_id = " . $_POST["searchText"];
    }

    if ( ( ($_POST["searchColumn"] == "firstname") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.firstname like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "lastname") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.lastname like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "fathername") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.fathername like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "adt") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.adt like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "afm") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.afm like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "address") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.address like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "phone") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.phone like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "mobile") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.mobile like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ($_POST["searchColumn"] == "count_engines") && ($_POST["searchText"]) ) {
        $where[] = "tmp_owner_engines.count_engines = " . $db->quote($_POST["searchText"]);
    }

    if ( ($_POST["searchColumn"] == "count_boats") && ($_POST["searchText"]) ) {
        $where[] = "tmp_owner_boats.count_boats = " . $db->quote($_POST["searchText"]);
    }

    if ( ( ($_POST["searchColumn"] == "comments") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "owners.comments like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( count($where) ) $where = array( "(". implode(" OR ", $where) .")" );


////AdvSearch ==========================================================================================================


    if ( $_POST["txtLastName"] ) {
        $where[] = "owners.lastname like " . $db->quote("%".$_POST["txtLastName"]."%");
    }

    if ( $_POST["txtFirstName"] ) {
        $where[] = "owners.firstname like " . $db->quote("%".$_POST["txtFirstName"]."%");
    }

    if ( $_POST["txtFatherName"] ) {
        $where[] = "owners.fathername like " . $db->quote("%".$_POST["txtFatherName"]."%");
    }

    if ( $_POST["txtADT"] ) {
        $where[] = "owners.adt like " . $db->quote("%".$_POST["txtADT"]."%");
    }

    if ( $_POST["txtAFM"] ) {
        $where[] = "owners.afm like " . $db->quote("%".$_POST["txtAFM"]."%");
    }

    if ( $_POST["txtAddress"] ) {
        $where[] = "owners.address like " . $db->quote("%".$_POST["txtAddress"]."%");
    }

    if ( $_POST["txtPhone"] ) {
        $where[] = "owners.phone like " . $db->quote("%".$_POST["txtPhone"]."%");
    }

    if ( $_POST["txtMobile"] ) {
        $where[] = "owners.mobile like " . $db->quote("%".$_POST["txtMobile"]."%");
    }

    if ( $_POST["txtComments"] ) {
        $where[] = "owners.comments like " . $db->quote("%".$_POST["txtComments"]."%");
    }

    $where = ( count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "");

////Sort ===============================================================================================================

    switch ( trim($_POST["orderBy"]) )
    {
        case "id" : $sortby = "owners.owner_id"; break;
        case "owner_id" : $sortby = "owners.owner_id"; break;
        case "firstname" : $sortby = "owners.firstname"; break;
        case "lastname" : $sortby = "owners.lastname"; break;
        case "fathername" : $sortby = "owners.fathername"; break;
        case "adt" : $sortby = "owners.adt"; break;
        case "afm" : $sortby = "owners.afm"; break;
        case "address" : $sortby = "owners.address"; break;
        case "phone" : $sortby = "owners.phone"; break;
        case "mobile" : $sortby = "owners.mobile"; break;
        case "count_engines" : $sortby = "tmp_owner_engines.count_engines"; break;
        case "count_owners" : $sortby = "tmp_owner_boats.count_boats"; break;
        case "comments" : $sortby = "owners.comments"; break;
        default : $sortby = "owners.lastname"; break;
    }


    switch ( trim($_POST["orderType"]) )
    {
        case "dropdown" : $sorttype = "ASC"; break;
        case "dropup" : $sorttype = "DESC"; break;
        default : $sorttype = "ASC"; break;
    }

    $order =  "ORDER BY ".$sortby." ".$sorttype;


////Query ==============================================================================================================

    $sql = "SELECT count(*) as total
            FROM owners
            LEFT JOIN ( SELECT count(*) as count_boats, owner_id FROM boat_owners GROUP BY owner_id ) as tmp_owner_boats ON owners.owner_id = tmp_owner_boats.owner_id
            LEFT JOIN ( SELECT count(*) as count_engines, owner_id FROM owner_engines GROUP BY owner_id ) as tmp_owner_engines ON owners.owner_id = tmp_owner_engines.owner_id
            ".$where;
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT
                owners.owner_id,
                owners.firstname,
                owners.lastname,
                owners.fathername,
                concat(owners.lastname, ' ', owners.firstname) as fullname,
                owners.adt,
                owners.afm,
                owners.address,
                owners.phone,
                owners.mobile,
                IFNULL(tmp_owner_boats.count_boats, 0) as count_boats,
                IFNULL(tmp_owner_engines.count_engines, 0) as count_engines,
                owners.comments
            FROM owners
            LEFT JOIN ( SELECT count(*) as count_boats, owner_id FROM boat_owners GROUP BY owner_id ) as tmp_owner_boats ON owners.owner_id = tmp_owner_boats.owner_id
            LEFT JOIN ( SELECT count(*) as count_engines, owner_id FROM owner_engines GROUP BY owner_id ) as tmp_owner_engines ON owners.owner_id = tmp_owner_engines.owner_id "
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