<?php
    header('Content-type: text/html; charset=utf-8');
    include_once 'istota.php'; 
    include_once 'uzytkownik.php';
    include_once 'przeciwnik.php';
    include_once 'connect.php';
    include_once 'functions.php';
    
    session_start();   //NAJPIERW KLASY. POTEM SESJA. ZAPAMIĘTAĆ.
    if(!isset($_SESSION['gracz'])){
        header("Location: index.php");
        exit();
    }
    $id_enemy = $_GET['przeciwnik'];
    if($_SESSION['gracz']->getEtap()<$id_enemy||$id_enemy<1){
        $_SESSION['error']="Walka z tym przeciwnikiem to samobójstwo!";
        header("Location: eksploracja.php");
        exit();
    }
    $query = "SELECT * FROM przeciwnik WHERE ID=$id_enemy";
    $result = $db_connect->query($query); 
        if($row=$result->fetch_assoc()){
                $przeciwnik = new przeciwnik($row);
                //wszystko bangla. Teraz stwórz funkcję która odbierze tego wroga, odbierze gracza
                //i ich na siebie napuści.
                
            }
            else{
                echo "Szukałeś, szukałeś, nic nie znalazłeś we mgle. Wyczyściłeś pustkę do cna! Możesz teraz iść spokojnie odpocząć z myślą że świat ludzi jest o krok bliżej do zwycięstwa!";
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
        <div class="title"><h1>Eksploracja</h1></div>
            <div id="obrazEnemy">
                <div id='imgBox'><img src="img/<?php echo $przeciwnik->getHref();?>"></div>
            </div>
        <div id="statystyki" style='height: 30%;'>
            <div id='mainBox'>
                <h1><?php echo $przeciwnik->getNazwa();?></h1>
                <h2>Poziom: <?php echo $przeciwnik->getLvl();?> </h2>
            </div>
            <div id='statBox'>
                <table>
                    <tr><th></th><th>Przeciwnika:</th></tr>
                    <tr><th>Siła</th><td><?php echo $przeciwnik->getSila()?></td></tr>
                    <tr><th>Wytrzymałość</th><td><?php echo $przeciwnik->getWytrz()?></td><td></tr>
                    <tr><th>Zręczność</th><td><?php echo $przeciwnik->getZre()?></td></tr>
                    <tr><th>Szczęście</th><td><?php echo $przeciwnik->getSzcz()?></td></tr>
                </table>
            </div>
        </div>
        <div id="opisEnemy">
            <h1>Narracja</h1>
            <?php 
                echo $przeciwnik->getOpis();
            ?>
        </div>
        <div id="Arena">
            <?php 
                eksploracjaWalka($_SESSION['gracz'],$przeciwnik);
            ?>
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