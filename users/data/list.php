<?php
try
{
    include "../info.php";

    include "../../includes/settings.php";
    include "../../includes/authentication.php";

    function toBool($val)
    {
        if ( in_array( strtolower($val), array("true", "1", "yes", "on", "ΝΑΙ", "ναί", "ναι") ) )
            return 1;
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
        $where[] = "users.user_id = " . $db->quote($_POST["id"]);
    }

    if ( $_POST["user_id"] ) {
        if (( ! ctype_digit($_POST["user_id"]) ) || ($_POST["user_id"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "users.user_id = " . $db->quote($_POST["user_id"]);
    }

    if ( ($_POST["searchColumn"] == "user_id") && ($_POST["searchText"]) ) {
        if (( ! ctype_digit($_POST["searchText"]) ) || ($_POST["searchText"] == 0) ) { throw new Exception("Ο Κωδικός εγγραφής είναι λάθος", 500);  }
        $where[] = "users.user_id = " . $_POST["searchText"];
    }

    if ( ( ($_POST["searchColumn"] == "lastname") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "users.lastname like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "firstname") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "users.firstname like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "username") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "users.username like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( ( ($_POST["searchColumn"] == "permission") || ($_POST["searchColumn"] == "all") ) && ($_POST["searchText"]) ) {
        $where[] = "permissions.name like " . $db->quote("%".$_POST["searchText"]."%");
    }

    if ( count($where) ) $where = array( "(". implode(" OR ", $where) .")" );




    if ( $_POST["txtLastname"] ) {
        $where[] = "users.lastname like " . $db->quote("%".$_POST["txtLastname"]."%");
    }

    if ( $_POST["txtFirstname"] ) {
        $where[] = "users.firstname like " . $db->quote("%".$_POST["txtFirstname"]."%");
    }

    if ( $_POST["txtUsername"] ) {
        $where[] = "users.username like " . $db->quote("%".$_POST["txtUsername"]."%");
    }

    if ( $_POST["spPermission"] ) {
        $where[] = "permissions.permission_id = " . $db->quote($_POST["spPermission"]);
    }

    $where = ( count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "");



    switch ( trim($_POST["orderBy"]) )
    {
        case "id" : $sortby = "users.user_id"; break;
        case "user_id" : $sortby = "users.user_id"; break;
        case "lastname" : $sortby = "users.lastname"; break;
        case "firstname" : $sortby = "users.firstname"; break;
        case "username" : $sortby = "users.username"; break;
        case "permission" : $sortby = "permissions.name"; break;
        default : $sortby = "users.lastname"; break;
    }


    switch ( trim($_POST["orderType"]) )
    {
        case "dropdown" : $sorttype = "ASC"; break;
        case "dropup" : $sorttype = "DESC"; break;
        default : $sorttype = "ASC"; break;
    }

    $order =  "ORDER BY ".$sortby." ".$sorttype;



    $sql = "SELECT count(*) as total
            FROM users
            LEFT JOIN permissions ON users.permission_id = permissions.permission_id
            ".$where;
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT
              user_id,
              username,
              permissions.permission_id,
              permissions.name as permission,
              firstname,
              lastname,
              concat(lastname, ' ', firstname) as fullname
            FROM users
            LEFT JOIN permissions ON users.permission_id = permissions.permission_id
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