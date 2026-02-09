-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20260116.01cb890f61
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 17, 2026 at 04:48 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `delivery_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `camions`
--

CREATE TABLE `camions` (
  `id` bigint UNSIGNED NOT NULL,
  `immatriculation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modele` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacite` decimal(10,2) NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `livreur_id` bigint UNSIGNED DEFAULT NULL,
  `statut` enum('disponible','en_service','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disponible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `camions`
--

INSERT INTO `camions` (`id`, `immatriculation`, `modele`, `capacite`, `admin_id`, `livreur_id`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'TN-1234-A', 'Mercedes Actros', 20.50, 2, 6, 'en_service', '2026-01-17 13:39:36', '2026-01-17 15:45:13'),
(2, 'TN-5678-B', 'Volvo FH16', 25.00, 2, 5, 'en_service', '2026-01-17 13:39:36', '2026-01-17 14:42:53'),
(3, 'TN-9012-C', 'Scania R500', 22.00, 3, NULL, 'disponible', '2026-01-17 13:39:36', '2026-01-17 13:39:36'),
(4, 'TN-2002-V', 'volvo', 26.00, 2, NULL, 'en_service', '2026-01-17 14:33:53', '2026-01-17 14:33:53'),
(5, 'TUN-2006', 'Renault', 30.00, 2, NULL, 'en_service', '2026-01-17 15:38:46', '2026-01-17 15:40:52');

-- --------------------------------------------------------

--
-- Table structure for table `camion_produit`
--

CREATE TABLE `camion_produit` (
  `id` bigint UNSIGNED NOT NULL,
  `camion_id` bigint UNSIGNED NOT NULL,
  `produit_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `camion_produit`
--

INSERT INTO `camion_produit` (`id`, `camion_id`, `produit_id`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2026-01-17 14:16:13', '2026-01-17 14:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_17_143302_create_camions_table', 1),
(5, '2026_01_17_143312_create_produits_table', 1),
(6, '2026_01_17_143326_create_camion_produit_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `poids` decimal(10,2) DEFAULT NULL,
  `volume` decimal(10,2) DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expediteur_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expediteur_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expediteur_societe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expediteur_matricule_fiscale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinataire_nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destinataire_prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destinataire_cin_3_derniers_chiffres` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('valide','prepare','en_route','livre') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valide',
  `camion_id` bigint UNSIGNED DEFAULT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `poids`, `volume`, `source`, `destination`, `expediteur_email`, `expediteur_phone`, `expediteur_societe`, `expediteur_matricule_fiscale`, `destinataire_nom`, `destinataire_prenom`, `destinataire_cin_3_derniers_chiffres`, `qr_code`, `statut`, `camion_id`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'Colis Électronique', 'Ordinateurs portables - Fragile', 15.50, 0.80, 'Tunis', 'Sfax', 'exp1@company.com', '+216 20 123 456', 'Tech Solutions', '1234567A', 'Gharbi', 'Fatma', '789', 'aefff594-ca31-4fe6-a085-b316b50a655b', 'livre', 1, 2, '2026-01-17 13:39:36', '2026-01-17 14:26:42'),
(2, 'Matériel Médical', 'Équipements hospitaliers', 45.00, 2.50, 'Sousse', 'Monastir', 'medical@hospital.tn', '+216 73 456 789', 'MediCare', '9876543B', 'Haddad', 'Karim', '456', '62fddeb8-4748-459d-b8ce-6da4022dccec', 'prepare', 1, 2, '2026-01-17 13:39:36', '2026-01-17 13:39:36'),
(3, 'Pièces Automobiles', 'Pièces de rechange', 120.00, 5.00, 'Bizerte', 'Tunis', 'auto@parts.tn', '+216 72 987 654', 'AutoParts TN', NULL, 'Mejri', 'Sami', '123', '91dd419f-7c77-4a75-9d32-f7ccb04a0a13', 'en_route', 2, 2, '2026-01-17 13:39:36', '2026-01-17 13:39:36'),
(4, 'Produits Alimentaires', 'Denrées périssables', 200.00, 8.00, 'Nabeul', 'Hammamet', 'food@export.tn', '+216 72 111 222', 'FoodExport', NULL, 'Sassi', 'Leila', '999', 'f74a07e4-4f6e-49b0-a025-e74a9a85a16d', 'livre', NULL, 3, '2026-01-17 13:39:36', '2026-01-17 13:39:36'),
(5, 'salon', 'salon cuir', 350.00, 4.00, 'Tunis', 'zagwen', 'admin@delivery.com', '46345226', 'Transport Express SARL', '11254479', 'Tbini', 'Mustapha Amin', '123', 'c88aaeaf-6558-4424-85bf-85c6c9cc8695', 'livre', 1, 2, '2026-01-17 14:16:13', '2026-01-17 14:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BOrVAPvc2uE8QlTu1iaAlnfvIzTG2Sea74LLLQxg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSEtVQ2MzdE93MXEzQW4xTmVlcXFYZmh5UmlxZWVxVkluUkdNRzlEdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1768662011),
('f6OW10gFWg6h9X1tYUrZXj76AnuKxz2CvpW2wdC6', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNlBYVGE0MnBWNnVTYU1YZGpWNDl4Wk8yQThOSUp6aEpCcG5ITmNtcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWl0cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1768668353),
('FlXMPV0RlcAiR0ifOYThnYvdxtZ2x9id51MubpGY', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNXJrMDVvWk1GQWxhM3JJQUxjbHFERGNWSHJGVTRsSFFUTU9JSEdvMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9saXZyZXVyL3NjYW4iO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1768668397);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin','livreur') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'livreur',
  `camion_id` bigint UNSIGNED DEFAULT NULL,
  `company_info` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `email_verified_at`, `password`, `role`, `camion_id`, `company_info`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'superadmin@delivery.com', NULL, '$2y$12$E3YjdPryP0sJAcPaelOJgOv8PcmpceAiMHPyjqQC1LtM57lHqwqi2', 'super_admin', NULL, NULL, NULL, '2026-01-17 13:39:35', '2026-01-17 13:39:35'),
(2, 'Dupont', 'Jean', 'admin@delivery.com', NULL, '$2y$12$YulWKFxohBJua2hwA2OEnuQyk.eez0APKfeCDrkfvcplbmXQNJAL.', 'admin', NULL, 'Transport Express SARL', NULL, '2026-01-17 13:39:35', '2026-01-17 13:39:35'),
(3, 'Martin', 'Sophie', 'admin2@delivery.com', NULL, '$2y$12$XqJ7LOPOiuNsupYx7RhxhudD9pW73wSUC/o0RH2MDzjjOX/WkeAeu', 'admin', NULL, 'Logistique Pro', NULL, '2026-01-17 13:39:36', '2026-01-17 13:39:36'),
(4, 'Ben Ali', 'Mohamed', 'livreur1@delivery.com', NULL, '$2y$12$vYt4EebYQiVyultzf.ook.Bix0TCx8Hcp2Uo/bZAZEIDJUFmHu70C', 'livreur', 4, '2', NULL, '2026-01-17 13:39:36', '2026-01-17 14:38:02'),
(5, 'Trabelsi', 'Ahmed', 'livreur2@delivery.com', NULL, '$2y$12$Q9T07K.56qAqiA44eF44HOqJkslUgopLHcRw4QamXy8UDPIb618fO', 'livreur', 2, '2', NULL, '2026-01-17 13:39:36', '2026-01-17 14:42:53'),
(6, 'Tbini', 'Mustapha Amin', 'livreur3@delivery.com', NULL, '$2y$12$ClP2sPHWgoWqvZHfbEd95.zyrbAlZ8FdXAi28/tUjaZp1vblgRiBC', 'livreur', 1, '2', NULL, '2026-01-17 14:38:33', '2026-01-17 15:45:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `camions`
--
ALTER TABLE `camions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `camions_immatriculation_unique` (`immatriculation`),
  ADD KEY `camions_admin_id_foreign` (`admin_id`),
  ADD KEY `camions_livreur_id_foreign` (`livreur_id`);

--
-- Indexes for table `camion_produit`
--
ALTER TABLE `camion_produit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `camion_produit_camion_id_produit_id_unique` (`camion_id`,`produit_id`),
  ADD KEY `camion_produit_produit_id_foreign` (`produit_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `produits_qr_code_unique` (`qr_code`),
  ADD KEY `produits_camion_id_foreign` (`camion_id`),
  ADD KEY `produits_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `camions`
--
ALTER TABLE `camions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `camion_produit`
--
ALTER TABLE `camion_produit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `camions`
--
ALTER TABLE `camions`
  ADD CONSTRAINT `camions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `camions_livreur_id_foreign` FOREIGN KEY (`livreur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `camion_produit`
--
ALTER TABLE `camion_produit`
  ADD CONSTRAINT `camion_produit_camion_id_foreign` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `camion_produit_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produits_camion_id_foreign` FOREIGN KEY (`camion_id`) REFERENCES `camions` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
