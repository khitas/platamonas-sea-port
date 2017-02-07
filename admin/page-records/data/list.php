<?php
try
{
    include "../info.php";

    include "../../../includes/settings.php";
    include "../../../includes/authentication.php";

    $result = array();
    $where = array();

    function toBool($val)
    {
        if ( in_array( strtolower($val), array("true", "1", "yes", "on", "ΝΑΙ", "ναί", "ναι") ) )
            return 1;
        else if ( in_array( strtolower($val), array("false", "0", "no", "off", "ΟΧΙ", "όχι", "οχι") ) )
            return 0;
        else
            return null;
    }

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
        if (( ! ctype_digit($_POST["id"]) ) || ($_POST["id"] == 0) ) { throw new Exception("Ο Κωδικός είναι λάθος", 500);  }
        $where[] = "page_records.page_record_id = " . $db->quote($_POST["id"]);
    }

    if ( $_POST["page_record_id"] ) {
        if (( ! ctype_digit($_POST["page_record_id"]) ) || ($_POST["page_record_id"] == 0) ) { throw new Exception("Ο Κωδικός είναι λάθος", 500);  }
        $where[] = "page_records.page_record_id = " . $db->quote($_POST["page_record_id"]);
    }

    if ( ($_POST["searchColumn"] == "page_record_id") && ($_POST["searchText"]) ) {
        if (( ! ctype_digit($_POST["searchText"]) ) || ($_POST["searchText"] == 0) ) { throw new Exception("Ο Κωδικός είναι λάθος", 500);  }
        $where[] = "page_records.page_record_id = " . $_POST["searchText"];
    }

    if ( ( ($_POST["searchColumn"] == "name") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "page_records.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ($_POST["searchColumn"] == "is_default") && ($_POST["searchText"]) ) {
        $is_default = toBool($search);
        $where[] = "page_records.is_default" . ($is_default ? " = " . $is_default : " is NULL");
    }

    if ( count($where) ) $where = array( "(". implode(" OR ", $where) .")" );




    if ( $_POST["txtName"] ) {
        $where[] = "page_records.name like " . $db->quote("%".$_POST["txtName"]."%");
    }

    if ( $_POST["cbIsDefault"] ) {
        $is_default = toBool($_POST["cbIsDefault"]);
        if (isset($is_default)) {
            $where[] = "page_records.is_default" . ($is_default ? " = " . $is_default : " is NULL");
        }
    }

    $where = ( count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "");



    switch ( trim($_POST["orderBy"]) )
    {
        case "id" : $sortby = "page_records.page_record_id"; break;
        case "page_record_id" : $sortby = "page_records.page_record_id"; break;
        case "name" : $sortby = "page_records.name"; break;
        case "is_default" : $sortby = "page_records.is_default"; break;
        default : $sortby = "page_records.name"; break;
    }


    switch ( trim($_POST["orderType"]) )
    {
        case "dropdown" : $sorttype = "ASC"; break;
        case "dropup" : $sorttype = "DESC"; break;
        default : $sorttype = "ASC"; break;
    }

    $order =  "ORDER BY ".$sortby." ".$sorttype;



    $sql = "SELECT count(*) as total FROM page_records ".$where;
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT
              page_record_id,
              name,
              is_default
            FROM page_records "
            .$where." ".$order." ".$pagination;
    //echo "<br><br>".$sql."<br><br>";

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