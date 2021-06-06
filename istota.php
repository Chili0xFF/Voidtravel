<?php 
    class istota{
        private $id,$lvl,$sila,$wytrz,$zre,$szcz;
        function __construct($aid,$lvl,$sila,$wytrz,$zre,$szcz){
            $this->id=$aid;
            $this->lvl=$lvl;
            $this->sila=$sila;
            $this->wytrz=$wytrz;
            $this->zre=$zre;
            $this->szcz=$szcz;
        }
        /**
         * Get the value of id
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of id
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of lvl
         */ 
        public function getLvl()
        {
                return $this->lvl;
        }

        /**
         * Set the value of lvl
         *
         * @return  self
         */ 
        public function setLvl($lvl)
        {
                $this->lvl = $lvl;

                return $this;
        }

        /**
         * Get the value of sila
         */ 
        public function getSila()
        {
                return $this->sila;
        }

        /**
         * Set the value of sila
         *
         * @return  self
         */ 
        public function setSila($sila)
        {
                $this->sila = $sila;

                return $this;
        }

        /**
         * Get the value of wytrz
         */ 
        public function getWytrz()
        {
                return $this->wytrz;
        }

        /**
         * Set the value of wytrz
         *
         * @return  self
         */ 
        public function setWytrz($wytrz)
        {
                $this->wytrz = $wytrz;

                return $this;
        }

        /**
         * Get the value of zre
         */ 
        public function getZre()
        {
                return $this->zre;
        }

        /**
         * Set the value of zre
         *
         * @return  self
         */ 
        public function setZre($zre)
        {
                $this->zre = $zre;

                return $this;
        }

        /**
         * Get the value of szcz
         */ 
        public function getSzcz()
        {
                return $this->szcz;
        }

        /**
         * Set the value of szcz
         *
         * @return  self
         */ 
        public function setSzcz($szcz)
        {
                $this->szcz = $szcz;

                return $this;
        }
        public function uderz(){
                return ($this->getSila());//*$this->getModyfikator());
        }
    }


?>