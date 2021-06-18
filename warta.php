<?php
    include_once 'istota.php'; 
    include_once 'uzytkownik.php';
    include_once 'connect.php';
    include_once 'functions.php';
    session_start();   //NAJPIERW KLASY. POTEM SESJA. ZAPAMIĘTAĆ.
    if(!isset($_SESSION['gracz'])){
        header("Location: index.php");
        exit();
    }
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
    <div id="awatar">
            <div id='imgBox'><img src="img/void.png" style="width: 100%; height: auto;"></div>
        </div>    
        <div class="opis"><h3>Raz na jakiś czas warto odpocząć i postać na murach. Twoim zadaniem jest wypatrywanie nadchodzących gremlinów, jednak nikt nie będzie miał ci tego za złe jeśli się zdrzemniesz...</h3></div>   
        <div id="wartowanie">
            <?php wartaText(wartaSpr());?>
        </div>
    
    </div>
    <div class="panel" id="prawy">

    </div>
    <div class="panel" id="dolny">
    Projekt wykonany przez: Artur Szulist, s23049. <a href="https://github.com/Chili0xFF">GitHub</a>
    </div>
</div>
    </body>
</html>