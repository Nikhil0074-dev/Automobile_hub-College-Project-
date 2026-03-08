-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 05:58 PM
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
-- Database: `automobile_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `designation` enum('owner','manager','mechanic') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `designation`, `email`, `phone_number`, `address`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'owner', 'abc@gmail.com', '9087654321', 'not available', '123456', '2025-03-18 16:00:51', '2025-03-18 16:00:51'),
(2, 'lovekesh patil', 'manager', 'abc1@gmail.com', '1234567890', 'kmfkl', '54321', '2025-03-20 07:15:10', '2025-03-20 07:15:10'),
(3, 'Pratik', 'owner', 'xyz@gmail.com', '1234567890', 'kmfkl', 'pratik123', '2025-03-20 08:07:20', '2025-03-20 08:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` enum('booked','completed','canceled') DEFAULT 'booked',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `customer_id`, `admin_id`, `service_id`, `appointment_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-03-21 12:38:00', 'completed', '2025-03-20 06:07:31', '2025-03-24 06:55:12'),
(2, 1, 1, 7, '2025-03-21 11:53:00', 'canceled', '2025-03-20 06:21:33', '2025-03-20 06:21:50'),
(3, 1, 0, 1, '2025-03-20 15:41:00', 'booked', '2025-03-20 07:11:27', '2025-03-20 07:11:27'),
(4, 1, 2, 3, '2025-03-20 13:42:00', 'canceled', '2025-03-20 07:12:15', '2025-03-20 07:16:22'),
(5, 1, 0, 1, '2025-03-20 15:33:00', 'booked', '2025-03-20 08:03:40', '2025-03-20 08:03:40'),
(6, 1, 1, 1, '2025-03-22 13:43:00', 'completed', '2025-03-20 08:11:14', '2025-03-24 06:55:08');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `adhar` varchar(255) NOT NULL,
  `pan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `email`, `phone_number`, `address`, `password`, `adhar`, `pan`, `created_at`, `updated_at`) VALUES
(1, 'Pratik Suryawanshi', 'abc@gmail.com', '9087654322', 'malegaon', 'pratik123', '\'Adhar card\'', '\'Pan Card\'', '2025-03-18 16:04:39', '2025-03-18 16:11:17'),
(8, 'Nikhil Nikam', 'abc1@gmail.com', '1234567890', 'kk', '123', '\'Adhar card\'', '\'Pan Card\'', '2025-03-20 07:20:53', '2025-03-20 07:20:53'),
(10, 'Nikhil Nikam', 'nik@gmail.com', '1234567890', 'kk', '12345', '', '\'Pan Card\'', '2025-03-20 15:06:11', '2025-03-24 06:53:15'),
(13, 'yash', 'yashwani362004@gmail.com', '1234567890', 'kk', '123', 'uploads/index.pdf', 'uploads/Project.pdf', '2025-03-21 13:55:30', '2025-03-21 13:55:30'),
(14, 'raj', 'raj@gmail.com', '1234567890', 'kk', '12345', 'uploads/index.pdf', 'uploads/resume.pdf', '2025-03-21 14:32:24', '2025-03-21 14:32:24');

-- --------------------------------------------------------

--
-- Table structure for table `insurance`
--

