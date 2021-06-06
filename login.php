<?php
    session_start();
    include 'functions.php';
    if(isset($_SESSION['gracz'])) {
        header("Location: main.php");
        exit();    
    }
    else{
        $login = $_POST['login'];
        $haslo = $_POST['password'];
        login($login,$haslo);
        header("Location: main.php");
        exit();
    }

?>