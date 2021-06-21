-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 21 Cze 2021, 18:18
-- Wersja serwera: 10.4.11-MariaDB
-- Wersja PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `voidtravel`
--
CREATE DATABASE IF NOT EXISTS `voidtravel2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `voidtravel2`;

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `dailyEnergy` ()  NO SQL
BEGIN
    	DECLARE nowaEnergiaStandard INT DEFAULT 30;
        DECLARE nowaEnergiaPremium INT DEFAULT 60;
        DECLARE nowaEnergiaAdmin INT DEFAULT 90;
    	UPDATE uzytkownik SET Energia = nowaEnergiaStandard WHERE Typ_kt=1;
        UPDATE uzytkownik SET Energia = nowaEnergiaPremium WHERE Typ_kt=2;
        UPDATE uzytkownik SET Energia = nowaEnergiaAdmin WHERE Typ_kt=3;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `energiaLower` (IN `graczId` INT)  NO SQL
BEGIN
    	DECLARE staraEnergia INT DEFAULT 0;
    	DECLARE nowaEnergia INT DEFAULT 0;
    	SELECT Energia INTO staraEnergia FROM uzytkownik WHERE ID = graczID;
    	SET nowaEnergia = staraEnergia-1;
    	UPDATE uzytkownik SET Energia=nowaEnergia WHERE ID=graczId;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `generateItems` (IN `graczId` INT)  NO SQL
BEGIN 
	DECLARE a int DEFAULT 0;
    DECLARE rand int DEFAULT 0;
    DECLARE limitRand int DEFAULT 0;
    SELECT count(*) into limitRand FROM przedmiot;
    label1:LOOP
    	IF a < 6 THEN 
        	SET rand = (RAND()%limitRand)+1;
          INSERT INTO sklep(Id_klienta,Id_przedmiotu) VALUES(graczId,rand);
            SET a = a+1;
            ITERATE label1;
       	END IF;
        LEAVE label1;
    END LOOP label1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getLogs` ()  NO SQL
BEGIN 
	SELECT * FROM logs INNER JOIN log_desc ON logs.Log_ID = log_desc.ID;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `itemPurchase` (IN `graczId` INT, IN `itemId` INT)  NO SQL
BEGIN
	DELETE FROM sklep WHERE Id_klienta = graczId AND Id_Przedmiotu = itemId LIMIT 1;
    INSERT INTO przedmiotgracz(Id_Wlasciciela,Id_Przedmiotu) VALUES(graczId,itemId);
    SELECT Cena from przedmiot WHERE ID=itemId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `items` (IN `graczId` INT)  NO SQL
BEGIN
    	DECLARE itemsNumber INT DEFAULT 0;
    SELECT count(*) INTO itemsNumber FROM sklep WHERE Id_klienta = graczId;
        IF itemsNumber = 0 THEN
        	CALL generateItems(graczId);
        END IF;
        SELECT Id_przedmiotu,Typ,Nazwa,Wartosc_max,Wartosc_min,Cena FROM przedmiot INNER JOIN sklep ON przedmiot.ID = sklep.Id_przedmiotu WHERE Id_klienta = graczId;
        
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `itemSell` (IN `graczId` INT, IN `itemId` INT)  NO SQL
BEGIN
	DELETE FROM przedmiotgracz WHERE Id_Wlasciciela = graczId AND Id_Przedmiotu = itemId LIMIT 1;
    SELECT Cena from przedmiot WHERE ID=itemId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `podnieshajs` (IN `graczID` INT, IN `hajs` INT)  BEGIN
    	DECLARE staryHajs INT DEFAULT 0;
    	DECLARE nowyHajs INT DEFAULT 0;
    	SELECT Waluta INTO staryHajs FROM uzytkownik WHERE ID = graczID;
    	SET nowyHajs = staryHajs+hajs;
    	UPDATE uzytkownik SET Waluta=nowyHajs WHERE ID=graczId;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `warta` (IN `graczId` INT, IN `zarobek` INT)  NO SQL
