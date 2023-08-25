-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2023 at 11:15 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hris_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applyleave_tb`
--

CREATE TABLE `applyleave_tb` (
  `col_ID` int(11) NOT NULL,
  `col_req_emp` int(11) NOT NULL,
  `col_LeaveType` varchar(50) NOT NULL,
  `col_LeavePeriod` varchar(30) NOT NULL,
  `col_strDate` date NOT NULL,
  `col_endDate` date NOT NULL,
  `col_reason` varchar(1000) NOT NULL,
  `col_dt_action` datetime NOT NULL,
  `col_file` longblob NOT NULL,
  `col_status` varchar(30) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applyleave_tb`
--
ALTER TABLE `applyleave_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applyleave_tb`
--
ALTER TABLE `applyleave_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
