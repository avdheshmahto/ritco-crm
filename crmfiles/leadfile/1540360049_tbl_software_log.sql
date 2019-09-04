-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2018 at 12:11 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ritcologistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_software_log`
--

CREATE TABLE `tbl_software_log` (
  `id` int(11) NOT NULL,
  `log_id` varchar(200) DEFAULT NULL,
  `contact_id` varchar(200) NOT NULL,
  `total` varchar(200) NOT NULL,
  `maker_id` varchar(200) DEFAULT NULL,
  `maker_date` date DEFAULT NULL,
  `author_id` varchar(200) DEFAULT NULL,
  `author_date` date DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `comp_id` varchar(200) DEFAULT NULL,
  `zone_id` varchar(200) DEFAULT NULL,
  `brnh_id` varchar(200) DEFAULT NULL,
  `divn_id` varchar(200) DEFAULT NULL,
  `status` varchar(100) DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_software_log`
--

INSERT INTO `tbl_software_log` (`id`, `log_id`, `contact_id`, `total`, `maker_id`, `maker_date`, `author_id`, `author_date`, `type`, `comp_id`, `zone_id`, `brnh_id`, `divn_id`, `status`) VALUES
(1, '1', '1', '0', '1', '2018-01-16', '11:32:37', NULL, 'Contact added', '1', '1', '1', '1', 'A'),
(2, '2', '2', '0', '1', '2018-01-16', '11:33:27', NULL, 'Contact added', '1', '1', '1', '1', 'A'),
(3, '2', '2', '3922.32', '1', '2018-01-16', '11:41:10', NULL, 'Invoice added', '1', '1', '1', '1', 'A'),
(4, '3', '2', '6648.00', '1', '2018-01-16', '12:20:36', NULL, 'Invoice added', '1', '1', '1', '1', 'A'),
(5, '3', '3', '0', '1', '2018-01-18', '15:42:21', NULL, 'Contact added', '1', '1', '1', '1', 'A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_software_log`
--
ALTER TABLE `tbl_software_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_software_log`
--
ALTER TABLE `tbl_software_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
