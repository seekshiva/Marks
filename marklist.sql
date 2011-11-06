-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 06, 2011 at 06:33 AM
-- Server version: 5.5.17
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `marklist`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `class_id` int(10) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(16) NOT NULL,
  `curriculum` enum('CBSE','Samacheer') NOT NULL DEFAULT 'CBSE',
  `cteacher_id` int(8) NOT NULL COMMENT 'Class Teacher Id',
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `curriculum`, `cteacher_id`) VALUES
(1, 'XII-A(Bio)', 'CBSE', 1),
(2, 'XII-A(CS)', 'Samacheer', 2);

-- --------------------------------------------------------

--
-- Table structure for table `coursecode`
--

CREATE TABLE IF NOT EXISTS `coursecode` (
  `course_code` int(3) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(32) NOT NULL,
  `curriculum` enum('CBSE','Samacheer') NOT NULL,
  PRIMARY KEY (`course_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `coursecode`
--

INSERT INTO `coursecode` (`course_code`, `course_name`, `curriculum`) VALUES
(1, 'Language', 'CBSE'),
(2, 'English', 'CBSE'),
(3, 'Mathematics', 'CBSE'),
(4, 'Science', 'CBSE'),
(5, 'Social Science', 'CBSE'),
(6, 'Language', 'Samacheer'),
(7, 'English', 'Samacheer'),
(8, 'Mathematics', 'Samacheer'),
(9, 'Science', 'Samacheer'),
(10, 'Mathematics-1', 'Samacheer'),
(11, 'Mathematics-2', 'Samacheer'),
(12, 'Social Science', 'Samacheer'),
(13, 'Physics', 'Samacheer'),
(14, 'Chemistry', 'Samacheer'),
(15, 'History', 'Samacheer'),
(16, 'Geography', 'Samacheer'),
(17, 'Biology', 'Samacheer');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE IF NOT EXISTS `exams` (
  `exam_id` int(32) NOT NULL AUTO_INCREMENT,
  `class_id` int(8) NOT NULL,
  `exam_name` varchar(128) NOT NULL,
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `class_id`, `exam_name`) VALUES
(1, 1, 'Quarterly'),
(2, 1, 'Half Yearly Examination'),
(3, 2, 'Quarterly'),
(4, 2, 'Half Yearly Examination');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE IF NOT EXISTS `houses` (
  `house_id` int(2) NOT NULL AUTO_INCREMENT,
  `house_name` varchar(32) NOT NULL,
  PRIMARY KEY (`house_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`house_id`, `house_name`) VALUES
(1, 'Satchidananda'),
(2, 'Chitbhavananda'),
(3, 'Vivekananda'),
(4, 'Ramalinga Adigal'),
(5, 'Thiruvalluvar'),
(6, 'Sivananda'),
(7, 'Bharathiyar'),
(8, 'Bharathidasan'),
(9, 'Elango'),
(10, 'Kambar'),
(11, 'Vedathri'),
(12, 'Kabilar'),
(13, 'Jansi Rani'),
(14, 'Sri Andal'),
(15, 'Mother Terasa'),
(16, 'Niveditha'),
(17, 'Annai Saradha'),
(18, 'Marie Curie');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE IF NOT EXISTS `marks` (
  `mark_id` int(32) NOT NULL AUTO_INCREMENT,
  `student_id` int(16) NOT NULL,
  `exam_id` int(32) NOT NULL,
  `course_code` int(3) NOT NULL,
  `marks` int(3) NOT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`mark_id`, `student_id`, `exam_id`, `course_code`, `marks`) VALUES
(61, 4, 3, 6, 98),
(62, 6, 3, 6, 78),
(63, 5, 3, 6, 98),
(64, 4, 3, 7, 78),
(65, 6, 3, 7, 78),
(66, 5, 3, 7, 98),
(67, 4, 3, 8, 98),
(68, 6, 3, 8, 98),
(69, 5, 3, 8, 78),
(70, 4, 3, 13, 78),
(71, 6, 3, 13, 87),
(72, 5, 3, 13, 87),
(73, 4, 3, 14, 96),
(74, 6, 3, 14, 78),
(75, 5, 3, 14, 98),
(76, 4, 4, 6, 98),
(77, 6, 4, 6, 87),
(78, 5, 4, 6, 68),
(79, 4, 4, 7, 78),
(80, 6, 4, 7, 87),
(81, 5, 4, 7, 67),
(82, 4, 4, 8, 87),
(83, 6, 4, 8, 67),
(84, 5, 4, 8, 98),
(85, 4, 4, 13, 98),
(86, 6, 4, 13, 78),
(87, 5, 4, 13, 98),
(88, 4, 4, 14, 98),
(89, 6, 4, 14, 78),
(90, 5, 4, 14, 98);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int(10) NOT NULL AUTO_INCREMENT,
  `adm_no` varchar(10) NOT NULL,
  `exam_no` int(8) NOT NULL,
  `student_name` varchar(64) NOT NULL,
  `class_id` int(8) NOT NULL,
  `team_id` int(4) NOT NULL,
  `house_id` int(4) NOT NULL,
  `mentor_id` int(8) NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `adm_no` (`adm_no`),
  UNIQUE KEY `exam_no` (`exam_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `adm_no`, `exam_no`, `student_name`, `class_id`, `team_id`, `house_id`, `mentor_id`) VALUES
(1, 'H201', 1201, 'Shiva Nandan', 1, 1, 1, 2),
(2, 'H202', 1203, 'Naresh Selvam', 1, 0, 0, 1),
(3, 'H203', 1202, 'Ankit', 1, 0, 0, 2),
(4, 'H204', 1207, 'Suga', 2, 3, 3, 2),
(5, 'H208', 1280, 'Shyam', 2, 1, 4, 2),
(6, 'H212', 1212, 'Vinodh', 2, 1, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int(16) NOT NULL AUTO_INCREMENT,
  `class_id` int(8) NOT NULL,
  `course_id` int(4) NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `class_id`, `course_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(6, 1, 5),
(17, 2, 6),
(18, 2, 7),
(19, 2, 8),
(20, 2, 13),
(21, 2, 14);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE IF NOT EXISTS `teachers` (
  `teacher_id` int(3) NOT NULL AUTO_INCREMENT,
  `teacher_code` varchar(5) NOT NULL,
  `teacher_name` varchar(128) NOT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `teacher_code`, `teacher_name`) VALUES
(1, 'EJL', 'Edward'),
(2, 'SG', 'Sheela Grace'),
(3, 'VV', 'Vijayakumar'),
(4, 'UB', 'Udhayabanu');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int(8) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(16) NOT NULL,
  PRIMARY KEY (`team_id`),
  UNIQUE KEY `team_name` (`team_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`) VALUES
(2, 'Agni'),
(1, 'Akash'),
(3, 'Prithvi'),
(4, 'Trishul');
