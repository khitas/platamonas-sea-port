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

    $option = $_POST["option"];

    if ( $option == "" ) { throw new Exception("Πρέπει να επιλέξετε Μεταβλητή", 500);  }

    $sql = "SELECT
                option_id,
                option_name,
                option_value
            FROM options
            WHERE option_name = ".$db->quote($option);

//    echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result["status"] = 200;
    $result["message"] = "";
    $result["rows"] = $rows;

}
catch (Exception $e)
{
    $result["status"] = $e->getCode();
    $result["message"] = $e->getMessage().($AppVars["debug"] ? "  SQL >> ".$sql : "");
}

echo json_encode( $result );

?>