CREATE TABLE `insurance` (
  `insurance_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL DEFAULT 'insurance',
  `insurance_company` varchar(100) NOT NULL,
  `insurance_type` enum('Comprehensive','Third Party','Own Damage','') NOT NULL DEFAULT 'Comprehensive',
  `premium` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurance`
--

INSERT INTO `insurance` (`insurance_id`, `image_url`, `insurance_company`, `insurance_type`, `premium`, `created_at`, `updated_at`, `admin_id`) VALUES
(1, 'sbi.jpeg', 'Sbi Insurance', 'Comprehensive', 10000.00, '2025-02-19 07:36:15', '2025-03-24 06:16:59', 1),
(2, 'hdfc.png', 'Hdfc', 'Third Party', 5000.00, '2025-02-19 07:36:15', '2025-03-20 07:04:31', 0),
(3, 'tata.png', 'Tata Insurance', 'Comprehensive', 100.00, '2025-02-19 07:40:58', '2025-03-20 07:05:48', 0),
(4, 'icici.png', 'ICICI Insurance', 'Third Party', 500.00, '2025-02-19 07:40:58', '2025-03-20 07:07:07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `insurancesale`
--

CREATE TABLE `insurancesale` (
  `sale_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `insurance_id` int(11) NOT NULL,
  `vehicle_no` varchar(50) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('in process','insured','','') DEFAULT 'in process',
  `payment_method` enum('upi','card') NOT NULL DEFAULT 'upi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurancesale`
--

INSERT INTO `insurancesale` (`sale_id`, `customer_id`, `admin_id`, `insurance_id`, `vehicle_no`, `sale_price`, `sale_date`, `status`, `payment_method`) VALUES
(1, 1, 1, 2, 'MH19Y6012', 5000.00, '2025-03-18 17:51:31', 'in process', 'card'),
(2, 1, 1, 1, 'MH19Y6017', 10000.00, '2025-03-21 16:24:27', 'insured', 'card');

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `part_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT 2,
  `image_url` varchar(255) NOT NULL DEFAULT 'part',
  `part_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `compatibility` varchar(255) NOT NULL DEFAULT 'All vehicle',
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`part_id`, `admin_id`, `image_url`, `part_type`, `price`, `compatibility`, `stock`, `created_at`, `updated_at`) VALUES
(1, 1, 'oil.jpeg', 'Oil ', 1500.00, 'Toyota Camry 2020', 8, '2025-02-16 09:58:44', '2025-03-20 07:24:04'),
(7, 2, 'gearbox.jpeg', 'Gear box', 100.00, 'all vehicles', 10, '2025-03-20 06:52:34', '2025-03-20 08:15:47'),
(8, 2, 'clutch plate.jpeg', 'Cluth Plate', 300.00, 'All Vehicle', 5, '2025-03-20 06:56:27', '2025-03-20 07:16:02');

-- --------------------------------------------------------

--
-- Table structure for table `partsales`
--

CREATE TABLE `partsales` (
  `sale_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('upi','card') NOT NULL DEFAULT 'upi',
  `delivery` enum('delivered','not_delivered','','') NOT NULL DEFAULT 'not_delivered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partsales`
--

INSERT INTO `partsales` (`sale_id`, `customer_id`, `admin_id`, `part_id`, `sale_price`, `sale_date`, `payment_method`, `delivery`) VALUES
(1, 1, 1, 1, 1500.00, '2025-03-18 17:12:38', 'card', 'not_delivered'),
(2, 1, 1, 1, 1500.00, '2025-03-20 07:24:04', 'card', 'delivered');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `service_type` enum('repair','ev','maintainance','customization') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `admin_id`, `service_name`, `price`, `service_type`, `created_at`, `updated_at`) VALUES
(1, 1, 'Oil Change', 50.00, 'repair', '2025-02-17 11:16:06', '2025-02-20 09:48:07'),
(2, 0, 'Interior', 100.00, 'customization', '2025-02-17 13:50:17', '2025-02-23 07:39:30'),
(3, 1, 'Silver Package', 50.00, 'maintainance', '2025-02-17 14:04:10', '2025-02-23 07:32:46'),
(4, 2, 'Platinum Package', 120.00, 'maintainance', '2025-02-23 07:32:03', '2025-03-20 07:25:23'),
(5, 2, 'Gold Package', 100.00, 'maintainance', '2025-02-23 07:32:03', '2025-03-20 07:25:14'),
(6, 1, 'EV Charging', 100.00, 'ev', '2025-02-23 07:35:43', '2025-02-23 07:36:20'),
(7, 1, 'engine repair', 100.00, 'repair', '2025-02-24 05:59:36', '2025-02-24 05:59:58'),
(8, 1, 'Wheel Alignment', 300.00, 'repair', '2025-03-24 06:29:15', '2025-03-24 06:29:15'),
(9, 1, 'wheel balancing', 300.00, 'repair', '2025-03-24 06:29:15', '2025-03-24 06:29:15'),
(10, 1, 'Battery check/change', 300.00, 'ev', '2025-03-24 06:36:00', '2025-03-24 06:36:00'),
(11, 1, 'Fluid refill', 300.00, 'ev', '2025-03-24 06:36:00', '2025-03-24 06:36:00'),
(12, 1, 'Software Update', 300.00, 'ev', '2025-03-24 06:36:37', '2025-03-24 06:36:37'),
(13, 1, 'Engine Tune up', 5000.00, 'customization', '2025-03-24 06:37:59', '2025-03-24 06:37:59'),
(14, 1, 'Exterior Upgrade', 1000.00, 'customization', '2025-03-24 06:37:59', '2025-03-24 06:37:59'),
(15, 1, 'PPF', 5000.00, 'customization', '2025-03-24 06:38:36', '2025-03-24 06:38:36'),
(16, 1, 'Paint', 1000.00, 'customization', '2025-03-24 06:38:36', '2025-03-24 06:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`id`, `file_name`, `file_path`, `uploaded_at`) VALUES
(1, 'index.pdf', 'uploads/index.pdf', '2025-03-21 07:44:16'),
(2, 'resume.pdf', 'uploads/resume.pdf', '2025-03-21 07:44:50'),
(3, 'class', 'uploads/class', '2025-03-21 07:45:21');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `mileage` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `transmission` enum('automatic','manual') NOT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') NOT NULL,
  `status` enum('available','sold') DEFAULT 'available',
  `type` enum('featured','vehicle') DEFAULT 'vehicle',
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `admin_id`, `make`, `model`, `price`, `image_url`, `mileage`, `color`, `transmission`, `fuel_type`, `status`, `type`, `stock`, `created_at`, `updated_at`) VALUES
(1, 3, 'Toyota', 'Camry', 24000000.00, 'camri.jpeg', 15000, 'Blue', 'automatic', 'petrol', 'available', 'vehicle', 4, '2025-02-16 09:52:27', '2025-03-20 09:47:04'),
(2, 3, 'Honda', 'Civic', 22000000.00, 'civic.jpeg', 20000, 'Red', 'manual', 'petrol', 'available', 'featured', 4, '2025-02-16 09:52:27', '2025-03-20 08:09:35'),
(3, 3, 'Ford', 'F-150', 3000000.00, 'ford.jpeg', 5000, 'Black', 'automatic', 'diesel', 'available', 'featured', 8, '2025-02-16 09:52:27', '2025-03-20 08:09:49'),
(4, 0, 'Tata', 'Safari', 1200000.00, 'safari.jpeg', 13, '', 'automatic', 'petrol', 'available', 'featured', 7, '2025-02-16 13:33:56', '2025-03-24 07:23:38'),
(6, 3, 'Honda', 'city', 99999999.99, 'city.jpeg', 15, 'black', 'automatic', 'petrol', 'available', 'vehicle', 9, '2025-03-18 07:26:14', '2025-03-20 08:08:49'),
(7, 2, 'Honda', 'Safari', 4687879.00, 'camri.jpeg', 0, '', 'automatic', 'petrol', 'available', 'vehicle', 10, '2025-03-20 06:19:47', '2025-03-20 07:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `vehiclesales`
--

CREATE TABLE `vehiclesales` (
  `sale_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('upi','card') NOT NULL DEFAULT 'upi',
  `delivery` enum('delivered','not_delivered','','') NOT NULL DEFAULT 'not_delivered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehiclesales`
--

INSERT INTO `vehiclesales` (`sale_id`, `customer_id`, `admin_id`, `vehicle_id`, `sale_price`, `sale_date`, `payment_method`, `delivery`) VALUES
(1, 1, 2, 1, 24000.00, '2025-03-18 16:11:34', 'card', 'delivered'),
(2, 1, 2, 1, 24000.00, '2025-03-20 06:18:18', '', 'delivered'),
(3, 1, 0, 4, 1200000.00, '2025-03-20 07:10:24', 'card', 'not_delivered'),
(4, 8, 1, 2, 22000.00, '2025-03-20 07:22:10', 'card', 'delivered'),
(5, 1, 0, 1, 24000.00, '2025-03-20 07:23:48', '', 'not_delivered'),
(6, 1, 0, 6, 10000.00, '2025-03-20 08:02:48', 'card', 'not_delivered'),
(7, 1, 0, 1, 24000000.00, '2025-03-20 08:10:55', 'card', 'not_delivered'),
(8, 1, 0, 1, 24000000.00, '2025-03-20 09:33:17', 'card', 'not_delivered'),
(9, 1, 0, 1, 24000000.00, '2025-03-20 09:47:04', 'card', 'not_delivered'),
(10, 1, 0, 4, 1200000.00, '2025-03-24 07:23:38', 'card', 'not_delivered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `insurance`
--
ALTER TABLE `insurance`
  ADD PRIMARY KEY (`insurance_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `insurancesale`
--
ALTER TABLE `insurancesale`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `insurance_id` (`insurance_id`),
  ADD KEY `admin_id` (`admin_id`) USING BTREE;

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`part_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `partsales`
--
ALTER TABLE `partsales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `vehiclesales`
--
ALTER TABLE `vehiclesales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `insurance`
--
ALTER TABLE `insurance`
  MODIFY `insurance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `insurancesale`
--
ALTER TABLE `insurancesale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `partsales`
--
ALTER TABLE `partsales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vehiclesales`
--
ALTER TABLE `vehiclesales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`);

--
-- Constraints for table `insurancesale`
--
ALTER TABLE `insurancesale`
  ADD CONSTRAINT `insurancesale_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `insurancesale_ibfk_2` FOREIGN KEY (`insurance_id`) REFERENCES `insurance` (`insurance_id`);

--
-- Constraints for table `partsales`
--
ALTER TABLE `partsales`
  ADD CONSTRAINT `partsales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `partsales_ibfk_2` FOREIGN KEY (`part_id`) REFERENCES `parts` (`part_id`);

--
-- Constraints for table `vehiclesales`
--
ALTER TABLE `vehiclesales`
  ADD CONSTRAINT `vehiclesales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `vehiclesales_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
