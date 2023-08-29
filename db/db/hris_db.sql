-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2023 at 04:44 AM
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
-- Table structure for table `actiontaken_tb`
--

CREATE TABLE `actiontaken_tb` (
  `col_ID` int(11) NOT NULL,
  `col_applyID` int(11) NOT NULL,
  `col_remarks` varchar(200) DEFAULT NULL,
  `col_status` varchar(30) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `allowancededuct_tb`
--

CREATE TABLE `allowancededuct_tb` (
  `id` int(11) NOT NULL,
  `id_emp` varchar(255) NOT NULL,
  `other_allowance` varchar(255) NOT NULL,
  `allowance_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_tb`
--

CREATE TABLE `announcement_tb` (
  `id` int(11) NOT NULL,
  `announce_title` varchar(255) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `announce_date` date NOT NULL,
  `description` varchar(1000) NOT NULL,
  `file_attachment` longblob NOT NULL,
  `date_file` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applyleave_tb`
--

CREATE TABLE `applyleave_tb` (
  `col_ID` int(11) NOT NULL,
  `col_req_emp` varchar(255) NOT NULL,
  `col_LeaveType` varchar(50) NOT NULL,
  `col_credit` varchar(30) NOT NULL,
  `col_LeavePeriod` varchar(30) NOT NULL,
  `col_strDate` date NOT NULL,
  `col_endDate` date NOT NULL,
  `col_reason` varchar(1000) NOT NULL,
  `col_dt_action` datetime NOT NULL,
  `col_PAID_LEAVE` varchar(30) NOT NULL,
  `col_file` longblob NOT NULL,
  `col_approver` varchar(30) NOT NULL,
  `col_status` varchar(30) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approver_tb`
--

CREATE TABLE `approver_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `approver_empid` varchar(255) NOT NULL,
  `_dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_company_code_tb`
--

CREATE TABLE `assigned_company_code_tb` (
  `id` int(11) NOT NULL,
  `company_code_id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `late` time NOT NULL,
  `early_out` time NOT NULL,
  `overtime` time NOT NULL,
  `total_work` time NOT NULL,
  `total_rest` time NOT NULL,
  `modified` int(11) NOT NULL,
  `_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_tb`
--

CREATE TABLE `attendance_tb` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `late` time NOT NULL,
  `early_out` time NOT NULL,
  `overtime` time NOT NULL,
  `total_work` time NOT NULL,
  `total_rest` time NOT NULL,
  `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_time_in`
--

CREATE TABLE `attendance_time_in` (
  `id` int(11) NOT NULL,
  `time_in_personId` varchar(20) NOT NULL,
  `date_time_in` date NOT NULL,
  `time_in` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_time_out`
--

CREATE TABLE `attendance_time_out` (
  `id` int(11) NOT NULL,
  `time_out_personId` varchar(20) NOT NULL,
  `date_time_out` date NOT NULL,
  `time_out` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_tb`
--

CREATE TABLE `branch_tb` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(200) NOT NULL,
  `branch_address` varchar(200) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classification_tb`
--

CREATE TABLE `classification_tb` (
  `id` int(11) NOT NULL,
  `classification` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_code_tb`
--

CREATE TABLE `company_code_tb` (
  `id` int(11) NOT NULL,
  `company_code` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cutoff_tb`
--

CREATE TABLE `cutoff_tb` (
  `col_ID` int(11) NOT NULL,
  `col_type` varchar(50) NOT NULL,
  `col_frequency` varchar(30) NOT NULL,
  `col_month` varchar(30) NOT NULL,
  `col_year` int(11) NOT NULL,
  `col_startDate` date NOT NULL,
  `col_endDate` date NOT NULL,
  `col_cutOffNum` int(11) NOT NULL,
  `_dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_time_records_tb`
--

CREATE TABLE `daily_time_records_tb` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_records` date NOT NULL,
  `department` varchar(100) NOT NULL,
  `schedule_type` varchar(100) NOT NULL,
  `time_entry` time NOT NULL,
  `time_out` time NOT NULL,
  `total_hours` time NOT NULL,
  `tardiness` time NOT NULL,
  `undertime` time NOT NULL,
  `overtime` time NOT NULL,
  `modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department_tb`
--

CREATE TABLE `department_tb` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dept_tb`
--

CREATE TABLE `dept_tb` (
  `col_ID` int(11) NOT NULL,
  `col_deptname` varchar(50) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empcutoff_tb`
--

CREATE TABLE `empcutoff_tb` (
  `col_ID` int(11) NOT NULL,
  `cutOff_ID` int(11) NOT NULL,
  `emp_ID` varchar(255) NOT NULL,
  `_dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_pakyawan_work_tb`
--

CREATE TABLE `employee_pakyawan_work_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `piece_rate_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_tb`
--

CREATE TABLE `employee_tb` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `company_code` varchar(255) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `cstatus` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `empdob` varchar(255) NOT NULL,
  `empsss` varchar(255) NOT NULL,
  `emptin` varchar(255) NOT NULL,
  `emppagibig` varchar(255) NOT NULL,
  `empphilhealth` varchar(255) NOT NULL,
  `empbranch` varchar(255) DEFAULT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `empposition` varchar(255) DEFAULT NULL,
  `empbsalary` float NOT NULL,
  `otrate` int(11) NOT NULL,
  `drate` varchar(255) NOT NULL,
  `empdate_hired` varchar(255) NOT NULL,
  `emptranspo` varchar(255) NOT NULL,
  `empmeal` varchar(255) NOT NULL,
  `empinternet` varchar(255) NOT NULL,
  `empaccess_id` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sss_amount` int(11) NOT NULL,
  `tin_amount` int(11) NOT NULL,
  `pagibig_amount` int(11) NOT NULL,
  `philhealth_amount` int(11) NOT NULL,
  `classification` varchar(50) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_number` varchar(255) NOT NULL,
  `emp_img_url` varchar(255) NOT NULL,
  `user_profile` longblob NOT NULL,
  `work_frequency` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empschedule_tb`
--

CREATE TABLE `empschedule_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(55) NOT NULL,
  `schedule_name` varchar(255) DEFAULT NULL,
  `sched_from` varchar(255) DEFAULT NULL,
  `sched_to` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_dtr_tb`
--

CREATE TABLE `emp_dtr_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` varchar(255) NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `file_attach` longblob NOT NULL,
  `status` varchar(255) NOT NULL,
  `_dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_official_tb`
--

CREATE TABLE `emp_official_tb` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `str_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(200) NOT NULL,
  `file_upl` longblob NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `action_taken` datetime NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `_dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_tb`
--

CREATE TABLE `event_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `event_title` varchar(500) NOT NULL,
  `date_event` date NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `governdeduct_tb`
--

CREATE TABLE `governdeduct_tb` (
  `id` int(11) NOT NULL,
  `id_emp` varchar(255) NOT NULL,
  `other_govern` varchar(255) NOT NULL,
  `govern_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_tb`
--

CREATE TABLE `holiday_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `holiday_title` varchar(500) NOT NULL,
  `date_holiday` date NOT NULL,
  `holiday_type` varchar(255) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaveinfo_tb`
--

CREATE TABLE `leaveinfo_tb` (
  `col_ID` int(11) NOT NULL,
  `col_empID` varchar(255) NOT NULL,
  `col_vctionCrdt` float NOT NULL,
  `col_sickCrdt` float NOT NULL,
  `col_brvmntCrdt` float NOT NULL,
  `_dateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leavetype_tb`
--

CREATE TABLE `leavetype_tb` (
  `col_ID` int(11) NOT NULL,
  `col_Leave_name` varchar(50) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `overtime_tb`
--

CREATE TABLE `overtime_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `work_schedule` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `ot_hours` time NOT NULL,
  `total_ot` time NOT NULL,
  `reason` varchar(500) NOT NULL,
  `file_attachment` longblob NOT NULL,
  `ot_action_taken` datetime NOT NULL,
  `ot_remarks` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_filed` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pakyawan_based_work_tb`
--

CREATE TABLE `pakyawan_based_work_tb` (
  `id` int(11) NOT NULL,
  `employee` varchar(255) NOT NULL,
  `work_frequency` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `unit_type` varchar(255) NOT NULL,
  `unit_work` int(11) NOT NULL,
  `work_pay` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pakyawan_cutoff_tb`
--

CREATE TABLE `pakyawan_cutoff_tb` (
  `id` int(11) NOT NULL,
  `work_type` varchar(50) NOT NULL,
  `work_frequency` varchar(50) NOT NULL,
  `work_month` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pakyawan_payroll_tb`
--

CREATE TABLE `pakyawan_payroll_tb` (
  `id` int(11) NOT NULL,
  `cutoff_id` int(11) NOT NULL,
  `pakyawan_empid` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pakyaw_cash_advance_tb`
--

CREATE TABLE `pakyaw_cash_advance_tb` (
  `id` int(11) NOT NULL,
  `cash_advance` float NOT NULL,
  `empid` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_loan_tb`
--

CREATE TABLE `payroll_loan_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `loan_type` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(255) NOT NULL,
  `cutoff_no` int(11) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `loan_date` varchar(11) NOT NULL,
  `payable_amount` int(11) NOT NULL,
  `amortization` int(11) NOT NULL,
  `applied_cutoff` varchar(255) NOT NULL,
  `loan_status` varchar(255) NOT NULL,
  `col_BAL_amount` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_report_tb`
--

CREATE TABLE `payslip_report_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `cutoff_startdate` date NOT NULL,
  `cutoff_enddate` date NOT NULL,
  `cutoff_num` varchar(255) NOT NULL,
  `working_days` varchar(255) NOT NULL,
  `basic_hours` varchar(255) NOT NULL,
  `basic_amount_pay` varchar(255) NOT NULL,
  `overtime_hours` varchar(255) NOT NULL,
  `overtime_amount` varchar(255) NOT NULL,
  `allowances` varchar(255) NOT NULL,
  `paid_leaves` varchar(255) NOT NULL,
  `holiday_pay` varchar(255) NOT NULL,
  `total_earnings` varchar(255) NOT NULL,
  `sss_contri` varchar(255) NOT NULL,
  `philhealth_contri` varchar(255) NOT NULL,
  `tin_contri` varchar(255) NOT NULL,
  `pagibig_contri` varchar(255) NOT NULL,
  `other_contri` varchar(255) NOT NULL,
  `tardiness_deduct` varchar(255) NOT NULL,
  `undertime_deduct` varchar(255) NOT NULL,
  `lwop_deduct` varchar(255) NOT NULL,
  `total_deduction` varchar(255) NOT NULL,
  `net_pay` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_tb`
--

CREATE TABLE `payslip_tb` (
  `col_ID` int(11) NOT NULL,
  `col_empid` varchar(50) NOT NULL,
  `col_Payslip_pdf` varchar(255) NOT NULL,
  `col_cutoffID` int(11) NOT NULL,
  `col_numDaysWork` int(11) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `piece_rate_tb`
--

CREATE TABLE `piece_rate_tb` (
  `id` int(11) NOT NULL,
  `unit_type` varchar(255) NOT NULL,
  `unit_quantity` int(11) NOT NULL,
  `unit_rate` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positionn_tb`
--

CREATE TABLE `positionn_tb` (
  `id` int(11) NOT NULL,
  `position` varchar(200) NOT NULL,
  `designation` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sample_ai`
--

CREATE TABLE `sample_ai` (
  `id` int(11) NOT NULL,
  `sample_ai` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tb`
--

CREATE TABLE `schedule_tb` (
  `id` int(11) NOT NULL,
  `schedule_name` varchar(255) NOT NULL,
  `monday` varchar(255) DEFAULT NULL,
  `mon_timein` varchar(255) DEFAULT NULL,
  `mon_timeout` varchar(255) DEFAULT NULL,
  `mon_wfh` varchar(255) DEFAULT NULL,
  `tuesday` varchar(255) DEFAULT NULL,
  `tues_timein` varchar(255) DEFAULT NULL,
  `tues_timeout` varchar(255) DEFAULT NULL,
  `tues_wfh` varchar(255) DEFAULT NULL,
  `wednesday` varchar(255) DEFAULT NULL,
  `wed_timein` varchar(255) DEFAULT NULL,
  `wed_timeout` varchar(255) DEFAULT NULL,
  `wed_wfh` varchar(255) DEFAULT NULL,
  `thursday` varchar(255) DEFAULT NULL,
  `thurs_timein` varchar(255) DEFAULT NULL,
  `thurs_timeout` varchar(255) DEFAULT NULL,
  `thurs_wfh` varchar(255) DEFAULT NULL,
  `friday` varchar(255) DEFAULT NULL,
  `fri_timein` varchar(255) DEFAULT NULL,
  `fri_timeout` varchar(255) DEFAULT NULL,
  `fri_wfh` varchar(255) DEFAULT NULL,
  `saturday` varchar(255) DEFAULT NULL,
  `sat_timein` varchar(255) DEFAULT NULL,
  `sat_timeout` varchar(255) DEFAULT NULL,
  `sat_wfh` varchar(255) DEFAULT NULL,
  `sunday` varchar(255) DEFAULT NULL,
  `sun_timein` varchar(255) DEFAULT NULL,
  `sun_timeout` varchar(255) DEFAULT NULL,
  `sun_wfh` varchar(255) DEFAULT NULL,
  `flexible` varchar(255) DEFAULT NULL,
  `enable_grace_period` varchar(255) NOT NULL,
  `grace_period` varchar(255) DEFAULT NULL,
  `enable_sched_ot` varchar(255) NOT NULL,
  `sched_ot` varchar(255) DEFAULT NULL,
  `sched_holiday` varchar(255) DEFAULT NULL,
  `restday` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_company_tb`
--

CREATE TABLE `settings_company_tb` (
  `id` int(11) NOT NULL,
  `cmpny_logo` longblob NOT NULL,
  `cmpny_name` varchar(255) NOT NULL,
  `cmpny_address` varchar(255) NOT NULL,
  `cmpny_zipcode` int(11) NOT NULL,
  `col_salary_settings` varchar(30) NOT NULL,
  `piece_rate_toggle` varchar(255) DEFAULT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings_tb`
--

CREATE TABLE `settings_tb` (
  `id` int(11) NOT NULL,
  `holiday_pay` varchar(255) NOT NULL,
  `_datetime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `undertime_tb`
--

CREATE TABLE `undertime_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_undertime` time NOT NULL,
  `file_attachment` longblob NOT NULL,
  `ut_action_taken` datetime NOT NULL,
  `ut_remarks` varchar(255) NOT NULL,
  `reason` varchar(500) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_file` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tb`
--

CREATE TABLE `user_tb` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `userType` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wfh_tb`
--

CREATE TABLE `wfh_tb` (
  `id` int(11) NOT NULL,
  `empid` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `schedule_type` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `reason` varchar(500) NOT NULL,
  `file_attachment` longblob NOT NULL,
  `wfh_action_taken` datetime NOT NULL,
  `wfh_remarks` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_file` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actiontaken_tb`
--
ALTER TABLE `actiontaken_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `allowancededuct_tb`
--
ALTER TABLE `allowancededuct_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcement_tb`
--
ALTER TABLE `announcement_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applyleave_tb`
--
ALTER TABLE `applyleave_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `approver_tb`
--
ALTER TABLE `approver_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assigned_company_code_tb`
--
ALTER TABLE `assigned_company_code_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance_tb`
--
ALTER TABLE `attendance_tb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empid` (`empid`);

--
-- Indexes for table `attendance_time_in`
--
ALTER TABLE `attendance_time_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personId` (`time_in_personId`);

--
-- Indexes for table `attendance_time_out`
--
ALTER TABLE `attendance_time_out`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_out_personId` (`time_out_personId`);

--
-- Indexes for table `branch_tb`
--
ALTER TABLE `branch_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classification_tb`
--
ALTER TABLE `classification_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_code_tb`
--
ALTER TABLE `company_code_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cutoff_tb`
--
ALTER TABLE `cutoff_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `daily_time_records_tb`
--
ALTER TABLE `daily_time_records_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_tb`
--
ALTER TABLE `department_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dept_tb`
--
ALTER TABLE `dept_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `empcutoff_tb`
--
ALTER TABLE `empcutoff_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `employee_pakyawan_work_tb`
--
ALTER TABLE `employee_pakyawan_work_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_tb`
--
ALTER TABLE `employee_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `empschedule_tb`
--
ALTER TABLE `empschedule_tb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empid` (`empid`);

--
-- Indexes for table `emp_dtr_tb`
--
ALTER TABLE `emp_dtr_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_official_tb`
--
ALTER TABLE `emp_official_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_tb`
--
ALTER TABLE `event_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `governdeduct_tb`
--
ALTER TABLE `governdeduct_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holiday_tb`
--
ALTER TABLE `holiday_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaveinfo_tb`
--
ALTER TABLE `leaveinfo_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `leavetype_tb`
--
ALTER TABLE `leavetype_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `overtime_tb`
--
ALTER TABLE `overtime_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pakyawan_based_work_tb`
--
ALTER TABLE `pakyawan_based_work_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pakyawan_cutoff_tb`
--
ALTER TABLE `pakyawan_cutoff_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pakyawan_payroll_tb`
--
ALTER TABLE `pakyawan_payroll_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pakyaw_cash_advance_tb`
--
ALTER TABLE `pakyaw_cash_advance_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_loan_tb`
--
ALTER TABLE `payroll_loan_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_report_tb`
--
ALTER TABLE `payslip_report_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_tb`
--
ALTER TABLE `payslip_tb`
  ADD PRIMARY KEY (`col_ID`);

--
-- Indexes for table `piece_rate_tb`
--
ALTER TABLE `piece_rate_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positionn_tb`
--
ALTER TABLE `positionn_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sample_ai`
--
ALTER TABLE `sample_ai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `1` (`sample_ai`);

--
-- Indexes for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_name` (`schedule_name`);

--
-- Indexes for table `settings_company_tb`
--
ALTER TABLE `settings_company_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings_tb`
--
ALTER TABLE `settings_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `undertime_tb`
--
ALTER TABLE `undertime_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tb`
--
ALTER TABLE `user_tb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wfh_tb`
--
ALTER TABLE `wfh_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actiontaken_tb`
--
ALTER TABLE `actiontaken_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allowancededuct_tb`
--
ALTER TABLE `allowancededuct_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcement_tb`
--
ALTER TABLE `announcement_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applyleave_tb`
--
ALTER TABLE `applyleave_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approver_tb`
--
ALTER TABLE `approver_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_company_code_tb`
--
ALTER TABLE `assigned_company_code_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_tb`
--
ALTER TABLE `attendance_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_time_in`
--
ALTER TABLE `attendance_time_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_time_out`
--
ALTER TABLE `attendance_time_out`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_tb`
--
ALTER TABLE `branch_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classification_tb`
--
ALTER TABLE `classification_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_code_tb`
--
ALTER TABLE `company_code_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cutoff_tb`
--
ALTER TABLE `cutoff_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_time_records_tb`
--
ALTER TABLE `daily_time_records_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department_tb`
--
ALTER TABLE `department_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dept_tb`
--
ALTER TABLE `dept_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empcutoff_tb`
--
ALTER TABLE `empcutoff_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_pakyawan_work_tb`
--
ALTER TABLE `employee_pakyawan_work_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_tb`
--
ALTER TABLE `employee_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empschedule_tb`
--
ALTER TABLE `empschedule_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_dtr_tb`
--
ALTER TABLE `emp_dtr_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_official_tb`
--
ALTER TABLE `emp_official_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_tb`
--
ALTER TABLE `event_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `governdeduct_tb`
--
ALTER TABLE `governdeduct_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holiday_tb`
--
ALTER TABLE `holiday_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaveinfo_tb`
--
ALTER TABLE `leaveinfo_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leavetype_tb`
--
ALTER TABLE `leavetype_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `overtime_tb`
--
ALTER TABLE `overtime_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pakyawan_based_work_tb`
--
ALTER TABLE `pakyawan_based_work_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pakyawan_cutoff_tb`
--
ALTER TABLE `pakyawan_cutoff_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pakyawan_payroll_tb`
--
ALTER TABLE `pakyawan_payroll_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pakyaw_cash_advance_tb`
--
ALTER TABLE `pakyaw_cash_advance_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_loan_tb`
--
ALTER TABLE `payroll_loan_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslip_report_tb`
--
ALTER TABLE `payslip_report_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslip_tb`
--
ALTER TABLE `payslip_tb`
  MODIFY `col_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `piece_rate_tb`
--
ALTER TABLE `piece_rate_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positionn_tb`
--
ALTER TABLE `positionn_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sample_ai`
--
ALTER TABLE `sample_ai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_tb`
--
ALTER TABLE `schedule_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_company_tb`
--
ALTER TABLE `settings_company_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings_tb`
--
ALTER TABLE `settings_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `undertime_tb`
--
ALTER TABLE `undertime_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tb`
--
ALTER TABLE `user_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wfh_tb`
--
ALTER TABLE `wfh_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
