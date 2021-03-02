-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 01, 2021 at 07:11 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `at203_poo`
--

-- --------------------------------------------------------

--
-- Table structure for table `personnages`
--

CREATE TABLE `personnages` (
  `id` smallint(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `atk` smallint(3) NOT NULL DEFAULT '5',
  `pv` smallint(3) NOT NULL DEFAULT '200'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personnages`
--

INSERT INTO `personnages` (`id`, `name`, `atk`, `pv`) VALUES
(30, 'Kuiil', 10, 200),
(26, 'Mando', 30, 200),
(27, 'Cara Dune', 20, 200),
(28, 'Bo-Katan Kryze', 25, 200),
(29, 'Moff Gideon', 30, 200),
(31, 'IG-11', 25, 200),
(32, 'Mythrol', 10, 200),
(33, 'Greef Karga', 15, 200),
(34, 'Dr Pershing', 10, 200),
(35, 'Le Client', 10, 200),
(36, 'Dark Trooper', 30, 200),
(37, 'Xi An', 25, 200);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `personnages`
--
ALTER TABLE `personnages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personnages`
--
ALTER TABLE `personnages`
  MODIFY `id` smallint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
