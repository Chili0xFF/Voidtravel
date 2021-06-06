<?php 
    include_once 'istota.php'; 
    include_once 'uzytkownik.php';
    session_start();
    include_once 'functions.php';
    $lvl = $_SESSION['gracz']->getLvL();
    if(isset($_POST['sila']))podniesStaty($_SESSION['gracz']->getSila(),$lvl,"str");
    else if(isset($_POST['zrecznosc']))podniesStaty($_SESSION['gracz']->getZre(),$lvl,"zre");
    else if(isset($_POST['wytrzymalosc']))podniesStaty($_SESSION['gracz']->getWytrz(),$lvl,"wyt");
    else if(isset($_POST['szczescie']))podniesStaty($_SESSION['gracz']->getSzcz(),$lvl,"szcz");
    header("Location: trening.php");
?>