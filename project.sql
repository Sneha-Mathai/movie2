-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2024 at 01:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `release_date` date NOT NULL,
  `synopsis` text NOT NULL,
  `poster_url` varchar(500) NOT NULL,
  `view_count` int(11) NOT NULL,
  `vdo_link` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `genre`, `release_date`, `synopsis`, `poster_url`, `view_count`, `vdo_link`) VALUES
(1, 'avatar', 'sci-fi', '2024-08-07', 'its wonderful', 'uploads/Avatar_(2009_film)_poster.jpg', 0, ''),
(2, 'interstellar', 'sci-fi', '2018-06-14', 'In Earth\'s future, a global crop blight and second Dust Bowl are slowly rendering the planet uninhabitable. Professor Brand (Michael Caine), a brilliant NASA physicist, is working on plans to save mankind by transporting Earth\'s population to a new home via a wormhole. But first, Brand must send former NASA pilot Cooper (Matthew McConaughey) and a team of researchers through the wormhole and across the galaxy to find out which of three planets could be mankind\'s new home.', 'uploads/Interstellar_film_poster.jpg', 0, ''),
(3, 'Tamasha', 'Drama', '2017-10-15', 'Tamasha is about the journey of someone who has lost his edge in trying to follow acceptable conventions of society. The film is based on the central theme of abrasion and loss of self that happens in an attempt to find oneself', 'uploads/Tamasha_(film_poster).jpg', 0, ''),
(4, 'Before sunrise', 'Drama', '2017-10-15', 'A young man and woman meet on a train in Europe, and wind up spending one evening together in Vienna. Unfortunately, both know that this will probably be their only night together.', 'uploads/before sunrise.jpg', 0, ''),
(5, 'Premam', 'Romance/Comedy', '2024-02-16', 'While George\'s first love turns out to be a disappointment, Malar, a college lecturer, rekindles his love interest. His romantic journey takes him through several stages, helping him find his purpose.', 'uploads/images.jfif', 0, ''),
(6, 'Inception', 'Sci-Fi', '2010-07-16', 'A skilled thief who steals corporate secrets through dream-sharing technology is given a chance to have his past erased.', 'uploads/71DwIcSgFcS.jpg', 0, ''),
(7, 'The Shawshank Redemption', 'Drama', '1994-09-22', 'Two imprisoned men bond over several years, finding solace and eventual redemption through acts of common decency.', 'uploads/6e3e579706908883944a6a0711295c8ef16fa7c9122e48d076a465e1464952bc._SX1080_FMjpg_.jpg', 0, ''),
(8, 'The Dark Knight', 'Action', '2008-07-18', 'When the menace known as the Joker wreaks havoc on Gotham, Batman must accept one of the greatest psychological tests.', 'uploads/The_Dark_Knight_(2008_film).jpg', 0, ''),
(9, 'Forrest Gump', 'Drama', '1994-07-06', 'The presidencies of Kennedy and Johnson, the events of Vietnam, Watergate, and other historical events unfold from the perspective of an Alabama man.', '', 0, ''),
(10, 'The Matrix', 'Sci-Fi', '1999-03-31', 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', '', 0, ''),
(11, 'Gladiator', 'Action', '2000-05-05', 'A former Roman General sets out to exact vengeance against the corrupt emperor who murdered his family and sent him into slavery.', '', 0, ''),
(12, 'Titanic', 'Romance', '1997-12-19', 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.', '', 0, ''),
(13, 'The Godfather', 'Crime', '1972-03-24', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', '', 0, ''),
(14, 'Pulp Fiction', 'Crime', '1994-10-14', 'The lives of two mob hitmen, a boxer, a gangster\'s wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', '', 0, ''),
(15, 'Schindler\'s List', 'Biography', '1993-12-15', 'In German-occupied Poland during World War II, Oskar Schindler gradually becomes concerned for his Jewish workforce.', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text NOT NULL,
  `like_dislike` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `image_url` varchar(200) NOT NULL,
  `gender` enum('Male','Female','other','') NOT NULL,
  `role` enum('Admin','User') NOT NULL DEFAULT 'User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `og_pass` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `dob`, `age`, `image_url`, `gender`, `role`, `created_at`, `og_pass`) VALUES
(1, 'Sneha', '$2y$10$6ZLRdnng0tCElzXT7Nm0tuUinyGJCWTFjmtCqcMqrKhmehEPjiGp2', 'snehamathai17@gmail.com', 787393723, '2024-07-31', 7, '', 'Female', 'User', '2024-08-14 03:13:52', ''),
(8, 'Admin', '$2y$10$mrK6s0Y3PqpMEEYUzVk9K.ylTh8K5UfbTKoCwVnKPjBRJHD6kVU8i', 'admin@gmail.com', 0, '0000-00-00', 0, '', '', 'Admin', '2024-08-14 03:38:29', ''),
(9, 'rinu', '$2y$10$DIYHIeeQcFd0VjF1ari0HOVZzFVugyxmAY6XorSDhbcV3YBeSONt6', 'rinu@gmail.com', 8948398, '2024-07-31', 88, '', 'Male', 'User', '2024-08-14 03:57:22', 'rinu'),
(10, 'vinayak', '$2y$10$qHGDzW5.BMKQmanbnqs1VOB2NRJI2ivP2u1rTxEt5bCYw9cgn2IIq', 'vina@gmail.com', 7879238928, '2024-07-29', 3, 'uploads/Screenshot (1).png', 'Male', 'User', '2024-08-14 04:30:17', 'vina'),
(12, 'sona', '$2y$10$6cVxqkj6PXbO6DSreAYiMOmY5b1FhONTD078rLGoFgzeVToWRNoFe', 'sona@gmail.com', 7879238928, '2024-07-01', 3, 'uploads/Screenshot (1).png', 'Male', 'User', '2024-08-14 04:41:06', 'sona'),
(13, 'sona1', '$2y$10$g2A9J4DeRN3sYsHuCc67pOaVwBVse1u3jeGCzldcrrAmfyZOe8Jhy', 'sona1@gmail.com', 7879238928, '2024-07-01', 3, 'uploads/Screenshot (1).png', 'Male', 'User', '2024-08-14 04:43:11', '111'),
(14, 'love', 'l@gmail.com', '$2y$10$R5qVgE6oF.29EKkJX3Tjp.PQ33yGJ7FbsE0n.ZRUkbb5evzI9MatW', 979278642, '2024-07-31', 2, 'uploads/pngtree-man-avatar-image-for-profile-png-image_13001882.png', 'Male', 'User', '2024-08-14 06:56:07', 'love');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK1` (`user_id`),
  ADD KEY `FK2` (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `FK1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK2` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
