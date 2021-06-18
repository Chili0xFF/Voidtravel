<?php 
session_start();
include 'functions.php';
    if(!isset($_SESSION['gracz'])){
        header("Location: index.php");
        exit();
    }
    $przedmiotId = $_GET['id'];
    if(kup($przedmiotId)==FALSE){
        $_SESSION['error']="Nie udało się kupić tego przedmiotu";
    }
    //header("Location: sklep.php");

?>