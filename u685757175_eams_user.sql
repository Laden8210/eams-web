-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 16, 2024 at 01:38 PM
-- Server version: 10.11.8-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u685757175_eams_user`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `attendanceid` bigint(20) NOT NULL,
  `id_student` varchar(255) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `session` varchar(255) NOT NULL,
  `type_` varchar(255) NOT NULL,
  `attendance` varchar(255) NOT NULL,
  `dt_attendance` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblattendance`
--

INSERT INTO `tblattendance` (`attendanceid`, `id_student`, `event_id`, `session`, `type_`, `attendance`, `dt_attendance`) VALUES
(1, '1', '1', 'Morning', 'Time In', '08:15:29', '2024-08-31 06:15:29'),
(2, '1', '2', 'Afternoon', 'Time Out', '08:16:40', '2024-08-31 06:16:40'),
(3, '1', '2', 'Afternoon', 'Time In', '08:18:01', '2024-08-31 06:18:01'),
(4, '1', '3', 'Afternoon', 'Time Out', '08:20:34', '2024-08-31 06:20:34'),
(5, '3', '1', 'Morning', 'Time In', '08:30:57', '2024-08-31 06:30:57'),
(6, '1', '5', 'Afternoon', 'Time In', '11:43:24', '2024-09-10 11:43:24');

-- --------------------------------------------------------

--
-- Table structure for table `tblevent`
--

CREATE TABLE `tblevent` (
  `eventid` bigint(20) NOT NULL,
  `title_event` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `duration` varchar(255) NOT NULL,
  `from_` varchar(255) NOT NULL,
  `to_` varchar(255) NOT NULL,
  `officer_id` varchar(255) NOT NULL,
  `program_idd` varchar(255) NOT NULL,
  `time_in_morning` varchar(255) NOT NULL,
  `time_out_morning` varchar(255) NOT NULL,
  `time_in_afternoon` varchar(255) NOT NULL,
  `time_out_afternoon` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `sem` varchar(255) NOT NULL,
  `dt_event` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblevent`
--

INSERT INTO `tblevent` (`eventid`, `title_event`, `venue`, `duration`, `from_`, `to_`, `officer_id`, `program_idd`, `time_in_morning`, `time_out_morning`, `time_in_afternoon`, `time_out_afternoon`, `amount`, `sem`, `dt_event`) VALUES
(1, 'Intrams', 'School', 'whole-day', '2024-09-02', '2024-09-02', '1', '2', '08:00', '12:00', '13:00', '17:00', '50', '1st', '2024-09-02 12:25:41'),
(2, 'IT Day', 'Gym', 'half-day-morning', '2024-08-31', '2024-08-31', '1', '1', '08:00', '12:00', '', '', '50', '1st', '2024-09-02 12:25:35'),
(3, 'qeqwe', 'qweqwe', 'half-day-afternoon', '2024-08-31', '2024-08-31', '1', '1', '', '', '13:00', '17:00', '50', '1st', '2024-09-02 12:25:31'),
(4, 'Event Sample 1', 'School', 'whole-day', '2024-09-02', '2024-09-03', '1', '1', '08:00', '12:00', '13:00', '17:00', '50', '1st', '2024-09-02 05:34:44'),
(5, 'val', 'voag', 'whole-day', '2024-01-01', '2024-01-02', '', 'All', '08:41', '12:41', '13:41', '16:41', '50', '', '2024-09-10 11:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `tblofficer`
--

CREATE TABLE `tblofficer` (
  `officerid` bigint(20) NOT NULL,
  `off_fname` varchar(255) NOT NULL,
  `off_mname` varchar(255) NOT NULL,
  `off_lname` varchar(255) NOT NULL,
  `off_mobile` varchar(255) NOT NULL,
  `off_pos` varchar(255) NOT NULL,
  `off_pic` varchar(255) NOT NULL,
  `off_username` varchar(255) NOT NULL,
  `off_passw` varchar(255) NOT NULL,
  `dt_off` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblofficer`
--

INSERT INTO `tblofficer` (`officerid`, `off_fname`, `off_mname`, `off_lname`, `off_mobile`, `off_pos`, `off_pic`, `off_username`, `off_passw`, `dt_off`) VALUES
(1, 'John', 'W', 'Wick', '091212154', 'Secretary', '', 'john.wick', 'password123', '2024-08-29 14:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment`
--

CREATE TABLE `tblpayment` (
  `paymentid` bigint(20) NOT NULL,
  `stud_payid` varchar(255) NOT NULL,
  `pay_eventid` varchar(255) NOT NULL,
  `totalamount` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `dt_payment` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayment`
--

INSERT INTO `tblpayment` (`paymentid`, `stud_payid`, `pay_eventid`, `totalamount`, `status`, `dt_payment`) VALUES
(1, '1', '1', '50', 'Paid', '2024-09-03 13:34:26'),
(2, '1', '2', '50', 'Paid', '2024-09-03 13:34:26'),
(3, '1', '3', '50', 'Paid', '2024-09-03 13:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `tblprogram`
--

CREATE TABLE `tblprogram` (
  `programid` bigint(20) NOT NULL,
  `program` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblprogram`
--

INSERT INTO `tblprogram` (`programid`, `program`) VALUES
(1, 'BSIT'),
(2, 'BSED');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `studentid` bigint(20) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `stud_fname` varchar(255) NOT NULL,
  `stud_mname` varchar(255) NOT NULL,
  `stud_lname` varchar(255) NOT NULL,
  `stud_programid` varchar(255) NOT NULL,
  `yrlevel` varchar(255) NOT NULL,
  `stud_section` varchar(255) NOT NULL,
  `stud_mobile` varchar(255) NOT NULL,
  `stud_profpic` varchar(255) NOT NULL,
  `stud_username` varchar(255) NOT NULL,
  `stud_password` varchar(255) NOT NULL,
  `dt_student` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`studentid`, `student_id`, `stud_fname`, `stud_mname`, `stud_lname`, `stud_programid`, `yrlevel`, `stud_section`, `stud_mobile`, `stud_profpic`, `stud_username`, `stud_password`, `dt_student`) VALUES
(1, '2024-00855', 'Stephen qe', 'Smith', 'Curry', '1', '2', 'A', '0912312231', 'images (5).jpeg', 'stephen.curry', 'Password123', '2024-09-10 11:09:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userid` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `passw` varchar(255) NOT NULL,
  `dt_user` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userid`, `username`, `passw`, `dt_user`) VALUES
(1, 'Administrator', 'admin1234', '2024-09-03 14:05:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`attendanceid`);

--
-- Indexes for table `tblevent`
--
ALTER TABLE `tblevent`
  ADD PRIMARY KEY (`eventid`);

--
-- Indexes for table `tblofficer`
--
ALTER TABLE `tblofficer`
  ADD PRIMARY KEY (`officerid`);

--
-- Indexes for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD PRIMARY KEY (`paymentid`);

--
-- Indexes for table `tblprogram`
--
ALTER TABLE `tblprogram`
  ADD PRIMARY KEY (`programid`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`studentid`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `attendanceid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblevent`
--
ALTER TABLE `tblevent`
  MODIFY `eventid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblofficer`
--
ALTER TABLE `tblofficer`
  MODIFY `officerid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblpayment`
--
ALTER TABLE `tblpayment`
  MODIFY `paymentid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblprogram`
--
ALTER TABLE `tblprogram`
  MODIFY `programid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `studentid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
