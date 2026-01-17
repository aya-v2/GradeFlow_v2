-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2026 at 06:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gradeflow_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `classe`
--

CREATE TABLE `classe` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `level` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `filiere_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classe`
--

INSERT INTO `classe` (`id`, `name`, `level`, `school_year_id`, `filiere_id`) VALUES
(1001, '3IIR-G1', 1, 1, 1),
(1002, '3GESI', 1, 1, 2),
(1003, '3GC', 1, 1, 3),
(1004, '3IIR-G2', 1, 1, 1),
(2001, '4IIR-G1', 2, 2, 1),
(2002, '4GESI', 2, 2, 2),
(2003, '4GC', 2, 2, 3),
(2004, '3IIR-G1', 1, 2, 1),
(2005, '3GESI', 1, 2, 2),
(2006, '3GC', 1, 2, 3),
(2007, '4IIR-G2', 2, 2, 1),
(2008, '3IIR-G2', 1, 2, 1),
(3001, '5IIR-G1', 3, 3, 1),
(3002, '5GESI', 3, 3, 2),
(3003, '5GC', 3, 3, 3),
(3004, '4IIR-G1', 2, 3, 1),
(3005, '4GESI', 2, 3, 2),
(3006, '4GC', 2, 3, 3),
(3007, '3IIR-G1', 1, 3, 1),
(3008, '3IIR-G2', 1, 3, 1),
(3009, '3GESI', 1, 3, 2),
(3010, '3GC', 1, 3, 3),
(3011, '5IIR-G2', 3, 3, 1),
(3012, '4IIR-G2', 2, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260108225348', '2026-01-08 23:53:55', 13447),
('DoctrineMigrations\\Version20260108230334', '2026-01-09 00:03:57', 423),
('DoctrineMigrations\\Version20260110000624', '2026-01-10 01:06:43', 1760),
('DoctrineMigrations\\Version20260111175211', '2026-01-11 18:52:41', 1331),
('DoctrineMigrations\\Version20260117145819', '2026-01-17 15:59:53', 2949);

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `id` int(11) NOT NULL,
  `enrollment_date` date NOT NULL,
  `student_id` int(11) NOT NULL,
  `associated_class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`id`, `enrollment_date`, `student_id`, `associated_class_id`) VALUES
(1, '2023-09-01', 101, 1001),
(2, '2023-09-01', 102, 1001),
(3, '2023-09-01', 103, 1001),
(4, '2023-09-01', 104, 1004),
(5, '2023-09-01', 105, 1004),
(8, '2023-09-01', 106, 1002),
(9, '2023-09-01', 107, 1002),
(10, '2023-09-01', 108, 1002),
(11, '2023-09-01', 109, 1002),
(12, '2023-09-01', 110, 1002),
(15, '2023-09-01', 111, 1003),
(16, '2023-09-01', 112, 1003),
(17, '2023-09-01', 113, 1003),
(18, '2023-09-01', 114, 1003),
(19, '2023-09-01', 115, 1003),
(22, '2024-09-01', 101, 2001),
(23, '2024-09-01', 102, 2001),
(24, '2024-09-01', 103, 2001),
(25, '2024-09-01', 104, 2007),
(26, '2024-09-01', 105, 2007),
(29, '2024-09-01', 106, 2002),
(30, '2024-09-01', 107, 2002),
(31, '2024-09-01', 108, 2002),
(32, '2024-09-01', 109, 2002),
(33, '2024-09-01', 110, 2002),
(36, '2024-09-01', 111, 2003),
(37, '2024-09-01', 112, 2003),
(38, '2024-09-01', 113, 2003),
(39, '2024-09-01', 114, 2003),
(40, '2024-09-01', 115, 2003),
(43, '2025-09-01', 101, 3001),
(44, '2025-09-01', 102, 3001),
(45, '2025-09-01', 103, 3001),
(46, '2025-09-01', 104, 3011),
(47, '2025-09-01', 105, 3011),
(50, '2025-09-01', 106, 3002),
(51, '2025-09-01', 107, 3002),
(52, '2025-09-01', 108, 3002),
(53, '2025-09-01', 109, 3002),
(54, '2025-09-01', 110, 3002),
(57, '2025-09-01', 111, 3003),
(58, '2025-09-01', 112, 3003),
(59, '2025-09-01', 113, 3003),
(60, '2025-09-01', 114, 3003),
(61, '2025-09-01', 115, 3003),
(64, '2024-09-01', 201, 2004),
(65, '2024-09-01', 202, 2004),
(66, '2024-09-01', 203, 2004),
(67, '2024-09-01', 204, 2008),
(68, '2024-09-01', 205, 2008),
(71, '2024-09-01', 206, 2005),
(72, '2024-09-01', 207, 2005),
(73, '2024-09-01', 208, 2005),
(74, '2024-09-01', 209, 2005),
(75, '2024-09-01', 210, 2005),
(78, '2024-09-01', 211, 2006),
(79, '2024-09-01', 212, 2006),
(80, '2024-09-01', 213, 2006),
(81, '2024-09-01', 214, 2006),
(82, '2024-09-01', 215, 2006),
(85, '2025-09-01', 201, 3004),
(86, '2025-09-01', 202, 3004),
(87, '2025-09-01', 203, 3004),
(88, '2025-09-01', 204, 3012),
(89, '2025-09-01', 205, 3012),
(92, '2025-09-01', 206, 3005),
(93, '2025-09-01', 207, 3005),
(94, '2025-09-01', 208, 3005),
(95, '2025-09-01', 209, 3005),
(96, '2025-09-01', 210, 3005),
(99, '2025-09-01', 211, 3006),
(100, '2025-09-01', 212, 3006),
(101, '2025-09-01', 213, 3006),
(102, '2025-09-01', 214, 3006),
(103, '2025-09-01', 215, 3006),
(106, '2025-09-01', 301, 3007),
(107, '2025-09-01', 302, 3007),
(108, '2025-09-01', 303, 3007),
(109, '2025-09-01', 304, 3007),
(110, '2025-09-01', 305, 3007),
(113, '2025-09-01', 306, 3008),
(114, '2025-09-01', 307, 3008),
(115, '2025-09-01', 308, 3008),
(116, '2025-09-01', 309, 3008),
(117, '2025-09-01', 310, 3008),
(120, '2025-09-01', 311, 3009),
(121, '2025-09-01', 312, 3009),
(122, '2025-09-01', 313, 3009),
(123, '2025-09-01', 314, 3009),
(124, '2025-09-01', 315, 3009),
(127, '2025-09-01', 316, 3010),
(128, '2025-09-01', 317, 3010),
(129, '2025-09-01', 318, 3010),
(130, '2025-09-01', 319, 3010),
(131, '2025-09-01', 320, 3010);

-- --------------------------------------------------------

--
-- Table structure for table `filiere`
--

CREATE TABLE `filiere` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `filiere`
--

INSERT INTO `filiere` (`id`, `name`, `code`) VALUES
(1, 'Ingénierie Informatique & Réseaux', 'IIR'),
(2, 'Génie Électrique & Systèmes Intelligents', 'GESI'),
(3, 'Génie Civil', 'GC');

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `id` int(11) NOT NULL,
  `cc_grade` double DEFAULT NULL,
  `exam_grade` double DEFAULT NULL,
  `is_published` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`id`, `cc_grade`, `exam_grade`, `is_published`, `student_id`, `module_id`) VALUES
(1, 16, 16, 2, 101, 101),
(2, 15, 19, 2, 102, 101),
(3, 18, 14, 2, 103, 101),
(4, 15, 14, 2, 104, 101),
(5, 14, 12, 2, 105, 101),
(6, 16, 14, 2, 101, 102),
(7, 13, 15, 2, 102, 102),
(8, 15, 10, 2, 103, 102),
(9, 18, 17, 2, 104, 102),
(10, 13, 16, 2, 105, 102),
(11, 12, 10, 2, 101, 103),
(12, 14, 13, 2, 102, 103),
(13, 11, 19, 2, 103, 103),
(14, 14, 10, 2, 104, 103),
(15, 18, 12, 2, 105, 103),
(16, 17, 10, 2, 101, 104),
(17, 19, 14, 2, 102, 104),
(18, 13, 13, 2, 103, 104),
(19, 18, 11, 2, 104, 104),
(20, 12, 18, 2, 105, 104),
(21, 14, 17, 2, 101, 105),
(22, 14, 17, 2, 102, 105),
(23, 16, 19, 2, 103, 105),
(24, 19, 17, 2, 104, 105),
(25, 10, 10, 2, 105, 105),
(26, 17, 19, 2, 101, 106),
(27, 13, 10, 2, 102, 106),
(28, 10, 12, 2, 103, 106),
(29, 19, 12, 2, 104, 106),
(30, 10, 18, 2, 105, 106),
(32, 18, 17, 2, 106, 201),
(33, 11, 16, 2, 107, 201),
(34, 18, 11, 2, 108, 201),
(35, 11, 13, 2, 109, 201),
(36, 14, 11, 2, 110, 201),
(37, 14, 18, 2, 106, 202),
(38, 17, 11, 2, 107, 202),
(39, 14, 16, 2, 108, 202),
(40, 12, 12, 2, 109, 202),
(41, 14, 13, 2, 110, 202),
(42, 13, 19, 2, 106, 203),
(43, 14, 13, 2, 107, 203),
(44, 16, 10, 2, 108, 203),
(45, 11, 17, 2, 109, 203),
(46, 14, 19, 2, 110, 203),
(47, 12, 13, 2, 106, 204),
(48, 12, 11, 2, 107, 204),
(49, 19, 12, 2, 108, 204),
(50, 15, 11, 2, 109, 204),
(51, 18, 19, 2, 110, 204),
(52, 19, 10, 2, 106, 205),
(53, 14, 11, 2, 107, 205),
(54, 11, 13, 2, 108, 205),
(55, 12, 14, 2, 109, 205),
(56, 12, 17, 2, 110, 205),
(57, 13, 12, 2, 106, 206),
(58, 11, 10, 2, 107, 206),
(59, 19, 15, 2, 108, 206),
(60, 17, 12, 2, 109, 206),
(61, 17, 12, 2, 110, 206),
(63, 18, 16, 2, 111, 301),
(64, 17, 18, 2, 112, 301),
(65, 17, 12, 2, 113, 301),
(66, 12, 12, 2, 114, 301),
(67, 15, 19, 2, 115, 301),
(68, 12, 13, 2, 111, 302),
(69, 10, 19, 2, 112, 302),
(70, 17, 17, 2, 113, 302),
(71, 14, 10, 2, 114, 302),
(72, 11, 13, 2, 115, 302),
(73, 14, 12, 2, 111, 303),
(74, 19, 10, 2, 112, 303),
(75, 14, 10, 2, 113, 303),
(76, 18, 11, 2, 114, 303),
(77, 19, 16, 2, 115, 303),
(78, 10, 15, 2, 111, 304),
(79, 13, 13, 2, 112, 304),
(80, 17, 17, 2, 113, 304),
(81, 13, 14, 2, 114, 304),
(82, 14, 17, 2, 115, 304),
(83, 14, 10, 2, 111, 305),
(84, 19, 16, 2, 112, 305),
(85, 13, 16, 2, 113, 305),
(86, 11, 19, 2, 114, 305),
(87, 12, 13, 2, 115, 305),
(88, 19, 15, 2, 111, 306),
(89, 11, 18, 2, 112, 306),
(90, 10, 16, 2, 113, 306),
(91, 18, 16, 2, 114, 306),
(92, 15, 17, 2, 115, 306),
(94, 10, 18, 2, 101, 107),
(95, 13, 12, 2, 102, 107),
(96, 10, 15, 2, 103, 107),
(97, 16, 16, 2, 104, 107),
(98, 10, 14, 2, 105, 107),
(99, 18, 12, 2, 101, 108),
(100, 14, 17, 2, 102, 108),
(101, 12, 18, 2, 103, 108),
(102, 17, 13, 2, 104, 108),
(103, 11, 19, 2, 105, 108),
(104, 13, 19, 2, 101, 109),
(105, 13, 12, 2, 102, 109),
(106, 10, 15, 2, 103, 109),
(107, 14, 17, 2, 104, 109),
(108, 14, 18, 2, 105, 109),
(109, 18, 16, 2, 101, 110),
(110, 19, 18, 2, 102, 110),
(111, 11, 11, 2, 103, 110),
(112, 13, 11, 2, 104, 110),
(113, 19, 10, 2, 105, 110),
(114, 15, 15, 2, 101, 111),
(115, 19, 12, 2, 102, 111),
(116, 12, 14, 2, 103, 111),
(117, 14, 11, 2, 104, 111),
(118, 14, 15, 2, 105, 111),
(119, 16, 16, 2, 101, 112),
(120, 11, 19, 2, 102, 112),
(121, 10, 15, 2, 103, 112),
(122, 15, 11, 2, 104, 112),
(123, 19, 14, 2, 105, 112),
(125, 15, 12, 2, 106, 207),
(126, 14, 15, 2, 107, 207),
(127, 15, 18, 2, 108, 207),
(128, 17, 13, 2, 109, 207),
(129, 14, 13, 2, 110, 207),
(130, 11, 17, 2, 106, 208),
(131, 13, 14, 2, 107, 208),
(132, 13, 14, 2, 108, 208),
(133, 10, 19, 2, 109, 208),
(134, 14, 14, 2, 110, 208),
(135, 18, 18, 2, 106, 209),
(136, 18, 17, 2, 107, 209),
(137, 19, 16, 2, 108, 209),
(138, 13, 17, 2, 109, 209),
(139, 18, 10, 2, 110, 209),
(140, 14, 14, 2, 106, 210),
(141, 15, 15, 2, 107, 210),
(142, 12, 15, 2, 108, 210),
(143, 10, 16, 2, 109, 210),
(144, 12, 10, 2, 110, 210),
(145, 15, 15, 2, 106, 211),
(146, 11, 11, 2, 107, 211),
(147, 12, 19, 2, 108, 211),
(148, 10, 11, 2, 109, 211),
(149, 16, 10, 2, 110, 211),
(150, 11, 14, 2, 106, 212),
(151, 19, 12, 2, 107, 212),
(152, 16, 15, 2, 108, 212),
(153, 18, 12, 2, 109, 212),
(154, 10, 12, 2, 110, 212),
(156, 10, 14, 2, 111, 307),
(157, 11, 15, 2, 112, 307),
(158, 10, 16, 2, 113, 307),
(159, 10, 12, 2, 114, 307),
(160, 10, 18, 2, 115, 307),
(161, 17, 11, 2, 111, 308),
(162, 18, 15, 2, 112, 308),
(163, 10, 18, 2, 113, 308),
(164, 12, 14, 2, 114, 308),
(165, 15, 14, 2, 115, 308),
(166, 16, 19, 2, 111, 309),
(167, 15, 10, 2, 112, 309),
(168, 16, 11, 2, 113, 309),
(169, 17, 10, 2, 114, 309),
(170, 13, 13, 2, 115, 309),
(171, 17, 19, 2, 111, 310),
(172, 12, 13, 2, 112, 310),
(173, 11, 17, 2, 113, 310),
(174, 13, 12, 2, 114, 310),
(175, 14, 15, 2, 115, 310),
(176, 12, 15, 2, 111, 311),
(177, 11, 10, 2, 112, 311),
(178, 19, 16, 2, 113, 311),
(179, 14, 13, 2, 114, 311),
(180, 14, 12, 2, 115, 311),
(181, 18, 16, 2, 111, 312),
(182, 14, 15, 2, 112, 312),
(183, 11, 19, 2, 113, 312),
(184, 16, 10, 2, 114, 312),
(185, 16, 10, 2, 115, 312),
(187, 12, 19, 2, 201, 101),
(188, 11, 17, 2, 202, 101),
(189, 13, 14, 2, 203, 101),
(190, 13, 13, 2, 204, 101),
(191, 18, 10, 2, 205, 101),
(192, 19, 12, 2, 201, 102),
(193, 15, 11, 2, 202, 102),
(194, 19, 14, 2, 203, 102),
(195, 12, 10, 2, 204, 102),
(196, 13, 18, 2, 205, 102),
(197, 19, 14, 2, 201, 103),
(198, 12, 18, 2, 202, 103),
(199, 13, 15, 2, 203, 103),
(200, 14, 18, 2, 204, 103),
(201, 16, 16, 2, 205, 103),
(202, 12, 13, 2, 201, 104),
(203, 10, 12, 2, 202, 104),
(204, 10, 15, 2, 203, 104),
(205, 16, 16, 2, 204, 104),
(206, 12, 13, 2, 205, 104),
(207, 11, 15, 2, 201, 105),
(208, 12, 17, 2, 202, 105),
(209, 10, 19, 2, 203, 105),
(210, 17, 15, 2, 204, 105),
(211, 18, 13, 2, 205, 105),
(212, 11, 19, 2, 201, 106),
(213, 14, 10, 2, 202, 106),
(214, 18, 11, 2, 203, 106),
(215, 12, 19, 2, 204, 106),
(216, 17, 11, 2, 205, 106),
(218, 15, 19, 2, 206, 201),
(219, 14, 10, 2, 207, 201),
(220, 11, 15, 2, 208, 201),
(221, 11, 13, 2, 209, 201),
(222, 14, 18, 2, 210, 201),
(223, 19, 12, 2, 206, 202),
(224, 12, 15, 2, 207, 202),
(225, 19, 19, 2, 208, 202),
(226, 12, 10, 2, 209, 202),
(227, 16, 10, 2, 210, 202),
(228, 15, 12, 2, 206, 203),
(229, 17, 19, 2, 207, 203),
(230, 15, 17, 2, 208, 203),
(231, 13, 15, 2, 209, 203),
(232, 14, 18, 2, 210, 203),
(233, 18, 18, 2, 206, 204),
(234, 14, 19, 2, 207, 204),
(235, 11, 19, 2, 208, 204),
(236, 12, 15, 2, 209, 204),
(237, 10, 14, 2, 210, 204),
(238, 10, 10, 2, 206, 205),
(239, 19, 15, 2, 207, 205),
(240, 19, 11, 2, 208, 205),
(241, 10, 15, 2, 209, 205),
(242, 17, 10, 2, 210, 205),
(243, 11, 16, 2, 206, 206),
(244, 18, 13, 2, 207, 206),
(245, 11, 15, 2, 208, 206),
(246, 11, 13, 2, 209, 206),
(247, 11, 17, 2, 210, 206),
(249, 13, 13, 2, 211, 301),
(250, 18, 13, 2, 212, 301),
(251, 18, 14, 2, 213, 301),
(252, 16, 19, 2, 214, 301),
(253, 18, 14, 2, 215, 301),
(254, 14, 11, 2, 211, 302),
(255, 11, 12, 2, 212, 302),
(256, 17, 12, 2, 213, 302),
(257, 18, 13, 2, 214, 302),
(258, 12, 13, 2, 215, 302),
(259, 11, 13, 2, 211, 303),
(260, 15, 16, 2, 212, 303),
(261, 13, 11, 2, 213, 303),
(262, 14, 18, 2, 214, 303),
(263, 17, 14, 2, 215, 303),
(264, 18, 18, 2, 211, 304),
(265, 19, 11, 2, 212, 304),
(266, 18, 19, 2, 213, 304),
(267, 11, 17, 2, 214, 304),
(268, 14, 10, 2, 215, 304),
(269, 17, 14, 2, 211, 305),
(270, 11, 12, 2, 212, 305),
(271, 10, 13, 2, 213, 305),
(272, 16, 12, 2, 214, 305),
(273, 11, 11, 2, 215, 305),
(274, 13, 11, 2, 211, 306),
(275, 15, 14, 2, 212, 306),
(276, 18, 15, 2, 213, 306),
(277, 11, 13, 2, 214, 306),
(278, 13, 17, 2, 215, 306);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `coefficient` double NOT NULL,
  `semester` varchar(20) NOT NULL,
  `filiere_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `coefficient`, `semester`, `filiere_id`) VALUES
