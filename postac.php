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
            <div id='imgBox'><img src="img/player1.jpg"></div>
        </div>
        <div id="statystyki">
            <div id='mainBox'>
            <h1><?php echo $_SESSION['gracz']->getLogin();?></h1>
            <h2>Poziom: <?php echo $_SESSION['gracz']->getLvl();?> Doświadczenie: <?php echo $_SESSION['gracz']->getExpCurrent()."/".$_SESSION['gracz']->getExpMax();?></h2>
            <h3>Fragmenty: <?php echo $_SESSION['gracz']->getFragmenty();?> Ukończone ekspedycje: <?php echo ($_SESSION['gracz']->getEtap()-1)."/".IloscEkspedycji();?></h3>
            </div>
            <div id='statBox'>  
            <table>
                    <tr><th></th><th>Twoja:</th><th>Maksymalna:</th></tr>
                    <tr><th>Siła</th><td><?php echo $_SESSION['gracz']->getSila()?></td><td><?php echo ($_SESSION['gracz']->getLvl()*5)?><td></tr>
                    <tr><th>Wytrzymałość</th><td><?php echo $_SESSION['gracz']->getWytrz()?></td><td><?php echo ($_SESSION['gracz']->getLvl()*5)?><td></tr>
                    <tr><th>Zręczność</th><td><?php echo $_SESSION['gracz']->getZre()?></td><td><?php echo ($_SESSION['gracz']->getLvl()*5)?><td></tr>
                    <tr><th>Szczęście</th><td><?php echo $_SESSION['gracz']->getSzcz()?></td><td><?php echo ($_SESSION['gracz']->getLvl()*5)?><td></tr>
                </table>
            </div>
        </div>
        <div id="eq">
            lewy dolny
        </div>
        <div id="plecak">
            <table id="plecakBox">
                <tr><th>Nazwa</th><th>Skutecznosc</th></tr>
            <?php 
                plecak($_SESSION['gracz']);
            ?>
            </table>
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