-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2020 at 10:16 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dashboard2020`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `idType` varchar(20) NOT NULL,
  `ipAddress` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `name`, `idType`, `ipAddress`) VALUES
('IDT', 'Indoor temperature', 'TMP', '192.168.0.3'),
('KTM', 'Kitchen temperature', 'TMP', '192.168.0.9'),
('LH', 'Lawn humidity', 'HMD', '192.168.0.5'),
('ODT', 'Outdoor temperature', 'TMP', '192.168.0.1'),
('RW', 'Roof wind ', 'WND', '192.168.0.4'),
('SM', 'HSSHDAJD', 'HMD', '192.198.23.3'),
('WL', 'Windt level', 'WND', '192.168.0.2');

-- --------------------------------------------------------

--
-- Table structure for table `devicetypes`
--

CREATE TABLE `devicetypes` (
  `id` varchar(5) NOT NULL,
  `description` varchar(50) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `minVal` int(11) NOT NULL DEFAULT 0,
  `maxVal` int(11) NOT NULL DEFAULT 100,
  `unitOfMeasuremet` varchar(50) NOT NULL,
  `chartColor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devicetypes`
--

INSERT INTO `devicetypes` (`id`, `description`, `icon`, `minVal`, `maxVal`, `unitOfMeasuremet`, `chartColor`) VALUES
('HMD', 'Humidity', 'humidity.png', 0, 100, '% Percentage', '#2196F3'),
('TMP', 'Temperature', 'temperature.png', 0, 100, '° Farenheit', '#D50000'),
('WND', 'Wind', 'wind.png', 0, 100, 'MPH', '#00695C');

-- --------------------------------------------------------

--
-- Table structure for table `readings`
--

CREATE TABLE `readings` (
  `id` int(11) NOT NULL,
  `idDevice` varchar(5) NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT current_timestamp(),
  `value` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `readings`
--

INSERT INTO `readings` (`id`, `idDevice`, `dateTime`, `value`) VALUES
(1, 'ODT', '2020-05-14 22:16:00', 67.3),
(2, 'LH', '2020-05-14 22:17:34', 34.3),
(3, 'IDT', '2020-05-14 22:17:50', 40),
(4, 'RW', '2020-05-14 22:18:45', 45.1),
(5, 'ODT', '2020-05-14 22:19:23', 29),
(6, 'RW', '2020-05-14 22:20:00', 54.9),
(7, 'IDT', '2020-05-14 22:21:00', 48.1),
(8, 'LH', '2020-05-14 22:22:00', 58),
(9, 'RW', '2020-05-14 22:23:00', 28.2),
(10, 'WL', '2020-05-14 22:24:00', 43.3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkDeviceType` (`idType`);

--
-- Indexes for table `devicetypes`
--
ALTER TABLE `devicetypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `readings`
--
ALTER TABLE `readings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkReadingDevice` (`idDevice`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `readings`
--
ALTER TABLE `readings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `fkDeviceType` FOREIGN KEY (`idType`) REFERENCES `devicetypes` (`id`);

--
-- Constraints for table `readings`
--
ALTER TABLE `readings`
  ADD CONSTRAINT `fkReadingDevice` FOREIGN KEY (`idDevice`) REFERENCES `devices` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
