-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 06 Jan 2026 pada 07.59
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SistemPempek`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'pempek', 'pempek', NULL, '🫔', 1, 1, '2025-12-06 08:57:23', '2025-12-06 08:57:23'),
(2, 'Roti', 'roti', NULL, '🍞', 2, 1, '2025-12-07 09:23:26', '2025-12-07 09:23:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Asset','Liability','Equity','Revenue','Expense') NOT NULL,
  `normal_balance` enum('Debit','Credit') NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `code`, `name`, `type`, `normal_balance`, `opening_balance`, `parent_id`, `description`, `created_at`, `updated_at`) VALUES
(1, '1101', 'Kas', 'Asset', 'Debit', 0.00, NULL, NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(2, '1102', 'Piutang Usaha', 'Asset', 'Debit', 0.00, NULL, NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(3, '2101', 'Hutang Usaha', 'Liability', 'Credit', 0.00, NULL, NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(4, '4000', 'Pendapatan Penjualan', 'Revenue', 'Credit', 0.00, NULL, NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(5, '5000', 'Beban Operasional', 'Expense', 'Debit', 0.00, NULL, NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(6, '5001', 'Beban Bahan Baku', 'Expense', 'Debit', 0.00, NULL, NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(7, '5002', 'Beban Gaji', 'Expense', 'Debit', 0.00, 5, NULL, '2025-11-12 23:00:54', '2025-11-12 23:00:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `has_unread_admin` tinyint(1) NOT NULL DEFAULT 0,
  `has_unread_user` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `conversations`
--

INSERT INTO `conversations` (`id`, `user_id`, `status`, `last_message_at`, `has_unread_admin`, `has_unread_user`, `created_at`, `updated_at`) VALUES
(1, 2, 'closed', '2025-12-06 03:32:58', 1, 0, '2025-12-06 03:26:08', '2025-12-06 03:33:46'),
(2, 2, 'closed', '2025-12-06 03:33:48', 1, 0, '2025-12-06 03:33:48', '2025-12-06 03:33:55'),
(3, 2, 'closed', '2025-12-06 03:33:58', 1, 0, '2025-12-06 03:33:58', '2025-12-06 03:34:04'),
(4, 2, 'open', '2025-12-12 08:38:07', 0, 0, '2025-12-06 03:34:08', '2025-12-13 11:00:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `expenses`
--

INSERT INTO `expenses` (`id`, `description`, `amount`, `date`, `chart_of_account_id`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 'Gaji Karyawan', 20000.00, '2025-11-13', 7, 1, '2025-11-12 23:01:35', '2025-11-12 23:01:35'),
(6, 'Gaji Karyawan', 500000.00, '2025-12-07', 7, 3, '2025-12-07 09:58:46', '2025-12-07 09:58:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `journals`
--

CREATE TABLE `journals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `referenceable_type` varchar(255) NOT NULL,
  `referenceable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `journals`
--

INSERT INTO `journals` (`id`, `date`, `description`, `referenceable_type`, `referenceable_id`, `created_at`, `updated_at`) VALUES
(3, '2025-11-12', 'Penjualan ORD-20251112-0001', 'App\\Models\\Order', 1, '2025-11-12 08:14:31', '2025-11-12 08:14:31'),
(5, '2025-11-13', 'Gaji Karyawan', 'App\\Models\\Expense', 4, '2025-11-12 23:01:35', '2025-11-12 23:01:35'),
(6, '2025-11-13', 'Pembelian kredit 1', 'App\\Models\\Purchase', 1, '2025-11-12 23:07:13', '2025-11-12 23:07:13'),
(7, '2025-11-13', 'Pembelian tunai 2', 'App\\Models\\Purchase', 2, '2025-11-12 23:08:56', '2025-11-12 23:08:56'),
(8, '2025-11-14', 'Penjualan ORD-20251114-0001', 'App\\Models\\Order', 2, '2025-11-13 20:31:16', '2025-11-13 20:31:16'),
(9, '2025-11-14', 'Pembelian tunai 3', 'App\\Models\\Purchase', 3, '2025-11-13 20:52:29', '2025-11-13 20:52:29'),
(10, '2025-12-06', 'Penjualan ORD-20251206-0003', 'App\\Models\\Order', 10, '2025-12-06 02:34:10', '2025-12-06 02:34:10'),
(11, '2025-12-06', 'Penjualan ORD-20251206-0004', 'App\\Models\\Order', 11, '2025-12-06 02:39:14', '2025-12-06 02:39:14'),
(12, '2025-12-06', 'Penjualan ORD-20251206-0005', 'App\\Models\\Order', 12, '2025-12-06 02:49:40', '2025-12-06 02:49:40'),
(13, '2025-12-06', 'Penjualan ORD-20251206-0006', 'App\\Models\\Order', 13, '2025-12-06 03:00:25', '2025-12-06 03:00:25'),
(14, '2025-12-06', 'Penjualan ORD-20251206-0007', 'App\\Models\\Order', 14, '2025-12-06 03:04:17', '2025-12-06 03:04:17'),
(15, '2025-12-06', 'Penjualan ORD-20251206-0008', 'App\\Models\\Order', 15, '2025-12-06 03:12:23', '2025-12-06 03:12:23'),
(16, '2025-12-06', 'Penjualan ORD-20251206-0009', 'App\\Models\\Order', 16, '2025-12-06 08:59:57', '2025-12-06 08:59:57'),
(17, '2025-12-06', 'Penjualan ORD-20251206-0010', 'App\\Models\\Order', 17, '2025-12-06 09:07:45', '2025-12-06 09:07:45'),
(18, '2025-12-06', 'Penjualan ORD-20251206-0011', 'App\\Models\\Order', 18, '2025-12-06 09:12:34', '2025-12-06 09:12:34'),
(19, '2025-12-07', 'Penjualan ORD-20251207-0001', 'App\\Models\\Order', 19, '2025-12-06 17:08:38', '2025-12-06 17:08:38'),
(21, '2025-12-07', 'Penjualan ORD-20251207-0002', 'App\\Models\\Order', 20, '2025-12-07 08:07:49', '2025-12-07 08:07:49'),
(22, '2025-12-07', 'Penjualan ORD-20251207-0003', 'App\\Models\\Order', 21, '2025-12-07 09:57:18', '2025-12-07 09:57:18'),
(23, '2025-12-07', 'Gaji Karyawan', 'App\\Models\\Expense', 6, '2025-12-07 09:58:47', '2025-12-07 09:58:47'),
(24, '2025-12-10', 'Penjualan ORD-20251210-0001', 'App\\Models\\Order', 22, '2025-12-10 07:28:35', '2025-12-10 07:28:35'),
(25, '2025-12-10', 'Penjualan ORD-20251210-0002', 'App\\Models\\Order', 23, '2025-12-10 09:12:18', '2025-12-10 09:12:18'),
(26, '2025-12-12', 'Penjualan ORD-20251212-0001', 'App\\Models\\Order', 24, '2025-12-12 08:40:36', '2025-12-12 08:40:36'),
(27, '2025-12-12', 'Penjualan ORD-20251212-0002', 'App\\Models\\Order', 25, '2025-12-12 08:51:16', '2025-12-12 08:51:16'),
(28, '2025-12-12', 'Penjualan ORD-20251212-0003', 'App\\Models\\Order', 26, '2025-12-12 09:49:37', '2025-12-12 09:49:37'),
(29, '2025-12-13', 'Penjualan ORD-20251213-0001', 'App\\Models\\Order', 27, '2025-12-13 08:12:19', '2025-12-13 08:12:19'),
(30, '2025-12-13', 'Penjualan ORD-20251213-0002', 'App\\Models\\Order', 28, '2025-12-13 08:30:33', '2025-12-13 08:30:33'),
(31, '2025-12-13', 'Penjualan ORD-20251213-0003', 'App\\Models\\Order', 29, '2025-12-13 09:05:51', '2025-12-13 09:05:51'),
(32, '2025-12-13', 'Penjualan ORD-20251213-0004', 'App\\Models\\Order', 30, '2025-12-13 09:16:20', '2025-12-13 09:16:20'),
(33, '2025-12-13', 'Penjualan ORD-20251213-0005', 'App\\Models\\Order', 31, '2025-12-13 09:22:34', '2025-12-13 09:22:34'),
(34, '2025-12-13', 'Penjualan ORD-20251213-0006', 'App\\Models\\Order', 32, '2025-12-13 11:58:18', '2025-12-13 11:58:18'),
(35, '2025-12-13', 'Penjualan ORD-20251213-0007', 'App\\Models\\Order', 33, '2025-12-13 12:18:24', '2025-12-13 12:18:24'),
(36, '2025-12-13', 'Penjualan ORD-20251213-0008', 'App\\Models\\Order', 34, '2025-12-13 13:55:07', '2025-12-13 13:55:07'),
(37, '2025-12-16', 'Penjualan ORD-20251216-0001', 'App\\Models\\Order', 35, '2025-12-16 05:09:53', '2025-12-16 05:09:53'),
(38, '2025-12-16', 'Penjualan ORD-20251216-0002', 'App\\Models\\Order', 36, '2025-12-16 05:37:52', '2025-12-16 05:37:52'),
(39, '2025-12-16', 'Penjualan ORD-20251216-0003', 'App\\Models\\Order', 37, '2025-12-16 05:42:25', '2025-12-16 05:42:25'),
(40, '2025-12-16', 'Penjualan ORD-20251216-0004', 'App\\Models\\Order', 38, '2025-12-16 05:44:11', '2025-12-16 05:44:11'),
(41, '2025-12-16', 'Penjualan ORD-20251216-0005', 'App\\Models\\Order', 39, '2025-12-16 05:46:18', '2025-12-16 05:46:18'),
(42, '2025-12-16', 'Penjualan ORD-20251216-0006', 'App\\Models\\Order', 40, '2025-12-16 05:49:00', '2025-12-16 05:49:00'),
(43, '2025-12-16', 'Penjualan ORD-20251216-0007', 'App\\Models\\Order', 41, '2025-12-16 06:02:57', '2025-12-16 06:02:57'),
(44, '2025-12-16', 'Penjualan ORD-20251216-0008', 'App\\Models\\Order', 42, '2025-12-16 06:11:43', '2025-12-16 06:11:43'),
(45, '2025-12-16', 'Penjualan ORD-20251216-0009', 'App\\Models\\Order', 43, '2025-12-16 06:35:39', '2025-12-16 06:35:39'),
(46, '2025-12-16', 'Penjualan ORD-20251216-0010', 'App\\Models\\Order', 44, '2025-12-16 08:21:45', '2025-12-16 08:21:45'),
(47, '2025-12-16', 'Penjualan ORD-20251216-0011', 'App\\Models\\Order', 45, '2025-12-16 09:59:29', '2025-12-16 09:59:29'),
(48, '2025-12-16', 'Penjualan ORD-20251216-0012', 'App\\Models\\Order', 46, '2025-12-16 10:02:58', '2025-12-16 10:02:58'),
(49, '2025-12-16', 'Penjualan ORD-20251216-0013', 'App\\Models\\Order', 47, '2025-12-16 10:05:55', '2025-12-16 10:05:55'),
(50, '2025-12-16', 'Penjualan ORD-20251216-0014', 'App\\Models\\Order', 48, '2025-12-16 10:09:43', '2025-12-16 10:09:43'),
(51, '2025-12-16', 'Penjualan ORD-20251216-0015', 'App\\Models\\Order', 49, '2025-12-16 10:30:18', '2025-12-16 10:30:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `journal_transactions`
--

CREATE TABLE `journal_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_id` bigint(20) UNSIGNED NOT NULL,
  `chart_of_account_id` bigint(20) UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `journal_transactions`
--

INSERT INTO `journal_transactions` (`id`, `journal_id`, `chart_of_account_id`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
(5, 3, 1, 70000.00, 0.00, '2025-11-12 08:14:31', '2025-11-12 08:14:31'),
(6, 3, 4, 0.00, 70000.00, '2025-11-12 08:14:31', '2025-11-12 08:14:31'),
(9, 5, 7, 20000.00, 0.00, '2025-11-12 23:01:35', '2025-11-12 23:01:35'),
(10, 5, 1, 0.00, 20000.00, '2025-11-12 23:01:35', '2025-11-12 23:01:35'),
(11, 6, 6, 10000.00, 0.00, '2025-11-12 23:07:13', '2025-11-12 23:07:13'),
(12, 6, 3, 0.00, 10000.00, '2025-11-12 23:07:13', '2025-11-12 23:07:13'),
(13, 7, 6, 10000.00, 0.00, '2025-11-12 23:08:56', '2025-11-12 23:08:56'),
(14, 7, 1, 0.00, 10000.00, '2025-11-12 23:08:56', '2025-11-12 23:08:56'),
(15, 8, 1, 40000.00, 0.00, '2025-11-13 20:31:16', '2025-11-13 20:31:16'),
(16, 8, 4, 0.00, 40000.00, '2025-11-13 20:31:16', '2025-11-13 20:31:16'),
(17, 9, 6, 20000.00, 0.00, '2025-11-13 20:52:29', '2025-11-13 20:52:29'),
(18, 9, 1, 0.00, 20000.00, '2025-11-13 20:52:29', '2025-11-13 20:52:29'),
(19, 10, 1, 44000.00, 0.00, '2025-12-06 02:34:10', '2025-12-06 02:34:10'),
(20, 10, 4, 0.00, 44000.00, '2025-12-06 02:34:10', '2025-12-06 02:34:10'),
(21, 11, 1, 70000.00, 0.00, '2025-12-06 02:39:14', '2025-12-06 02:39:14'),
(22, 11, 4, 0.00, 70000.00, '2025-12-06 02:39:14', '2025-12-06 02:39:14'),
(23, 12, 1, 49000.00, 0.00, '2025-12-06 02:49:40', '2025-12-06 02:49:40'),
(24, 12, 4, 0.00, 49000.00, '2025-12-06 02:49:40', '2025-12-06 02:49:40'),
(25, 13, 1, 89000.00, 0.00, '2025-12-06 03:00:25', '2025-12-06 03:00:25'),
(26, 13, 4, 0.00, 89000.00, '2025-12-06 03:00:25', '2025-12-06 03:00:25'),
(27, 14, 1, 80000.00, 0.00, '2025-12-06 03:04:17', '2025-12-06 03:04:17'),
(28, 14, 4, 0.00, 80000.00, '2025-12-06 03:04:17', '2025-12-06 03:04:17'),
(29, 15, 1, 100000.00, 0.00, '2025-12-06 03:12:23', '2025-12-06 03:12:23'),
(30, 15, 4, 0.00, 100000.00, '2025-12-06 03:12:23', '2025-12-06 03:12:23'),
(31, 16, 1, 38000.00, 0.00, '2025-12-06 08:59:57', '2025-12-06 08:59:57'),
(32, 16, 4, 0.00, 38000.00, '2025-12-06 08:59:57', '2025-12-06 08:59:57'),
(33, 17, 1, 35000.00, 0.00, '2025-12-06 09:07:45', '2025-12-06 09:07:45'),
(34, 17, 4, 0.00, 35000.00, '2025-12-06 09:07:45', '2025-12-06 09:07:45'),
(35, 18, 1, 44000.00, 0.00, '2025-12-06 09:12:34', '2025-12-06 09:12:34'),
(36, 18, 4, 0.00, 44000.00, '2025-12-06 09:12:34', '2025-12-06 09:12:34'),
(37, 19, 1, 247000.00, 0.00, '2025-12-06 17:08:38', '2025-12-06 17:08:38'),
(38, 19, 4, 0.00, 247000.00, '2025-12-06 17:08:38', '2025-12-06 17:08:38'),
(41, 21, 1, 51000.00, 0.00, '2025-12-07 08:07:49', '2025-12-07 08:07:49'),
(42, 21, 4, 0.00, 51000.00, '2025-12-07 08:07:49', '2025-12-07 08:07:49'),
(43, 22, 1, 188000.00, 0.00, '2025-12-07 09:57:18', '2025-12-07 09:57:18'),
(44, 22, 4, 0.00, 188000.00, '2025-12-07 09:57:18', '2025-12-07 09:57:18'),
(45, 23, 7, 500000.00, 0.00, '2025-12-07 09:58:47', '2025-12-07 09:58:47'),
(46, 23, 1, 0.00, 500000.00, '2025-12-07 09:58:47', '2025-12-07 09:58:47'),
(47, 24, 1, 54000.00, 0.00, '2025-12-10 07:28:35', '2025-12-10 07:28:35'),
(48, 24, 4, 0.00, 54000.00, '2025-12-10 07:28:35', '2025-12-10 07:28:35'),
(49, 25, 1, 51000.00, 0.00, '2025-12-10 09:12:18', '2025-12-10 09:12:18'),
(50, 25, 4, 0.00, 51000.00, '2025-12-10 09:12:18', '2025-12-10 09:12:18'),
(51, 26, 1, 150000.00, 0.00, '2025-12-12 08:40:36', '2025-12-12 08:40:36'),
(52, 26, 4, 0.00, 150000.00, '2025-12-12 08:40:36', '2025-12-12 08:40:36'),
(53, 27, 1, 198000.00, 0.00, '2025-12-12 08:51:16', '2025-12-12 08:51:16'),
(54, 27, 4, 0.00, 198000.00, '2025-12-12 08:51:16', '2025-12-12 08:51:16'),
(55, 28, 1, 39600.00, 0.00, '2025-12-12 09:49:37', '2025-12-12 09:49:37'),
(56, 28, 4, 0.00, 39600.00, '2025-12-12 09:49:37', '2025-12-12 09:49:37'),
(57, 29, 1, 38000.00, 0.00, '2025-12-13 08:12:19', '2025-12-13 08:12:19'),
(58, 29, 4, 0.00, 38000.00, '2025-12-13 08:12:19', '2025-12-13 08:12:19'),
(59, 30, 1, 49000.00, 0.00, '2025-12-13 08:30:33', '2025-12-13 08:30:33'),
(60, 30, 4, 0.00, 49000.00, '2025-12-13 08:30:33', '2025-12-13 08:30:33'),
(61, 31, 1, 49000.00, 0.00, '2025-12-13 09:05:51', '2025-12-13 09:05:51'),
(62, 31, 4, 0.00, 49000.00, '2025-12-13 09:05:51', '2025-12-13 09:05:51'),
(63, 32, 1, 49000.00, 0.00, '2025-12-13 09:16:20', '2025-12-13 09:16:20'),
(64, 32, 4, 0.00, 49000.00, '2025-12-13 09:16:20', '2025-12-13 09:16:20'),
(65, 33, 1, 22000.00, 0.00, '2025-12-13 09:22:34', '2025-12-13 09:22:34'),
(66, 33, 4, 0.00, 22000.00, '2025-12-13 09:22:34', '2025-12-13 09:22:34'),
(67, 34, 1, 49000.00, 0.00, '2025-12-13 11:58:18', '2025-12-13 11:58:18'),
(68, 34, 4, 0.00, 49000.00, '2025-12-13 11:58:18', '2025-12-13 11:58:18'),
(69, 35, 1, 30000.00, 0.00, '2025-12-13 12:18:24', '2025-12-13 12:18:24'),
(70, 35, 4, 0.00, 30000.00, '2025-12-13 12:18:24', '2025-12-13 12:18:24'),
(71, 36, 1, 43500.00, 0.00, '2025-12-13 13:55:07', '2025-12-13 13:55:07'),
(72, 36, 4, 0.00, 43500.00, '2025-12-13 13:55:07', '2025-12-13 13:55:07'),
(73, 37, 1, 25000.00, 0.00, '2025-12-16 05:09:53', '2025-12-16 05:09:53'),
(74, 37, 4, 0.00, 25000.00, '2025-12-16 05:09:53', '2025-12-16 05:09:53'),
(75, 38, 1, 44000.00, 0.00, '2025-12-16 05:37:52', '2025-12-16 05:37:52'),
(76, 38, 4, 0.00, 44000.00, '2025-12-16 05:37:52', '2025-12-16 05:37:52'),
(77, 39, 1, 69000.00, 0.00, '2025-12-16 05:42:25', '2025-12-16 05:42:25'),
(78, 39, 4, 0.00, 69000.00, '2025-12-16 05:42:25', '2025-12-16 05:42:25'),
(79, 40, 1, 49000.00, 0.00, '2025-12-16 05:44:11', '2025-12-16 05:44:11'),
(80, 40, 4, 0.00, 49000.00, '2025-12-16 05:44:11', '2025-12-16 05:44:11'),
(81, 41, 1, 49000.00, 0.00, '2025-12-16 05:46:18', '2025-12-16 05:46:18'),
(82, 41, 4, 0.00, 49000.00, '2025-12-16 05:46:18', '2025-12-16 05:46:18'),
(83, 42, 1, 44000.00, 0.00, '2025-12-16 05:49:00', '2025-12-16 05:49:00'),
(84, 42, 4, 0.00, 44000.00, '2025-12-16 05:49:00', '2025-12-16 05:49:00'),
(85, 43, 1, 58000.00, 0.00, '2025-12-16 06:02:57', '2025-12-16 06:02:57'),
(86, 43, 4, 0.00, 58000.00, '2025-12-16 06:02:57', '2025-12-16 06:02:57'),
(87, 44, 1, 41000.00, 0.00, '2025-12-16 06:11:43', '2025-12-16 06:11:43'),
(88, 44, 4, 0.00, 41000.00, '2025-12-16 06:11:43', '2025-12-16 06:11:43'),
(89, 45, 1, 39000.00, 0.00, '2025-12-16 06:35:39', '2025-12-16 06:35:39'),
(90, 45, 4, 0.00, 39000.00, '2025-12-16 06:35:39', '2025-12-16 06:35:39'),
(91, 46, 1, 49000.00, 0.00, '2025-12-16 08:21:45', '2025-12-16 08:21:45'),
(92, 46, 4, 0.00, 49000.00, '2025-12-16 08:21:45', '2025-12-16 08:21:45'),
(93, 47, 1, 91500.00, 0.00, '2025-12-16 09:59:29', '2025-12-16 09:59:29'),
(94, 47, 4, 0.00, 91500.00, '2025-12-16 09:59:29', '2025-12-16 09:59:29'),
(95, 48, 1, 49000.00, 0.00, '2025-12-16 10:02:58', '2025-12-16 10:02:58'),
(96, 48, 4, 0.00, 49000.00, '2025-12-16 10:02:58', '2025-12-16 10:02:58'),
(97, 49, 1, 40500.00, 0.00, '2025-12-16 10:05:55', '2025-12-16 10:05:55'),
(98, 49, 4, 0.00, 40500.00, '2025-12-16 10:05:55', '2025-12-16 10:05:55'),
(99, 50, 1, 41500.00, 0.00, '2025-12-16 10:09:43', '2025-12-16 10:09:43'),
(100, 50, 4, 0.00, 41500.00, '2025-12-16 10:09:43', '2025-12-16 10:09:43'),
(101, 51, 1, 41000.00, 0.00, '2025-12-16 10:30:18', '2025-12-16 10:30:18'),
(102, 51, 4, 0.00, 41000.00, '2025-12-16 10:30:18', '2025-12-16 10:30:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT 200,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'pempek',
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `weight`, `image`, `category`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pempek Lenjer', 'Pempek berbentuk memanjang dengan tekstur kenyal dan rasa gurih. Disajikan dengan kuah cuko yang asam pedas.', 15000.00, 200, 'menu-images/vWDW4oGGLcBpSA4S91s1Nu3G9pSx7cuWQEIjUvdW.jpg', 'pempek', 1, '2025-11-12 08:01:59', '2025-12-06 08:57:45'),
(2, 1, 'Pempek Kapal Selam', 'Pempek berisi telur ayam utuh di dalamnya. Favorit pelanggan dengan rasa yang unik dan mengenyangkan.', 20000.00, 200, 'menu-images/1qeSdVen97bYo5xEWJlTUF6inVNyWnQ2WE2uB35k.jpg', 'pempek', 1, '2025-11-12 08:01:59', '2025-12-06 08:57:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` varchar(255) NOT NULL DEFAULT 'user',
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `message_status` varchar(255) NOT NULL DEFAULT 'sent',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `admin_reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `user_id`, `sender_type`, `sender_id`, `subject`, `message`, `message_status`, `is_read`, `admin_reply`, `replied_at`, `created_at`, `updated_at`) VALUES
(15, NULL, 2, 'user', NULL, 'Chat Admin', '[SYSTEM_INIT]', 'sent', 1, 'masuk', '2025-11-15 07:44:33', '2025-11-15 07:44:33', '2025-11-15 07:44:33'),
(16, 1, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'sent', 0, NULL, NULL, '2025-12-06 03:32:58', '2025-12-06 03:32:58'),
(17, 1, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda. Silakan hubungi kami untuk konfirmasi.', 'read', 1, NULL, NULL, '2025-12-06 03:32:58', '2025-12-06 03:32:58'),
(18, 2, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'sent', 0, NULL, NULL, '2025-12-06 03:33:48', '2025-12-06 03:33:48'),
(19, 2, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout berdasarkan lokasi Anda.', 'read', 1, NULL, NULL, '2025-12-06 03:33:48', '2025-12-06 03:33:48'),
(20, 3, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'sent', 0, NULL, NULL, '2025-12-06 03:33:58', '2025-12-06 03:33:58'),
(21, 3, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga Pempek N-Kitchen*\n\nSilakan kunjungi halaman menu kami untuk melihat daftar lengkap menu dan harga terbaru.\n\n👉 Apakah ada menu tertentu yang ingin Anda tanyakan?', 'read', 1, NULL, NULL, '2025-12-06 03:33:58', '2025-12-06 03:33:59'),
(22, 4, 2, 'user', 2, 'Chat Pelanggan', 'Cara Pesan', 'read', 1, NULL, NULL, '2025-12-06 03:34:08', '2025-12-06 03:45:06'),
(23, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Cara Memesan*\n\n1️⃣ Pilih menu yang diinginkan\n2️⃣ Tambahkan ke keranjang\n3️⃣ Isi data pengiriman\n4️⃣ Pilih metode pembayaran\n5️⃣ Selesaikan pembayaran\n\n✅ Pesanan akan diproses setelah pembayaran dikonfirmasi!', 'read', 1, NULL, NULL, '2025-12-06 03:34:08', '2025-12-06 03:34:10'),
(24, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-06 03:36:34', '2025-12-06 03:45:06'),
(25, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\nKami menerima berbagai metode pembayaran:\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n• E-wallet lainnya\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-06 03:36:34', '2025-12-06 03:36:35'),
(26, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-06 03:36:41', '2025-12-06 03:45:06'),
(27, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda. Silakan hubungi kami untuk konfirmasi.', 'read', 1, NULL, NULL, '2025-12-06 03:36:41', '2025-12-06 03:36:44'),
(28, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-06 03:36:48', '2025-12-06 03:45:06'),
(29, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga Pempek N-Kitchen*\n\nSilakan kunjungi halaman menu kami untuk melihat daftar lengkap menu dan harga terbaru.\n\n👉 Apakah ada menu tertentu yang ingin Anda tanyakan?', 'read', 1, NULL, NULL, '2025-12-06 03:36:48', '2025-12-06 03:36:50'),
(30, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-06 03:36:54', '2025-12-06 03:45:06'),
(31, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda. Silakan hubungi kami untuk konfirmasi.', 'read', 1, NULL, NULL, '2025-12-06 03:36:54', '2025-12-06 03:36:56'),
(32, 4, 2, 'user', 2, 'Chat Pelanggan', 'Cara Pesan', 'read', 1, NULL, NULL, '2025-12-06 03:37:36', '2025-12-06 03:45:06'),
(33, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Cara Memesan*\n\n1️⃣ Pilih menu yang diinginkan\n2️⃣ Tambahkan ke keranjang\n3️⃣ Isi data pengiriman\n4️⃣ Pilih metode pembayaran\n5️⃣ Selesaikan pembayaran\n\n✅ Pesanan akan diproses setelah pembayaran dikonfirmasi!', 'read', 1, NULL, NULL, '2025-12-06 03:37:36', '2025-12-06 03:37:38'),
(34, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 03:38:25', '2025-12-06 03:45:06'),
(35, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout berdasarkan lokasi Anda.', 'read', 1, NULL, NULL, '2025-12-06 03:38:25', '2025-12-06 03:38:27'),
(36, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-06 03:41:07', '2025-12-06 03:45:06'),
(37, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga Pempek N-Kitchen*\n\nSilakan kunjungi halaman menu kami untuk melihat daftar lengkap menu dan harga terbaru.\n\n👉 Apakah ada menu tertentu yang ingin Anda tanyakan?', 'read', 1, NULL, NULL, '2025-12-06 03:41:07', '2025-12-06 03:41:09'),
(38, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-06 03:41:54', '2025-12-06 03:45:06'),
(39, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-06 03:41:54', '2025-12-06 03:41:54'),
(40, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 03:42:09', '2025-12-06 03:45:06'),
(41, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 03:42:09', '2025-12-06 03:42:12'),
(42, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-06 03:42:13', '2025-12-06 03:45:06'),
(43, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-06 03:42:13', '2025-12-06 03:42:15'),
(44, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:42:19', '2025-12-06 03:45:06'),
(45, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:42:19', '2025-12-06 03:42:21'),
(46, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-06 03:42:55', '2025-12-06 03:45:06'),
(47, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-06 03:42:55', '2025-12-06 03:42:57'),
(48, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:55:19', '2025-12-06 04:12:51'),
(49, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   ✅ Dikonfirmasi\n   🚚 No. Resi: REG001 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:55:19', '2025-12-06 03:55:20'),
(50, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 03:56:09', '2025-12-06 04:12:51'),
(51, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 03:56:09', '2025-12-06 03:56:10'),
(52, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:57:35', '2025-12-06 04:12:51'),
(53, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   ✅ Dikonfirmasi\n   🚚 No. Resi: REG001 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:57:35', '2025-12-06 03:57:37'),
(54, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:59:09', '2025-12-06 04:12:51'),
(55, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   📦 Siap Diambil\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 03:59:09', '2025-12-06 03:59:10'),
(56, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 04:04:46', '2025-12-06 04:12:51'),
(57, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 04:04:46', '2025-12-06 04:04:48'),
(58, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-06 04:42:50', '2025-12-06 08:17:30'),
(59, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda.', 'read', 1, NULL, NULL, '2025-12-06 04:42:50', '2025-12-06 04:42:51'),
(60, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 04:42:54', '2025-12-06 08:17:30'),
(61, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 04:42:54', '2025-12-06 04:42:55'),
(62, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-06 04:42:56', '2025-12-06 08:17:30'),
(63, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-06 04:42:56', '2025-12-06 04:42:57'),
(64, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-06 04:43:05', '2025-12-06 08:17:30'),
(65, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-06 04:43:05', '2025-12-06 04:43:06'),
(66, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 04:43:09', '2025-12-06 08:17:30'),
(67, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 04:43:09', '2025-12-06 04:43:10'),
(68, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-06 04:55:30', '2025-12-06 08:17:30'),
(69, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda.', 'read', 1, NULL, NULL, '2025-12-06 04:55:30', '2025-12-06 04:55:31'),
(70, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 04:55:32', '2025-12-06 08:17:30'),
(71, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 04:55:32', '2025-12-06 04:55:33'),
(72, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-06 04:55:35', '2025-12-06 08:17:30'),
(73, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-06 04:55:35', '2025-12-06 04:55:36'),
(74, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 05:15:45', '2025-12-06 08:17:30'),
(75, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 05:15:45', '2025-12-06 05:15:45'),
(76, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-06 05:15:47', '2025-12-06 08:17:30'),
(77, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-06 05:15:47', '2025-12-06 05:15:48'),
(78, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 05:15:48', '2025-12-06 08:17:30'),
(79, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 05:15:48', '2025-12-06 05:15:49'),
(80, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:02:59', '2025-12-06 08:17:30'),
(81, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:02:59', '2025-12-06 08:03:01'),
(82, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 08:03:01', '2025-12-06 08:17:30'),
(83, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 08:03:01', '2025-12-06 08:03:04'),
(84, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-06 08:15:16', '2025-12-06 08:17:30'),
(85, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-06 08:15:16', '2025-12-06 08:15:16'),
(86, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-06 08:15:20', '2025-12-06 08:17:30'),
(87, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-06 08:15:20', '2025-12-06 08:15:21'),
(88, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-06 08:15:22', '2025-12-06 08:17:30'),
(89, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda.', 'read', 1, NULL, NULL, '2025-12-06 08:15:22', '2025-12-06 08:15:24'),
(90, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:15:24', '2025-12-06 08:17:30'),
(91, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:15:24', '2025-12-06 08:15:24'),
(92, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:15:32', '2025-12-06 08:17:30'),
(93, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:15:32', '2025-12-06 08:15:33'),
(94, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-06 08:15:33', '2025-12-06 08:17:30'),
(95, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda.', 'read', 1, NULL, NULL, '2025-12-06 08:15:33', '2025-12-06 08:15:36'),
(96, 4, 2, 'user', 2, 'Chat Pelanggan', 'bukanya jam berapa?', 'read', 1, NULL, NULL, '2025-12-06 08:15:42', '2025-12-06 08:17:30'),
(97, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-06 08:15:42', '2025-12-06 08:15:42'),
(98, 4, 2, 'user', 2, 'Chat Pelanggan', 'a', 'read', 1, NULL, NULL, '2025-12-06 08:15:47', '2025-12-06 08:17:30'),
(99, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🤖 Terima kasih atas pesan Anda!\n\nSaya belum bisa menjawab pertanyaan ini secara otomatis. Admin kami akan segera membalas pesan Anda.\n\n⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\nSambil menunggu, Anda bisa tanyakan:\n• Menu & harga\n• Status pesanan\n• Jam operasional\n• Info pengiriman', 'read', 1, NULL, NULL, '2025-12-06 08:15:47', '2025-12-06 08:15:48'),
(100, 4, 2, 'user', 2, 'Chat Pelanggan', 'menu', 'read', 1, NULL, NULL, '2025-12-06 08:15:56', '2025-12-06 08:17:30'),
(101, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-06 08:15:56', '2025-12-06 08:15:57'),
(102, 4, 2, 'user', 2, 'Chat Pelanggan', 'status', 'read', 1, NULL, NULL, '2025-12-06 08:15:59', '2025-12-06 08:17:30'),
(103, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251206-0008*\n   📅 06 Dec 2025\n   💰 Rp 100.000\n   🎉 Selesai\n   🚚 No. Resi: REG002 (JNE)\n\n🔖 *ORD-20251206-0007*\n   📅 06 Dec 2025\n   💰 Rp 80.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251206-0006*\n   📅 06 Dec 2025\n   💰 Rp 89.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-06 08:15:59', '2025-12-06 08:16:00'),
(104, 4, 2, 'user', 2, 'Chat Pelanggan', 'm', 'read', 1, NULL, NULL, '2025-12-06 08:16:05', '2025-12-06 08:17:30'),
(105, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🤖 Terima kasih atas pesan Anda!\n\nSaya belum bisa menjawab pertanyaan ini secara otomatis. Admin kami akan segera membalas pesan Anda.\n\n⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\nSambil menunggu, Anda bisa tanyakan:\n• Menu & harga\n• Status pesanan\n• Jam operasional\n• Info pengiriman', 'read', 1, NULL, NULL, '2025-12-06 08:16:05', '2025-12-06 08:16:06'),
(106, 4, 2, 'user', 2, 'Chat Pelanggan', 'x', 'read', 1, NULL, NULL, '2025-12-06 08:16:09', '2025-12-06 08:17:30'),
(107, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🤖 Terima kasih atas pesan Anda!\n\nSaya belum bisa menjawab pertanyaan ini secara otomatis. Admin kami akan segera membalas pesan Anda.\n\n⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\nSambil menunggu, Anda bisa tanyakan:\n• Menu & harga\n• Status pesanan\n• Jam operasional\n• Info pengiriman', 'read', 1, NULL, NULL, '2025-12-06 08:16:09', '2025-12-06 08:16:09'),
(108, 4, 2, 'user', 2, 'Chat Pelanggan', 'untuk sampainya pada hari apa ya kak>', 'read', 1, NULL, NULL, '2025-12-06 08:16:44', '2025-12-06 08:17:30'),
(109, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🤖 Terima kasih atas pesan Anda!\n\nSaya belum bisa menjawab pertanyaan ini secara otomatis. Admin kami akan segera membalas pesan Anda.\n\n⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\nSambil menunggu, Anda bisa tanyakan:\n• Menu & harga\n• Status pesanan\n• Jam operasional\n• Info pengiriman', 'read', 1, NULL, NULL, '2025-12-06 08:16:44', '2025-12-06 08:16:45'),
(110, 4, 2, 'admin', 3, 'Admin Reply', 'test', 'read', 1, NULL, NULL, '2025-12-06 17:19:19', '2025-12-06 17:25:08'),
(111, 4, 2, 'admin', 3, 'Admin Reply', 'iya', 'read', 1, NULL, NULL, '2025-12-06 17:25:03', '2025-12-06 17:25:08'),
(112, 4, 2, 'user', 2, 'Chat Pelanggan', 'bagaimana', 'read', 1, NULL, NULL, '2025-12-06 17:25:14', '2025-12-06 17:25:16'),
(113, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🤖 Terima kasih atas pesan Anda!\n\nSaya belum bisa menjawab pertanyaan ini secara otomatis. Admin kami akan segera membalas pesan Anda.\n\n⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\nSambil menunggu, Anda bisa tanyakan:\n• Menu & harga\n• Status pesanan\n• Jam operasional\n• Info pengiriman', 'read', 1, NULL, NULL, '2025-12-06 17:25:14', '2025-12-06 17:25:14'),
(114, 4, 2, 'user', 2, 'Chat Pelanggan', 'Menu & Harga', 'read', 1, NULL, NULL, '2025-12-07 11:38:56', '2025-12-13 11:00:14'),
(115, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🍽️ *Menu & Harga N-Kitchen*\n━━━━━━━━━━━━━━━━━━━━\n\n📌 *pempek*\n• Pempek Lenjer - Rp 15.000\n• Pempek Kapal Selam - Rp 20.000\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Kunjungi halaman Menu untuk pemesanan!', 'read', 1, NULL, NULL, '2025-12-07 11:38:56', '2025-12-07 11:38:57'),
(116, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-07 11:38:58', '2025-12-13 11:00:14'),
(117, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251207-0003*\n   📅 07 Dec 2025\n   💰 Rp 188.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0002*\n   📅 07 Dec 2025\n   💰 Rp 51.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0001*\n   📅 07 Dec 2025\n   💰 Rp 247.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-07 11:38:58', '2025-12-07 11:39:00'),
(118, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-07 11:39:09', '2025-12-13 11:00:14'),
(119, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda.', 'read', 1, NULL, NULL, '2025-12-07 11:39:09', '2025-12-07 11:39:09'),
(120, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-07 11:39:10', '2025-12-13 11:00:14'),
(121, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-07 11:39:10', '2025-12-07 11:39:12'),
(122, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-07 11:39:13', '2025-12-13 11:00:14'),
(123, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-07 11:39:13', '2025-12-07 11:39:15'),
(124, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-07 11:39:15', '2025-12-13 11:00:14'),
(125, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-07 11:39:15', '2025-12-07 11:39:18'),
(126, 4, 2, 'user', 2, 'Chat Pelanggan', 'Halo Admin, saya ingin bertanya tentang pesanan saya:📦 Order #ORD-20251210-0001📅 Tanggal: 10/12/2025 14:27🍽️ Item: Pempek Lenjer x1, Pempek Kapal Selam x1💰 Total: Rp 54.000📊 Status: ConfirmedPertanyaan saya:', 'read', 1, NULL, NULL, '2025-12-10 07:34:39', '2025-12-13 11:00:14'),
(127, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251210-0001*\n   📅 10 Dec 2025\n   💰 Rp 54.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0003*\n   📅 07 Dec 2025\n   💰 Rp 188.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0002*\n   📅 07 Dec 2025\n   💰 Rp 51.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-10 07:34:39', '2025-12-10 07:34:40'),
(128, 4, 2, 'user', 2, 'Chat Pelanggan', 'Halo Admin, saya ingin bertanya tentang pesanan saya:\n\n📦 Order #ORD-20251210-0001\n📅 Tanggal: 10/12/2025 14:27\n🍽️ Item: Pempek Lenjer x1, Pempek Kapal Selam x1\n💰 Total: Rp 54.000\n📊 Status: Confirmed\n\nPertanyaan saya:', 'read', 1, NULL, NULL, '2025-12-10 07:36:28', '2025-12-13 11:00:14'),
(129, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251210-0001*\n   📅 10 Dec 2025\n   💰 Rp 54.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0003*\n   📅 07 Dec 2025\n   💰 Rp 188.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0002*\n   📅 07 Dec 2025\n   💰 Rp 51.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-10 07:36:28', '2025-12-10 07:36:29'),
(130, 4, 2, 'user', 2, 'Pertanyaan Pesanan', 'Halo Admin, saya ingin bertanya tentang pesanan saya:\n\n📦 Order #ORD-20251210-0001\n📅 Tanggal: 10/12/2025 14:27\n🍽️ Item: Pempek Lenjer x1, Pempek Kapal Selam x1\n💰 Total: Rp 54.000\n📊 Status: Confirmed\n\nPertanyaan saya:', 'read', 1, NULL, NULL, '2025-12-10 07:38:34', '2025-12-13 11:00:14'),
(131, 4, 2, 'chatbot', NULL, 'Auto Reply', '✅ Pertanyaan Anda tentang pesanan telah diterima!\n\nAdmin kami akan segera membalas pesan Anda. Mohon ditunggu ya 🙏', 'read', 1, NULL, NULL, '2025-12-10 07:38:34', '2025-12-10 07:38:36'),
(132, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-10 07:38:42', '2025-12-13 11:00:14'),
(133, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-10 07:38:42', '2025-12-10 07:38:45'),
(134, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-10 07:38:43', '2025-12-13 11:00:14'),
(135, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251210-0001*\n   📅 10 Dec 2025\n   💰 Rp 54.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0003*\n   📅 07 Dec 2025\n   💰 Rp 188.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0002*\n   📅 07 Dec 2025\n   💰 Rp 51.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-10 07:38:43', '2025-12-10 07:38:45'),
(136, 4, 2, 'user', 2, 'Chat Pelanggan', 'Status Pesanan', 'read', 1, NULL, NULL, '2025-12-10 09:12:47', '2025-12-13 11:00:14'),
(137, 4, 2, 'chatbot', NULL, 'Chatbot Response', '📦 *Status Pesanan Anda*\n━━━━━━━━━━━━━━━━━━━━\n\n🔖 *ORD-20251210-0002*\n   📅 10 Dec 2025\n   💰 Rp 51.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251210-0001*\n   📅 10 Dec 2025\n   💰 Rp 54.000\n   ✅ Dikonfirmasi\n\n🔖 *ORD-20251207-0003*\n   📅 07 Dec 2025\n   💰 Rp 188.000\n   ✅ Dikonfirmasi\n\n━━━━━━━━━━━━━━━━━━━━\n💡 Lihat detail di menu Riwayat Pesanan', 'read', 1, NULL, NULL, '2025-12-10 09:12:47', '2025-12-10 09:12:48'),
(138, 4, 2, 'user', 2, 'Chat Pelanggan', 'Jam Buka', 'read', 1, NULL, NULL, '2025-12-10 09:13:07', '2025-12-13 11:00:14'),
(139, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🕐 *Jam Operasional N-Kitchen*\n\n📅 Senin - Sabtu: 08:00 - 20:00 WIB\n📅 Minggu: 10:00 - 18:00 WIB\n\n⚠️ Hari libur nasional mungkin berbeda.', 'read', 1, NULL, NULL, '2025-12-10 09:13:07', '2025-12-10 09:13:09'),
(140, 4, 2, 'user', 2, 'Chat Pelanggan', 'Info Ongkir', 'read', 1, NULL, NULL, '2025-12-10 09:13:10', '2025-12-13 11:00:14'),
(141, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🚚 *Info Pengiriman*\n\nKami melayani pengiriman ke seluruh Indonesia dengan ekspedisi:\n• JNE\n• SiCepat\n• J&T Express\n• GoSend (khusus area Karawang)\n\n💡 Ongkir dihitung otomatis saat checkout.', 'read', 1, NULL, NULL, '2025-12-10 09:13:10', '2025-12-10 09:13:12'),
(142, 4, 2, 'user', 2, 'Chat Pelanggan', 'Pembayaran', 'read', 1, NULL, NULL, '2025-12-10 09:13:13', '2025-12-13 11:00:14'),
(143, 4, 2, 'chatbot', NULL, 'Chatbot Response', '💳 *Metode Pembayaran*\n\n• Transfer Bank (BCA, BNI, Mandiri, BRI)\n• Virtual Account\n• QRIS\n• GoPay & ShopeePay\n\n🔒 Semua transaksi aman melalui Midtrans.', 'read', 1, NULL, NULL, '2025-12-10 09:13:13', '2025-12-10 09:13:15'),
(144, 4, 2, 'user', 2, 'Chat Pelanggan', 'd', 'read', 1, NULL, NULL, '2025-12-12 08:38:07', '2025-12-13 11:00:14'),
(145, 4, 2, 'chatbot', NULL, 'Chatbot Response', '🤖 Terima kasih atas pesan Anda!\n\nSaya belum bisa menjawab pertanyaan ini secara otomatis. Admin kami akan segera membalas pesan Anda.\n\n⏰ Waktu respon rata-rata: 5-30 menit pada jam kerja.\n\nSambil menunggu, Anda bisa tanyakan:\n• Menu & harga\n• Status pesanan\n• Jam operasional\n• Info pengiriman', 'read', 1, NULL, NULL, '2025-12-12 08:38:07', '2025-12-12 08:38:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_25_131939_create_menus_table', 1),
(5, '2025_05_25_132019_create_orders_table', 1),
(6, '2025_05_25_132048_create_order_items_table', 1),
(7, '2025_05_25_132114_create_messages_table', 1),
(8, '2025_05_26_150226_create_payments_table', 1),
(9, '2025_05_26_151637_create_payment_methods_table', 1),
(10, '2025_11_09_055340_create_chart_of_accounts_table', 1),
(11, '2025_11_09_061513_create_journals_table', 1),
(12, '2025_11_09_061520_create_journal_transactions_table', 1),
(13, '2025_11_09_085837_create_purchases_table', 1),
(14, '2025_11_09_162010_create_purchase_details_table', 1),
(15, '2025_11_12_220156_create_expenses_table', 1),
(16, '2025_11_12_150357_add_opening_balance_to_chart_of_accounts_table', 2),
(17, '2025_11_13_061720_add_google_id_to_users_table', 3),
(18, '2025_12_02_064736_add_weight_to_menus_table', 4),
(19, '2025_12_02_154245_add_shipping_cost_to_orders_table', 5),
(20, '2025_12_06_100819_add_courier_fields_to_orders_table', 6),
(21, '2025_12_06_102052_create_conversations_table', 7),
(22, '2025_12_06_102053_add_chat_fields_to_messages_table', 7),
(23, '2025_12_06_104002_add_tracking_number_to_orders_table', 8),
(24, '2025_12_06_220000_create_categories_table', 9),
(25, '2025_12_06_220001_add_category_id_to_menus_table', 9),
(26, '2025_12_06_224500_add_destination_fields_to_orders_table', 10),
(27, '2025_12_06_230100_add_province_postal_to_orders_table', 11),
(28, '2025_12_10_153122_add_refunded_status_to_payments_table', 12),
(29, '2025_12_13_152150_add_biteship_fields_to_orders_table', 13),
(30, '2025_12_16_164852_add_coordinates_to_orders_table', 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `order_number` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tracking_number` varchar(255) DEFAULT NULL,
  `biteship_order_id` varchar(255) DEFAULT NULL,
  `biteship_waybill_id` varchar(255) DEFAULT NULL,
  `biteship_status` varchar(255) DEFAULT NULL,
  `biteship_label_url` text DEFAULT NULL,
  `biteship_tracking_url` text DEFAULT NULL,
  `total_weight` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','confirmed','preparing','ready','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `phone` varchar(255) NOT NULL,
  `courier` varchar(255) DEFAULT NULL,
  `shipping_service` varchar(255) DEFAULT NULL,
  `city_id` varchar(255) DEFAULT NULL,
  `destination_city` varchar(255) DEFAULT NULL,
  `destination_district` varchar(255) DEFAULT NULL,
  `destination_province` varchar(255) DEFAULT NULL,
  `destination_postal_code` varchar(255) DEFAULT NULL,
  `destination_latitude` decimal(10,7) DEFAULT NULL,
  `destination_longitude` decimal(11,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `recipient_name`, `order_number`, `total_amount`, `shipping_cost`, `tracking_number`, `biteship_order_id`, `biteship_waybill_id`, `biteship_status`, `biteship_label_url`, `biteship_tracking_url`, `total_weight`, `status`, `notes`, `delivery_address`, `phone`, `courier`, `shipping_service`, `city_id`, `destination_city`, `destination_district`, `destination_province`, `destination_postal_code`, `destination_latitude`, `destination_longitude`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'ORD-20251112-0001', 70000.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-12 08:14:02', '2025-11-12 22:32:20'),
(2, 2, NULL, 'ORD-20251114-0001', 40000.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-13 20:30:48', '2025-11-13 20:31:16'),
(3, 2, NULL, 'ORD-20251202-0001', 79000.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jalan Basuki rahmat,surabaya', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 02:08:11', '2025-12-02 02:08:11'),
(4, 2, NULL, 'ORD-20251202-0002', 79000.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 02:13:22', '2025-12-02 02:13:22'),
(5, 2, NULL, 'ORD-20251202-0003', 244000.00, 229000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 08:44:03', '2025-12-02 08:44:03'),
(6, 2, NULL, 'ORD-20251202-0004', 196000.00, 176000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 11:02:38', '2025-12-02 11:02:38'),
(7, 2, NULL, 'ORD-20251202-0005', 158000.00, 138000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 11:19:04', '2025-12-02 11:19:04'),
(8, 2, NULL, 'ORD-20251206-0001', 90000.00, 75000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 20:37:42', '2025-12-05 20:37:42'),
(9, 2, NULL, 'ORD-20251206-0002', 44000.00, 29000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'pending', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 02:13:31', '2025-12-06 02:13:31'),
(10, 2, NULL, 'ORD-20251206-0003', 44000.00, 29000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 02:27:36', '2025-12-06 02:34:10'),
(11, 2, NULL, 'ORD-20251206-0004', 70000.00, 15000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 02:38:46', '2025-12-06 02:39:14'),
(12, 2, NULL, 'ORD-20251206-0005', 49000.00, 29000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 02:49:18', '2025-12-06 02:49:40'),
(13, 2, NULL, 'ORD-20251206-0006', 89000.00, 69000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 02:59:43', '2025-12-06 03:00:25'),
(14, 2, NULL, 'ORD-20251206-0007', 80000.00, 60000.00, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 03:03:54', '2025-12-06 03:04:17'),
(15, 2, 'Surya Guntur', 'ORD-20251206-0008', 100000.00, 80000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'cancelled', '[REFUND] Dana dikembalikan oleh admin. Waktu: 10/12/2025 15:39:00 WIB. Refund Midtrans berhasil.', 'Jl. Sudirman No. 123, Jakarta', '081987654321', 'jne', 'REG', '377', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 03:11:48', '2025-12-10 08:39:00'),
(16, 2, 'Surya', 'ORD-20251206-0009', 38000.00, 18000.00, 'REG002', NULL, NULL, NULL, NULL, NULL, 200, 'ready', NULL, 'Jl. Sudirman No. 123, yogyakarta', '081987654321', 'jne', 'REG', '31397', 'TEGALREJO', 'BENER', NULL, NULL, NULL, NULL, '2025-12-06 08:59:26', '2025-12-06 09:06:08'),
(17, 2, 'Surya Guntur', 'ORD-20251206-0010', 35000.00, 15000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', 'gosend', 'gosend_instant', '37958', 'KARAWANG BARAT', 'KARAWANG KULON', 'KARAWANG', NULL, NULL, NULL, '2025-12-06 09:07:15', '2025-12-06 09:07:45'),
(18, 2, 'Guntur', 'ORD-20251206-0011', 44000.00, 29000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jl. Sudirman No. 123', '081987654321', 'jne', 'REG', '48151', 'PANJI', 'CURAH JERU', 'JAWA TIMUR', '68323', NULL, NULL, '2025-12-06 09:12:14', '2025-12-06 09:12:34'),
(19, 2, 'Surya Guntur', 'ORD-20251207-0001', 247000.00, 227000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jl. Sudirman No. 123, Jakarta', '081987654321', 'jne', 'REG', '23473', 'TINGGINAMBUT', 'PAPUA', 'PAPUA', '98912', NULL, NULL, '2025-12-06 17:08:14', '2025-12-06 17:08:38'),
(20, 2, 'Budi', 'ORD-20251207-0002', 51000.00, 21000.00, NULL, NULL, NULL, NULL, NULL, NULL, 400, 'confirmed', NULL, 'Jln Kertajaya no 12', '0851512343444', 'jne', 'REG', '69243', 'GUBENG', 'GUBENG', 'JAWA TIMUR', '60281', NULL, NULL, '2025-12-07 08:07:30', '2025-12-07 08:07:49'),
(21, 2, 'Suprapto', 'ORD-20251207-0003', 188000.00, 58000.00, NULL, NULL, NULL, NULL, NULL, NULL, 1400, 'confirmed', NULL, 'Jln Basuki Rahmat', '0813341122022', 'jne', 'REG', '48155', 'PANJI', 'MIMBAAN', 'JAWA TIMUR', '68322', NULL, NULL, '2025-12-07 09:56:55', '2025-12-07 09:57:18'),
(22, 2, 'Fauzi', 'ORD-20251210-0001', 54000.00, 19000.00, NULL, NULL, NULL, NULL, NULL, NULL, 400, 'confirmed', '[REFUND] Dana dikembalikan oleh admin. Waktu: 10/12/2025 15:30:15 WIB. Refund Midtrans berhasil.\n[REFUND] Dana dikembalikan oleh admin. Waktu: 10/12/2025 15:33:42 WIB. Refund Midtrans berhasil.\n[REFUND] Dana dikembalikan oleh admin. Waktu: 10/12/2025 15:34:20 WIB. Refund Midtrans berhasil.', 'Jln Kabupaten no 21', '08133412333', 'jne', 'REG', '16679', 'SUBANG', 'SUBANG', 'JAWA BARAT', '45586', NULL, NULL, '2025-12-10 07:27:50', '2025-12-10 08:42:48'),
(23, 2, 'Budi', 'ORD-20251210-0002', 51000.00, 21000.00, NULL, NULL, NULL, NULL, NULL, NULL, 400, 'confirmed', NULL, 'Jln Merdeka no123', '081987654321', 'jne', 'REG', '69243', 'GUBENG', 'GUBENG', 'JAWA TIMUR', '60281', NULL, NULL, '2025-12-10 09:11:06', '2025-12-10 09:12:18'),
(24, 2, 'Andy', 'ORD-20251212-0001', 150000.00, 15000.00, NULL, NULL, NULL, NULL, NULL, NULL, 1400, 'confirmed', NULL, 'Jln Merdeka no 123', '081522334424', 'gosend', 'gosend_instant', '37965', 'KARAWANG TIMUR', 'KARAWANG WETAN', 'JAWA BARAT', '41314', NULL, NULL, '2025-12-12 08:40:05', '2025-12-12 08:40:36'),
(25, 2, 'Santoso', 'ORD-20251212-0002', 198000.00, 183000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln Kenangan 1234', '08133412333', 'jne', 'REG', '1191', 'KOTA MASOHI', 'AMPERA', 'MALUKU', '97513', NULL, NULL, '2025-12-12 08:50:50', '2025-12-12 08:51:16'),
(26, 2, 'Busan', 'ORD-20251212-0003', 39600.00, 24600.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'jln basuki rahmat no 12', '08123233343', 'sicepat', 'REG', '48156', 'PANJI', 'PANJI KIDUL', 'JAWA TIMUR', '68323', NULL, NULL, '2025-12-12 09:47:09', '2025-12-12 09:49:37'),
(27, 2, 'BUDI', 'ORD-20251213-0001', 38000.00, 18000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln Basuki Rahmat 444', '08123244444', 'jnt', 'ez', 'IDNP11IDNC434IDND5427IDZ60281', 'Surabaya', 'Gubeng', 'Jawa Timur', '60281', NULL, NULL, '2025-12-13 08:11:52', '2025-12-13 08:12:19'),
(28, 2, 'Santos', 'ORD-20251213-0002', 49000.00, 29000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln Basuki Rahmat no 22', '081232423444', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68321', 'Situbondo', 'Panji', 'Jawa Timur', '68321', NULL, NULL, '2025-12-13 08:30:03', '2025-12-13 08:30:33'),
(29, 2, 'Budi', 'ORD-20251213-0003', 49000.00, 29000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln Basuki Rahmat', '08133412333', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-13 09:05:27', '2025-12-13 09:05:51'),
(30, 2, 'budi', 'ORD-20251213-0004', 49000.00, 29000.00, 'WYB-1765617382112', '693d2ee62e2a914917029f98', 'WYB-1765617382112', 'confirmed', 'https://track.biteship.com/HsijfjtX1UolxYsmdaqPnuUH?environment=development', NULL, 200, 'cancelled', NULL, 'Jln Basuki rahmat gg 22', '081231333444', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-13 09:15:56', '2025-12-13 11:53:24'),
(31, 2, 'budi', 'ORD-20251213-0005', 22000.00, 7000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln teluk jambe no 12', '08133412333', 'jnt', 'ez', 'IDNP9IDNC165IDND1180IDZ41361', 'Karawang', 'Telukjambe Barat', 'Jawa Barat', '41361', NULL, NULL, '2025-12-13 09:22:08', '2025-12-13 09:22:34'),
(32, 2, 'budi S', 'ORD-20251213-0006', 49000.00, 29000.00, 'WYB-1765627099603', '693d54db5f45cf04589e7ebb', 'WYB-1765627099603', 'confirmed', 'https://track.biteship.com/uOpDBQi8cF8H5MSqWoUljIZA?environment=development', NULL, 200, 'confirmed', NULL, 'Jln Merder 1245', '0812323444', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68321', 'Situbondo', 'Panji', 'Jawa Timur', '68321', NULL, NULL, '2025-12-13 11:57:55', '2025-12-13 11:58:19'),
(33, 2, 'santoso', 'ORD-20251213-0007', 30000.00, 10000.00, '3708782500001708', '693d5991d6f36f7464365410', '3708782500001708', 'confirmed', 'https://track.biteship.com/693d5992d6f36fd1b9365414', NULL, 200, 'confirmed', NULL, 'Jln merderka 1234', '0813341123', 'jne', 'reg', 'IDNP9IDNC165IDND1180IDZ41361', 'Karawang', 'Telukjambe Barat', 'Jawa Barat', '41361', NULL, NULL, '2025-12-13 12:17:56', '2025-12-13 12:18:25'),
(34, 2, 'budi', 'ORD-20251213-0008', 43500.00, 23500.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln Puseurjaya no123', '08134244444', 'gosend', 'instant', 'IDNP9IDNC165IDND1180IDZ41361', 'Karawang', 'Telukjambe Barat', 'Jawa Barat', '41361', NULL, NULL, '2025-12-13 13:54:43', '2025-12-13 13:55:07'),
(35, 2, 'budi', 'ORD-20251216-0001', 25000.00, 10000.00, 'WYB-1765861794318', '6940e9a2cac64526b0313c7f', 'WYB-1765861794318', 'confirmed', 'https://track.biteship.com/ECvidz61s6wvSYNmgsouW3FQ?environment=development', NULL, 200, 'confirmed', NULL, 'Jln Merdeka no 1233', '08513324444', 'jne', 'reg', 'IDNP9IDNC165IDND1181IDZ41360', 'Karawang', 'Telukjambe Timur', 'Jawa Barat', '41360', NULL, NULL, '2025-12-16 05:09:08', '2025-12-16 05:09:54'),
(36, 2, 'santos', 'ORD-20251216-0002', 44000.00, 24000.00, 'WYB-1765863474078', '6940f03231c4447b7effd9d1', 'WYB-1765863474078', 'confirmed', 'https://track.biteship.com/d4XJrAUb0zCJBSCxSfoM89Mn?environment=development', NULL, 200, 'confirmed', NULL, 'JLN Basuki Rahmat no 33', '08152144554', 'jnt', 'ez', 'IDNP11IDNC411IDND5038IDZ68311', 'Situbondo', 'Situbondo', 'Jawa Timur', '68311', NULL, NULL, '2025-12-16 05:37:22', '2025-12-16 05:37:54'),
(37, 2, 'Santos', 'ORD-20251216-0003', 69000.00, 29000.00, 'WYB-1765863747952', '6940f14331c444d9a7ffe31f', 'WYB-1765863747952', 'confirmed', 'https://track.biteship.com/PEbpYVMvqRTsRBUA1Nte01aq?environment=development', NULL, 400, 'confirmed', NULL, 'JLN Basuki rahmat no 22', '0812344424', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-16 05:41:53', '2025-12-16 05:42:28'),
(38, 2, 'Haji Samsul', 'ORD-20251216-0004', 49000.00, 29000.00, 'WYB-1765863855629', '6940f1afdf641c21bd157d8c', 'WYB-1765863855629', 'confirmed', 'https://track.biteship.com/sz33KbVhsujhgDM1VxtkBOtK?environment=development', NULL, 200, 'confirmed', NULL, 'JLN Basuki rahmat no 66', '081234342344', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-16 05:43:38', '2025-12-16 05:44:16'),
(39, 2, 'Gayo', 'ORD-20251216-0005', 49000.00, 29000.00, 'WYB-1765863979432', '6940f22b31c44421f6ffec3e', 'WYB-1765863979432', 'confirmed', 'https://track.biteship.com/yysMkmM0EtTBpfwjIPGy6gf8?environment=development', NULL, 200, 'confirmed', NULL, 'JLN Basuki Rahmat no 55', '08135344444', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-16 05:45:56', '2025-12-16 05:46:19'),
(40, 2, 'Budi Santoso', 'ORD-20251216-0006', 44000.00, 29000.00, 'WYB-1765864141805', '6940f2cd87d528e5474db9c6', 'WYB-1765864141805', 'confirmed', 'https://track.biteship.com/M3FscMAooeaBzGKljVQ5hGHA?environment=development', NULL, 200, 'confirmed', NULL, 'JLN Basuki Rahmat gg nusantara', '081234444444', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-16 05:48:33', '2025-12-16 05:49:02'),
(41, 2, 'budi', 'ORD-20251216-0007', 58000.00, 18000.00, NULL, NULL, NULL, NULL, NULL, NULL, 400, 'confirmed', NULL, 'Jln sukaharja no 122', '081244442333', 'gosend', 'instant', 'IDNP9IDNC165IDND1177IDZ41352', 'Karawang', 'Rengasdengklok', 'Jawa Barat', '41352', NULL, NULL, '2025-12-16 06:00:23', '2025-12-16 06:02:56'),
(42, 2, 'Budi', 'ORD-20251216-0008', 41000.00, 21000.00, 'WYB-1765865504956', '6940f82031c44490a000258c', 'WYB-1765865504956', 'confirmed', 'https://track.biteship.com/Zj4MAB9B6EqvXBP18YqmbzVl?environment=development', NULL, 200, 'ready', NULL, 'JLN Basuki rahmtar 22', '08133412344', 'jne', 'reg', 'IDNP11IDNC434IDND5427IDZ60281', 'Surabaya', 'Gubeng', 'Jawa Timur', '60281', NULL, NULL, '2025-12-16 06:11:21', '2025-12-16 06:21:08'),
(43, 2, 'Budi', 'ORD-20251216-0009', 39000.00, 19000.00, 'WYB-1765866940637', '6940fdbc31c444f0360060ea', 'WYB-1765866940637', 'confirmed', 'https://track.biteship.com/FInwV7yfan5ZZyuhew9tt1nn?environment=development', NULL, 200, 'confirmed', NULL, 'Jln Kartini no 1234', '08123424444', 'jnt', 'ez', 'IDNP10IDNC157IDND1009IDZ59411', 'Jepara', 'Jepara', 'Jawa Tengah', '59411', NULL, NULL, '2025-12-16 06:35:18', '2025-12-16 06:35:40'),
(44, 2, 'budi', 'ORD-20251216-0010', 49000.00, 29000.00, 'WYB-1765873306882', '6941169a9ab40dc82f539e82', 'WYB-1765873306882', 'confirmed', 'https://track.biteship.com/9Q7AHkuQvZQYOY1mJOauM35d?environment=development', NULL, 200, 'confirmed', NULL, 'JLn BASUKJI RAHMAT323', '08123424444', 'jne', 'reg', 'IDNP11IDNC411IDND5037IDZ68322', 'Situbondo', 'Panji', 'Jawa Timur', '68322', NULL, NULL, '2025-12-16 08:21:19', '2025-12-16 08:21:47'),
(45, 2, 'Surya', 'ORD-20251216-0011', 91500.00, 21500.00, NULL, NULL, NULL, NULL, NULL, NULL, 800, 'confirmed', NULL, 'Jln basuki rahmat no 1233', '08123442444', 'gosend', 'instant', 'IDNP9IDNC165IDND1165IDZ41311', 'Karawang', 'Karawang Barat', 'Jawa Barat', '41311', -6.3591459, 107.3333359, '2025-12-16 09:59:01', '2025-12-16 09:59:29'),
(46, 2, 'Budi', 'ORD-20251216-0012', 49000.00, 34000.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln merdeka no 1234', '08123244444', 'gosend', 'instant', 'IDNP9IDNC165IDND1166IDZ41314', 'Karawang', 'Karawang Timur', 'Jawa Barat', '41314', -6.3666099, 107.2977161, '2025-12-16 10:02:33', '2025-12-16 10:02:58'),
(47, 2, 'santoso', 'ORD-20251216-0013', 40500.00, 20500.00, NULL, NULL, NULL, NULL, NULL, NULL, 200, 'confirmed', NULL, 'Jln Merdeka 123', '081234244444', 'gosend', 'instant', 'IDNP9IDNC165IDND1181IDZ41360', 'Karawang', 'Telukjambe Timur', 'Jawa Barat', '41360', -6.3582929, 107.3237228, '2025-12-16 10:05:38', '2025-12-16 10:05:55'),
(48, 2, 'budi', 'ORD-20251216-0014', 41500.00, 21500.00, 'WYB-1765879784952', '69412fe82daeb0462a129817', 'WYB-1765879784952', 'confirmed', 'https://track.biteship.com/hVy7cKYHXp5Q66N6mVxZJTHp?environment=development', NULL, 200, 'confirmed', NULL, 'JLN Merdeka 123', '08123424444', 'gosend', 'instant', 'IDNP9IDNC165IDND1181IDZ41360', 'Karawang', 'Telukjambe Timur', 'Jawa Barat', '41360', -6.3630698, 107.3181438, '2025-12-16 10:09:28', '2025-12-16 10:09:45'),
(49, 2, 'budi', 'ORD-20251216-0015', 41000.00, 21000.00, 'WYB-1765881487024', '6941368fc9f56192129adfe0', 'WYB-1765881487024', 'confirmed', 'https://track.biteship.com/gO1YvhbZKlWKYzsgSaVNjegU?environment=development', NULL, 200, 'confirmed', NULL, 'jln merdeka 123', '08123424444', 'jne', 'reg', 'IDNP11IDNC434IDND5427IDZ60281', 'Surabaya', 'Gubeng', 'Jawa Timur', '60281', NULL, NULL, '2025-12-16 10:29:55', '2025-12-16 10:38:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `quantity`, `price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 15000.00, NULL, '2025-11-12 08:14:02', '2025-11-12 08:14:02'),
(2, 1, 2, 2, 20000.00, NULL, '2025-11-12 08:14:02', '2025-11-12 08:14:02'),
(3, 2, 2, 2, 20000.00, NULL, '2025-11-13 20:30:48', '2025-11-13 20:30:48'),
(4, 3, 1, 1, 15000.00, NULL, '2025-12-02 02:08:11', '2025-12-02 02:08:11'),
(5, 4, 1, 1, 15000.00, NULL, '2025-12-02 02:13:22', '2025-12-02 02:13:22'),
(6, 5, 1, 1, 15000.00, NULL, '2025-12-02 08:44:03', '2025-12-02 08:44:03'),
(7, 6, 2, 1, 20000.00, NULL, '2025-12-02 11:02:38', '2025-12-02 11:02:38'),
(8, 7, 2, 1, 20000.00, NULL, '2025-12-02 11:19:04', '2025-12-02 11:19:04'),
(9, 8, 1, 1, 15000.00, NULL, '2025-12-05 20:37:42', '2025-12-05 20:37:42'),
(10, 9, 1, 1, 15000.00, NULL, '2025-12-06 02:13:31', '2025-12-06 02:13:31'),
(11, 10, 1, 1, 15000.00, NULL, '2025-12-06 02:27:36', '2025-12-06 02:27:36'),
(12, 11, 1, 1, 15000.00, NULL, '2025-12-06 02:38:46', '2025-12-06 02:38:46'),
(13, 11, 2, 2, 20000.00, NULL, '2025-12-06 02:38:46', '2025-12-06 02:38:46'),
(14, 12, 2, 1, 20000.00, NULL, '2025-12-06 02:49:18', '2025-12-06 02:49:18'),
(15, 13, 2, 1, 20000.00, NULL, '2025-12-06 02:59:43', '2025-12-06 02:59:43'),
(16, 14, 2, 1, 20000.00, NULL, '2025-12-06 03:03:54', '2025-12-06 03:03:54'),
(17, 15, 2, 1, 20000.00, NULL, '2025-12-06 03:11:48', '2025-12-06 03:11:48'),
(18, 16, 2, 1, 20000.00, NULL, '2025-12-06 08:59:26', '2025-12-06 08:59:26'),
(19, 17, 2, 1, 20000.00, NULL, '2025-12-06 09:07:15', '2025-12-06 09:07:15'),
(20, 18, 1, 1, 15000.00, NULL, '2025-12-06 09:12:14', '2025-12-06 09:12:14'),
(21, 19, 2, 1, 20000.00, NULL, '2025-12-06 17:08:14', '2025-12-06 17:08:14'),
(22, 20, 1, 2, 15000.00, NULL, '2025-12-07 08:07:30', '2025-12-07 08:07:30'),
(23, 21, 1, 2, 15000.00, NULL, '2025-12-07 09:56:55', '2025-12-07 09:56:55'),
(24, 21, 2, 5, 20000.00, NULL, '2025-12-07 09:56:55', '2025-12-07 09:56:55'),
(25, 22, 1, 1, 15000.00, NULL, '2025-12-10 07:27:50', '2025-12-10 07:27:50'),
(26, 22, 2, 1, 20000.00, NULL, '2025-12-10 07:27:50', '2025-12-10 07:27:50'),
(27, 23, 1, 2, 15000.00, NULL, '2025-12-10 09:11:06', '2025-12-10 09:11:06'),
(28, 24, 1, 1, 15000.00, NULL, '2025-12-12 08:40:05', '2025-12-12 08:40:05'),
(29, 24, 2, 6, 20000.00, NULL, '2025-12-12 08:40:05', '2025-12-12 08:40:05'),
(30, 25, 1, 1, 15000.00, NULL, '2025-12-12 08:50:50', '2025-12-12 08:50:50'),
(31, 26, 1, 1, 15000.00, NULL, '2025-12-12 09:47:09', '2025-12-12 09:47:09'),
(32, 27, 2, 1, 20000.00, NULL, '2025-12-13 08:11:52', '2025-12-13 08:11:52'),
(33, 28, 2, 1, 20000.00, NULL, '2025-12-13 08:30:03', '2025-12-13 08:30:03'),
(34, 29, 2, 1, 20000.00, NULL, '2025-12-13 09:05:27', '2025-12-13 09:05:27'),
(35, 30, 2, 1, 20000.00, NULL, '2025-12-13 09:15:56', '2025-12-13 09:15:56'),
(36, 31, 1, 1, 15000.00, NULL, '2025-12-13 09:22:08', '2025-12-13 09:22:08'),
(37, 32, 2, 1, 20000.00, NULL, '2025-12-13 11:57:55', '2025-12-13 11:57:55'),
(38, 33, 2, 1, 20000.00, NULL, '2025-12-13 12:17:56', '2025-12-13 12:17:56'),
(39, 34, 2, 1, 20000.00, NULL, '2025-12-13 13:54:43', '2025-12-13 13:54:43'),
(40, 35, 1, 1, 15000.00, NULL, '2025-12-16 05:09:08', '2025-12-16 05:09:08'),
(41, 36, 2, 1, 20000.00, NULL, '2025-12-16 05:37:22', '2025-12-16 05:37:22'),
(42, 37, 2, 2, 20000.00, NULL, '2025-12-16 05:41:53', '2025-12-16 05:41:53'),
(43, 38, 2, 1, 20000.00, NULL, '2025-12-16 05:43:38', '2025-12-16 05:43:38'),
(44, 39, 2, 1, 20000.00, NULL, '2025-12-16 05:45:56', '2025-12-16 05:45:56'),
(45, 40, 1, 1, 15000.00, NULL, '2025-12-16 05:48:33', '2025-12-16 05:48:33'),
(46, 41, 2, 2, 20000.00, NULL, '2025-12-16 06:00:23', '2025-12-16 06:00:23'),
(47, 42, 2, 1, 20000.00, NULL, '2025-12-16 06:11:21', '2025-12-16 06:11:21'),
(48, 43, 2, 1, 20000.00, NULL, '2025-12-16 06:35:18', '2025-12-16 06:35:18'),
(49, 44, 2, 1, 20000.00, NULL, '2025-12-16 08:21:19', '2025-12-16 08:21:19'),
(50, 45, 1, 2, 15000.00, NULL, '2025-12-16 09:59:01', '2025-12-16 09:59:01'),
(51, 45, 2, 2, 20000.00, NULL, '2025-12-16 09:59:01', '2025-12-16 09:59:01'),
(52, 46, 1, 1, 15000.00, NULL, '2025-12-16 10:02:33', '2025-12-16 10:02:33'),
(53, 47, 2, 1, 20000.00, NULL, '2025-12-16 10:05:38', '2025-12-16 10:05:38'),
(54, 48, 2, 1, 20000.00, NULL, '2025-12-16 10:09:28', '2025-12-16 10:09:28'),
(55, 49, 2, 1, 20000.00, NULL, '2025-12-16 10:29:55', '2025-12-16 10:29:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('rapbobs82@gmail.com', '$2y$12$Gcf6ovsap5G/VDnO2NBMLeRSMTR76sssH62kp7zw6fdD8zZ.NiUl.', '2025-11-13 02:14:28'),
('suryaguntur2000@gmail.com', '$2y$12$GV3z3FAeAaPzBTo3zol3IO6nzGssO1ZaqNHQPtFh36CUWkbFrOU/O', '2025-12-12 09:12:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','failed','cancelled','paid','settlement','capture','refunded','expired') DEFAULT 'pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `midtrans_transaction_id` varchar(255) DEFAULT NULL,
  `midtrans_order_id` varchar(255) DEFAULT NULL,
  `midtrans_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`midtrans_response`)),
  `payment_type` varchar(255) DEFAULT NULL,
  `midtrans_paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method_id`, `amount`, `status`, `payment_proof`, `notes`, `paid_at`, `midtrans_transaction_id`, `midtrans_order_id`, `midtrans_response`, `payment_type`, `midtrans_paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 75000.00, 'confirmed', NULL, NULL, '2025-11-12 08:14:31', 'cd42d7ef-0284-4b8b-8433-63042f9fd50e', 'ORD-20251112-0001-1762960442', '{\"status_code\":\"200\",\"transaction_id\":\"cd42d7ef-0284-4b8b-8433-63042f9fd50e\",\"gross_amount\":\"75000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251112-0001-1762960442\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"1029adf6fe2dee3aae092143ea24a1cef3616afbaf338b8235ec6a01562178cb4d405d84577747b4b8116b3ccec5e62e5f0035884ddca16a68972e6a30bacbb5\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972499791313793288138\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-11-12 22:14:09\",\"settlement_time\":\"2025-11-12 22:14:25\",\"expiry_time\":\"2025-11-12 23:14:02\"}', 'bank_transfer', '2025-11-12 08:14:31', '2025-11-12 08:14:02', '2025-11-12 08:14:31'),
(2, 2, 1, 45000.00, 'confirmed', NULL, NULL, '2025-11-13 20:31:16', 'ab1f5311-2657-46dd-bff2-a4a39c992f5f', 'ORD-20251114-0001-1763091048', '{\"status_code\":\"200\",\"transaction_id\":\"ab1f5311-2657-46dd-bff2-a4a39c992f5f\",\"gross_amount\":\"45000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251114-0001-1763091048\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"40995150134447d6ddad2510725eb5efdf9f44fe994cf2a7930af37a329567909d69a1920004542303d9f7419b44d5179f9f0237bef7845ccffaa3afc3b5d4cd\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972287783721820942063\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-11-14 10:30:57\",\"settlement_time\":\"2025-11-14 10:31:10\",\"expiry_time\":\"2025-11-14 11:30:48\"}', 'bank_transfer', '2025-11-13 20:31:16', '2025-11-13 20:30:48', '2025-11-13 20:31:16'),
(3, 3, 1, 79000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251202-0001-1764667707', NULL, NULL, NULL, '2025-12-02 02:08:11', '2025-12-02 02:28:28'),
(4, 4, 1, 79000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251202-0002-1764684976', NULL, NULL, NULL, '2025-12-02 02:13:22', '2025-12-02 07:16:16'),
(5, 5, 1, 244000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251202-0003-1764692948', NULL, NULL, NULL, '2025-12-02 08:44:03', '2025-12-02 09:29:08'),
(6, 6, 1, 196000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251202-0004-1764698558', NULL, NULL, NULL, '2025-12-02 11:02:38', '2025-12-02 11:02:39'),
(7, 7, 1, 158000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251202-0005-1764699544', NULL, NULL, NULL, '2025-12-02 11:19:04', '2025-12-02 11:19:05'),
(8, 8, 1, 90000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251206-0001-1765352696', NULL, NULL, NULL, '2025-12-05 20:37:42', '2025-12-10 07:44:59'),
(9, 9, 1, 44000.00, 'pending', NULL, NULL, NULL, NULL, 'ORD-20251206-0002-1765012411', NULL, NULL, NULL, '2025-12-06 02:13:31', '2025-12-06 02:13:31'),
(10, 10, 1, 44000.00, 'confirmed', NULL, NULL, '2025-12-06 02:35:20', '300bc94f-3de0-4c75-a7ae-0dec9428d547', 'ORD-20251206-0003-1765013448', '{\"status_code\":\"200\",\"transaction_id\":\"300bc94f-3de0-4c75-a7ae-0dec9428d547\",\"gross_amount\":\"44000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0003-1765013448\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"89daf9bebf26e04458b0a26ff404775d0bff00f58e8b1519777044df33cdda7ce58710b44a7337a1ee130704f7d9e0350a6d7395affa3463649b04675649caea\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972839279200210820822\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 16:31:10\",\"settlement_time\":\"2025-12-06 16:34:03\",\"expiry_time\":\"2025-12-06 17:30:48\"}', 'bank_transfer', '2025-12-06 02:35:20', '2025-12-06 02:27:36', '2025-12-06 02:35:20'),
(11, 11, 1, 70000.00, 'confirmed', NULL, NULL, '2025-12-06 02:39:14', 'a7ef60dd-e108-4412-8bcf-845e2f99b7e7', 'ORD-20251206-0004-1765013926', '{\"status_code\":\"200\",\"transaction_id\":\"a7ef60dd-e108-4412-8bcf-845e2f99b7e7\",\"gross_amount\":\"70000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0004-1765013926\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"cbc2f3d19f00ea9bd7a6e5afad878ff370a97cd240d1e980a4580b1a2f4ead8edbe7cbd76889de5947447574fc013355bbbe7d03164ee55be0ce678b6de764c7\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972237208568415442793\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 16:38:53\",\"settlement_time\":\"2025-12-06 16:39:04\",\"expiry_time\":\"2025-12-06 17:38:46\"}', 'bank_transfer', '2025-12-06 02:39:14', '2025-12-06 02:38:46', '2025-12-06 02:39:14'),
(12, 12, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-06 02:49:40', 'fda473a8-45af-4f94-adea-02b14314332c', 'ORD-20251206-0005-1765014558', '{\"status_code\":\"200\",\"transaction_id\":\"fda473a8-45af-4f94-adea-02b14314332c\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0005-1765014558\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"2198b6ab1409ae187a485a632d84889817dd20d5f5ba8a1f4e240ed336994ebf8969e776eb07a1ca3a0bcb56f9b015f84e13827d3de6e809791d6d271b14a5a2\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972893700935138863487\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 16:49:24\",\"settlement_time\":\"2025-12-06 16:49:32\",\"expiry_time\":\"2025-12-06 17:49:18\"}', 'bank_transfer', '2025-12-06 02:49:40', '2025-12-06 02:49:18', '2025-12-06 02:49:40'),
(13, 13, 1, 89000.00, 'confirmed', NULL, NULL, '2025-12-06 03:00:25', '4a11be57-539d-4056-9301-b9e7cd8b82f2', 'ORD-20251206-0006-1765015183', '{\"status_code\":\"200\",\"transaction_id\":\"4a11be57-539d-4056-9301-b9e7cd8b82f2\",\"gross_amount\":\"89000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0006-1765015183\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"c83649f31703a3140c5d5cc86dba25051e985f85da524b8a2c547720ec94b65de460b3c9823194da10fb54c401ebaf29010185fc7c0a5f3c2f62ae49e28643bf\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972215637670609863731\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 16:59:59\",\"settlement_time\":\"2025-12-06 17:00:19\",\"expiry_time\":\"2025-12-06 17:59:43\"}', 'bank_transfer', '2025-12-06 03:00:25', '2025-12-06 02:59:43', '2025-12-06 03:00:25'),
(14, 14, 1, 80000.00, 'confirmed', NULL, NULL, '2025-12-06 03:04:17', '3d79e46e-9400-4c0e-8352-9de4d736eab0', 'ORD-20251206-0007-1765015434', '{\"status_code\":\"200\",\"transaction_id\":\"3d79e46e-9400-4c0e-8352-9de4d736eab0\",\"gross_amount\":\"80000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0007-1765015434\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"5fe12fc1868e22dfaef0be06e5b7145b311f6cb942c5053d91562305928b333847dc601cdaf30c7dff556aa8473430a32feba553849aaee6ca55957c8a27af52\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972052259770633988587\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 17:04:00\",\"settlement_time\":\"2025-12-06 17:04:11\",\"expiry_time\":\"2025-12-06 18:03:54\"}', 'bank_transfer', '2025-12-06 03:04:17', '2025-12-06 03:03:54', '2025-12-06 03:04:17'),
(15, 15, 1, 100000.00, 'refunded', NULL, 'Refund diproses oleh admin pada 10/12/2025 15:39:00', '2025-12-06 03:12:23', '113b91d1-d874-4fa3-9249-46bcde6cf9d1', 'ORD-20251206-0008-1765015908', '{\"status_code\":\"200\",\"transaction_id\":\"113b91d1-d874-4fa3-9249-46bcde6cf9d1\",\"gross_amount\":\"100000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0008-1765015908\",\"payment_type\":\"qris\",\"signature_key\":\"5edc1f8cc29790a2118e7172539f59a28fdb764e900efb2e01c39149a155352f7b6f354aefeb6002acf6282322c1d3f09b6ac99e171f5a0d9bb4e1ffd37635a4\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-06 17:11:52\",\"settlement_time\":\"2025-12-06 17:12:16\",\"expiry_time\":\"2025-12-06 18:11:48\"}', 'qris', '2025-12-06 03:12:23', '2025-12-06 03:11:48', '2025-12-10 08:39:00'),
(16, 16, 1, 38000.00, 'confirmed', NULL, NULL, '2025-12-06 08:59:57', '794bcf44-ae43-487c-8315-0c1073c25c51', 'ORD-20251206-0009-1765036766', '{\"status_code\":\"200\",\"transaction_id\":\"794bcf44-ae43-487c-8315-0c1073c25c51\",\"gross_amount\":\"38000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0009-1765036766\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"f1a38ad12f5c636bad75fab20cddbaade6e6696a75b4a28302fb261185ddc4682ca901df1682244d1dd5aa2219babd506a0d47d015b210d07f679558f7b36fdf\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972576338265809951413\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 22:59:33\",\"settlement_time\":\"2025-12-06 22:59:50\",\"expiry_time\":\"2025-12-06 23:59:26\"}', 'bank_transfer', '2025-12-06 08:59:57', '2025-12-06 08:59:26', '2025-12-06 08:59:57'),
(17, 17, 1, 35000.00, 'confirmed', NULL, NULL, '2025-12-06 09:07:45', 'ed436870-29e8-45f2-8eed-b8dfa888a7e7', 'ORD-20251206-0010-1765037236', '{\"status_code\":\"200\",\"transaction_id\":\"ed436870-29e8-45f2-8eed-b8dfa888a7e7\",\"gross_amount\":\"35000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0010-1765037236\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"bc5b94b86791eb94dc853f61756af0affba3c226072d8519899c728546d6e187622e14211bd7ddca7fe4cfb6f85026869b62ef55537535876af12190f7374dec\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972660615097205984643\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 23:07:21\",\"settlement_time\":\"2025-12-06 23:07:39\",\"expiry_time\":\"2025-12-07 00:07:16\"}', 'bank_transfer', '2025-12-06 09:07:45', '2025-12-06 09:07:16', '2025-12-06 09:07:45'),
(18, 18, 1, 44000.00, 'confirmed', NULL, NULL, '2025-12-06 09:12:34', '536938de-89b9-4edb-bed9-d5fb25b78111', 'ORD-20251206-0011-1765037534', '{\"status_code\":\"200\",\"transaction_id\":\"536938de-89b9-4edb-bed9-d5fb25b78111\",\"gross_amount\":\"44000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251206-0011-1765037534\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"6edfdd21c32d768e31d89589af35e146e2aefa6097dc01916d66bd2e8c36024b472b5139184004b474d97bc8ae40cca5b3208407645748847bd08b2f997a3d88\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972727701433693856656\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-06 23:12:20\",\"settlement_time\":\"2025-12-06 23:12:30\",\"expiry_time\":\"2025-12-07 00:12:14\"}', 'bank_transfer', '2025-12-06 09:12:34', '2025-12-06 09:12:14', '2025-12-06 09:12:34'),
(19, 19, 1, 247000.00, 'confirmed', NULL, NULL, '2025-12-06 17:08:38', '393fad4d-4551-464c-bd1b-8aa1a5602a9a', 'ORD-20251207-0001-1765040894', '{\"status_code\":\"200\",\"transaction_id\":\"393fad4d-4551-464c-bd1b-8aa1a5602a9a\",\"gross_amount\":\"247000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251207-0001-1765040894\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"918976d98b3961a1b62dd41f8e2db10c0875d041c279ad22420250b65dc6306cb946912db9590ee4b0a02fb7ce97affe81729378107d31568b483316766d74ca\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972700346082104252605\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-07 00:08:20\",\"settlement_time\":\"2025-12-07 00:08:34\",\"expiry_time\":\"2025-12-07 01:08:14\"}', 'bank_transfer', '2025-12-06 17:08:38', '2025-12-06 17:08:14', '2025-12-06 17:08:38'),
(20, 20, 1, 51000.00, 'confirmed', NULL, NULL, '2025-12-07 08:07:49', '7fb6c74a-5043-424f-8b98-a2cacac2ba5c', 'ORD-20251207-0002-1765094850', '{\"status_code\":\"200\",\"transaction_id\":\"7fb6c74a-5043-424f-8b98-a2cacac2ba5c\",\"gross_amount\":\"51000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251207-0002-1765094850\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"625ae21868f26581828d03748f1a6c0affddcf25cb15bac25d1a2c86577c76170d1c28925a3f3c147720127ffaa67e7239c84fa71326b049c98f579494b145fd\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972970005452054054960\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-07 15:07:36\",\"settlement_time\":\"2025-12-07 15:07:45\",\"expiry_time\":\"2025-12-07 16:07:30\"}', 'bank_transfer', '2025-12-07 08:07:49', '2025-12-07 08:07:30', '2025-12-07 08:07:49'),
(21, 21, 1, 188000.00, 'confirmed', NULL, NULL, '2025-12-07 09:57:18', '526b0716-afc8-43dd-a81b-ec7bedd9710b', 'ORD-20251207-0003-1765101415', '{\"status_code\":\"200\",\"transaction_id\":\"526b0716-afc8-43dd-a81b-ec7bedd9710b\",\"gross_amount\":\"188000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251207-0003-1765101415\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"972cd4797a6c8f7c5bf8dde73b4ed1121bfcb24d7f8fff08c1404f6b6045dc2efeb24d4e11b29f2f5a73cd42d4e642e69d8ef86221be900d055fe704c9b9f37e\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972164042430831314763\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-07 16:57:01\",\"settlement_time\":\"2025-12-07 16:57:13\",\"expiry_time\":\"2025-12-07 17:56:55\"}', 'bank_transfer', '2025-12-07 09:57:18', '2025-12-07 09:56:55', '2025-12-07 09:57:18'),
(22, 22, 1, 54000.00, 'refunded', NULL, 'Refund diproses oleh admin pada 10/12/2025 15:34:20', '2025-12-10 07:28:35', 'caac3963-e26c-4e58-abf7-5fd3cc36c8f9', 'ORD-20251210-0001-1765351670', '{\"status_code\":\"200\",\"transaction_id\":\"caac3963-e26c-4e58-abf7-5fd3cc36c8f9\",\"gross_amount\":\"54000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251210-0001-1765351670\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"0b64d77f109d11f88ce59d669ec3179ceccbdd433e0a7af6dccd4e3b4a37460dff58413357df31de859ea173fa96d8c9003502eb1eec5bd026634b2997067da0\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972350672377514558189\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-10 14:28:02\",\"settlement_time\":\"2025-12-10 14:28:27\",\"expiry_time\":\"2025-12-10 15:27:50\"}', 'bank_transfer', '2025-12-10 07:28:35', '2025-12-10 07:27:50', '2025-12-10 08:34:20'),
(23, 23, 1, 51000.00, 'confirmed', NULL, NULL, '2025-12-10 09:12:18', '6fc0e911-2057-4122-a7e9-386ae653aa39', 'ORD-20251210-0002-1765357866', '{\"status_code\":\"200\",\"transaction_id\":\"6fc0e911-2057-4122-a7e9-386ae653aa39\",\"gross_amount\":\"51000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251210-0002-1765357866\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"e21c071b5e5851d037a2f5206ff3b27bc57513cab7eabf9eadac12e1bf36f2b6b7c253fcb0d35351e03ccaebc2557011289d109802a0b1640ecbbafadd655290\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972748912329424535782\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-10 16:12:01\",\"settlement_time\":\"2025-12-10 16:12:12\",\"expiry_time\":\"2025-12-10 17:11:06\"}', 'bank_transfer', '2025-12-10 09:12:18', '2025-12-10 09:11:06', '2025-12-10 09:12:18'),
(24, 24, 1, 150000.00, 'confirmed', NULL, NULL, '2025-12-12 08:40:36', 'bf2ec4c9-9f06-4069-ae5a-3f9f67c3f386', 'ORD-20251212-0001-1765528805', '{\"status_code\":\"200\",\"transaction_id\":\"bf2ec4c9-9f06-4069-ae5a-3f9f67c3f386\",\"gross_amount\":\"150000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251212-0001-1765528805\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"be8afe812d864cc7751b57592ab6707926c75df53baf18f62d86ff904a55f0987eab7b5b48ba0c21f51a97a110782af7382fc6b655ccbb047e211c6618694a7b\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972899322337061103453\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-12 15:40:21\",\"settlement_time\":\"2025-12-12 15:40:31\",\"expiry_time\":\"2025-12-12 16:40:05\"}', 'bank_transfer', '2025-12-12 08:40:36', '2025-12-12 08:40:05', '2025-12-12 08:40:36'),
(25, 25, 1, 198000.00, 'confirmed', NULL, NULL, '2025-12-12 08:51:16', '690c7a66-6ebf-4d96-85f1-5d0c5f0a7e6b', 'ORD-20251212-0002-1765529450', '{\"status_code\":\"200\",\"transaction_id\":\"690c7a66-6ebf-4d96-85f1-5d0c5f0a7e6b\",\"gross_amount\":\"198000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251212-0002-1765529450\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"b60973f9bf226eace31b54fc2ff3707a72c70e2c2bb41f696e2478f77980560777d683f1214004f4d92b99748c77496d455db791ca11393ad5fa5958cc376c1e\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G904777972\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"77972351539090046313801\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-12 15:50:58\",\"settlement_time\":\"2025-12-12 15:51:11\",\"expiry_time\":\"2025-12-12 16:50:50\"}', 'bank_transfer', '2025-12-12 08:51:16', '2025-12-12 08:50:50', '2025-12-12 08:51:16'),
(26, 26, 1, 39600.00, 'confirmed', NULL, NULL, '2025-12-12 09:49:37', 'aa032609-0e14-4b5e-b4f0-23821732579d', 'ORD-20251212-0003-1765532945', '{\"status_code\":\"200\",\"transaction_id\":\"aa032609-0e14-4b5e-b4f0-23821732579d\",\"gross_amount\":\"39600.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251212-0003-1765532945\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"accc492a68d7760fa544bf6977c59f9d6e31b00e10c27895a2f906ec78a9a4a7d148c0ef52941fa3fb791a2c56300f1f9e8f5dbdd1b574936aa1c797da70d7b1\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257402457575816164048\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-12 16:49:19\",\"settlement_time\":\"2025-12-12 16:49:30\",\"expiry_time\":\"2025-12-12 17:49:05\"}', 'bank_transfer', '2025-12-12 09:49:37', '2025-12-12 09:47:09', '2025-12-12 09:49:37'),
(27, 27, 1, 38000.00, 'confirmed', NULL, NULL, '2025-12-13 08:12:19', '836cbe1a-4260-4751-b96d-65b66dce0c94', 'ORD-20251213-0001-1765613512', '{\"status_code\":\"200\",\"transaction_id\":\"836cbe1a-4260-4751-b96d-65b66dce0c94\",\"gross_amount\":\"38000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0001-1765613512\",\"payment_type\":\"qris\",\"signature_key\":\"9030dc44b881b1c0149cf2cf3a14dbfee8e4268fab4c650d0630ea1fd46584aa2d0a2066f456d79f7d279d97804a82203c8979105e0d43ba4e6f216ebbc6c018\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 15:12:00\",\"settlement_time\":\"2025-12-13 15:12:14\",\"expiry_time\":\"2025-12-13 16:11:52\"}', 'qris', '2025-12-13 08:12:19', '2025-12-13 08:11:52', '2025-12-13 08:12:19'),
(28, 28, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-13 08:30:33', '0c56c511-40e1-48a7-9bd5-88122d8b4e2f', 'ORD-20251213-0002-1765614603', '{\"status_code\":\"200\",\"transaction_id\":\"0c56c511-40e1-48a7-9bd5-88122d8b4e2f\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0002-1765614603\",\"payment_type\":\"qris\",\"signature_key\":\"87fe025183817c0a0f95c571d3ef3bda92d867658dd75e4430811471d1f94d30f53d12fe179918ca26d547f92c2a0313bdbeee9029e250dd60a7984168ef2fe6\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 15:30:10\",\"settlement_time\":\"2025-12-13 15:30:24\",\"expiry_time\":\"2025-12-13 16:30:03\"}', 'qris', '2025-12-13 08:30:33', '2025-12-13 08:30:03', '2025-12-13 08:30:33'),
(29, 29, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-13 09:05:50', '397e68f8-11bd-42b6-9d18-0bbdd7bc9ba0', 'ORD-20251213-0003-1765616727', '{\"status_code\":\"200\",\"transaction_id\":\"397e68f8-11bd-42b6-9d18-0bbdd7bc9ba0\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0003-1765616727\",\"payment_type\":\"qris\",\"signature_key\":\"2b51e2e09752cac0050c4291da58abb4e832dd27c27ebfd000f759ef970cf5141f3167aae1d585a0573da984f1fef072d3feab52e5bd255e50360ef54f022320\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 16:05:31\",\"settlement_time\":\"2025-12-13 16:05:47\",\"expiry_time\":\"2025-12-13 17:05:27\"}', 'qris', '2025-12-13 09:05:50', '2025-12-13 09:05:27', '2025-12-13 09:05:50'),
(30, 30, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-13 09:16:20', 'd711851c-eeed-4fbb-88cc-0ef42eb8a3cf', 'ORD-20251213-0004-1765617356', '{\"status_code\":\"200\",\"transaction_id\":\"d711851c-eeed-4fbb-88cc-0ef42eb8a3cf\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0004-1765617356\",\"payment_type\":\"qris\",\"signature_key\":\"e6da4863982f3e7d38d8218e98e3a2fcc976904e0c2917b729fd4f80e3724dfa6e000d070aba6b7da7962bbb779a56e36d6718c52aca1d79a7c2f3c017b2a3df\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 16:16:00\",\"settlement_time\":\"2025-12-13 16:16:13\",\"expiry_time\":\"2025-12-13 17:15:56\"}', 'qris', '2025-12-13 09:16:20', '2025-12-13 09:15:56', '2025-12-13 09:16:20'),
(31, 31, 1, 22000.00, 'confirmed', NULL, NULL, '2025-12-13 09:22:34', '1e422f3e-db30-4f6f-acd8-2ff20c2daded', 'ORD-20251213-0005-1765617728', '{\"status_code\":\"200\",\"transaction_id\":\"1e422f3e-db30-4f6f-acd8-2ff20c2daded\",\"gross_amount\":\"22000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0005-1765617728\",\"payment_type\":\"qris\",\"signature_key\":\"66c282d2b60f7329b7bb24275622f6f10f4303f89fe298c85c77da5b3058dba0ba89d833fe94fa734f7e65644ee483f592868ef906c51e805fa5626594382435\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 16:22:12\",\"settlement_time\":\"2025-12-13 16:22:27\",\"expiry_time\":\"2025-12-13 17:22:08\"}', 'qris', '2025-12-13 09:22:34', '2025-12-13 09:22:08', '2025-12-13 09:22:34'),
(32, 32, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-13 11:58:18', '271607cb-eb75-45fe-8da7-9ceafe819d54', 'ORD-20251213-0006-1765627075', '{\"status_code\":\"200\",\"transaction_id\":\"271607cb-eb75-45fe-8da7-9ceafe819d54\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0006-1765627075\",\"payment_type\":\"qris\",\"signature_key\":\"f1cd4dda8c71e2d41ef02452439eaf388242387d6b2754409f777c349450f42bd62dbfd1cb044343e97fed4443572dbe8a9c9e29eb8de7c2aa4f676dc6808427\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 18:57:59\",\"settlement_time\":\"2025-12-13 18:58:12\",\"expiry_time\":\"2025-12-13 19:57:55\"}', 'qris', '2025-12-13 11:58:18', '2025-12-13 11:57:55', '2025-12-13 11:58:18'),
(33, 33, 1, 30000.00, 'confirmed', NULL, NULL, '2025-12-13 12:18:24', '5276e2cf-5a32-4652-8adb-68623c703219', 'ORD-20251213-0007-1765628276', '{\"status_code\":\"200\",\"transaction_id\":\"5276e2cf-5a32-4652-8adb-68623c703219\",\"gross_amount\":\"30000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0007-1765628276\",\"payment_type\":\"qris\",\"signature_key\":\"bd5266ebe5233e43196064a7f5c9011bcbbdbed778a24fe061a7d310da3644c45bffe278a01e0946cafa19bd8107f01021034e7d0e8549c9e5a91e848bccc8e0\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 19:18:05\",\"settlement_time\":\"2025-12-13 19:18:17\",\"expiry_time\":\"2025-12-13 20:17:56\"}', 'qris', '2025-12-13 12:18:24', '2025-12-13 12:17:56', '2025-12-13 12:18:24'),
(34, 34, 1, 43500.00, 'confirmed', NULL, NULL, '2025-12-13 13:55:07', '66e00a98-ba71-4906-9c47-cd8a75dba7af', 'ORD-20251213-0008-1765634083', '{\"status_code\":\"200\",\"transaction_id\":\"66e00a98-ba71-4906-9c47-cd8a75dba7af\",\"gross_amount\":\"43500.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251213-0008-1765634083\",\"payment_type\":\"qris\",\"signature_key\":\"e71355f4d9a8ab8ed41441bc68cb6e29c0a2b64bffce8531fce63779264b0683af513a1bf9eb44daa4b4365ca0c71bd5e0e2c4c3178a53ea37819dd5b1439749\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-13 20:54:48\",\"settlement_time\":\"2025-12-13 20:55:00\",\"expiry_time\":\"2025-12-13 21:54:43\"}', 'qris', '2025-12-13 13:55:07', '2025-12-13 13:54:43', '2025-12-13 13:55:07'),
(35, 35, 1, 25000.00, 'confirmed', NULL, NULL, '2025-12-16 05:09:52', 'b915dd82-735e-45b1-bad3-ab2be86a9111', 'ORD-20251216-0001-1765861748', '{\"status_code\":\"200\",\"transaction_id\":\"b915dd82-735e-45b1-bad3-ab2be86a9111\",\"gross_amount\":\"25000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0001-1765861748\",\"payment_type\":\"qris\",\"signature_key\":\"a66e8bea0539830bae5aee4d0d14d1c2cb3f83c53b7d7bf387b0463a5f0117f36541862eceb542d6f7882bc4aab4678b4512dbb0461968f287aabf3b2bf87d4b\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-16 12:09:25\",\"settlement_time\":\"2025-12-16 12:09:41\",\"expiry_time\":\"2025-12-16 13:09:08\"}', 'qris', '2025-12-16 05:09:52', '2025-12-16 05:09:08', '2025-12-16 05:09:52'),
(36, 36, 1, 44000.00, 'confirmed', NULL, NULL, '2025-12-16 05:37:52', '8bdd0c62-31ad-4177-a71b-8a84d91ee7a5', 'ORD-20251216-0002-1765863442', '{\"status_code\":\"200\",\"transaction_id\":\"8bdd0c62-31ad-4177-a71b-8a84d91ee7a5\",\"gross_amount\":\"44000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0002-1765863442\",\"payment_type\":\"qris\",\"signature_key\":\"a537fbe1475986f85c69fd71e7de3f26dd9ce9e51977ed30d08370e206bef5efcc7be8e381a99f901dfa5364cf6c2f8e95c303e37e38dbd0a48b900ec99d6eee\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-16 12:37:28\",\"settlement_time\":\"2025-12-16 12:37:42\",\"expiry_time\":\"2025-12-16 13:37:22\"}', 'qris', '2025-12-16 05:37:52', '2025-12-16 05:37:22', '2025-12-16 05:37:52'),
(37, 37, 1, 69000.00, 'confirmed', NULL, NULL, '2025-12-16 05:42:25', 'b52639e6-dc71-4c34-9fe6-d05393c1f4cc', 'ORD-20251216-0003-1765863714', '{\"status_code\":\"200\",\"transaction_id\":\"b52639e6-dc71-4c34-9fe6-d05393c1f4cc\",\"gross_amount\":\"69000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0003-1765863714\",\"payment_type\":\"qris\",\"signature_key\":\"c0c6d70964bf0aea154c0f75533d0439d2a6117b020c70f771a30b5af397162075fcc068cdf2e4b131e23f3fcfb3b7dfc11bd0bb0d51b1269f53615fe939f901\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-16 12:42:01\",\"settlement_time\":\"2025-12-16 12:42:13\",\"expiry_time\":\"2025-12-16 13:41:54\"}', 'qris', '2025-12-16 05:42:25', '2025-12-16 05:41:53', '2025-12-16 05:42:25'),
(38, 38, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-16 05:44:11', '3e513db0-ca2b-4315-9a5c-863f7e00126a', 'ORD-20251216-0004-1765863818', '{\"status_code\":\"200\",\"transaction_id\":\"3e513db0-ca2b-4315-9a5c-863f7e00126a\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0004-1765863818\",\"payment_type\":\"qris\",\"signature_key\":\"ceefb7519c064f77f90c0c2a609129d316919e8b36ca6427eed2c50f929bc66078008e3972b977d43c1919f8d45ee661343645851b76b849d3cb0655a0cb9393\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-16 12:43:41\",\"settlement_time\":\"2025-12-16 12:44:03\",\"expiry_time\":\"2025-12-16 13:43:38\"}', 'qris', '2025-12-16 05:44:11', '2025-12-16 05:43:38', '2025-12-16 05:44:11'),
(39, 39, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-16 05:46:18', 'cacccade-cdc0-4875-aa20-531446976550', 'ORD-20251216-0005-1765863956', '{\"status_code\":\"200\",\"transaction_id\":\"cacccade-cdc0-4875-aa20-531446976550\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0005-1765863956\",\"payment_type\":\"qris\",\"signature_key\":\"973f3f58b6c731b6113031f66bf46c0e4b72682a3787ba8476348730f5438e0ab516e99498a9eeeea0e32ad764090b8b8973073a71a4718ec638a52a3c04445f\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-16 12:45:59\",\"settlement_time\":\"2025-12-16 12:46:11\",\"expiry_time\":\"2025-12-16 13:45:56\"}', 'qris', '2025-12-16 05:46:18', '2025-12-16 05:45:56', '2025-12-16 05:46:18'),
(40, 40, 1, 44000.00, 'confirmed', NULL, NULL, '2025-12-16 05:49:00', '2d5c397b-8f9c-442e-a514-3ad9dbcf9da5', 'ORD-20251216-0006-1765864113', '{\"status_code\":\"200\",\"transaction_id\":\"2d5c397b-8f9c-442e-a514-3ad9dbcf9da5\",\"gross_amount\":\"44000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0006-1765864113\",\"payment_type\":\"qris\",\"signature_key\":\"71a680649e6ffda348c94d5d8ccac2449a78a3478e393c16a0f790a72bf2f737bedcfc55402514a76a574aa933f8b1a41af7f77214c8ff650669c53616a81321\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-12-16 12:48:37\",\"settlement_time\":\"2025-12-16 12:48:50\",\"expiry_time\":\"2025-12-16 13:48:33\"}', 'qris', '2025-12-16 05:49:00', '2025-12-16 05:48:33', '2025-12-16 05:49:00'),
(41, 41, 1, 58000.00, 'confirmed', NULL, NULL, '2025-12-16 06:02:56', 'f07f3d20-31bf-4cc7-981f-2beb458abea0', 'ORD-20251216-0007-1765864960', '{\"status_code\":\"200\",\"transaction_id\":\"f07f3d20-31bf-4cc7-981f-2beb458abea0\",\"gross_amount\":\"58000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0007-1765864960\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"141118825060179d92b1a6be5f46a0bd09688ada69f0a3139cf6924185b4e7ec5c25cfbd2ea6d95e983aa9e4d15d981b86c87a0e5221224ee18ca5ebc171723d\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257508944824497583772\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 13:02:45\",\"settlement_time\":\"2025-12-16 13:02:52\",\"expiry_time\":\"2025-12-16 14:02:40\"}', 'bank_transfer', '2025-12-16 06:02:56', '2025-12-16 06:00:23', '2025-12-16 06:02:56'),
(42, 42, 1, 41000.00, 'confirmed', NULL, NULL, '2025-12-16 06:11:43', 'c0dec167-e975-479c-a153-3c9301a8b458', 'ORD-20251216-0008-1765865481', '{\"status_code\":\"200\",\"transaction_id\":\"c0dec167-e975-479c-a153-3c9301a8b458\",\"gross_amount\":\"41000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0008-1765865481\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"5fa721afe2a20d51667e1c02d5344b26e32a6661b4688e19223cd2a598591df5137e33c624ed1dd10d6dc790d7ee3717403968b92b08e4427701517e8fa0b245\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257474416359667380778\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 13:11:29\",\"settlement_time\":\"2025-12-16 13:11:37\",\"expiry_time\":\"2025-12-16 14:11:21\"}', 'bank_transfer', '2025-12-16 06:11:43', '2025-12-16 06:11:21', '2025-12-16 06:11:43'),
(43, 43, 1, 39000.00, 'confirmed', NULL, NULL, '2025-12-16 06:35:39', '60194726-a165-4656-b4c3-a87f437b2977', 'ORD-20251216-0009-1765866918', '{\"status_code\":\"200\",\"transaction_id\":\"60194726-a165-4656-b4c3-a87f437b2977\",\"gross_amount\":\"39000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0009-1765866918\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"0e6ae8b72dbe3c04ff06a27bd8b4824dcc00ccbdfdb89e4dde24efd3e1f7c1b2a5c1d51eaa0cf86683e27ff9aed150f02fd20057d8175919248a8646283d4049\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257955172340033919861\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 13:35:24\",\"settlement_time\":\"2025-12-16 13:35:31\",\"expiry_time\":\"2025-12-16 14:35:18\"}', 'bank_transfer', '2025-12-16 06:35:39', '2025-12-16 06:35:18', '2025-12-16 06:35:39'),
(44, 44, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-16 08:21:45', '2c9f3626-3dda-49d9-91f8-15b76f5a9186', 'ORD-20251216-0010-1765873279', '{\"status_code\":\"200\",\"transaction_id\":\"2c9f3626-3dda-49d9-91f8-15b76f5a9186\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0010-1765873279\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"05866941e7008bf9a5b8b612244b88fd1cb6e4d3c8859e62ad81042a4525213e59f93be4cc2a55e7c0a82e417a85195f992ed99316a77e7187e9f53269d2d5fa\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257978706487526162720\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 15:21:27\",\"settlement_time\":\"2025-12-16 15:21:39\",\"expiry_time\":\"2025-12-16 16:21:19\"}', 'bank_transfer', '2025-12-16 08:21:45', '2025-12-16 08:21:19', '2025-12-16 08:21:45'),
(45, 45, 1, 91500.00, 'confirmed', NULL, NULL, '2025-12-16 09:59:29', 'ef20635a-643a-4b28-8b76-9503e44244a3', 'ORD-20251216-0011-1765879141', '{\"status_code\":\"200\",\"transaction_id\":\"ef20635a-643a-4b28-8b76-9503e44244a3\",\"gross_amount\":\"91500.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0011-1765879141\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"0dbd2634770161ca3fa30353c64dfc7c6e26ec9a8dedf9912f2b9d7ba7f653a516c57721536115d55156f58730cb9001c827dd5b619c239c06e4f99d33efb04f\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257544839738906441723\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 16:59:12\",\"settlement_time\":\"2025-12-16 16:59:23\",\"expiry_time\":\"2025-12-16 17:59:01\"}', 'bank_transfer', '2025-12-16 09:59:29', '2025-12-16 09:59:01', '2025-12-16 09:59:29'),
(46, 46, 1, 49000.00, 'confirmed', NULL, NULL, '2025-12-16 10:02:58', '63390b7e-79c1-43af-8966-9b99d93a6189', 'ORD-20251216-0012-1765879353', '{\"status_code\":\"200\",\"transaction_id\":\"63390b7e-79c1-43af-8966-9b99d93a6189\",\"gross_amount\":\"49000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0012-1765879353\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"730115b862c156beb29c5d35fa7f56102b30db9a1700b9e74ef7b125c43c642348084043146ca4b345f1abb29c9e9858e34bd0899a74b731f28631755d275e16\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257508231026652291387\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 17:02:39\",\"settlement_time\":\"2025-12-16 17:02:49\",\"expiry_time\":\"2025-12-16 18:02:33\"}', 'bank_transfer', '2025-12-16 10:02:58', '2025-12-16 10:02:33', '2025-12-16 10:02:58'),
(47, 47, 1, 40500.00, 'confirmed', NULL, NULL, '2025-12-16 10:05:55', 'd54f1170-e1ff-4814-8d82-7f8cac9f67d0', 'ORD-20251216-0013-1765879538', '{\"status_code\":\"200\",\"transaction_id\":\"d54f1170-e1ff-4814-8d82-7f8cac9f67d0\",\"gross_amount\":\"40500.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0013-1765879538\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"f5aa5fa697e391d6ed90713d0f91de93892f0762d6d3ab7b3458b8502ea8e64b48da808616eeac514bca3bad6f6484a6f1b756347d664f6ea92f7df5f48a9582\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257219739459658746029\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 17:05:43\",\"settlement_time\":\"2025-12-16 17:05:49\",\"expiry_time\":\"2025-12-16 18:05:38\"}', 'bank_transfer', '2025-12-16 10:05:55', '2025-12-16 10:05:38', '2025-12-16 10:05:55'),
(48, 48, 1, 41500.00, 'confirmed', NULL, NULL, '2025-12-16 10:09:43', 'dadc5651-44f9-40e9-ace3-91bb41cbbc68', 'ORD-20251216-0014-1765879768', '{\"status_code\":\"200\",\"transaction_id\":\"dadc5651-44f9-40e9-ace3-91bb41cbbc68\",\"gross_amount\":\"41500.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0014-1765879768\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"bed79a2a621f8ef630c203a3e8f7fd289e2fb094dace80f8c18b0e2cf856908bc2a249825754ba12940dbd2acb5a0c558a4c23147bcbb275270213bb10126faa\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257138274637015344598\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 17:09:32\",\"settlement_time\":\"2025-12-16 17:09:38\",\"expiry_time\":\"2025-12-16 18:09:28\"}', 'bank_transfer', '2025-12-16 10:09:43', '2025-12-16 10:09:28', '2025-12-16 10:09:43'),
(49, 49, 1, 41000.00, 'confirmed', NULL, NULL, '2025-12-16 10:30:18', '7219f3e8-534f-4f2b-a6ef-9dde13665fad', 'ORD-20251216-0015-1765880995', '{\"status_code\":\"200\",\"transaction_id\":\"7219f3e8-534f-4f2b-a6ef-9dde13665fad\",\"gross_amount\":\"41000.00\",\"currency\":\"IDR\",\"order_id\":\"ORD-20251216-0015-1765880995\",\"payment_type\":\"bank_transfer\",\"signature_key\":\"fc5a443a3069bec731d17b8860fda206e821222f0a2f2395e676ac81d78333ecf653c3c7587b7ff61626ff473780e0a94973b6ec2465c7750e3097a62f36cc04\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G791810257\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"10257725982502638110729\"}],\"payment_amounts\":[],\"transaction_time\":\"2025-12-16 17:29:59\",\"settlement_time\":\"2025-12-16 17:30:07\",\"expiry_time\":\"2025-12-16 18:29:55\"}', 'bank_transfer', '2025-12-16 10:30:18', '2025-12-16 10:29:55', '2025-12-16 10:30:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `type`, `account_number`, `account_name`, `instructions`, `logo`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Midtrans Payment Gateway', 'midtrans', NULL, NULL, 'Bayar aman dan otomatis melalui Midtrans (E-Wallet, Virtual Account, Kartu Kredit).', NULL, 1, '2025-11-12 08:01:59', '2025-11-12 08:01:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `supplier_name` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `purchases`
--

INSERT INTO `purchases` (`id`, `purchase_date`, `supplier_name`, `invoice_number`, `total_amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, '2025-11-13', 'Pasar', NULL, 10000.00, 'unpaid', 'Jatuh tempo 1-12-2025', '2025-11-12 23:07:13', '2025-11-12 23:07:13'),
(2, '2025-11-13', 'Pasar', NULL, 10000.00, 'paid', NULL, '2025-11-12 23:08:56', '2025-11-12 23:08:56'),
(3, '2025-11-14', 'Pasar', NULL, 20000.00, 'paid', NULL, '2025-11-13 20:52:29', '2025-11-13 20:52:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_details`
--

CREATE TABLE `purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'kg',
  `price_per_unit` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `purchase_details`
--

INSERT INTO `purchase_details` (`id`, `purchase_id`, `item_name`, `quantity`, `unit`, `price_per_unit`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ikan Tenggiri', 1.00, '1 kg', 10000.00, 10000.00, '2025-11-12 23:07:13', '2025-11-12 23:07:13'),
(2, 2, 'Ikan Tenggiri', 1.00, '1 kg', 10000.00, 10000.00, '2025-11-12 23:08:56', '2025-11-12 23:08:56'),
(3, 3, 'Ikan Tenggiri', 1.00, 'kg', 20000.00, 20000.00, '2025-11-13 20:52:29', '2025-11-13 20:52:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('uGsyiak0O70kU5nZYzGyeqIHuR5hHj8AUFWH6DpP', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.4 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.4 facebookexternalhit/1.1 Facebot Twitterbot/1.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFpVVkFvS002a1JXRmZTTUo1TFNrRm95RVFtbzJtVUJyTlFIQUhWRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1767679939),
('WtYiFzGVA6xFuz39YRq1UzK4X2uDiQXN3YqwsX3e', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN1RBdXBDdUQ5YU83QXZ3SEVEdTNKSXpPUmxkZ050MmxrdDBDenI0MyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1767682605);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `google_id`, `email_verified_at`, `password`, `role`, `phone`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin N-Kitchen', 'admin', 'admin@nkitchen.com', NULL, '2025-11-13 01:16:23', '$2y$12$GNnOE6Y5IW4.XuSIt19uauIlk9PzhbYwCz65l7q696ZrMU6K0nSt.', 'admin', '081234567890', 'Jl. Musi Palembang', NULL, '2025-11-12 08:01:59', '2025-11-12 08:01:59'),
(2, 'Surya Guntur', 'surya', 'suryaguntur2000@gmail.com', '111920585527392984708', '2025-11-13 01:23:05', '$2y$12$qr.0P3KPVPlSoiBTC3Ouh.9aShLJUAPqbef64QSL0giiHvh8Xfa6.', 'customer', '081987654321', 'Jl. Sudirman No. 123, Jakarta', 'Oo4FIhbiIrqJ4Afc1n5dJphQvDg62jjx8uuCRj5NlcHREeNgcWSpJlTkzRke', '2025-11-12 08:01:59', '2025-11-13 20:25:56'),
(3, 'Prince', 'prince257', 'rapbobs82@gmail.com', '103989965979670014000', '2025-11-13 01:16:23', NULL, 'admin', NULL, NULL, NULL, '2025-11-12 23:41:02', '2025-11-13 01:16:23');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indeks untuk tabel `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_code_unique` (`code`),
  ADD KEY `chart_of_accounts_parent_id_foreign` (`parent_id`);

--
-- Indeks untuk tabel `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_chart_of_account_id_foreign` (`chart_of_account_id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journals_referenceable_type_referenceable_id_index` (`referenceable_type`,`referenceable_id`);

--
-- Indeks untuk tabel `journal_transactions`
--
ALTER TABLE `journal_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_transactions_journal_id_foreign` (`journal_id`),
  ADD KEY `journal_transactions_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indeks untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menus_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_menu_id_foreign` (`menu_id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indeks untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_details_purchase_id_foreign` (`purchase_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `journals`
--
ALTER TABLE `journals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `journal_transactions`
--
ALTER TABLE `journal_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT untuk tabel `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `journal_transactions`
--
ALTER TABLE `journal_transactions`
  ADD CONSTRAINT `journal_transactions_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `journal_transactions_journal_id_foreign` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
