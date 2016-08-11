-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2016 at 10:31 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `schedule_ace`
--

-- --------------------------------------------------------

--
-- Table structure for table `materie`
--

CREATE TABLE IF NOT EXISTS `materie` (
  `id_materie` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) NOT NULL,
  `an` int(1) NOT NULL,
  `semestru` int(11) NOT NULL,
  `descriere` mediumtext NOT NULL,
  `tip_evaluare` int(1) NOT NULL COMMENT '1 - examen, 2 - colocviu, 3 - proiect',
  `nr_credite` int(2) NOT NULL,
  `tip_materie` int(1) NOT NULL COMMENT '1 - curs, 2 - laborator, 3 - seminar, 4 - proiect',
  `durata` int(2) NOT NULL COMMENT '1-o ora, 2-2 ore, 3-3 ore',
  `tip_sala_curs` int(2) NOT NULL COMMENT '1 - normal, 2 - videoproiector, 3 - calculatoare, 4 - aula, 5 - sala speciala ',
  `id_sala_dedicata` int(3) NOT NULL COMMENT '0 - no dedicated hall for this class',
  `frecventa` int(2) NOT NULL COMMENT '1 - normal, 2 - la 2 saptamani',
  PRIMARY KEY (`id_materie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `materie`
--

INSERT INTO `materie` (`id_materie`, `nume`, `an`, `semestru`, `descriere`, `tip_evaluare`, `nr_credite`, `tip_materie`, `durata`, `tip_sala_curs`, `id_sala_dedicata`, `frecventa`) VALUES
(1, 'Interfete Om-Masina', 4, 1, '', 1, 4, 1, 2, 2, 0, 1),
(2, 'Interfete Om-Masina', 4, 1, '', 1, 4, 2, 2, 5, 6, 1),
(3, 'Aplicatii Internet', 4, 1, '', 1, 4, 1, 2, 3, 0, 1),
(4, 'Aplicatii Internet', 4, 1, '', 1, 4, 2, 2, 5, 2, 2),
(5, 'Sisteme de comunicatie', 4, 1, '', 1, 4, 1, 2, 1, 0, 1),
(6, 'Sisteme de comunicatie', 4, 1, '', 1, 4, 2, 2, 3, 0, 1),
(7, 'Prelucrarea si recunoasterea imaginilor', 4, 1, '', 1, 4, 1, 2, 2, 0, 1),
(8, 'Prelucrarea si recunoasterea imaginilor', 4, 1, '', 1, 4, 2, 2, 5, 4, 1),
(9, 'Design, Estetica si Semiotica AV', 4, 1, '', 2, 4, 1, 2, 5, 5, 1),
(10, 'Design, Estetica si Semiotica AV', 4, 1, '', 2, 4, 2, 2, 5, 5, 2),
(11, 'Tehnologii MM in E-Learning', 4, 1, '', 1, 5, 1, 2, 5, 7, 1),
(12, 'Tehnologii MM in E-Learning', 4, 1, '', 1, 5, 2, 2, 5, 7, 2),
(13, 'Tehnologii MM in E-Learning', 4, 1, '', 3, 5, 4, 2, 2, 0, 2),
(14, 'Echipamente Audio - Video', 4, 1, '', 1, 4, 1, 2, 5, 6, 1),
(15, 'Echipamente Audio - Video', 4, 1, '', 1, 4, 2, 2, 5, 6, 1),
(16, 'Tehnici de securizare si criptare', 4, 2, '', 1, 5, 1, 2, 1, 0, 1),
(17, 'Tehnici de securizare si criptare', 4, 2, '', 1, 5, 2, 2, 5, 10, 1),
(18, 'Retele de calculatoare', 4, 2, '', 1, 5, 1, 2, 2, 0, 1),
(19, 'Retele de calculatoare', 4, 2, '', 1, 5, 2, 2, 5, 2, 1),
(20, 'Realitate virtuala', 4, 2, '', 1, 4, 1, 2, 2, 0, 1),
(21, 'Realitate virtuala', 4, 2, '', 1, 4, 2, 2, 5, 4, 1),
(22, 'Protectia legala a informatiei', 4, 2, '', 1, 2, 1, 2, 1, 0, 1),
(23, 'Protectia legala a informatiei', 4, 2, '', 1, 2, 3, 2, 1, 0, 2),
(24, 'Tehnologii si tehnici tv si mm', 4, 2, '', 1, 4, 1, 2, 5, 6, 1),
(25, 'Tehnologii si tehnici tv si mm', 4, 2, '', 1, 4, 2, 2, 5, 6, 2),
(26, 'Tehnologii web', 4, 2, '', 1, 4, 1, 2, 4, 0, 1),
(27, 'Tehnologii web', 4, 2, '', 1, 4, 2, 2, 5, 5, 1),
(28, 'Fundamente AV', 3, 1, '', 1, 4, 1, 2, 5, 5, 1),
(29, 'Fundamente AV', 3, 1, '', 1, 4, 2, 2, 5, 5, 1),
(30, 'Sisteme de masurare si instrumentatie', 3, 1, '', 1, 4, 1, 2, 4, 0, 1),
(31, 'Sisteme de masurare si instrumentatie', 3, 1, '', 1, 4, 2, 2, 5, 15, 1),
(32, 'Microcontrolere si microprocesoare', 3, 1, '', 1, 4, 1, 2, 5, 9, 1),
(33, 'Microcontrolere si microprocesoare', 3, 1, '', 1, 4, 2, 2, 5, 9, 2),
(34, 'Grafica 3D si animatie', 3, 1, '', 1, 4, 1, 2, 5, 17, 1),
(35, 'Grafica 3D si animatie', 3, 1, '', 1, 4, 2, 2, 5, 17, 1),
(36, 'Microcontrolere si microprocesoare', 4, 1, '', 3, 4, 4, 2, 5, 9, 2),
(37, 'Jurnalism radio-tv', 3, 1, '', 2, 2, 1, 2, 5, 4, 1),
(38, 'Jurnalism radio-tv', 3, 1, '', 2, 2, 2, 2, 5, 4, 1),
(39, 'Grafica 3D si animatie', 3, 1, '', 3, 2, 4, 2, 5, 17, 2),
(40, 'Sisteme in timp real', 3, 1, '', 1, 4, 1, 2, 2, 0, 1),
(41, 'Sisteme in timp real', 3, 1, '', 1, 4, 2, 2, 5, 16, 1),
(42, 'Sisteme de masurare si instrumentatie', 3, 1, '', 1, 2, 3, 2, 5, 15, 2),
(43, 'Managementul proiectelor', 3, 2, '', 2, 4, 1, 2, 2, 0, 1),
(44, 'Managementul proiectelor', 3, 2, '', 2, 4, 2, 2, 5, 2, 2),
(45, 'Managementul proiectelor', 3, 2, '', 2, 4, 3, 2, 5, 2, 2),
(46, 'P.N.S.', 3, 2, '', 1, 5, 1, 2, 2, 0, 1),
(47, 'P.N.S.', 3, 2, '', 1, 5, 2, 2, 5, 16, 1),
(48, 'Sisteme Automate', 3, 2, '', 1, 5, 1, 3, 1, 0, 1),
(49, 'Sisteme Automate', 3, 2, '', 1, 5, 2, 2, 5, 19, 1),
(50, 'Teoria Transmisiei Informatiei', 3, 2, '', 1, 4, 1, 3, 1, 0, 1),
(51, 'Teoria Transmisiei Informatiei', 3, 2, '', 1, 4, 2, 2, 5, 16, 1),
(52, 'Software pt. Sisteme Multimedia', 3, 2, '', 2, 2, 1, 2, 5, 5, 1),
(53, 'Software pt. Sisteme Multimedia', 3, 2, '', 2, 2, 2, 2, 5, 5, 1),
(54, 'Software pt. Sisteme Multimedia', 3, 2, '', 2, 2, 4, 2, 5, 5, 2),
(55, 'Structuri Electronice pt. Multimedia', 3, 2, '', 1, 4, 2, 2, 5, 4, 1),
(56, 'Structuri Electronice pt. Multimedia', 3, 2, '', 1, 4, 1, 2, 5, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `materie_specializare`
--

CREATE TABLE IF NOT EXISTS `materie_specializare` (
  `id_materie_specializare` int(11) NOT NULL AUTO_INCREMENT,
  `id_materie` int(11) NOT NULL,
  `subgrupe` text NOT NULL COMMENT 'JSON format',
  PRIMARY KEY (`id_materie_specializare`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `materie_specializare`
--

INSERT INTO `materie_specializare` (`id_materie_specializare`, `id_materie`, `subgrupe`) VALUES
(1, 1, '["ISM, 10409, A","ISM, 10409, B"]'),
(2, 2, '["ISM, 10409, A","ISM, 10409, B"]'),
(3, 3, '["ISM, 10409, A","ISM, 10409, B"]'),
(4, 4, '["ISM, 10409, A","ISM, 10409, B"]'),
(5, 5, '["ISM, 10409, A","ISM, 10409, B"]'),
(6, 6, '["ISM, 10409, A","ISM, 10409, B"]'),
(7, 7, '["ISM, 10409, A","ISM, 10409, B"]'),
(8, 8, '["ISM, 10409, A","ISM, 10409, B"]'),
(9, 9, '["ISM, 10409, A","ISM, 10409, B"]'),
(10, 10, '["ISM, 10409, A","ISM, 10409, B"]'),
(11, 11, '["ISM, 10409, A","ISM, 10409, B"]'),
(12, 12, '["ISM, 10409, A","ISM, 10409, B"]'),
(13, 13, '["ISM, 10409, A","ISM, 10409, B"]'),
(14, 14, '["ISM, 10409, A","ISM, 10409, B"]'),
(15, 15, '["ISM, 10409, A","ISM, 10409, B"]'),
(16, 16, '["ISM, 10409, A","ISM, 10409, B"]'),
(17, 17, '["ISM, 10409, A","ISM, 10409, B"]'),
(18, 18, '["ISM, 10409, A","ISM, 10409, B"]'),
(19, 19, '["ISM, 10409, A","ISM, 10409, B"]'),
(20, 20, '["ISM, 10409, A","ISM, 10409, B"]'),
(21, 21, '["ISM, 10409, A","ISM, 10409, B"]'),
(22, 22, '["ISM, 10409, A","ISM, 10409, B"]'),
(23, 23, '["ISM, 10409, A","ISM, 10409, B"]'),
(24, 24, '["ISM, 10409, A","ISM, 10409, B"]'),
(25, 25, '["ISM, 10409, A","ISM, 10409, B"]'),
(26, 26, '["ISM, 10409, A","ISM, 10409, B"]'),
(27, 27, '["ISM, 10409, A","ISM, 10409, B"]'),
(28, 28, '["ISM, 10309, A","ISM, 10309, B"]'),
(29, 29, '["ISM, 10309, A","ISM, 10309, B"]'),
(30, 30, '["ISM, 10309, A","ISM, 10309, B"]'),
(31, 31, '["ISM, 10309, A","ISM, 10309, B"]'),
(32, 32, '["ISM, 10309, A","ISM, 10309, B"]'),
(33, 33, '["ISM, 10309, A","ISM, 10309, B"]'),
(34, 34, '["ISM, 10309, A","ISM, 10309, B"]'),
(35, 35, '["ISM, 10309, A","ISM, 10309, B"]'),
(36, 36, '["ISM, 10309, A","ISM, 10309, B"]'),
(37, 37, '["ISM, 10309, A","ISM, 10309, B"]'),
(38, 38, '["ISM, 10309, A","ISM, 10309, B"]'),
(39, 39, '["ISM, 10309, A","ISM, 10309, B"]'),
(40, 40, '["ISM, 10309, A","ISM, 10309, B"]'),
(41, 41, '["ISM, 10309, A","ISM, 10309, B"]'),
(42, 42, '["ISM, 10309, A","ISM, 10309, B"]'),
(43, 43, '["ISM, 10309, A","ISM, 10309, B"]'),
(44, 44, '["ISM, 10309, A","ISM, 10309, B"]'),
(45, 45, '["ISM, 10309, A","ISM, 10309, B"]'),
(46, 46, '["ISM, 10309, A","ISM, 10309, B"]'),
(47, 47, '["ISM, 10309, A","ISM, 10309, B"]'),
(48, 48, '["ISM, 10309, A","ISM, 10309, B"]'),
(49, 49, '["ISM, 10309, A","ISM, 10309, B"]'),
(50, 50, '["ISM, 10309, A","ISM, 10309, B"]'),
(51, 51, '["ISM, 10309, A","ISM, 10309, B"]'),
(52, 52, '["ISM, 10309, A","ISM, 10309, B"]'),
(53, 53, '["ISM, 10309, A","ISM, 10309, B"]'),
(54, 54, '["ISM, 10309, A","ISM, 10309, B"]'),
(55, 55, '["ISM, 10309, A","ISM, 10309, B"]'),
(56, 56, '["ISM, 10309, A","ISM, 10309, B"]');

-- --------------------------------------------------------

--
-- Table structure for table `orar_zi_subgrupa`
--

CREATE TABLE IF NOT EXISTS `orar_zi_subgrupa` (
  `id_orar_zi_subgrupa` int(11) NOT NULL AUTO_INCREMENT,
  `id_specializare_an_subgrupa` int(11) NOT NULL,
  `id_materie` int(11) NOT NULL,
  `id_sala_curs` int(11) NOT NULL,
  `id_profesor` int(11) NOT NULL,
  `ziua` int(2) NOT NULL,
  `ora` varchar(10) NOT NULL,
  `semestru` int(2) NOT NULL,
  `an` int(4) NOT NULL,
  `exceptii_adaugate` int(1) NOT NULL COMMENT '0-nu, 1-da',
  PRIMARY KEY (`id_orar_zi_subgrupa`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `orar_zi_subgrupa`
--

INSERT INTO `orar_zi_subgrupa` (`id_orar_zi_subgrupa`, `id_specializare_an_subgrupa`, `id_materie`, `id_sala_curs`, `id_profesor`, `ziua`, `ora`, `semestru`, `an`, `exceptii_adaugate`) VALUES
(1, 1, 3, 8, 14, 1, '8:00', 1, 2016, 0),
(2, 1, 9, 5, 8, 1, '10:00', 1, 2016, 0),
(3, 1, 1, 1, 23, 1, '12:00', 1, 2016, 0),
(4, 1, 5, 11, 13, 1, '14:00', 1, 2016, 0),
(5, 1, 7, 1, 21, 2, '8:00', 1, 2016, 0),
(6, 1, 14, 6, 27, 2, '10:00', 1, 2016, 0),
(7, 1, 11, 7, 30, 2, '12:00', 1, 2016, 0),
(8, 1, 12, 7, 30, 2, '14:00', 1, 2016, 0),
(9, 1, 13, 1, 30, 3, '8:00', 1, 2016, 0),
(10, 1, 4, 2, 14, 3, '10:00', 1, 2016, 0),
(11, 1, 6, 8, 28, 3, '12:00', 1, 2016, 0),
(12, 1, 8, 4, 29, 3, '14:00', 1, 2016, 0),
(13, 1, 15, 6, 27, 4, '8:00', 1, 2016, 0),
(14, 1, 2, 6, 31, 4, '10:00', 1, 2016, 0),
(15, 1, 10, 5, 32, 4, '12:00', 1, 2016, 0),
(16, 2, 3, 8, 14, 1, '8:00', 1, 2016, 0),
(17, 2, 9, 5, 8, 1, '10:00', 1, 2016, 0),
(18, 2, 1, 1, 23, 1, '12:00', 1, 2016, 0),
(19, 2, 5, 11, 13, 1, '14:00', 1, 2016, 0),
(20, 2, 7, 1, 21, 2, '8:00', 1, 2016, 0),
(21, 2, 14, 6, 27, 2, '10:00', 1, 2016, 0),
(22, 2, 11, 7, 30, 2, '12:00', 1, 2016, 0),
(23, 2, 12, 7, 30, 2, '14:00', 1, 2016, 0),
(24, 2, 13, 3, 30, 3, '8:00', 1, 2016, 0),
(25, 2, 4, 2, 14, 3, '10:00', 1, 2016, 0),
(26, 2, 6, 0, 28, 3, '12:00', 1, 2016, 0),
(27, 2, 8, 4, 29, 3, '14:00', 1, 2016, 0),
(28, 2, 15, 6, 27, 4, '8:00', 1, 2016, 0),
(29, 2, 2, 6, 31, 4, '10:00', 1, 2016, 0),
(30, 2, 10, 5, 32, 4, '12:00', 1, 2016, 0);

-- --------------------------------------------------------

--
-- Table structure for table `profesor`
--

CREATE TABLE IF NOT EXISTS `profesor` (
  `id_profesor` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) NOT NULL,
  `parola` varchar(255) NOT NULL,
  `ultima_logare` datetime NOT NULL,
  `prioritate` int(2) NOT NULL COMMENT '1 - ridicata, 2 - medie, 3 - scazuta',
  PRIMARY KEY (`id_profesor`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `profesor`
--

INSERT INTO `profesor` (`id_profesor`, `nume`, `parola`, `ultima_logare`, `prioritate`) VALUES
(1, 'Eugen Bobasu', '$2y$10$zHdmz3u8Qo7HH0cUMgJt0ezjVtC8Q/kQNF5lZdqPcLiln6UnQW1d2', '0000-00-00 00:00:00', 1),
(2, 'Eugen Iancu', '$2y$10$3XUqiGd/eIUKTAFPQso94eHyZhqqHKbYgziRpLxWNEDMztPjEgiWS', '2016-07-13 10:19:24', 1),
(3, 'Dan Selisteanu', '$2y$10$y6e5twRseyHrlxkHFOD.BOkOFZcVx2ImvK39uWlsskJOwOTc7xIYe', '0000-00-00 00:00:00', 1),
(4, 'Cosmin Ionete', '$2y$10$xDOpsXkMo.UUFd69KqJzCeX2pnenIGCJBaOLkhMa4/bWHosDL7E7.', '0000-00-00 00:00:00', 1),
(5, 'Emil Petre', '$2y$10$15jOMAeSm6ZFt3k7wadra.229HhIoyoGwd9f2bWGYgS2wed9jxN/K', '0000-00-00 00:00:00', 1),
(6, 'Dan Popescu', '$2y$10$CRCXKZVastxA1Am2KSmN9uaQbPxCbYuK1.VkDkMEysZOrITNsQ4.a', '0000-00-00 00:00:00', 1),
(7, 'Dorina Purcaru', '$2y$10$T8Va0tVdblYNAaJzRFeTNeFZynlwoJWt63y3LLL5HvXpBFZGQ.b3G', '0000-00-00 00:00:00', 1),
(8, 'Daniela Danciu', '$2y$10$pjPfBoseKB1wZH1HC8rJku6.veM3SALSzzp6KPphogYH.SA8Qt0GS', '0000-00-00 00:00:00', 2),
(9, 'Elena Doicaru', '$2y$10$jH5F9TEJpjSI1RqAdvu4r.miu5OtxhAlaAIYb9AqbNghn1QwByWM2', '0000-00-00 00:00:00', 2),
(10, 'Sorin Nicola', '$2y$10$yp80Y3RAQqO6ESwYIy8phug1QRa8TzM0Gw6ZuX1pbwNUUyi0wQpYa', '0000-00-00 00:00:00', 2),
(11, 'Monica Roman', '$2y$10$vFMijgh.KF7hVbzwTymhIuzH5vKp.tDgvlBYmJfh1gEPFy9ta2qF.', '0000-00-00 00:00:00', 2),
(12, 'Dorin Sendrescu', '$2y$10$9EVkuhl8wnYs0SxFMq0px.7rbLdY4qbuCXxQytPyUzh3ksL.2e2gq', '0000-00-00 00:00:00', 2),
(13, 'Catalin Constantin Cerbulescu', '$2y$10$OOyR9ZRYLdSLJIhbEFEAqecHTzfCDsHeGLCVP8hvZn98XK5E67Jai', '0000-00-00 00:00:00', 3),
(14, 'Camelia Maican', '$2y$10$rjXyFHm0I.JoOvao2wkheebekcLa.iI0ccXFJZUKJpG52u1NkRgF6', '2016-07-05 18:30:24', 3),
(15, 'Marian Popescu', '$2y$10$UHT3rfTEMG6HnIzWf6RT1OVzfGUXKcpHdFWx.lJmNrxXLE19aJIs2', '0000-00-00 00:00:00', 3),
(16, 'Florin Stinga', '$2y$10$19nQ.Djv5GoPJ6sHMkLGMeUaDJ0BG1BFTIlxXmY1A2WSeHbu/Z36O', '0000-00-00 00:00:00', 3),
(17, 'Traian Serban', '$2y$10$RO2dcEWZvK/4TflYCvofeuJcL.FXKil.jC5E5ZbT5NzsOlvcjNvlq', '0000-00-00 00:00:00', 3),
(18, 'Mircea Ivanescu', '$2y$10$Ghql1umG/f6R/S56aC51weJSg5sehyRnAYNMp78cyRfC6GcF6Cnqq', '0000-00-00 00:00:00', 1),
(19, 'Ilie Diaconu', '$2y$10$TpyWx2GMTREbEcWPo9y.VOp3zBn.iJkWdUOEvLx4w7nvC3nOOa3R.', '0000-00-00 00:00:00', 1),
(20, 'Mircea Nitulescu', '$2y$10$yVstv2nPP0UdrkQ/oVCJge0nlaXH8Vli/bjpv3YUskP6trcC4OxCC', '0000-00-00 00:00:00', 1),
(21, 'Dorian Cojocaru', '$2y$10$CIGmf2ez53XkQJ36sf8w.Ocfs4M/oHeCFXtUtXbNxc0aZo2hLy77q', '0000-00-00 00:00:00', 1),
(22, 'Viorel Stoian', '$2y$10$mIYCjQIuHJ1F2xo.fVjSVOfWQjmpTpzwghkJk/rSVprE/9mZXKVam', '0000-00-00 00:00:00', 1),
(23, 'Nicu Bizdoaca', '$2y$10$PTadM3RgRyn2.OLtpXwqdemQLg9xFL4iY6LfupF9unju0pPdfli7m', '0000-00-00 00:00:00', 1),
(24, 'Dorin Popescu', '$2y$10$n9YwIwlAXtbv/yuKAExYruQk4xGX9wXCzUEoybDSHIYtvx2SuikAO', '0000-00-00 00:00:00', 1),
(25, 'Marius Niculescu', '$2y$10$DZyeQYIiCXb3/WvCtSfNyO82zLsdeMf8/iliRhGgIUeIojAK.HOe6', '0000-00-00 00:00:00', 3),
(26, 'Cristina Pana', '$2y$10$w5exuvplYolwAPoTV3lW2evpuj24IbMnVe7C0WF/KQwvCm8OJwohW', '0000-00-00 00:00:00', 3),
(27, 'Ionut Resceanu', '$2y$10$z7..kHIDCegdCqejYX1l7OsJeJ4h81TFMIAKcCq53/PnwcvEejyGq', '0000-00-00 00:00:00', 3),
(28, 'C. Constatinescu', '$2y$10$UygAgBbHeG4uCvLtNpRrge8sEEKCBI82GwOFEY.4eLnsiPSONUTNa', '0000-00-00 00:00:00', 3),
(29, 'F. Manta', '$2y$10$LJK0.VJj9Yaq8GNI1P4cuu5RTn2nDOirzVscsTtFEu0rEeUsOuhwu', '0000-00-00 00:00:00', 3),
(30, 'Elvira Popescu', '$2y$10$xUf9YvG0BiCUJSIkVFQjGe3ALElm2qhjvVuaZZjaBJTqfNmUjVFf6', '0000-00-00 00:00:00', 1),
(31, 'Cristina Resceanu', '$2y$10$cJZXkbKiQbA7u3XCfYuSveniqklLtNU348JRejOrXwJj5q9mQ1MfK', '0000-00-00 00:00:00', 3),
(32, 'Virginia Radulescu', '$2y$10$MdMc3rRFycfbw6sEcdRUluxlGj0SwiGRKJjffrhIo4JOvMi7qGKmG', '0000-00-00 00:00:00', 3),
(33, 'D. Firinca', '$2y$10$pMjfxcTOhL/YfYMM9/2w4eBVwRtBQ9yCm.XWvzzGWklI57EILOQA2', '0000-00-00 00:00:00', 3),
(34, 'R. Tanasie', '$2y$10$TooTxk2IsEJ6gETRXDXb1uWTxV05AQ4ywOTULfuysk1nIIzR5E336', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `profesor_exceptii`
--

CREATE TABLE IF NOT EXISTS `profesor_exceptii` (
  `id_profesor_exceptie` int(11) NOT NULL AUTO_INCREMENT,
  `id_profesor` int(11) NOT NULL,
  `zi` int(1) NOT NULL,
  `ora` varchar(20) NOT NULL,
  PRIMARY KEY (`id_profesor_exceptie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `profesor_exceptii`
--

INSERT INTO `profesor_exceptii` (`id_profesor_exceptie`, `id_profesor`, `zi`, `ora`) VALUES
(1, 2, 2, '12:00-16:00');

-- --------------------------------------------------------

--
-- Table structure for table `profesor_materie`
--

CREATE TABLE IF NOT EXISTS `profesor_materie` (
  `id_profesor_materie` int(11) NOT NULL AUTO_INCREMENT,
  `id_profesor` int(11) NOT NULL,
  `id_materie_specializare` int(11) NOT NULL,
  PRIMARY KEY (`id_profesor_materie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `profesor_materie`
--

INSERT INTO `profesor_materie` (`id_profesor_materie`, `id_profesor`, `id_materie_specializare`) VALUES
(15, 14, 4),
(14, 14, 3),
(3, 4, 18),
(4, 5, 40),
(5, 3, 48),
(6, 2, 50),
(7, 7, 30),
(8, 7, 42),
(9, 8, 9),
(10, 8, 52),
(11, 8, 53),
(12, 8, 54),
(13, 23, 1),
(16, 13, 5),
(17, 28, 6),
(18, 21, 7),
(19, 29, 8),
(20, 27, 14),
(21, 27, 15),
(22, 30, 11),
(23, 30, 12),
(24, 30, 13),
(25, 31, 2),
(26, 32, 10),
(27, 31, 28),
(28, 31, 29),
(29, 33, 31),
(30, 10, 32),
(31, 10, 36),
(32, 16, 41),
(33, 34, 34),
(34, 34, 35),
(35, 34, 39),
(36, 19, 37),
(37, 19, 38),
(38, 27, 33),
(39, 14, 43),
(40, 14, 44),
(41, 14, 45),
(42, 6, 46),
(43, 16, 47),
(44, 15, 49),
(45, 2, 51),
(46, 25, 55),
(47, 25, 56);

-- --------------------------------------------------------

--
-- Table structure for table `sala_curs`
--

CREATE TABLE IF NOT EXISTS `sala_curs` (
  `id_sala_curs` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) NOT NULL,
  `locatie` varchar(100) NOT NULL,
  `facilitati` int(11) NOT NULL COMMENT '1 - normal, 2 - videoproiector, 3 - calculatoare, 4 - aula, 5 - sala speciala ',
  PRIMARY KEY (`id_sala_curs`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `sala_curs`
--

INSERT INTO `sala_curs` (`id_sala_curs`, `nume`, `locatie`, `facilitati`) VALUES
(1, 'C11', 'IE, etaj 1', 2),
(2, '208', 'IE, etaj 3', 5),
(3, 'N10', 'ACE, etaj 3', 2),
(4, 'IPAC', 'ACE', 5),
(5, 'MM', 'ACE, parter', 5),
(6, 'EMEC', 'ACE, etaj 1', 5),
(7, 'S2', 'IE, etaj 3', 5),
(8, 'S6N', 'IE', 3),
(9, '122', 'IE', 5),
(10, '211', 'IE', 5),
(11, 'C2', 'IE, turn, etaj 2', 1),
(12, 'SMC', 'ACE', 1),
(13, 'ACB', 'ACE, parter', 4),
(14, 'C4', 'IE, turn, etaj 4', 1),
(15, '109N', 'ACE', 5),
(16, '209', 'ACE', 5),
(17, 'S7B', 'IE, turn, etaj 7', 5),
(18, '210', 'IE', 5),
(19, '212', 'IE, etaj 2', 5);

-- --------------------------------------------------------

--
-- Table structure for table `specializare`
--

CREATE TABLE IF NOT EXISTS `specializare` (
  `id_specializare` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(100) NOT NULL,
  `acronim` varchar(10) NOT NULL,
  PRIMARY KEY (`id_specializare`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `specializare`
--

INSERT INTO `specializare` (`id_specializare`, `nume`, `acronim`) VALUES
(1, 'Automatica si Informatica Aplicata', 'AIA'),
(2, 'Calculatoare cu predare in limba romana', 'CR'),
(3, 'Calculatoare cu predare in limba engleza', 'CE'),
(4, 'Electronica Aplicata', 'ELA'),
(5, 'Ingineria Sistemelor Multimedia', 'ISM'),
(6, 'Mecatronica', 'MCT'),
(7, 'Robotica', 'ROB');

-- --------------------------------------------------------

--
-- Table structure for table `specializare_an`
--

CREATE TABLE IF NOT EXISTS `specializare_an` (
  `id_specializare_an` int(11) NOT NULL AUTO_INCREMENT,
  `id_specializare` int(11) NOT NULL,
  `an` int(2) NOT NULL,
  `nr_identificare` varchar(10) NOT NULL,
  PRIMARY KEY (`id_specializare_an`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `specializare_an`
--

INSERT INTO `specializare_an` (`id_specializare_an`, `id_specializare`, `an`, `nr_identificare`) VALUES
(1, 1, 1, '10101'),
(2, 1, 1, '10102'),
(3, 1, 2, '10201'),
(4, 1, 2, '10202'),
(5, 1, 3, '10301'),
(6, 1, 3, '10302'),
(7, 1, 4, '10401'),
(8, 1, 4, '10402'),
(9, 2, 1, '10103'),
(10, 2, 1, '10104'),
(11, 2, 2, '10203'),
(12, 2, 2, '10204'),
(13, 2, 3, '10303'),
(14, 2, 3, '10304'),
(15, 2, 4, '10403'),
(16, 2, 4, '10404'),
(17, 3, 1, '10105'),
(18, 3, 1, '10106'),
(19, 3, 2, '10205'),
(20, 3, 2, '10206'),
(21, 3, 3, '10305'),
(22, 3, 3, '10306'),
(23, 3, 4, '10405'),
(24, 3, 4, '10406'),
(25, 4, 1, '10107'),
(26, 4, 2, '10207'),
(27, 4, 3, '10307'),
(28, 4, 4, '10407'),
(29, 5, 1, '10109'),
(30, 5, 2, '10209'),
(31, 5, 3, '10309'),
(32, 5, 4, '10409'),
(33, 6, 1, '10108'),
(34, 6, 2, '10208'),
(35, 6, 3, '10308'),
(36, 6, 4, '10408'),
(37, 7, 1, '10108'),
(38, 7, 2, '10208'),
(39, 7, 3, '10308'),
(40, 7, 4, '10408');

-- --------------------------------------------------------

--
-- Table structure for table `specializare_an_subgrupa`
--

CREATE TABLE IF NOT EXISTS `specializare_an_subgrupa` (
  `id_specializare_an_subgrupa` int(11) NOT NULL AUTO_INCREMENT,
  `id_specializare_an` int(11) NOT NULL,
  `nume` varchar(20) NOT NULL,
  PRIMARY KEY (`id_specializare_an_subgrupa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `specializare_an_subgrupa`
--

INSERT INTO `specializare_an_subgrupa` (`id_specializare_an_subgrupa`, `id_specializare_an`, `nume`) VALUES
(1, 32, 'A'),
(2, 32, 'B'),
(3, 31, 'A'),
(4, 31, 'B');

-- --------------------------------------------------------

--
-- Table structure for table `subgrupa_exceptii`
--

CREATE TABLE IF NOT EXISTS `subgrupa_exceptii` (
  `id_subgrupa_exceptii` int(11) NOT NULL AUTO_INCREMENT,
  `id_specializare_an_subgrupa` int(11) NOT NULL,
  `zi` int(1) NOT NULL,
  `ora` varchar(20) NOT NULL,
  PRIMARY KEY (`id_subgrupa_exceptii`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `subgrupa_exceptii`
--

INSERT INTO `subgrupa_exceptii` (`id_subgrupa_exceptii`, `id_specializare_an_subgrupa`, `zi`, `ora`) VALUES
(1, 1, 1, '16:00-18:00'),
(2, 2, 3, '16:00-18:00'),
(3, 3, 3, '16:00-18:00'),
(4, 4, 3, '16:00-18:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
