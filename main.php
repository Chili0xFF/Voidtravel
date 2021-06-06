<?php
    include_once 'connect.php';
    include_once 'functions.php';
    session_start();
    if(!isset($_SESSION['gracz']))header("Location: index.php");
    ?>
<html>
<head>
    <title>Voidtravel</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css" type="text/css">
    </head>
    <body>
<div id='container'>
    <div class="panel" id="gorny">
        <div id="main-baner"><img src="img/logo.png"></div>
    </div>
    <div class="panel" id="lewy">
        <div id="menu">
            <table>
                <tr><td><a href='main.php'>Miasto</a></td></tr>
                <tr><td><a href='postac.php'>Postać</a></td></tr>
                <tr><td><a href='eksploracja.php'>Eksploracja</a></td></tr>
                <tr><td><a href='ranking.php'>Ranking</a></td></tr>
                <tr><td><a href='pomoc.php'>Pomoc</a></td></tr>
                <tr><td><a href='logout.php'>Wyloguj</a></td></tr>
            </table>
        </div>
    </div>
    <div class="panel" id="srodkowy">
        <div id="sklep"><a href="sklep.php">SKLEP</a></div><div id="trening"><a href="trening.php">PLAC TRENINGOWY</a></div>
        <div id="warta"><a href="warta.php">WARTA</a></div><div id="świątynia"><a href="świątynia.php">ŚWIĄTYNIA</a></div>
    </div>
    <div class="panel" id="prawy">

    </div>
    <div class="panel" id="dolny">
    Projekt wykonany przez: Artur Szulist, s23049. <a href="https://github.com/Chili0xFF">GitHub</a>
    </div>
</div>
    </body>
</html>