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

        $query = "SELECT Nazwa, Wartosc_min, Wartosc_max FROM `przedmiot` INNER JOIN `przedmiotgracz` ON `przedmiot`.`ID` = `przedmiotgracz`.`Id_Wlasciciela` INNER JOIN `uzytkownik` ON `przedmiotgracz`.Id_Wlasciciela = `uzytkownik`.ID WHERE `uzytkownik`.ID='1'";
        $result = $db_connect->query($query);
        while($row=$result->fetch_assoc()){
            echo "<tr><td>".$row['Nazwa']."</td><td>".$row['Wartosc_min']."~".$row['Wartosc_max']."</td></tr>";
            echo "<br>";
        }
    }
?>