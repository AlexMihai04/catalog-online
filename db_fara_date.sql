-- phpMyAdmin SQL Dump
-- version 4.9.7deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 27, 2021 at 10:29 PM
-- Server version: 8.0.26-0ubuntu0.21.04.3
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catalog_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `lista_absente`
--

CREATE TABLE `lista_absente` (
  `id_elev` int DEFAULT NULL,
  `materie` text,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lista_clase`
--

CREATE TABLE `lista_clase` (
  `id_clasa` int NOT NULL,
  `nume_clasa` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_clase`
--

-- --------------------------------------------------------

--
-- Table structure for table `lista_conturi`
--

CREATE TABLE `lista_conturi` (
  `user_id` int NOT NULL,
  `username` text,
  `parola` text,
  `grad` text,
  `last_name` text,
  `first_name` text,
  `email` text NOT NULL,
  `scoala` varchar(200) NOT NULL DEFAULT 'Colegiul National Pedagogic "Regina Maria"',
  `judet` varchar(50) NOT NULL DEFAULT 'PRAHOVA',
  `date` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_conturi`
--

INSERT INTO `lista_conturi` (`user_id`, `username`, `parola`, `grad`, `last_name`, `first_name`, `email`, `scoala`, `judet`, `date`) VALUES
(1, 'admin', '$2y$10$Dy.OA5RXnbMGpYv6yO21mODDDjs7nRNWO6FxHaJM00rGZusxvn0W2', 'admin', 'Admin', 'Cont', '', 'Colegiul National Pedagogic \"Regina Maria\"', 'PRAHOVA', NULL);
-- --------------------------------------------------------

--
-- Table structure for table `lista_date`
--

CREATE TABLE `lista_date` (
  `user_id` int NOT NULL,
  `clasa` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lista_date`
--


-- --------------------------------------------------------

--
-- Table structure for table `lista_loguri`
--

CREATE TABLE `lista_loguri` (
  `log_text` text,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- --------------------------------------------------------

--
-- Table structure for table `lista_materii`
--

CREATE TABLE `lista_materii` (
  `id_materie` int NOT NULL,
  `nume_materie` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_materii`
--

INSERT INTO `lista_materii` (`id_materie`, `nume_materie`) VALUES
(1, 'matematica'),
(2, 'informatica');

-- --------------------------------------------------------

--
-- Table structure for table `lista_note`
--

CREATE TABLE `lista_note` (
  `id_elev` int NOT NULL,
  `materie` text NOT NULL,
  `nota` float NOT NULL DEFAULT '0',
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lista_note`
--


-- --------------------------------------------------------

--
-- Table structure for table `table_name`
--

CREATE TABLE `table_name` (
  `Nume` varchar(100) DEFAULT NULL,
  `Prenuma` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nustiu` varchar(100) DEFAULT NULL,
  `column_4` varchar(100) DEFAULT NULL,
  `grup` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lista_clase`
--
ALTER TABLE `lista_clase`
  ADD PRIMARY KEY (`id_clasa`);

--
-- Indexes for table `lista_conturi`
--
ALTER TABLE `lista_conturi`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lista_materii`
--
ALTER TABLE `lista_materii`
  ADD PRIMARY KEY (`id_materie`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lista_clase`
--
ALTER TABLE `lista_clase`
  MODIFY `id_clasa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `lista_conturi`
--
ALTER TABLE `lista_conturi`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lista_materii`
--
ALTER TABLE `lista_materii`
  MODIFY `id_materie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