BEGIN
    	DELETE FROM warta WHERE id_gracza = graczId;
        CALL podnieshajs(graczId,zarobek);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logs`
--

CREATE TABLE `logs` (
  `ID` int(11) NOT NULL,
  `Log_ID` int(11) DEFAULT NULL,
  `Wartosc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `logs`
--

INSERT INTO `logs` (`ID`, `Log_ID`, `Wartosc`) VALUES
(1, 1, 'artix.112@wp.pl'),
(2, 1, 'test2@wp.pl'),
(3, 1, 'test3@wp.pl'),
(4, 1, 'testfinal@wp.pl');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `log_desc`
--

CREATE TABLE `log_desc` (
  `ID` int(11) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `log_desc`
--

INSERT INTO `log_desc` (`ID`, `Description`) VALUES
(1, 'Utworzono nowego uzytkownika\r\nValue = email'),
(2, 'Kupno premium, value = email');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przeciwnik`
--

CREATE TABLE `przeciwnik` (
  `ID` int(11) NOT NULL,
  `Nazwa` varchar(25) DEFAULT NULL,
  `Poziom` int(11) DEFAULT NULL,
  `Sila` int(11) DEFAULT NULL,
  `Wytrzymalosc` int(11) DEFAULT NULL,
  `Zrecznosc` int(11) DEFAULT NULL,
  `Szczescie` int(11) DEFAULT NULL,
  `Opis` text DEFAULT NULL,
  `href` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `przeciwnik`
--

INSERT INTO `przeciwnik` (`ID`, `Nazwa`, `Poziom`, `Sila`, `Wytrzymalosc`, `Zrecznosc`, `Szczescie`, `Opis`, `href`) VALUES
(1, 'Mały Gremlin', 1, 2, 2, 2, 1, 'Zaraz po przejściu przez bramę miasta, napotykasz niewielką, bardzo uroczą istotę której widocznie nie przeszkadza tak bardzo ludzkie światło. Gdy tylko cię spostrzega, wyszczerza długie, kilkucentymetrowe kły i rzuca się w twoim kierunku', 'Small-Gremlin.png'),
(2, 'Istota z mgły', 2, 8, 10, 4, 5, 'Gdy przedzierasz się przez mgłę, zauważasz że w jednym miejscu wygląda ona na znacznie gęstszą i prawie że namacalną. Już po chwili, dziwna formacja zaczyna zbliżać się do Ciebie, a z każdym metrem coraz bardziej przypomina zdeformowanego humanoida', 'Lesser-Void-Cloud.png'),
(3, 'Żołnierz pustki', 4, 18, 16, 11, 14, 'Gdy idziesz jeszcze głębiej w mgłę, zaczynasz słyszeć dźwięczenie metalu uderzanego o coś twardego. Zbliżasz się do źródła dźwięku, a twoim oczom ukazuje się humanoidalna figura zbudowana z tkanki kostnej, dzierżąca miecz. Potwór rzuca się w twoim kierunku jak tylko cię spostrzega.', 'VoidSoldier.png'),
(4, 'Spaczona Latarnia', 5, 22, 20, 16, 20, 'To be added.', 'Corrupted_Witch.jpg'),
(5, 'Komandor pustki', 7, 35, 33, 28, 30, 'To be added.', 'VoidCommander.jpg'),
(6, 'Spaczony Kroczący', 8, 40, 49, 35, 35, 'To be added.', 'TheFacelessOne.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmiot`
--

CREATE TABLE `przedmiot` (
  `ID` int(11) NOT NULL,
  `Typ` varchar(15) DEFAULT NULL,
  `Nazwa` varchar(25) DEFAULT NULL,
  `Wartosc_min` int(11) DEFAULT NULL,
  `Wartosc_max` int(11) DEFAULT NULL,
  `Cena` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `przedmiot`
--

INSERT INTO `przedmiot` (`ID`, `Typ`, `Nazwa`, `Wartosc_min`, `Wartosc_max`, `Cena`) VALUES
(1, 'Bron', 'Prosty sztylet', 1, 3, 50),
(2, 'Bron', 'Krotki miecz', 2, 4, 80),
(3, 'Bron', 'Miedziana wlocznia', 1, 5, 75),
(4, 'Bron', 'Stalowy sztylet', 3, 8, 160);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmiotgracz`
--

CREATE TABLE `przedmiotgracz` (
  `ID` int(11) NOT NULL,
  `Id_Wlasciciela` int(11) DEFAULT NULL,
  `Id_Przedmiotu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `przedmiotgracz`
--

INSERT INTO `przedmiotgracz` (`ID`, `Id_Wlasciciela`, `Id_Przedmiotu`) VALUES
(30, 1, 1),
(31, 1, 1),
(32, 1, 2),
(33, 1, 2),
(34, 1, 2),
(35, 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sklep`
--

CREATE TABLE `sklep` (
  `ID` int(11) NOT NULL,
  `Id_klienta` int(11) DEFAULT NULL,
  `Id_przedmiotu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `sklep`
--

INSERT INTO `sklep` (`ID`, `Id_klienta`, `Id_przedmiotu`) VALUES
(95481, 1, 1),
(95482, 1, 1),
(95483, 1, 1),
(95484, 1, 1),
(95485, 1, 2),
(95486, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownik`
--

CREATE TABLE `uzytkownik` (
  `ID` int(11) NOT NULL,
  `Typ_kt` int(11) NOT NULL DEFAULT 0,
  `Login` varchar(15) NOT NULL,
  `Haslo` varchar(15) NOT NULL,
  `Waluta` int(11) NOT NULL DEFAULT 50,
  `Poziom` int(11) NOT NULL DEFAULT 1,
  `Exp_curr` int(11) NOT NULL DEFAULT 0,
  `Exp_max` int(11) NOT NULL DEFAULT 100,
  `Sila` int(11) NOT NULL DEFAULT 1,
  `Wytrzymalosc` int(11) NOT NULL DEFAULT 1,
  `Zrecznosc` int(11) NOT NULL DEFAULT 1,
  `Szczescie` int(11) NOT NULL DEFAULT 1,
  `Etap_eksp` int(11) NOT NULL DEFAULT 1,
  `Email` text NOT NULL,
  `Energia` int(11) NOT NULL DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `uzytkownik`
--

INSERT INTO `uzytkownik` (`ID`, `Typ_kt`, `Login`, `Haslo`, `Waluta`, `Poziom`, `Exp_curr`, `Exp_max`, `Sila`, `Wytrzymalosc`, `Zrecznosc`, `Szczescie`, `Etap_eksp`, `Email`, `Energia`) VALUES
(1, 3, 'Admin', 'Admin', 3039, 6, 17190, 23000, 30, 30, 30, 30, 6, 'artix.112@wp.pl', 0),
(12, 1, 'Ordinary_Guy', 'Kontrakt', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'artix.112@wp.pl', 30),
(13, 2, 'Premium_Guy', 'haslo', 975, 3, 2386, 2700, 15, 15, 15, 15, 4, 'nowy@gracz.pl', 60),
(14, 0, 'Fresh_account', 'Haslo4499', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'arfes716@wp.pl', 30),
(15, 0, 'TesterTriggera', 'Triggera', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'test@test.pl', 30),
(16, 0, 'test2', 'test2', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'test2@wp.pl', 30),
(17, 0, 'test3', 'test3', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'test3@wp.pl', 30),
(18, 0, 'asdf', 'asdf', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'asdf@wp.pl', 30),
(19, 0, 'test4', 'test4', 50, 1, 0, 100, 1, 1, 1, 1, 1, 'testfinal@wp.pl', 30);

--
-- Wyzwalacze `uzytkownik`
--
DELIMITER $$
CREATE TRIGGER `register` AFTER INSERT ON `uzytkownik` FOR EACH ROW BEGIN 
	DECLARE a TEXT;
	SELECT email INTO a FROM `uzytkownik` ORDER BY ID DESC LIMIT 1;
    INSERT INTO logs(Log_ID,Wartosc) VALUES(1,a); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `warta`
--

CREATE TABLE `warta` (
  `id_warty` int(11) NOT NULL,
  `id_gracza` int(11) DEFAULT NULL,
  `data_zakonczenia` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Log_ID` (`Log_ID`);

--
-- Indeksy dla tabeli `log_desc`
--
ALTER TABLE `log_desc`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `przeciwnik`
--
ALTER TABLE `przeciwnik`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `przedmiot`
--
ALTER TABLE `przedmiot`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `przedmiotgracz`
--
ALTER TABLE `przedmiotgracz`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Id_Wlasciciela` (`Id_Wlasciciela`),
  ADD KEY `Id_Przedmiotu` (`Id_Przedmiotu`);

--
-- Indeksy dla tabeli `sklep`
--
ALTER TABLE `sklep`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Id_klienta` (`Id_klienta`),
  ADD KEY `Id_przedmiotu` (`Id_przedmiotu`);

--
-- Indeksy dla tabeli `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `warta`
--
ALTER TABLE `warta`
  ADD PRIMARY KEY (`id_warty`),
  ADD KEY `wartauzytkownik` (`id_gracza`);

--
-- AUTO_INCREMENT dla tabel zrzutów
--

--
-- AUTO_INCREMENT dla tabeli `logs`
--
ALTER TABLE `logs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `log_desc`
--
ALTER TABLE `log_desc`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `przeciwnik`
--
ALTER TABLE `przeciwnik`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `przedmiot`
--
ALTER TABLE `przedmiot`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `przedmiotgracz`
--
ALTER TABLE `przedmiotgracz`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT dla tabeli `sklep`
--
ALTER TABLE `sklep`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95487;

--
-- AUTO_INCREMENT dla tabeli `uzytkownik`
--
ALTER TABLE `uzytkownik`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `warta`
--
ALTER TABLE `warta`
  MODIFY `id_warty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`Log_ID`) REFERENCES `log_desc` (`ID`);

--
-- Ograniczenia dla tabeli `przedmiotgracz`
--
ALTER TABLE `przedmiotgracz`
  ADD CONSTRAINT `przedmiotgracz_ibfk_1` FOREIGN KEY (`Id_Wlasciciela`) REFERENCES `uzytkownik` (`ID`),
  ADD CONSTRAINT `przedmiotgracz_ibfk_2` FOREIGN KEY (`Id_Przedmiotu`) REFERENCES `przedmiot` (`ID`);

--
-- Ograniczenia dla tabeli `sklep`
--
ALTER TABLE `sklep`
  ADD CONSTRAINT `sklep_ibfk_1` FOREIGN KEY (`Id_klienta`) REFERENCES `uzytkownik` (`ID`),
  ADD CONSTRAINT `sklep_ibfk_2` FOREIGN KEY (`Id_przedmiotu`) REFERENCES `przedmiot` (`ID`);

--
-- Ograniczenia dla tabeli `warta`
--
ALTER TABLE `warta`
  ADD CONSTRAINT `wartauzytkownik` FOREIGN KEY (`id_gracza`) REFERENCES `uzytkownik` (`ID`);

DELIMITER $$
--
-- Zdarzenia
--
CREATE DEFINER=`root`@`localhost` EVENT `dailyResetSklep` ON SCHEDULE EVERY 1 DAY STARTS '2021-06-21 16:36:00' ON COMPLETION PRESERVE ENABLE DO DELETE FROM sklep$$

CREATE DEFINER=`root`@`localhost` EVENT `dailyEnergyEvent` ON SCHEDULE EVERY 24 HOUR STARTS '2021-06-18 00:00:00' ON COMPLETION PRESERVE ENABLE DO CALL dailyEnergy()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
