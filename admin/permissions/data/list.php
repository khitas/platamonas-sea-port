<?php
try
{
    include "../../../includes/settings.php";
    include "../../../includes/authentication.php";

    $result = array();

    function toBool($val)
    {
        if ( in_array( strtolower($val), array("true", "1", "yes", "on", "ΝΑΙ", "ναί", "ναι") ) )
            return 1;
        else
            return null;
    }

    $page = ($_POST["page"] > 1 ? $_POST["page"] -1 : 0);
    $limit = (ctype_digit($_POST["limit"]) ? $_POST["limit"] : 0);
    $search = trim($_POST["search"]);
    $field = trim($_POST["field"]);
    $sortby = trim($_POST["sortby"]);
    $sorttype = trim($_POST["sorttype"]);

    $start = $page * $limit;
    $pagination = ($limit ? "LIMIT ".$start.", ".$limit : "");

    if ($search)
    {
        $where = array();

        switch ($field)
        {
            case "id" : $where[] = "permission_id = " . $db->quote($search); break;
            case "name" : $where[] = "name like " . $db->quote("%".$search."%"); break;
            default :
                $where[] = "permission_id = " . $db->quote($search);
                $where[] = "name like " . $db->quote("%".$search."%");
            break;
        }
    }
    $where = ( count($where) > 0 ? "WHERE " . implode(" OR ", $where) : "");

    switch ($sortby)
    {
        case "id" : $sortby = "permission_id"; break;
        case "name" : $sortby = "name"; break;
        default : $sortby = "permission_id"; break;
    }

    switch ($sorttype)
    {
        case "dropdown" : $sorttype = "ASC"; break;
        case "dropup" : $sorttype = "DESC"; break;
        default : $sorttype = "ASC"; break;
    }

    $order =  "ORDER BY ".$sortby." ".$sorttype;

    $sql = "SELECT count(*) as total FROM permissions ".$where;
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT
              permission_id,
              name
            FROM permissions "
            .$where." ".$order." ".$pagination;
    //echo "<br><br>".$sql."<br><br>";ss

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = ($row["total"] > 0 ? "Βρέθηκαν ".$row["total"]." εγγραφές" : "Δεν βρέθηκαν εγγραφές").($AppVars["debug"] ? "  SQL >> ".$sql : "");
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