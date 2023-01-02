-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 01 oct. 2022 à 08:23
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `api_starter`
--

-- --------------------------------------------------------

--
-- Structure de la table `budget_comsumption`
--

DROP TABLE IF EXISTS `aa`;
CREATE TABLE IF NOT EXISTS `aa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `nb_unity_per_package` int(11) NOT NULL,
  `daily_average` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `price_daily` int(11) NOT NULL,
  `price_monthly` int(11) NOT NULL,
  `type_actived` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8  COLLATE=`utf8_general_ci`;

--
-- Déchargement des données de la table `budget_comsumption`
--

INSERT INTO `aa` (`id`, `title`, `content`, `nb_unity_per_package`, `daily_average`, `price`, `price_daily`, `price_monthly`, `type_actived`) VALUES
(1, 'Café nespresso', 'environ 3 la semaine, et 10 le weekend', 30, 6, 10, 2, 30, 1),
(2, 'Cigarette', 'environ 1 paquet par jour', 20, 20, 11, 11, 315, 1),
(3, 'Ouef', 'environ 3 le matin', 10, 3, 5, 2, 45, 1),
(4, 'Cereals', 'environ 1 bol par jour', 3, 1, 5, 2, 45, 1),
(5, 'Lait', 'environ 1 verre par jour', 4, 1, 2, 1, 15, 1),
(6, 'Repas midi', 'environ 1 repas midi par jour', 1, 1, 10, 10, 300, 1),
(7, 'Perrier', 'environ 1 perrier midi par jour', 1, 1, 1, 1, 30, 1),
(8, 'Pain', 'environ 1 un pain tradition midi par jour', 1, 1, 1, 1, 42, 1),
(9, 'Repas soir', 'environ 1 repas le soir , a calculer, salade, viande, patte, fromage', 1, 1, 7, 7, 210, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
