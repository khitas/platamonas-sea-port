<?php

$db = new PDO( "mysql:host=".$conOptions["Host"]."; port=".$conOptions["Port"]."; dbname=".$conOptions["Database"], $conOptions["Username"], $conOptions["Password"] );
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->query("SET NAMES UTF8");

?>