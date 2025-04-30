-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 apr 2025 om 11:40
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ledenadministratie.j`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `archive`
--

CREATE TABLE `archive` (
  `id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `family_member` int(11) NOT NULL,
  `member_type` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `bookyear` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bookyear`
--

CREATE TABLE `bookyear` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `contribution` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `bookyear`
--

INSERT INTO `bookyear` (`id`, `year`, `contribution`, `is_active`) VALUES
(1, '2025', 1000.00, 1),
(2, '2024', 1500.00, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contribution`
--

CREATE TABLE `contribution` (
  `id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `family_member` int(11) NOT NULL,
  `member_type` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `bookyear` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `contribution`
--

INSERT INTO `contribution` (`id`, `age`, `family_member`, `member_type`, `amount`, `bookyear`) VALUES
(1, 27, 1, 3, 100.00, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `family`
--

CREATE TABLE `family` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `adress` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `family`
--

INSERT INTO `family` (`id`, `name`, `adress`) VALUES
(1, 'De Bruin', 'Abeelstraat 41, 1741TJ, Schagen, Nederland');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `family_member`
--

CREATE TABLE `family_member` (
  `id` int(11) NOT NULL,
  `family_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `member_type` int(11) NOT NULL DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `family_member`
--

INSERT INTO `family_member` (`id`, `family_id`, `name`, `date_of_birth`, `member_type`) VALUES
(1, 1, 'Bas', '1997-09-27', 3);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `member_type`
--

CREATE TABLE `member_type` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `member_type`
--

INSERT INTO `member_type` (`id`, `description`, `discount_percentage`) VALUES
(1, 'Standaard lid', 0.00),
(2, 'Student-lid', 25.00),
(3, 'Erelid', 75.00),
(4, 'Familielid', 50.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','treasurer','secretary','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$UHKhcRnhdCSSeGDgzrqtgeeSS2nn7n06AXgTk0BrSWbOoGc/J4yHC', 'admin'),
(2, 'secretary', '$2y$10$NDVcwffJpW7oCZpM3YUigu8x8j6zQW3ALIdPng39bBEZgfgqsboJm', 'admin'),
(3, 'treasurer', '$2y$10$stbCysK975gWsMtBfHDna.G/wNM6Mu7R/J4IO1hxp829iMzAMTNxC', 'admin');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `bookyear`
--
ALTER TABLE `bookyear`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `contribution`
--
ALTER TABLE `contribution`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `family_member` (`family_member`),
  ADD UNIQUE KEY `member_type` (`member_type`),
  ADD UNIQUE KEY `bookyear` (`bookyear`);

--
-- Indexen voor tabel `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `family_member`
--
ALTER TABLE `family_member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `family_id` (`family_id`),
  ADD UNIQUE KEY `member_type` (`member_type`);

--
-- Indexen voor tabel `member_type`
--
ALTER TABLE `member_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `archive`
--
ALTER TABLE `archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `bookyear`
--
ALTER TABLE `bookyear`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `contribution`
--
ALTER TABLE `contribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `family`
--
ALTER TABLE `family`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `family_member`
--
ALTER TABLE `family_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT voor een tabel `member_type`
--
ALTER TABLE `member_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `contribution`
--
ALTER TABLE `contribution`
  ADD CONSTRAINT `contribution_ibfk_1` FOREIGN KEY (`family_member`) REFERENCES `family_member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contribution_ibfk_2` FOREIGN KEY (`member_type`) REFERENCES `member_type` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contribution_ibfk_3` FOREIGN KEY (`bookyear`) REFERENCES `bookyear` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `family_member`
--
ALTER TABLE `family_member`
  ADD CONSTRAINT `family_member_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `family` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `family_member_ibfk_2` FOREIGN KEY (`member_type`) REFERENCES `member_type` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
