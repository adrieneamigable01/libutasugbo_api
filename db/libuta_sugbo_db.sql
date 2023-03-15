-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2023 at 07:33 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libuta_sugbo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `business`
--

CREATE TABLE `business` (
  `id` int(11) NOT NULL,
  `business_id` varchar(100) NOT NULL,
  `business_name` varchar(30) NOT NULL,
  `business_type` enum('Resorts','Bed & Breakfast',' Resto & Cafe','Rental Vehicles','Tourist spot') NOT NULL,
  `business_address` varchar(30) NOT NULL,
  `business_email` varchar(100) NOT NULL,
  `business_phone` varchar(30) NOT NULL,
  `business_image` text NOT NULL,
  `owner_id` varchar(100) NOT NULL,
  `status` enum('Approved','Pending','Disapproved') NOT NULL DEFAULT 'Pending',
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `business`
--

INSERT INTO `business` (`id`, `business_id`, `business_name`, `business_type`, `business_address`, `business_email`, `business_phone`, `business_image`, `owner_id`, `status`, `is_active`) VALUES
(1, '632d3230-3233-4033-b133-303832303331', 'DreyComoanyz', 'Resorts', 'Sibonga Cebu', 'adrienecarreamigable01@gmail.com', '09154366083', '', '752d3230-3233-4033-b133-303134323033', 'Pending', 0),
(2, '632d3230-3233-4033-b133-303832353436', 'DreyCompany', 'Resorts', 'Sibonga Cebu', 'adrienecarreamigable01@gmail.c', '09154366083', '', '752d3230-3233-4033-b133-303134323033', 'Approved', 1),
(3, '622d3230-3233-4033-b134-303633343332', 'DreyCompanyx', 'Resorts', 'Sibonga Cebu', 'adrienecarreamigable01@gmail.com', '09154366083', '', '752d3230-3233-4033-b133-303134323033', 'Pending', 1),
(4, '622d3230-3233-4033-b134-303633343437', 'DreyCompanyxx', 'Resorts', 'Sibonga Cebu', 'adrienecarreamigable01@gmail.com', '09154366083', '', '752d3230-3233-4033-b133-303134323033', 'Pending', 1);

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `id` int(11) NOT NULL,
  `listing_id` varchar(100) NOT NULL,
  `business_id` varchar(100) NOT NULL,
  `listing_name` varchar(100) NOT NULL,
  `listing_type` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `accomodates` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL DEFAULT 0,
  `bedrooms` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `amenities` text NOT NULL,
  `recommendations` text NOT NULL,
  `rules` text NOT NULL,
  `directions` text NOT NULL,
  `status` enum('Approved','Pending','Disapproved') NOT NULL DEFAULT 'Pending',
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `is_active` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`id`, `listing_id`, `business_id`, `listing_name`, `listing_type`, `size`, `price`, `accomodates`, `bathrooms`, `bedrooms`, `description`, `amenities`, `recommendations`, `rules`, `directions`, `status`, `date_added`, `is_active`) VALUES
(1, '6c2d3230-3233-4033-b135-303332393336', '632d3230-3233-4033-b133-303832303331', 'Sample Listing 0', 'Resorts', '150', 1500, 4, 0, 0, 'Sample', 'Sample', 'sample recomendation', 'No food', 'near shelomet', 'Approved', '2023-03-15 11:36:03', 0),
(2, '6c2d3230-3233-4033-b135-303433313437', '632d3230-3233-4033-b133-303832303331', 'Sample Listing 1', 'Resorts', '150', 1500, 4, 0, 0, 'Sample', 'Sample', 'sample recomendation', 'No food', 'near shelomet', 'Pending', '2023-03-15 04:31:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `listing_address`
--

CREATE TABLE `listing_address` (
  `id` int(11) NOT NULL,
  `listing_id` varchar(100) NOT NULL,
  `listing_address_id` varchar(100) NOT NULL,
  `address_line1` varchar(200) NOT NULL,
  `address_line2` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `map_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `listing_address`
--

INSERT INTO `listing_address` (`id`, `listing_id`, `listing_address_id`, `address_line1`, `address_line2`, `country`, `province`, `city`, `zip_code`, `map_location`) VALUES
(1, '6c2d3230-3233-4033-b135-303332393336', '6c613230-3233-4033-b135-303332393336', 'hermosisima st', 'poblacion,barilir cebu', 'philippines', 'barili', 'cebu', '6038', ''),
(2, '6c2d3230-3233-4033-b135-303433313437', '6c613230-3233-4033-b135-303433313437', 'hermosisima st', 'poblacion,barilir cebu', 'philippines', 'barili', 'cebu', '6038', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(250) NOT NULL,
  `level` int(11) NOT NULL,
  `user_type` enum('Admin','Staff') NOT NULL,
  `role` enum('Store Owner','Manager','Staff','Traveler') NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `username`, `password`, `level`, `user_type`, `role`, `is_active`) VALUES
(1, '752d3230-3233-4033-b133-303133373035', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, 'Admin', 'Store Owner', 1),
(2, '752d3230-3233-4033-b133-303134323033', 'admin1', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, 'Admin', 'Store Owner', 1),
(3, '752d3230-3233-4033-b133-303134323239', 'admin2', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, 'Admin', 'Store Owner', 1),
(4, '752d3230-3233-4033-b133-303135343235', 'admin3', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, 'Admin', 'Store Owner', 1),
(5, '752d3230-3233-4033-b133-303231363533', 'manager', '1a8565a9dc72048ba03b4156be3e569f22771f23', 0, 'Staff', 'Staff', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `user_info_id` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `middlename` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`user_info_id`, `user_id`, `lastname`, `middlename`, `firstname`, `mobile`, `email`) VALUES
(1, '752d3230-3233-4033-b133-303133373035', 'Staff', 'Staff', 'Staff', '09154366083', 'adrienecarreamigable01@gmail.c'),
(2, '752d3230-3233-4033-b133-303134323033', 'Staff', 'Staff', 'Staff', '09154366083', 'adrienecarreamigable01@gmail.c'),
(3, '752d3230-3233-4033-b133-303134323239', 'Staff', 'Staff', 'Staff', '09154366083', 'adrienecarreamigable01@gmail.c'),
(4, '752d3230-3233-4033-b133-303135343235', 'Staff', 'Staff', 'Staff', '09154366083', 'adrienecarreamigable01@gmail.c'),
(5, '752d3230-3233-4033-b133-303231363533', 'Staff', 'Staff', 'Staff', '09154366083', 'adrienecarreamigable01@gmail.c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `business`
--
ALTER TABLE `business`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listing_address`
--
ALTER TABLE `listing_address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `listing_id` (`listing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`user_info_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `business`
--
ALTER TABLE `business`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `listing_address`
--
ALTER TABLE `listing_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `user_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
