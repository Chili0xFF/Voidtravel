<?php 
    include_once 'istota.php'; 
    class przeciwnik extends istota{
        private $nazwa,$opis,$href;
        function __construct($row){
            parent::__construct($row['ID'],$row['Poziom'],$row['Sila'],$row['Wytrzymalosc'],$row['Zrecznosc'],$row['Szczescie']);
            $this->nazwa=$row['Nazwa'];
            $this->opis=$row['Opis'];
            $this->href=$row['href'];
        }

        /**
         * Get the value of nazwa
         */ 
        public function getNazwa()
        {
                return $this->nazwa;
        }

        /**
         * Set the value of nazwa
         *
         * @return  self
         */ 
        public function setNazwa($nazwa)
        {
                $this->nazwa = $nazwa;

                return $this;
        }

        /**
         * Get the value of opis
         */ 
        public function getOpis()
        {
                return $this->opis;
        }

        /**
         * Set the value of opis
         *
         * @return  self
         */ 
        public function setOpis($opis)
        {
                $this->opis = $opis;

                return $this;
        }

        /**
         * Get the value of href
         */ 
        public function getHref()
        {
                return $this->href;
        }

        /**
         * Set the value of href
         *
         * @return  self
         */ 
        public function setHref($href)
        {
                $this->href = $href;

                return $this;
        }
    }

?>