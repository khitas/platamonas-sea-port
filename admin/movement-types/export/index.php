<?php
    include "../info.php";

    include "../../../includes/settings.php";
    include "../../../includes/authentication.php";

    require "../../../libs/php-excel/php-excel.class.php";

    $file = $AppVars["SERVER_HOST"].$packages[$package]["parent"].$packages[$package]["path"]."data/list.php";
    $curl = curl_init($file);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $User["username"].":".$User["password"]);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $_GET);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($curl);
    $data = json_decode($data);

    $lines = array( 0 => array("AA", "Κωδικός", "Όνομα"));

    $counter = 0;
    foreach( $data->rows as $row)
    {
        $counter++;
        $lines[] = array($counter, $row->movement_type_id, $row->name);
    }

    $xls = new Excel_XML;
    $xls->addWorksheet($packages[$package]["name"], $lines);
    $xls->sendWorkbook($packages[$package]["name"].'.xls');
?>