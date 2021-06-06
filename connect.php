<?php 
    $host="localhost";
    $db_user="root";
    $db_password="";
    $db_name="voidtravel";
    $db_connect = new mysqli($host,$db_user,$db_password,$db_name);
    if($db_connect->connect_error)
    {
        die("Połączenie nieudane: ". $db_connect->connect_error);
    }
?>

