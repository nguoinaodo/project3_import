-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2017 at 06:59 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `co_authors_network_management_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `given_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `university_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_paper`
--

CREATE TABLE `author_paper` (
  `author_id` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paper_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `author_subject`
--

CREATE TABLE `author_subject` (
  `author_id` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(10) UNSIGNED NOT NULL,
  `co_author_id` int(10) UNSIGNED NOT NULL,
  `no_of_mutual_authors` smallint(5) UNSIGNED DEFAULT NULL,
  `no_of_joint_papers` smallint(5) UNSIGNED DEFAULT NULL,
  `no_of_joint_subjects` smallint(5) UNSIGNED DEFAULT NULL,
  `no_of_joint_keywords` smallint(5) UNSIGNED DEFAULT NULL,
  `score_1` double(8,2) DEFAULT NULL,
  `score_2` double(8,2) DEFAULT NULL,
  `score_3` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `co_authors`
--

CREATE TABLE `co_authors` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_author_id` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `second_author_id` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `co_author_paper`
--

CREATE TABLE `co_author_paper` (
  `co_author_id` int(10) UNSIGNED NOT NULL,
  `paper_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `id` int(10) UNSIGNED NOT NULL,
  `content` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyword_paper`
--

CREATE TABLE `keyword_paper` (
  `keyword_id` int(10) UNSIGNED NOT NULL,
  `paper_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(18, '2014_10_12_000000_create_users_table', 1),
(19, '2014_10_12_100000_create_password_resets_table', 1),
(20, '2017_10_08_130503_change_author_table_to_authors', 1),
(21, '2017_10_08_135327_change_paper_table_to_papers', 1),
(22, '2017_10_08_143155_create_countries_table', 1),
(23, '2017_10_08_143225_create_cities_table', 1),
(24, '2017_10_08_144458_create_universities_table', 1),
(25, '2017_10_08_144460_create_authors_table', 1),
(26, '2017_10_08_144461_create_subjects_table', 1),
(27, '2017_10_08_144462_create_author_subject_table', 1),
(28, '2017_10_08_144470_create_papers_table', 1),
(29, '2017_10_08_144480_create_author_paper_table', 1),
(30, '2017_10_08_144529_create_keywords_table', 1),
(31, '2017_10_08_144641_create_keyword_paper_table', 1),
(32, '2017_10_08_145018_create_co_authors_table', 1),
(33, '2017_10_08_145219_create_co_author_paper_table', 1),
(34, '2017_10_08_154934_create_candidates_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_date` datetime DEFAULT NULL,
  `abstract` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci DEFAULT 'Other',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `gender`, `phone`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$dHH0uGltEHR1.tDo7XLfn.GvhA1IZB92zHxrF3J6ii6/W6KQYuKkK', 'Male', NULL, '3yiqdU1f1QrsNzNrB695YCuAuPlMXMIzG9c6XJXuiae4QIdYegGVwzYpK0tr', '2017-10-16 16:54:19', '2017-10-16 16:54:19'),
(2, 'Trang Ha Viet', 'tranghv@example.com', '$2y$10$DsWoc5aXBmNkBSjfvRcxD.j5Tk5SRtsCRrPFWMa5Lkoxk.5Hxx2z2', 'Male', NULL, 'PdjyP5vqA9WhbpmyrYnO5TFJG7c4gFqgobYsBjMho2hBIUsUunhN3ebwIyDG', '2017-10-16 16:54:20', '2017-10-16 16:54:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `authors_university_id_foreign` (`university_id`);

--
-- Indexes for table `author_paper`
--
ALTER TABLE `author_paper`
  ADD PRIMARY KEY (`author_id`,`paper_id`),
  ADD KEY `author_paper_paper_id_foreign` (`paper_id`);

--
-- Indexes for table `author_subject`
--
ALTER TABLE `author_subject`
  ADD PRIMARY KEY (`author_id`,`subject_id`),
  ADD KEY `author_subject_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidates_co_author_id_foreign` (`co_author_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_country_id_foreign` (`country_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `co_authors`
--
ALTER TABLE `co_authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `co_authors_first_author_id_second_author_id_unique` (`first_author_id`,`second_author_id`),
  ADD KEY `co_authors_first_author_id_second_author_id_index` (`first_author_id`,`second_author_id`);

--
-- Indexes for table `co_author_paper`
--
ALTER TABLE `co_author_paper`
  ADD PRIMARY KEY (`co_author_id`,`paper_id`),
  ADD KEY `co_author_paper_paper_id_foreign` (`paper_id`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyword_paper`
--
ALTER TABLE `keyword_paper`
  ADD PRIMARY KEY (`keyword_id`,`paper_id`),
  ADD KEY `keyword_paper_paper_id_foreign` (`paper_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `universities_city_id_foreign` (`city_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `co_authors`
--
ALTER TABLE `co_authors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `authors`
--
ALTER TABLE `authors`
  ADD CONSTRAINT `authors_university_id_foreign` FOREIGN KEY (`university_id`) REFERENCES `universities` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `author_paper`
--
ALTER TABLE `author_paper`
  ADD CONSTRAINT `author_paper_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `author_paper_paper_id_foreign` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `author_subject`
--
ALTER TABLE `author_subject`
  ADD CONSTRAINT `author_subject_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `author_subject_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_co_author_id_foreign` FOREIGN KEY (`co_author_id`) REFERENCES `co_authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `co_author_paper`
--
ALTER TABLE `co_author_paper`
  ADD CONSTRAINT `co_author_paper_co_author_id_foreign` FOREIGN KEY (`co_author_id`) REFERENCES `co_authors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `co_author_paper_paper_id_foreign` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keyword_paper`
--
ALTER TABLE `keyword_paper`
  ADD CONSTRAINT `keyword_paper_keyword_id_foreign` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keyword_paper_paper_id_foreign` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `universities`
--
ALTER TABLE `universities`
  ADD CONSTRAINT `universities_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- 16/10/2017
ALTER TABLE `author_subject`
  ADD PRIMARY KEY (author_id, subject_id);
ALTER TABLE `co_author_paper`
  ADD PRIMARY KEY (co_author_id, paper_id);
ALTER TABLE `keyword_paper`
  ADD PRIMARY KEY (keyword_id, paper_id);    

ALTER TABLE `keywords` 
  MODIFY `content` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL;
-- 24/10/2017
ALTER TABLE `papers`
  MODIFY `id` varchar(45) COLLATE ascii_general_ci NOT NULL;
ALTER TABLE `keyword_paper` 
  MODIFY `paper_id` varchar(45) COLLATE ascii_general_ci NOT NULL;
ALTER TABLE `author_paper`
  MODIFY `paper_id` varchar(45) COLLATE ascii_general_ci NOT NULL;
ALTER TABLE `co_author_paper`
  MODIFY `paper_id` varchar(45) COLLATE ascii_general_ci NOT NULL;
  