<?php
    include_once 'istota.php'; 
    class uzytkownik extends istota{
        private $typ,$login,$haslo,$fragmenty,$expCurr,$expMax,$etap,$email;
        function __construct($row){
            parent::__construct($row['ID'],$row['Poziom'],$row['Sila'],$row['Wytrzymalosc'],$row['Zrecznosc'],$row['Szczescie']);
            $this->typ=$row['Typ_kt'];
            $this->login=$row['Login'];
            $this->haslo=$row['Haslo'];
            $this->email=$row['Email'];
            $this->fragmenty=$row['Waluta'];
            $this->expCurr=$row['Exp_curr'];
            $this->expMax=$row['Exp_max'];
            $this->etap=$row['Etap_eksp'];
        }
        function getTyp(){
            return $this->typ;
        }
        function getLogin(){
            return $this->login;
        }
        function getHaslo(){
            return $this->haslo;
        }
        function getEmail(){
            return $this->email;
        }
        function getFragmenty(){
            return $this->fragmenty;
        }
        function getExpCurrent(){
            return $this->expCurr;
        }
        function getExpMax(){
            return $this->expMax;
        }
        function getEtap(){
            return $this->etap;
        }
        /**
         * Set the value of email
         *
         * @return  self
         */ 
        public function setEmail($email)
        {
                $this->email = $email;

                return $this;
        }

        /**
         * Set the value of typ
         *
         * @return  self
         */ 
        public function setTyp($typ)
        {
                $this->typ = $typ;

                return $this;
        }

        /**
         * Set the value of login
         *
         * @return  self
         */ 
        public function setLogin($login)
        {
                $this->login = $login;

                return $this;
        }

        /**
         * Set the value of haslo
         *
         * @return  self
         */ 
        public function setHaslo($haslo)
        {
                $this->haslo = $haslo;

                return $this;
        }

        /**
         * Set the value of fragmenty
         *
         * @return  self
         */ 
        public function setFragmenty($fragmenty)
        {
                $this->fragmenty=$fragmenty;
                return $this;
        }
        /**
         * Set the value of expCurr
         *
         * @return  self
         */ 
        public function setExpCurr($expCurr)
        {
                $this->expCurr = $expCurr;

                return $this;
        }

        /**
         * Set the value of expMax
         *
         * @return  self
         */ 
        public function setExpMax($expMax)
        {
                $this->expMax = $expMax;

                return $this;
        }
        /**
         * Set the value of etap
         *
         * @return  self
         */ 
        public function setEtap($etap)
        {
                $this->etap = $etap;

                return $this;
        }
        public function changeFrags($modyfikator){
            include 'connect.php';
            $portfel = $this->getFragmenty();
            $nowyPortfel = $portfel + $modyfikator;
            $graczId=$this->getId();
            if($nowyPortfel>=0){
                $this->setFragmenty($nowyPortfel);
                $query = "UPDATE uzytkownik SET Waluta=$nowyPortfel WHERE ID='$graczId'";
                $result = $db_connect->query($query);
                return true;
            }
            else{
                $_SESSION['error']="Niewystarczająca ilość fragmentów!";
                return false;
            }
        }
        public function gainExp($modyfikator){
            include 'connect.php';
            $expCurr = $this->getExpCurrent();
            $expNew = $expCurr+$modyfikator;
            $this->setExpCurr($expNew);
            $graczId=$this->getId();
            $query = "UPDATE uzytkownik SET Exp_curr=$expNew WHERE ID='$graczId'";
            $result = $db_connect->query($query);
            if($this->getExpMax()!=0)
                {
                    if($this->getExpCurrent()>=$this->getExpMax())
                    {
                        return true;
                    }
                    else return false;
                }
        }
        public function lvlUp(){
            include 'connect.php';
            $expAdvancement = [0,300,900,2700,6500,14000,23000,34000,48000,64000,85000,100000,120000,140000,165000,195000,225000,265000,305000,355000,0];
            $lvlCurr = $this->getLvl();
            $lvlNew = $lvlCurr+1;
            $graczId=$this->getId();
            $query = "UPDATE uzytkownik SET Poziom=$lvlNew WHERE ID='$graczId'";
            $result = $db_connect->query($query);
            $this->setLvl($lvlNew);
            $newExpMax = $expAdvancement[$lvlNew];
            $this->setExpMax($newExpMax);
            $query = "UPDATE uzytkownik SET Exp_max=$newExpMax WHERE ID='$graczId'";
            $result = $db_connect->query($query);
        }
        public function etapUp(){
            include 'connect.php';
            $etapCurr = $this->getEtap();
            $etapNew = $etapCurr+1;
            $this->setEtap($etapNew);
            $graczId=$this->getId();
            $query = "UPDATE uzytkownik SET Etap_eksp=$etapNew WHERE ID='$graczId'";
            $result = $db_connect->query($query);
        }
    }
?>