(101, 'Programmation Orientée Objet Avancée', 4, 'S5', 1),
(102, 'Systèmes de Gestion de Bases de Données', 4, 'S5', 1),
(103, 'Ingénierie Logicielle & Modélisation UML', 3, 'S5', 1),
(104, 'Développement Web Full-Stack & DevOps', 4, 'S6', 1),
(105, 'Systèmes d’Exploitation Avancés & Cloud', 4, 'S6', 1),
(106, 'Projet de Fin d’Année I (PFA I)', 3, 'S6', 1),
(107, 'Architectures Réseaux & Cybersécurité', 4, 'S7', 1),
(108, 'Apprentissage Automatique (ML)', 4, 'S7', 1),
(109, 'Stage Technique d’Été', 2, 'S7', 1),
(110, 'Systèmes Distribués & Middleware', 4, 'S8', 1),
(111, 'Big Data & Data Engineering', 4, 'S8', 1),
(112, 'Projet Ingénieur I', 3, 'S8', 1),
(113, 'Intégration des Systèmes d’Information', 3, 'S9', 1),
(114, 'Transformation Digitale & Innovation', 3, 'S9', 1),
(115, 'Projet Ingénieur II', 3, 'S9', 1),
(116, 'Stage de Fin d’Études', 4, 'S10', 1),
(117, 'Projet de Fin d’Études (PFE)', 6, 'S10', 1),
(201, 'Machines Électriques & Entraînements', 4, 'S5', 2),
(202, 'Automatique Continue & Discrète', 3, 'S5', 2),
(203, 'Électronique de Puissance Industrielle', 4, 'S5', 2),
(204, 'Automates Programmables & SCADA', 3, 'S6', 2),
(205, 'Systèmes Embarqués Temps Réel', 4, 'S6', 2),
(206, 'Projet de Fin d’Année I (PFA I)', 3, 'S6', 2),
(207, 'Robotique Industrielle Avancée', 4, 'S7', 2),
(208, 'Architectures Industrie 4.0 & IIoT', 3, 'S7', 2),
(209, 'Stage Technique d’Été', 2, 'S7', 2),
(210, 'Systèmes d’Énergies Renouvelables', 4, 'S8', 2),
(211, 'Réseaux Électriques Intelligents', 4, 'S8', 2),
(212, 'Projet Ingénieur I', 3, 'S8', 2),
(213, 'Optimisation Énergétique & Audit', 3, 'S9', 2),
(214, 'Systèmes Intelligents & Commande', 3, 'S9', 2),
(215, 'Projet Ingénieur II', 3, 'S9', 2),
(216, 'Stage de Fin d’Études', 4, 'S10', 2),
(217, 'Projet de Fin d’Études (PFE)', 6, 'S10', 2),
(301, 'Calcul des Structures en Béton Armé', 4, 'S5', 3),
(302, 'Mécanique des Sols & Géotechnique', 4, 'S5', 3),
(303, 'Topographie & Levés Techniques', 3, 'S5', 3),
(304, 'Structures en Béton Précontraint', 4, 'S6', 3),
(305, 'Hydraulique des Ouvrages', 4, 'S6', 3),
(306, 'Projet de Fin d’Année I (PFA I)', 3, 'S6', 3),
(307, 'Conception des Routes & Infrastructures', 3, 'S7', 3),
(308, 'Réseaux d’Eau Potable & Assainissement', 3, 'S7', 3),
(309, 'Stage Technique d’Été', 2, 'S7', 3),
(310, 'Génie Parasismique', 4, 'S8', 3),
(311, 'Modélisation BIM & SIG', 3, 'S8', 3),
(312, 'Projet Ingénieur I', 3, 'S8', 3),
(313, 'Conception des Smart Cities', 3, 'S9', 3),
(314, 'Développement Durable', 3, 'S9', 3),
(315, 'Projet Ingénieur II', 3, 'S9', 3),
(316, 'Stage de Fin d’Études', 4, 'S10', 3),
(317, 'Projet de Fin d’Études (PFE)', 6, 'S10', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `is_current` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `name`, `is_current`) VALUES
(1, '2023-2024', 0),
(2, '2024-2025', 0),
(3, '2025-2026', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teaching_assignment`
--

CREATE TABLE `teaching_assignment` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `associated_class_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teaching_assignment`
--

INSERT INTO `teaching_assignment` (`id`, `teacher_id`, `associated_class_id`, `module_id`) VALUES
(1, 2, 3001, 113),
(2, 3, 3001, 114),
(3, 4, 3001, 115),
(4, 5, 3004, 110),
(5, 6, 3004, 111),
(6, 7, 3004, 112),
(7, 8, 3007, 101),
(8, 9, 3007, 102),
(9, 2, 3007, 103),
(10, 8, 3008, 101),
(11, 9, 3008, 102),
(12, 2, 3008, 103),
(13, 11, 3002, 213),
(14, 12, 3002, 214),
(15, 13, 3002, 215),
(16, 14, 3005, 210),
(17, 15, 3005, 211),
(18, 16, 3005, 212),
(19, 17, 3009, 201),
(20, 18, 3009, 202),
(21, 19, 3009, 203),
(22, 20, 3003, 313),
(23, 21, 3003, 314),
(24, 22, 3003, 315),
(25, 23, 3006, 310),
(26, 24, 3006, 311),
(27, 25, 3006, 312),
(28, 26, 3010, 301),
(29, 27, 3010, 302),
(30, 28, 3010, 303),
(31, 2, 3011, 113),
(32, 3, 3011, 114),
(33, 4, 3011, 115),
(34, 5, 3012, 110),
(35, 6, 3012, 111),
(36, 7, 3012, 112);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `cin` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `cne` varchar(50) DEFAULT NULL,
  `specialty` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `classe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `cin`, `type`, `cne`, `specialty`, `birth_date`, `email`, `password`, `roles`, `classe_id`) VALUES
