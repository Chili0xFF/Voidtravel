<?php 
    function login($login,$haslo){
        session_start();
        include 'connect.php';
        include_once 'istota.php'; 
        include_once 'uzytkownik.php';
        $login = htmlentities($login,ENT_QUOTES,"UTF-8");
        $haslo = htmlentities($haslo,ENT_QUOTES,"UTF-8");
        $query = "SELECT * FROM uzytkownik WHERE login='$login' AND Haslo='$haslo';";
        $result = $db_connect->query($query);
        if($row=$result->fetch_assoc()){
            var_dump($row);
            $_SESSION['gracz'] = new uzytkownik($row);
            echo "<br><br>";
            var_dump($_SESSION['gracz']);
            echo "<br><br><br>".$_SESSION['gracz']->getSila();
        }

    }
    
    function register($login,$haslo,$email){
        include 'connect.php';
        $login = htmlentities($login,ENT_QUOTES,"UTF-8");
        $haslo = htmlentities($haslo,ENT_QUOTES,"UTF-8");
        $query = "INSERT INTO uzytkownik(Login,Haslo,Email) VALUES ('$login','$haslo','$email');";
        $result = $db_connect->query($query);
        if($result){
            login($login,$haslo);
        }
    }
    ///ZROBIĆ ABY TA FUNKCJA ZLICZAŁA ILOŚĆ ETAPÓW EKSPEDYCJI A NIE NA TWARDO DAWAŁA 10
    function IloscEkspedycji(){
        include 'connect.php';
        $query = 'SELECT count(ID) FROM przeciwnik;';
        $result = $db_connect->query($query);
        $row=$result->fetch_assoc();
        return $row['count(ID)'];
    }
    function podniesStaty($aktualna,$lvl,$stat){
        //Sprawdzam drugi raz, dla pewności. Ma to na celu 
        //jedynie zabezpieczenie bazy danych przed dziwnymi exploitami
        include_once 'connect.php';
        include_once 'uzytkownik.php';
        session_start();
        if($aktualna<$lvl*5){
            $nowa = $aktualna+1;
            $id_gracza = $_SESSION['gracz']->getId();
            $cena = $aktualna*5*(-1);
            if($_SESSION['gracz']->changeFrags($cena)){
                switch ($stat) {
                    case 'str':
                        $query = "UPDATE uzytkownik SET Sila='$nowa' WHERE ID='$id_gracza'";
                        $_SESSION['gracz']->setSila($nowa);
                        break;
                    case 'zre':
                        $query = "UPDATE uzytkownik SET Zrecznosc='$nowa' WHERE ID='$id_gracza'";
                        $_SESSION['gracz']->setZre($nowa);
                        break;
                    case 'wyt':
                        $query = "UPDATE uzytkownik SET Wytrzymalosc='$nowa' WHERE ID='$id_gracza'";
                        $_SESSION['gracz']->setWytrz($nowa);
                        break;
                    case 'szcz':
                        $query = "UPDATE uzytkownik SET Szczescie='$nowa' WHERE ID='$id_gracza'";
                        $_SESSION['gracz']->setSzcz($nowa);
                        break;
                    default:
                        return false;
                        break;
                }
                $result = $db_connect->query($query);
            }
        }
    }
    function change_fragments($modyfikator,$graczId){    //gdy modyfikator przesłany będzie dodatni, stan konta sesji i bazy danych podniesie się i odwrotnie 
        include 'connect.php';
        include_once 'uzytkownik.php';
        $portfel = $_SESSION['gracz']->getFragmenty();
        $nowyPortfel = $portfel + $modyfikator;
        if($nowyPortfel>=0){
            $_SESSION['gracz']->setFragmenty($nowyPortfel);
            $query = "UPDATE uzytkownik SET Waluta=$nowyPortfel WHERE ID='$graczId'";
            $result = $db_connect->query($query);
            echo $query;
            echo $result;
            return true;
        }
        else{
            $_SESSION['error']="Niewystarczająca ilość fragmentów!";
            return false;
        }
        //zwracamy true, jeśli operacja się udała, false jeśli nie
    }
    function resetError(){
        $_SESSION['error']="";
    }
    function critChance($istota){
        include_once 'istota.php';
        $critBase = 1/($istota->getLvl());
        $critChance = $critBase+($istota->getSzcz()/2);
        return round($critChance,2);
    }
    function initiative($istota){
        include_once 'istota.php';
        $init = rand(1,20)+$istota->getZre();
        return $init;
    }
    function eksploracjaWalka($gracz,$przeciwnik){
        include_once 'uzytkownik.php';
        include_once 'przeciwnik.php';
        $hpGracz = $gracz->getWytrz()*5;
        $hpPrzec = $przeciwnik->getWytrz()*5;
        $initGracz = initiative($gracz);
        $initPrzec = initiative($przeciwnik);
        $critGracz=critChance($gracz);
        $critPrzec=critChance($przeciwnik);
        echo "Inicjatywa: Gracz($initGracz) Przeciwnik($initPrzec)<br>";
        if($initGracz>=$initPrzec)$a=0;
        else $a=1;
        $runda=1;
        for($i=$a;$hpGracz>0&&$hpPrzec>0;$i++){
            $a = rand(1,10000)/100;
            if($i%2==0){
                echo $gracz->getLogin()." atakuje";
                if($a<$critGracz){
                    echo " krytycznie";
                    $hpPrzec-=$gracz->uderz();
                }
                echo "!<br>";
                $hpPrzec-=$gracz->uderz();
            }
            else{
                echo $przeciwnik->getNazwa()." atakuje";
                if($a<$critPrzec){
                    echo " krytycznie";
                    $hpGracz-=$przeciwnik->uderz();
                }
                echo "!<br>";
                $hpGracz-=$przeciwnik->uderz();
            }
        }
        echo "<h1>Wygrał: ";
        if($hpPrzec>0){
            echo $przeciwnik->getNazwa()."</h1>";
        }
        else {
            echo $gracz->getLogin()."</h1>";
            wygrana($gracz,$przeciwnik);
        }
    }
    function wygrana($gracz,$przeciwnik){
        $expGain=ceil(($przeciwnik->getLvl()*50)/$gracz->getLvl());
        $fragGain=($przeciwnik->getLvl()*25);
        echo "Doświadczenie: ".$expGain;
        echo "<br>Fragmenty: ".$fragGain;
        $gracz->changeFrags($fragGain);
        if($gracz->gainExp($expGain)){
            echo "<h1>LVLUP!!!</h1>";
            $gracz->lvlUp();
        }
        if($gracz->getEtap()<=$przeciwnik->getId()){
           $gracz->etapUp();
        }
    }
    function ranking(){
        include 'connect.php';
        $query = 'SELECT * FROM uzytkownik ORDER BY Exp_curr DESC';
        $result = $db_connect->query($query);
        $i=1;
        while($row=$result->fetch_assoc()){
            $uzytkownikRank = new uzytkownik($row);
            $nazwa = $uzytkownikRank->getLogin();
            $poziom = $uzytkownikRank->getLvl();
            $doswiadczenie = $uzytkownikRank->getExpCurrent();
            $ekspedycja = $uzytkownikRank->getEtap()-1;
            echo "<tr><td>$i.</td><td>$nazwa</td><td>$poziom</td><td>$doswiadczenie</td><td>$ekspedycja</td></tr>";
            $i++;
        }
    }
    function plecak($gracz){
        include 'connect.php';
        $id = $gracz->getId();
        $query = "SELECT przedmiot.ID, Nazwa, Wartosc_min, Wartosc_max, Cena FROM `przedmiot` INNER JOIN `przedmiotgracz` ON `przedmiot`.`ID` = `przedmiotgracz`.`Id_Przedmiotu` INNER JOIN `uzytkownik` ON `przedmiotgracz`.Id_Wlasciciela = `uzytkownik`.ID WHERE `uzytkownik`.ID='$id'";
        $result = $db_connect->query($query);
        while($row=$result->fetch_assoc()){
            echo "<tr><td>".$row['Nazwa']."</td><td>".$row['Wartosc_min']."~".$row['Wartosc_max']."</td><td>".$row['Cena']."</td><td><a href='sell.php?id=".$row['ID']."'>SPRZEDAJ</a></td></tr>";
            echo "<br>";
        }
    }
    function enemies($etapMax){
        include 'connect.php';
        $query = "SELECT * FROM przeciwnik WHERE ID<=".$etapMax.";";
        $result = $db_connect->query($query);
        $i=0;
        
        while($row=$result->fetch_assoc()){
        $przeciwnik[$i] = new przeciwnik($row);
        $i++;
        }
        echo "<table id='galeriaPrzeciwnikow'>";
        for($j=0;$j-1<sizeof($przeciwnik)%4;$j++){
            echo "<tr>";
            for($i=$j*4;$i<sizeof($przeciwnik)&&($i<($j+1)*4);$i++){
                echo "<td><B>".($przeciwnik[$i]->getNazwa())."</B></td>";
            }echo "</tr>";
            for($i=$j*4;$i<sizeof($przeciwnik)&&($i<($j+1)*4);$i++){
                echo "<td><img class='enemyImage' src='img/".$przeciwnik[$i]->getHref()."'></td>";
            }echo "</tr>";
            for($i=$j*4;$i<sizeof($przeciwnik)&&($i<($j+1)*4);$i++){
                echo "<td><a href='walka.php?przeciwnik=".$przeciwnik[$i]->getId()."'>Walcz!</a></td>";
            }echo "</tr>";
            }
        
        echo "</table>";
    }
    function wartaSpr(){
        include 'connect.php';
        $graczId = $_SESSION['gracz']->getId();
        $query = "SELECT * FROM warta WHERE id_gracza=$graczId";
        $result = $db_connect->query($query);
        $row=$result->fetch_assoc();
        if($row==NULL){
            return FALSE;
        }
        else{
            return $row['data_zakonczenia'];
        }
        
    }
    function wartaText($result){
        if($result==FALSE){
            echo "<form action='wartujemy.php' method='get'>
            <label>Ile godzin chcesz wartować: <input type='number' min='1' max='48' name='czas'></label>
            <input type='submit'></form>";
        }
        else{
            echo "Twoja warta kończy się: ".$result;
        }
    }
    function warta($czas){
        include 'connect.php';
        $graczId = $_SESSION['gracz']->getId();
        $year = date("Y");
        $month = date("F");
        $day = date("D");
        $hour = date("G");
        $minute = date("i");
        $hajs=$czas*5*$_SESSION['gracz']->getLvl();
        $czas = $czas*3600; //godziny na sekundy;
        $dateEnd = date("Y-m-d H:i:s",time()+$czas);
        echo $dateEnd;
        
//date("Y F D G:i",time()+$czas),
        $query = "INSERT INTO warta(id_gracza,data_zakonczenia)VALUES('$graczId','$dateEnd');";
        $result = $db_connect->query($query);
        if($result==false){
            $_SESSION['error']="ERROR OCCURED. PLEASE TRY AGAIN LATER";
        }
        $query = "CREATE EVENT IF NOT EXISTS warta$graczId
        ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL $czas second
        DO
          CALL warta($graczId,$hajs);
        ";
        $result = $db_connect->query($query);
        var_dump($result);
    }
    function item(){
        include 'connect.php';
        $graczId = $_SESSION['gracz']->getId();
        $query = "CALL items($graczId);";
        $result = $db_connect->query($query);
        echo "<table id='tableitems'><tr><th>Typ</th><th>Nazwa</th><th>Wartosc</th><th>Cena</th></tr>";
        while($row = $result->fetch_assoc()){
            echo "<tr><td>".$row['Typ']."</td><td>".$row['Nazwa']."</td><td>".$row['Wartosc_min']."/".$row['Wartosc_max']."</td><td>".$row['Cena']."</td><td><a href='kup.php?id=".$row['Id_przedmiotu']."'>KUP TERAZ</a></td></tr>";
        }
        echo "</table>";
    }
    function kup($itemId){
        include 'connect.php';
        
        $graczId = $_SESSION['gracz']->getId();
        echo "itemId ".$itemId." graczId ".$graczId."<br>";
        $query = "SELECT * FROM sklep WHERE Id_klienta=$graczId AND Id_przedmiotu=$itemId";
        $result = $db_connect->query($query);
        $row=$result->fetch_assoc();
        
        $query = "CALL itemPurchase($graczId,$itemId);";
        $result = $db_connect->query($query);
        $row = $result->fetch_assoc();
        change_fragments(($row['Cena'])*(-1),$graczId);
    }
    function sprzedaj($itemId){
        include 'connect.php';
        $graczId = $_SESSION['gracz']->getId();
        //echo "itemId ".$itemId." graczId ".$graczId."<br>";
        $query = "SELECT * FROM przedmiotgracz WHERE Id_Wlasciciela=$graczId AND Id_Przedmiotu=$itemId";
        $result = $db_connect->query($query);
        if($row=$result->fetch_assoc()){
            $query = "CALL itemSell($graczId,$itemId);";
            $result = $db_connect->query($query);
            $row = $result->fetch_assoc();
            change_fragments(($row['Cena']),$graczId);
        }
    }
    function getLogs(){
        include 'connect.php';
        $query = 'CALL getLogs();';
        $result = $db_connect->query($query);
        echo "<table id='logs'><tr><th>ID</th><th>Description</th><th>Value</th></tr>";
        while($row = $result->fetch_array()){
            echo "<tr><td>".$row['ID']."</td><td>".$row['Description']."</td><td>".$row['Wartosc']."</td></tr>";
        }
        echo "</table>";
    }
    function energiaSpr(){
        include 'connect.php';
        $graczId = $_SESSION['gracz']->getId();
        $query = "SELECT energia FROM uzytkownik WHERE ID = $graczId";
        $result = $db_connect->query($query);
        if($row = $result->fetch_assoc()){
            return $row['energia'];
        }
    }
    function energiaLower($graczId){
        include 'connect.php';
        $query = "CALL energiaLower($graczId)";
        $result = $db_connect->query($query);
    }
?>