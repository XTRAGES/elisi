-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2025 at 10:13 PM
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
-- Database: `elisi`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `firstlastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `checkin` datetime NOT NULL,
  `checkout` datetime NOT NULL,
  `adults` varchar(255) NOT NULL,
  `kids` varchar(255) NOT NULL,
  `rooms` varchar(255) NOT NULL,
  `specialrequests` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `firstlastname`, `email`, `checkin`, `checkout`, `adults`, `kids`, `rooms`, `specialrequests`) VALUES
(1, 'Aldin Zendeli', 'aldinzendeli@gmail.com', '2025-04-07 02:38:00', '2025-04-16 18:42:00', '3', '4', '3', 'I want to have a balcony with a jacuzzi');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `firstlastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `firstlastname`, `email`, `subject`, `message`) VALUES
(3, 'Aldin Zendeli', 'aldinzendeli@gmail.com', 'Do rooms have AC?', 'I was interested if the rooms have AC.');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `bed` varchar(255) NOT NULL,
  `bath` varchar(255) NOT NULL,
  `wifi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `price`, `description`, `image`, `bed`, `bath`, `wifi`) VALUES
(32, 'Junior Suite', '100', 'The Junior Suite offers warmth, elegance, a plush bed, and garden views.', 'room-1.jpg', '1', '1', 'Yes'),
(33, 'Executive Suite', '200', 'The Executive Suite offers modern luxury space, a king bed, and elegant décor.', 'room-2.jpg', '2', '2', 'Yes'),
(34, 'Super Suite', '300', 'The Super Suite radiates elegance and comfort. Comes with a plush king bed, and stylish décor.', 'room-3.jpg', '3', '3', 'Yes'),
(38, 'Royal Suite', '400', 'The Royal Suite comes with elegant blue décor, and modern furnishings.', 'blog3.jpg', '4', '4', 'Yes'),
(39, 'Emerald Suite', '500', 'The Emerald Suite comes with plush bedding, and sleek décor.', 'room4.jpg', '5', '5', 'Yes'),
(43, 'Elite Suite', '600', 'The Elite Suite comes with sleek furnishings, striking design, and high-end comfort.', 'room3.jpg', '6', '6', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `job`, `image`) VALUES
(10, 'Aldin Zendeli', 'Owner', 'generalmanager.jpg'),
(11, 'Ryan Carter', 'Fitness Instructor', 'fitnessinstructor.jpg'),
(12, 'James Parker', 'Desk Supervisor', 'fdsupervisor.jpg'),
(13, 'Lucas Davis', 'Event Coordinator', 'coordinator.jpg'),
(14, 'Gordon Ramsay', 'Executive Chef', 'GordonRamsay.jpg'),
(15, 'Ethan Reynolds', 'Housekeeping Manager', 'man.jpg'),
(16, 'Lucas Bennett', 'Concierge', 'concierge.jpg'),
(17, 'Daniel Foster', 'Maintenance Supervisor', 'msupervisor.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `emri` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirm_password` varchar(255) NOT NULL,
  `is_admin` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `emri`, `username`, `email`, `password`, `confirm_password`, `is_admin`) VALUES
(1, 'Aldin', 'aldin', 'aldin@gmail.com', '$2y$10$qvtuf4l5gouc9enrc.NI4eqwxAE8pvhHyqGvoOjP7vwN1b0Lt9U32', '$2y$10$.EmOzcIO6TqimPlYiAr2e.owbTf6oqYCwOXeZGZ6BAdP25XSpBR0C', 'true');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
