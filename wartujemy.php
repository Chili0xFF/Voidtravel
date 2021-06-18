<?php 
    include 'functions.php';
    include 'uzytkownik.php';
    session_start();
    $czas = $_GET['czas'];
    if($czas<1||$czas>48){
        $_SESSION['error']="Zła ilość godzin !!!";
        header("Location: warta.php");
        exit();
    }
    else{
        if(wartaSpr()==FALSE){
            warta($czas);   
        }
        header("Location: warta.php");
    }


?>