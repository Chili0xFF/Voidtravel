<?php 
include 'uzytkownik.php';
session_start();

include 'functions.php';
    if(!isset($_SESSION['gracz'])){
        header("Location: index.php");
        exit();
    }
    $przedmiotId = $_GET['id'];
    kup($przedmiotId);
    header("Location: sklep.php");

?>