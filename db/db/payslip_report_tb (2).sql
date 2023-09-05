-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2023 at 07:47 AM
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
-- Table structure for table `payslip_report_tb`
--

CREATE TABLE `payslip_report_tb` (
  `id` int(11) NOT NULL,
  `cutoff_ID` int(11) NOT NULL,
  `pay_rule` varchar(255) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `col_frequency` varchar(255) NOT NULL,
  `cutoff_month` varchar(255) NOT NULL,
  `cutoff_startdate` date NOT NULL,
  `cutoff_enddate` date NOT NULL,
  `cutoff_num` varchar(255) NOT NULL,
  `working_days` varchar(255) NOT NULL,
  `basic_hours` varchar(255) NOT NULL,
  `basic_amount_pay` varchar(255) NOT NULL,
  `overtime_hours` varchar(255) NOT NULL,
  `overtime_amount` varchar(255) NOT NULL,
  `transpo_allow` varchar(255) NOT NULL,
  `meal_allow` varchar(255) NOT NULL,
  `net_allowance` varchar(255) NOT NULL,
  `add_allow` varchar(255) NOT NULL,
  `allowances` varchar(255) NOT NULL,
  `number_leave` varchar(255) NOT NULL,
  `paid_leaves` varchar(255) NOT NULL,
  `holiday_pay` varchar(255) NOT NULL,
  `total_earnings` varchar(255) NOT NULL,
  `absence` varchar(255) NOT NULL,
  `absence_deduction` varchar(255) NOT NULL,
  `sss_contri` varchar(255) NOT NULL,
  `philhealth_contri` varchar(255) NOT NULL,
  `tin_contri` varchar(255) NOT NULL,
  `pagibig_contri` varchar(255) NOT NULL,
  `other_contri` varchar(255) NOT NULL,
  `total_late` varchar(255) NOT NULL,
  `tardiness_deduct` varchar(255) NOT NULL,
  `ut_time` varchar(255) NOT NULL,
  `undertime_deduct` varchar(255) NOT NULL,
  `number_lwop` varchar(255) NOT NULL,
  `lwop_deduct` varchar(255) NOT NULL,
  `total_deduction` varchar(255) NOT NULL,
  `net_pay` varchar(255) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payslip_report_tb`
--
ALTER TABLE `payslip_report_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payslip_report_tb`
--
ALTER TABLE `payslip_report_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