(1, 'Admin', 'Principal', 'A10000', 'admin', NULL, NULL, NULL, 'admin@emsi.ma', '$2y$13$20IEjY2svgrWP1UEuRE68OFIR0jbdbxTCCRcgEeX4KCQUot2zjIpO', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', NULL),
(2, 'Ahmed', 'Benani', 'AA101', 'teacher', NULL, 'IIR', NULL, 'ahmed.benani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(3, 'Sara', 'Idrissi', 'AA102', 'teacher', NULL, 'IIR', NULL, 'sara.idrissi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(4, 'Karim', 'Alami', 'AA103', 'teacher', NULL, 'IIR', NULL, 'karim.alami@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(5, 'Noura', 'Fassi', 'AA104', 'teacher', NULL, 'IIR', NULL, 'noura.fassi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(6, 'Youssef', 'Tazi', 'AA105', 'teacher', NULL, 'IIR', NULL, 'youssef.tazi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(7, 'Meryem', 'Berrada', 'AA106', 'teacher', NULL, 'IIR', NULL, 'meryem.berrada@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(8, 'Omar', 'Chraibi', 'AA107', 'teacher', NULL, 'IIR', NULL, 'omar.chraibi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(9, 'Fatima', 'Zahra', 'AA108', 'teacher', NULL, 'IIR', NULL, 'fatima.zahra@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(10, 'Khalid', 'Amrani', 'AA109', 'teacher', NULL, 'IIR', NULL, 'khalid.amrani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(11, 'Hassan', 'Ouazzani', 'BB101', 'teacher', NULL, 'GESI', NULL, 'hassan.ouazzani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(12, 'Layla', 'Mansouri', 'BB102', 'teacher', NULL, 'GESI', NULL, 'layla.mansouri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(13, 'Rachid', 'ElAmrani', 'BB103', 'teacher', NULL, 'GESI', NULL, 'rachid.elamrani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(14, 'Salma', 'Bennani', 'BB104', 'teacher', NULL, 'GESI', NULL, 'salma.bennani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(15, 'Mehdi', 'Jelloun', 'BB105', 'teacher', NULL, 'GESI', NULL, 'mehdi.jelloun@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(16, 'Sofia', 'Kabbaj', 'BB106', 'teacher', NULL, 'GESI', NULL, 'sofia.kabbaj@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(17, 'Driss', 'Filali', 'BB107', 'teacher', NULL, 'GESI', NULL, 'driss.filali@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(18, 'Amina', 'Chaoui', 'BB108', 'teacher', NULL, 'GESI', NULL, 'amina.chaoui@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(19, 'Tarik', 'Bouzidi', 'BB109', 'teacher', NULL, 'GESI', NULL, 'tarik.bouzidi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(20, 'Nabil', 'Daoudi', 'CC101', 'teacher', NULL, 'GC', NULL, 'nabil.daoudi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(21, 'Sanaa', 'Hamdi', 'CC102', 'teacher', NULL, 'GC', NULL, 'sanaa.hamdi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(22, 'Hicham', 'Mokhtar', 'CC103', 'teacher', NULL, 'GC', NULL, 'hicham.mokhtar@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(23, 'Kenza', 'Slaoui', 'CC104', 'teacher', NULL, 'GC', NULL, 'kenza.slaoui@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(24, 'Yassine', 'Rghioui', 'CC105', 'teacher', NULL, 'GC', NULL, 'yassine.rghioui@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(25, 'Imane', 'Tazi', 'CC106', 'teacher', NULL, 'GC', NULL, 'imane.tazi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(26, 'Anas', 'Bakkali', 'CC107', 'teacher', NULL, 'GC', NULL, 'anas.bakkali@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(27, 'Hinde', 'Laraki', 'CC108', 'teacher', NULL, 'GC', NULL, 'hinde.laraki@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(28, 'Reda', 'Kadiri', 'CC109', 'teacher', NULL, 'GC', NULL, 'reda.kadiri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_TEACHER\", \"ROLE_USER\"]', NULL),
(101, 'Amine', 'El Amrani', 'A101', 'student', 'CNE101', NULL, '2001-01-01', 'amine.elamrani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3001),
(102, 'Salma', 'Benkirane', 'A102', 'student', 'CNE102', NULL, '2001-01-02', 'salma.benkirane@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3001),
(103, 'Yassine', 'Tazi', 'A103', 'student', 'CNE103', NULL, '2001-01-03', 'yassine.tazi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3001),
(104, 'Hiba', 'Fassi', 'A104', 'student', 'CNE104', NULL, '2001-01-04', 'hiba.fassi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3011),
(105, 'Omar', 'Berrada', 'A105', 'student', 'CNE105', NULL, '2001-01-05', 'omar.berrada@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3011),
(106, 'Mehdi', 'Chraibi', 'B101', 'student', 'CNE106', NULL, '2001-01-01', 'mehdi.chraibi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3002),
(107, 'Noura', 'Alami', 'B102', 'student', 'CNE107', NULL, '2001-01-02', 'noura.alami@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3002),
(108, 'Karim', 'Idrissi', 'B103', 'student', 'CNE108', NULL, '2001-01-03', 'karim.idrissi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3002),
(109, 'Fatima', 'Mansouri', 'B104', 'student', 'CNE109', NULL, '2001-01-04', 'fatima.mansouri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3002),
(110, 'Youssef', 'Ouazzani', 'B105', 'student', 'CNE110', NULL, '2001-01-05', 'youssef.ouazzani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3002),
(111, 'Kenza', 'Tahiri', 'C101', 'student', 'CNE111', NULL, '2001-01-01', 'kenza.tahiri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3003),
(112, 'Anas', 'Bouzidi', 'C102', 'student', 'CNE112', NULL, '2001-01-02', 'anas.bouzidi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3003),
(113, 'Meryem', 'Filali', 'C103', 'student', 'CNE113', NULL, '2001-01-03', 'meryem.filali@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3003),
(114, 'Driss', 'Chaoui', 'C104', 'student', 'CNE114', NULL, '2001-01-04', 'driss.chaoui@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3003),
(115, 'Sofia', 'El Idrissi', 'C105', 'student', 'CNE115', NULL, '2001-01-05', 'sofia.elidrissi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3003),
(201, 'Rania', 'Hamdi', 'D101', 'student', 'CNE201', NULL, '2002-01-01', 'rania.hamdi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3004),
(202, 'Taha', 'Daoudi', 'D102', 'student', 'CNE202', NULL, '2002-01-02', 'taha.daoudi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3004),
(203, 'Ghita', 'Mokhtar', 'D103', 'student', 'CNE203', NULL, '2002-01-03', 'ghita.mokhtar@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3004),
(204, 'Reda', 'Rghioui', 'D104', 'student', 'CNE204', NULL, '2002-01-04', 'reda.rghioui@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3012),
(205, 'Oumaima', 'Laraki', 'D105', 'student', 'CNE205', NULL, '2002-01-05', 'oumaima.laraki@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3012),
(206, 'Hamza', 'Kadiri', 'E101', 'student', 'CNE206', NULL, '2002-01-01', 'hamza.kadiri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3005),
(207, 'Zineb', 'Bakkali', 'E102', 'student', 'CNE207', NULL, '2002-01-02', 'zineb.bakkali@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3005),
(208, 'Walid', 'Jelloun', 'E103', 'student', 'CNE208', NULL, '2002-01-03', 'walid.jelloun@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3005),
(209, 'Aya', 'Benjelloun', 'E104', 'student', 'CNE209', NULL, '2002-01-04', 'aya.benjelloun@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3005),
(210, 'Saad', 'Tahiri', 'E105', 'student', 'CNE210', NULL, '2002-01-05', 'saad.tahiri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3005),
(211, 'Imane', 'Ziani', 'F101', 'student', 'CNE211', NULL, '2002-01-01', 'imane.ziani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3006),
(212, 'Nizar', 'El Fassi', 'F102', 'student', 'CNE212', NULL, '2002-01-02', 'nizar.elfassi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3006),
(213, 'Lamia', 'Saber', 'F103', 'student', 'CNE213', NULL, '2002-01-03', 'lamia.saber@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3006),
(214, 'Hicham', 'Naciri', 'F104', 'student', 'CNE214', NULL, '2002-01-04', 'hicham.naciri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3006),
(215, 'Sanaa', 'Rais', 'F105', 'student', 'CNE215', NULL, '2002-01-05', 'sanaa.rais@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3006),
(301, 'Adam', 'Belkadi', 'G101', 'student', 'CNE301', NULL, '2003-01-01', 'adam.belkadi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3007),
(302, 'Lina', 'Akrouh', 'G102', 'student', 'CNE302', NULL, '2003-01-02', 'lina.akrouh@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3007),
(303, 'Mohamed', 'Amraoui', 'G103', 'student', 'CNE303', NULL, '2003-01-03', 'mohamed.amraoui@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3007),
(304, 'Kaoutar', 'Hilali', 'G104', 'student', 'CNE304', NULL, '2003-01-04', 'kaoutar.hilali@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3007),
(305, 'Ismail', 'Jazouli', 'G105', 'student', 'CNE305', NULL, '2003-01-05', 'ismail.jazouli@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3007),
(306, 'Marwa', 'Elkhalfi', 'H101', 'student', 'CNE306', NULL, '2003-01-01', 'marwa.elkhalfi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3008),
(307, 'Ayoub', 'Ennaji', 'H102', 'student', 'CNE307', NULL, '2003-01-02', 'ayoub.ennaji@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3008),
(308, 'Noha', 'Semlali', 'H103', 'student', 'CNE308', NULL, '2003-01-03', 'noha.semlali@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3008),
(309, 'Zakaria', 'Touhami', 'H104', 'student', 'CNE309', NULL, '2003-01-04', 'zakaria.touhami@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3008),
(310, 'Rim', 'Chafik', 'H105', 'student', 'CNE310', NULL, '2003-01-05', 'rim.chafik@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3008),
(311, 'Othmane', 'Wahbi', 'I101', 'student', 'CNE311', NULL, '2003-01-01', 'othmane.wahbi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3009),
(312, 'Manal', 'Saidi', 'I102', 'student', 'CNE312', NULL, '2003-01-02', 'manal.saidi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3009),
(313, 'Bilal', 'Rachidi', 'I103', 'student', 'CNE313', NULL, '2003-01-03', 'bilal.rachidi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3009),
(314, 'Hafsa', 'Mouradi', 'I104', 'student', 'CNE314', NULL, '2003-01-04', 'hafsa.mouradi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3009),
(315, 'Ilias', 'Zenati', 'I105', 'student', 'CNE315', NULL, '2003-01-05', 'ilias.zenati@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3009),
(316, 'Yasmine', 'Kettani', 'J101', 'student', 'CNE316', NULL, '2003-01-01', 'yasmine.kettani@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3010),
(317, 'Mouad', 'Hafidi', 'J102', 'student', 'CNE317', NULL, '2003-01-02', 'mouad.hafidi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3010),
(318, 'Salma', 'Benchekroun', 'J103', 'student', 'CNE318', NULL, '2003-01-03', 'salma.benchekroun@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3010),
(319, 'Riad', 'Essalmi', 'J104', 'student', 'CNE319', NULL, '2003-01-04', 'riad.essalmi@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3010),
(320, 'Chaimae', 'Amiri', 'J105', 'student', 'CNE320', NULL, '2003-01-05', 'chaimae.amiri@emsi.ma', '$2y$13$WfofJ9Qye0/mmwZPjQAFc.hHADPdSUl6rILDsK0DkvwAHMfF7aArm', '[\"ROLE_STUDENT\", \"ROLE_USER\"]', 3010);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8F87BF96D2EECC3F` (`school_year_id`),
  ADD KEY `IDX_8F87BF96180AA129` (`filiere_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DBDCD7E1CB944F1A` (`student_id`),
  ADD KEY `IDX_DBDCD7E19BAAC766` (`associated_class_id`);

--
-- Indexes for table `filiere`
--
ALTER TABLE `filiere`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_595AAE34CB944F1A` (`student_id`),
  ADD KEY `IDX_595AAE34AFC2B591` (`module_id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C242628180AA129` (`filiere_id`);

--
-- Indexes for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teaching_assignment`
--
ALTER TABLE `teaching_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_93BFC67E41807E1D` (`teacher_id`),
  ADD KEY `IDX_93BFC67E9BAAC766` (`associated_class_id`),
  ADD KEY `IDX_93BFC67EAFC2B591` (`module_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D6498F5EA509` (`classe_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classe`
--
ALTER TABLE `classe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3013;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `filiere`
--
ALTER TABLE `filiere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=318;

--
-- AUTO_INCREMENT for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teaching_assignment`
--
ALTER TABLE `teaching_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `FK_8F87BF96180AA129` FOREIGN KEY (`filiere_id`) REFERENCES `filiere` (`id`),
  ADD CONSTRAINT `FK_8F87BF96D2EECC3F` FOREIGN KEY (`school_year_id`) REFERENCES `school_year` (`id`);

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `FK_DBDCD7E19BAAC766` FOREIGN KEY (`associated_class_id`) REFERENCES `classe` (`id`),
  ADD CONSTRAINT `FK_DBDCD7E1CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `FK_595AAE34AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`),
  ADD CONSTRAINT `FK_595AAE34CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `FK_C242628180AA129` FOREIGN KEY (`filiere_id`) REFERENCES `filiere` (`id`);

--
-- Constraints for table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `teaching_assignment`
--
ALTER TABLE `teaching_assignment`
  ADD CONSTRAINT `FK_93BFC67E41807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_93BFC67E9BAAC766` FOREIGN KEY (`associated_class_id`) REFERENCES `classe` (`id`),
  ADD CONSTRAINT `FK_93BFC67EAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D6498F5EA509` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
