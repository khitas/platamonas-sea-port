<?php
session_start();

$deepPath = ($package ? str_repeat("../", $packages[$package]["level"] + ($action ? 1 : 0)) : "");

if ( $_SESSION[ "User" ] )
{
    $User = $_SESSION["User"];
}
else if ( isset( $_REQUEST[ "username" ] ) || isset( $_REQUEST[ "password" ] ) )
{
    $sql = "SELECT user_id, username, firstname, lastname, password, permission_id
            FROM users
            WHERE username = ".$db->quote( $_REQUEST[ "username" ] )." and password = ".$db->quote( md5( $_REQUEST[ "password" ] ) );
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ( count($rows) > 0 )
    {
        $User["user_id"] = $rows[0]["user_id"];
        $_SESSION["User"]["user_id"] = $User["user_id"];

        $User["username"] = $rows[0]["username"];
        $_SESSION["User"]["username"] = $User["username"];

        $User["firstname"] = $rows[0]["firstname"];
        $_SESSION["User"]["firstname"] = $User["firstname"];

        $User["lastname"] = $rows[0]["lastname"];
        $_SESSION["User"]["lastname"] = $User["lastname"];

        $User["password"] = $rows[0]["password"];
        $_SESSION["User"]["password"] = $User["password"];

        $User["permission_id"] = $rows[0]["permission_id"];
        $_SESSION["User"]["permission_id"] = $User["permission_id"];
    }
    else
    {
        header( "location: ".$deepPath."logout/" );
        exit;
    }
}
else if ( isset( $_SERVER[ "PHP_AUTH_USER" ] ) || isset( $_SERVER[ "PHP_AUTH_PW" ] ) )
{
    $sql = "SELECT user_id, username, firstname, lastname, password, permission_id
            FROM users
            WHERE username = ".$db->quote( $_SERVER[ "PHP_AUTH_USER" ] )." and password = ".$db->quote( $_SERVER[ "PHP_AUTH_PW" ] );
    //echo "<br><br>".$sql."<br><br>";

    $stmt = $db->query( $sql );
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ( count($rows) > 0 )
    {
        $User["user_id"] = $rows[0]["user_id"];
        $_SESSION["User"]["user_id"] = $User["user_id"];

        $User["username"] = $rows[0]["username"];
        $_SESSION["User"]["username"] = $User["username"];

        $User["firstname"] = $rows[0]["firstname"];
        $_SESSION["User"]["firstname"] = $User["firstname"];

        $User["lastname"] = $rows[0]["lastname"];
        $_SESSION["User"]["lastname"] = $User["lastname"];

        $User["password"] = $rows[0]["password"];
        $_SESSION["User"]["password"] = $User["password"];

        $User["permission_id"] = $rows[0]["permission_id"];
        $_SESSION["User"]["permission_id"] = $User["permission_id"];
    }
    else
    {
        header( "location: ".$deepPath."logout/" );
        exit;
    }
}
else
{
    header( "location: ".$deepPath."logout/" );
    exit;
}
?>