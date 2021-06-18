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
        <div class="opis"><h3>Plotka w mieście mówi, że ten kamień runiczny wyrósł spod ziemi niedługo po tym jak pojawiła się mgła. Mówi się także, że Kroczący w Mgle tacy jak Ty, mogą tutaj ofiarować fragmenty które pozostają z pokonanych potworów, aby podnieść swoje zdolności bojowe. Może i to tylko bujda... a może nie?</h3></div>
        <br>
        <br>
        <br>
        <div id="fragmenty"><h2>Fragmenty: <?php
                                             echo $_SESSION['gracz']->getFragmenty();
                                             if(isset($_SESSION['error']))echo "<div style='color: red'>".$_SESSION['error']."</div>";?>
                                             </h2></div>
        <div id="treningStaty">
            <div id="treningKoszta">
                    <div id="treningSila"><form action="trenuj.php" method='post'>Siła: <?php echo $_SESSION['gracz']->getSila()."/".($_SESSION['gracz']->getLvl()*5); if($_SESSION['gracz']->getSila()<($_SESSION['gracz']->getLvl()*5)){echo "&nbsp&nbsp&nbsp&nbspKoszt:".($_SESSION['gracz']->getSila()*5)."<input type='submit' class='guzik_trenuj' name='sila' value='trenuj'></input>";}?></form></div>
                    <div id="treningSila"><form action="trenuj.php" method='post'>Zręczność: <?php echo $_SESSION['gracz']->getZre()."/".($_SESSION['gracz']->getLvl()*5); if($_SESSION['gracz']->getZre()<($_SESSION['gracz']->getLvl()*5)){echo "&nbsp&nbspKoszt:".($_SESSION['gracz']->getZre()*5)."<input type='submit' class='guzik_trenuj' name='zrecznosc' value='trenuj'></input>";}?></form></div>
                    <div id="treningSila"><form action="trenuj.php" method='post'>Wytrzymałość: <?php echo $_SESSION['gracz']->getWytrz()."/".($_SESSION['gracz']->getLvl()*5); if($_SESSION['gracz']->getWytrz()<($_SESSION['gracz']->getLvl()*5)){echo "&nbsp&nbspKoszt:".($_SESSION['gracz']->getWytrz()*5)."<input type='submit' class='guzik_trenuj' name='wytrzymalosc' value='trenuj'></input>";}?></form></div>
                    <div id="treningSila"><form action="trenuj.php" method='post'>Szczęście: <?php echo $_SESSION['gracz']->getSzcz()."/".($_SESSION['gracz']->getLvl()*5); if($_SESSION['gracz']->getSzcz()<($_SESSION['gracz']->getLvl()*5)){echo "&nbsp&nbspKoszt:".($_SESSION['gracz']->getSzcz()*5)."<input type='submit' class='guzik_trenuj' name='szczescie' value='trenuj'></input>";}?></form></div>
            </div>
        </div>
    </div>
    <div class="panel" id="prawy">

    </div>
    <div class="panel" id="dolny">
    Projekt wykonany przez: Artur Szulist, s23049. <a href="https://github.com/Chili0xFF">GitHub</a>
    </div>
</div>
<?php 
    resetError();
?>
    </body>
</html>