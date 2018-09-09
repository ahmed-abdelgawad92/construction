-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 09, 2018 at 05:32 PM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `construction`
--

-- --------------------------------------------------------

--
-- Table structure for table `advances`
--

CREATE TABLE `advances` (
  `id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED DEFAULT NULL,
  `company_employee_id` int(10) UNSIGNED DEFAULT NULL,
  `advance` double NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `payment_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `advances`
--

INSERT INTO `advances` (`id`, `employee_id`, `company_employee_id`, `advance`, `active`, `payment_at`, `created_at`, `updated_at`) VALUES
(4, NULL, 2, 300, '1', '2016-07-18 06:18:50', '2016-07-18 06:00:03', '2016-07-18 06:18:50'),
(5, 5, NULL, 300, '1', '2016-07-18 06:26:57', '2016-07-18 06:03:43', '2016-07-18 06:26:57'),
(7, 5, NULL, 300, '0', '0000-00-00 00:00:00', '2016-07-18 06:21:37', '2016-07-18 06:21:37'),
(8, NULL, 2, 500, '0', '0000-00-00 00:00:00', '2016-07-18 06:23:11', '2016-07-18 06:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `company_employees`
--

CREATE TABLE `company_employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `salary` double NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company_employees`
--

INSERT INTO `company_employees` (`id`, `name`, `job`, `salary`, `phone`, `address`, `village`, `city`, `updated_at`, `created_at`) VALUES
(2, 'خالد', 'غفير', 2500, '011112131311', '', '', 'أسيوط', '2016-07-15 16:26:31', '2016-07-15 16:26:31');

-- --------------------------------------------------------

--
-- Table structure for table `consumptions`
--

CREATE TABLE `consumptions` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `term_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `consumptions`
--

INSERT INTO `consumptions` (`id`, `type`, `amount`, `term_id`, `created_at`, `updated_at`) VALUES
(1, 'أسمنت', 500, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'حديد', 2000, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'أسمنت', 1000, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'حديد', 600, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'خشب', 100, 4, '2016-07-10 05:13:13', '2016-07-10 05:13:13'),
(7, 'حديد', 200, 4, '2016-07-10 05:19:12', '2016-07-10 05:19:12'),
(8, 'أسمنت', 300, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'أسمنت', 1000, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'حديد', 1000, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'حديد', 100, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'أسمنت', 500, 4, '2016-07-11 08:00:02', '2016-07-11 08:00:02'),
(13, 'حديد', 100, 4, '2016-07-11 08:18:27', '2016-07-11 08:18:27'),
(14, 'أسمنت', 200, 4, '2016-07-11 08:48:50', '2016-07-11 08:48:50'),
(15, 'أسمنت', 500, 3, '2016-07-11 23:29:40', '2016-07-11 23:29:40'),
(16, 'أسمنت', 500, 3, '2016-07-12 00:43:55', '2016-07-12 00:43:55'),
(17, 'أسمنت', 122, 7, '2016-07-20 07:21:20', '2016-07-20 07:21:20'),
(18, 'أسمنت', 50, 8, '2018-09-09 13:38:14', '2018-09-09 13:38:14'),
(19, 'حديد', 10, 8, '2018-09-09 14:16:29', '2018-09-09 14:16:29'),
(20, 'حديد', 10000, 8, '2018-09-09 14:23:47', '2018-09-09 14:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `center` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`id`, `name`, `address`, `center`, `city`, `phone`, `type`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'خالد أحمد', NULL, NULL, 'المنيا', '0111111111111', 'محارة', 4, '0000-00-00 00:00:00', '2016-06-13 13:52:22'),
(3, 'حسنين', '', '', 'أسيوط', '011112131311', 'محارة,خرسانة', NULL, '2016-06-19 02:23:23', '2016-06-19 02:23:23'),
(4, 'كمال', '', '', 'أسيوط', '011112131311', 'محارة,خرسانة', NULL, '2016-06-29 03:03:15', '2016-06-29 03:03:15'),
(5, 'حمادة', '', '', 'الجيزة', '011112131311', 'محارة , خرسانة', NULL, '2016-06-29 03:04:46', '2016-06-29 03:04:46'),
(6, 'حسنين', '', '', 'أسيوط', '011112131311', 'خرسانة , تشطيب ', NULL, '2016-06-29 03:22:41', '2016-06-29 03:22:41'),
(9, 'حمادة سياحة', '', '', 'أسيوط', '011112131311', 'خرسانة ,تشطيب', NULL, '2016-06-29 03:32:21', '2016-06-29 03:32:21'),
(10, 'حسن محمد', '', '', 'سوهاج', '011112131311', 'نقاشة,حدادة', NULL, '2016-06-29 03:51:54', '2016-06-29 03:51:54'),
(11, 'هشام', '', '', 'أسيوط', '011112131311', 'نقاشة,حدادة', NULL, '2016-06-29 03:55:46', '2016-06-29 03:55:46'),
(12, 'خالد', '', '', 'الجيزة', '011112131311', 'حدادة,محارة', NULL, '2016-06-29 04:04:42', '2016-06-29 04:05:19'),
(13, 'حمادة', '', '', 'أسيوط', '011112131311', 'نقاشة,حدادة', NULL, '2016-06-29 17:37:33', '2016-06-29 17:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `village` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `job`, `phone`, `address`, `village`, `city`, `created_at`, `updated_at`) VALUES
(5, 'حسن سليم', 'مهندس', '011212133333', '', '', 'أسيوط', '2016-07-15 13:40:05', '2016-07-15 13:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `employee_project`
--

CREATE TABLE `employee_project` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `salary` double NOT NULL,
  `done` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ended_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employee_project`
--

INSERT INTO `employee_project` (`id`, `project_id`, `employee_id`, `salary`, `done`, `ended_at`, `created_at`, `updated_at`) VALUES
(5, 25, 5, 2000, '0', NULL, '2016-07-15 16:12:21', '2016-07-15 16:42:53');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `whom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expense` double NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `whom`, `expense`, `project_id`, `created_at`, `updated_at`) VALUES
(2, 'الصنايعية', 200, 2, '2016-07-12 07:03:05', '2016-07-12 07:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `graphs`
--

CREATE TABLE `graphs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `table` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `record_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_05_06_195328_create_employees_table', 1),
('2016_05_06_195348_create_organizations_table', 1),
('2016_05_06_195605_create_projects_table', 1),
('2016_05_06_195649_create_stores_table', 1),
('2016_05_06_195739_create_productions_table', 1),
('2016_05_06_195826_create_consumptions_table', 1),
('2016_05_06_195850_create_taxes_table', 1),
('2016_05_06_195945_create_contractors_table', 1),
('2016_05_06_200121_create_labor_suppliers_table', 1),
('2016_05_06_200202_create_raw_suppliers_table', 1),
('2016_05_06_215428_create_advances_table', 1),
('2016_05_06_215937_create_employee_project_table', 1),
('2016_05_06_222022_create_logs_table', 1),
('2016_05_07_004925_create_terms_table', 1),
('2016_05_10_201410_create_graphs_table', 1),
('2016_05_10_201442_create_expenses_table', 1),
('2016_05_10_231715_create_db_relations', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(10) NOT NULL,
  `note` longtext NOT NULL,
  `term_id` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `center` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `address`, `center`, `city`, `phone`, `type`, `created_at`, `updated_at`) VALUES
(1, 'المحلفيين يسرى', 'يسرى راغب', 'أسيوط', 'أسيوط', '011112131311', '0', '2016-05-12 22:29:51', '2016-05-12 22:29:51'),
(2, 'خالد', '6 أكتوبر', 'أسيوط', '6 أكتوبر', '011112131311', '1', '2016-05-12 22:41:04', '2016-05-18 01:01:49'),
(3, 'اليونيسكو', 'جامعة الدول العربية', 'الجيزة', 'الجيزة', '01010101010101', '1', '2016-05-19 13:17:18', '2016-05-19 13:17:18'),
(4, 'حمادة', 'يسرى راغب', '', '', '', '1', '2016-05-23 22:46:18', '2016-05-23 22:46:46'),
(5, 'محمود', '', '', '', '', '0', '2016-05-26 14:44:18', '2016-05-26 14:44:18'),
(6, 'حسن', '', '', '', '', '1', '2016-05-26 14:45:01', '2016-05-26 14:45:01'),
(7, 'الخال بندرة', 'يسرى راغب', 'أسيوط', 'أسيوط ', '', '0', '2016-06-02 15:25:40', '2016-06-02 15:25:40');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` int(10) UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `rate` enum('1','2','3','4','5','6','7','8','9','10') COLLATE utf8_unicode_ci NOT NULL DEFAULT '5',
  `note` text COLLATE utf8_unicode_ci,
  `term_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `productions`
--

INSERT INTO `productions` (`id`, `amount`, `rate`, `note`, `term_id`, `created_at`, `updated_at`) VALUES
(2, 200, '8', NULL, 2, '2016-05-25 22:00:00', '2016-05-25 22:00:00'),
(3, 200, '8', '', 1, '2016-07-01 00:45:01', '2016-07-01 00:45:01'),
(26, 200, '9', '', 4, '2016-07-11 08:51:25', '2016-07-11 08:51:25'),
(27, 500, '9', '', 4, '2016-07-11 08:52:13', '2016-07-11 08:52:13'),
(28, 500, '10', '', 3, '2016-07-11 23:29:25', '2016-07-11 23:29:25'),
(29, 200, '8', '', 7, '2016-07-19 04:21:49', '2016-07-19 04:21:49'),
(30, 200, '10', '', 7, '2016-07-19 04:22:11', '2016-07-19 04:22:11'),
(31, 200, '9', '', 7, '2016-07-20 07:21:12', '2016-07-20 07:21:12'),
(32, 200, '10', '', 4, '2016-07-20 10:59:34', '2016-07-20 10:59:34'),
(33, 300, '8', '', 7, '2016-07-21 13:36:27', '2016-07-21 13:36:27'),
(34, 200, '8', NULL, 8, '2018-09-09 13:17:24', '2018-09-09 13:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `def_num` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `village` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `center` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model_used` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `implementing_period` int(3) DEFAULT NULL,
  `floor_num` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `organization_id` int(10) UNSIGNED NOT NULL,
  `non_organization_payment` double DEFAULT NULL,
  `approximate_price` double NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `started_at` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `def_num`, `address`, `village`, `center`, `city`, `extra_data`, `model_used`, `implementing_period`, `floor_num`, `organization_id`, `non_organization_payment`, `approximate_price`, `done`, `started_at`, `created_at`, `updated_at`) VALUES
(2, 'مجمع مبانى', 0, '', '', '', 'أسيوط', '', '', 0, '', 2, 5, 0, 0, '2016-07-03', '2016-05-20 16:43:06', '2016-07-19 04:14:22'),
(3, 'حمار', 0, '', '', '', '', '', '', 0, '', 2, 12, 0, 0, NULL, '2016-05-20 16:50:20', '2016-05-20 16:53:56'),
(20, 'أم على', 12121212, '6 أكتوبر', 'جهينا', 'وسط المدينة', 'أسيوط', '', '', 0, '', 1, NULL, 0, 0, '2016-08-15', '2016-05-20 22:03:14', '2016-05-20 22:05:35'),
(21, 'خرسانة', 0, '', '', '', '', '', '', 0, '', 4, NULL, 0, 0, '2016-11-14', '2016-05-23 23:00:36', '2016-05-23 23:00:36'),
(23, 'أساسات', 0, '', '', '', '', '', '', 0, '', 4, 0, 0, 0, '2016-07-31', '2016-05-24 10:55:15', '2016-05-24 11:14:11'),
(24, 'مدرسة الثانوية', 0, '', '', '', '', '', '', 0, '', 6, 10, 0, 0, NULL, '2016-05-26 14:46:31', '2016-05-26 14:47:36'),
(25, 'مدرسة الدويكات', 13121412, 'الكورنيش', '', '', 'الأقصر', '', '', 12, '4+ب+جراج', 7, NULL, 2000000, 0, '2016-06-14', '2016-06-30 01:20:15', '2016-06-30 01:20:15');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `value` double NOT NULL,
  `amount_paid` double DEFAULT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `type`, `amount`, `value`, `amount_paid`, `project_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(1, 'أسمنت', 2000, 50, 10000, 20, 1, '2016-07-10 07:20:15', '2016-07-10 07:20:15'),
(2, 'حديد', 4, 3400, 5000, 20, 1, '2016-07-10 07:21:05', '2016-07-10 07:21:05'),
(3, 'حديد', 4000, 3000, 1000000, 2, 1, '2016-07-11 08:18:09', '2016-07-11 08:18:09'),
(4, 'أسمنت', 5000, 50, 10000, 2, 1, '2016-07-11 08:48:39', '2016-07-11 08:48:39'),
(5, 'خشب', 11, 10, 10, 2, 2, '2018-09-09 14:11:20', '2018-09-09 14:11:20'),
(6, 'حديد', 100, 10000, 2000, 2, 1, '2018-09-09 14:12:13', '2018-09-09 14:12:13'),
(7, 'حديد', 100, 1000, 100, 2, 1, '2018-09-09 14:17:10', '2018-09-09 14:17:10'),
(8, 'حديد', 10000, 10000000, 1000, 2, 3, '2018-09-09 14:23:37', '2018-09-09 14:23:37');

-- --------------------------------------------------------

--
-- Table structure for table `store_types`
--

CREATE TABLE `store_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(30) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `store_types`
--

INSERT INTO `store_types` (`id`, `name`, `unit`, `updated_at`, `created_at`) VALUES
(1, 'أسمنت', 'كجم', '2016-06-29 01:17:03', '2016-06-29 01:17:03'),
(2, 'حديد', 'طن', '2016-06-29 01:22:05', '2016-06-29 01:22:05'),
(3, 'خشب', 'كجم', '2016-06-29 01:23:04', '2016-06-29 01:23:04');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `center` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `address`, `center`, `city`, `phone`, `type`, `user_id`, `updated_at`, `created_at`) VALUES
(1, 'حازم', '', '', 'أسيوط', '011112131311', 'أسمنت,حديد', NULL, '2016-06-29 17:55:56', '2016-06-29 17:55:56'),
(2, 'حسن', 'يسرى راغب', 'أسيوط', 'أسيوط', '0121212121212', 'خشب', NULL, '2016-07-12 02:28:13', '2016-07-12 02:28:13'),
(3, 'hamza', 'afafa', 'afadf', 'afdadf', '0111111111', 'أسمنت,حديد', NULL, '2018-09-09 14:10:12', '2018-09-09 14:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` double NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `name`, `percent`, `project_id`, `created_at`, `updated_at`) VALUES
(2, 'تعطيل', 2, 2, '2016-07-12 09:17:12', '2016-07-12 09:17:12'),
(3, 'نتانة', 3, 2, '2016-07-12 10:26:18', '2016-07-12 10:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `terms`
--

CREATE TABLE `terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `statement` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unit` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `value` double NOT NULL,
  `num_phases` int(2) NOT NULL DEFAULT '1',
  `deduction_percent` int(2) NOT NULL DEFAULT '0',
  `done` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `project_id` int(10) UNSIGNED NOT NULL,
  `contractor_id` int(10) UNSIGNED DEFAULT NULL,
  `contractor_unit_price` double DEFAULT NULL,
  `contract_text` longtext COLLATE utf8_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `type`, `code`, `statement`, `unit`, `amount`, `value`, `num_phases`, `deduction_percent`, `done`, `project_id`, `contractor_id`, `contractor_unit_price`, `contract_text`, `started_at`, `created_at`, `updated_at`) VALUES
(1, 'خرسانة', '5/09', 'اى ابن متناكة', 'متر2', 200, 1000, 1, 0, '1', 23, NULL, NULL, NULL, '2016-05-25 22:00:00', '2016-05-25 12:34:11', '2016-05-25 12:34:11'),
(2, 'خرسانة', '02/02/01/5', 'لمقالة نوع من الأدب، هي قطعة إنشائية، ذات طول معتدل تُكتب نثراً، وتُهتمُّ بالمظاهر الخارجية للموضوع بطريقة سهلةٍ سريعة، ولا تعنى إلا بالناحية التي تمسُّ الكاتب عن قرب. رأى النور في عصر النهضة الأوروبية، واتخذ مفهومه من محاولات التي أطلق عليها اسم لمقالة ن', '', 0, 0, 1, 0, '0', 23, NULL, NULL, NULL, NULL, '2016-05-25 12:38:29', '2016-05-25 12:38:29'),
(3, 'خرسانة', '30/44', 'شيىتيى نم شيةنش ةمشك شةيم يبش', 'متر2', 2000, 150, 1, 0, '0', 2, 3, 120, 'أنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حما', '2016-07-17 22:00:00', '2016-05-26 14:48:51', '2016-07-19 04:17:11'),
(4, 'نقاشة', '12/05/122', 'البنود المتعاقد عليهاالبنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليهاالبنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها', 'متر2', 1000, 100, 1, 0, '0', 2, NULL, 80, 'البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها نود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها البنود المتعاقد عليها', '2016-06-08 22:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'حدادة', '20/23', 'أنشاء مبانى أنشاء مستخلص تجريبى للمشروع حما أنشاء مستخلص تجريبى للمشروع حما أنشاء مستخلص تجريبى للمشروع حما', 'كجم', 2000, 50, 1, 0, '0', 2, 11, 30, 'أنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حما', '2016-07-18 22:00:00', '2016-07-19 04:11:17', '2016-07-19 04:21:02'),
(8, 'نقاشة', '01/20/30', 'أنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حماأنشاء مستخلص تجريبى للمشروع حما', 'متر2', 3000, 100, 1, 0, '0', 2, 11, 111, 'gghjhj', '2018-09-18 22:00:00', '2016-07-19 04:12:04', '2018-09-09 13:05:40');

-- --------------------------------------------------------

--
-- Table structure for table `term_types`
--

CREATE TABLE `term_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `term_types`
--

INSERT INTO `term_types` (`id`, `name`, `updated_at`, `created_at`) VALUES
(4, 'نقاشة', '2016-06-18 21:55:50', '2016-06-18 21:55:50'),
(5, 'حدادة', '2016-06-18 21:56:06', '2016-06-18 21:56:06'),
(6, 'محارة', '2016-06-19 02:09:08', '2016-06-19 02:09:08'),
(7, 'خرسانة', '2016-06-19 02:20:15', '2016-06-19 02:20:15'),
(8, 'تشطيب', '2016-06-29 02:09:32', '2016-06-29 02:09:32'),
(9, 'شرمطة', '2018-09-09 12:37:35', '2018-09-09 12:37:35'),
(10, 'ahmed', '2018-09-09 12:43:44', '2018-09-09 12:43:44'),
(11, 'aadd', '2018-09-09 12:44:52', '2018-09-09 12:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction` double NOT NULL,
  `type` enum('in','out') NOT NULL,
  `term_id` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction`, `type`, `term_id`, `updated_at`, `created_at`) VALUES
(4, 50000, 'in', 4, '2016-07-19 11:25:04', '2016-07-19 11:25:04'),
(5, 20000, 'in', 7, '2016-07-19 11:39:36', '2016-07-19 11:39:36'),
(6, 5000, 'in', 7, '2016-07-19 15:37:55', '2016-07-19 15:37:55'),
(7, 30000, 'in', 3, '2016-07-19 15:55:43', '2016-07-19 15:55:43'),
(8, 2000, 'out', 7, '2016-07-21 13:17:37', '0000-00-00 00:00:00'),
(9, 20000, 'in', 4, '2016-07-20 07:11:30', '2016-07-20 07:11:30'),
(10, 15000, 'in', 7, '2016-07-21 13:17:53', '2016-07-20 07:11:57'),
(11, 45000, 'in', 3, '2016-07-20 07:11:57', '2016-07-20 07:11:57'),
(12, 100000, 'in', 1, '2016-07-20 09:35:19', '2016-07-20 09:35:19'),
(13, 10000, 'in', 4, '2016-07-20 11:00:40', '2016-07-20 11:00:40'),
(14, 20000, 'in', 4, '2016-07-20 11:01:47', '2016-07-20 11:01:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('admin','contractor') COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `type`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ahmed', '$2y$10$bPMJVAJzqnzc.cHVnWK3GORgNz7G3brxKHaB6BFHCJ8xXmCVRF3y6', 'admin', 'd6f0XWXQlU70RLtf1svjEKnzzkRAgOA67XQbZAE1lBLKMR3sqTx98JAyCotH', '0000-00-00 00:00:00', '2016-06-13 12:23:38'),
(2, 'boxer', '$2y$10$k5Lf51DKVpF/cC8fiHZRouwpe7CD0cMOsGmp6M3Au4oZ/zndThXEm', 'admin', NULL, '2016-05-23 00:31:34', '2016-05-23 00:31:34'),
(3, 'abdo', '$2y$10$OMp1NlNrVWazXfd1Q18i2.YcJC84A66z0x3nZ3tDcrFwkzjamI9M6', 'contractor', 'BbiQQAaUJSywWEkxWaP4T2zjAJqNo7l1iawyxhiqX6NoU3h3sWaO6KgJjpLH', '2016-06-13 12:23:16', '2016-06-13 12:25:04'),
(4, 'khaled', '$2y$10$HD9IqrN4MvPWQoBEIBIlC.JW/63EyuwlpnI/JozeslfSLIcIGVwW.', 'contractor', NULL, '2016-06-13 13:52:22', '2016-06-13 13:52:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advances`
--
ALTER TABLE `advances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_employee_id` (`company_employee_id`),
  ADD KEY `advances_employee_project_id_foreign` (`employee_id`);

--
-- Indexes for table `company_employees`
--
ALTER TABLE `company_employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumptions`
--
ALTER TABLE `consumptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consumptions_term_id_foreign` (`term_id`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contractors_user_id_unique` (`user_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_project`
--
ALTER TABLE `employee_project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_project_employee_id_foreign` (`employee_id`),
  ADD KEY `employee_project_project_id_foreign` (`project_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_project_id_foreign` (`project_id`);

--
-- Indexes for table `graphs`
--
ALTER TABLE `graphs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `graphs_project_id_foreign` (`project_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `term_id` (`term_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_term_id_foreign` (`term_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_organization_id_foreign` (`organization_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stores_project_id_foreign` (`project_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `store_types`
--
ALTER TABLE `store_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taxes_project_id_foreign` (`project_id`);

--
-- Indexes for table `terms`
--
ALTER TABLE `terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `terms_project_id_foreign` (`project_id`),
  ADD KEY `terms_contractor_id_foreign` (`contractor_id`);

--
-- Indexes for table `term_types`
--
ALTER TABLE `term_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `term_id` (`term_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advances`
--
ALTER TABLE `advances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `company_employees`
--
ALTER TABLE `company_employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `consumptions`
--
ALTER TABLE `consumptions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_project`
--
ALTER TABLE `employee_project`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `graphs`
--
ALTER TABLE `graphs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `store_types`
--
ALTER TABLE `store_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `term_types`
--
ALTER TABLE `term_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advances`
--
ALTER TABLE `advances`
  ADD CONSTRAINT `advances_employee_project_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `advances_ibfk_1` FOREIGN KEY (`company_employee_id`) REFERENCES `company_employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `consumptions`
--
ALTER TABLE `consumptions`
  ADD CONSTRAINT `consumptions_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contractors`
--
ALTER TABLE `contractors`
  ADD CONSTRAINT `contractors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_project`
--
ALTER TABLE `employee_project`
  ADD CONSTRAINT `employee_project_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_project_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `graphs`
--
ALTER TABLE `graphs`
  ADD CONSTRAINT `graphs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `taxes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `terms`
--
ALTER TABLE `terms`
  ADD CONSTRAINT `terms_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `contractors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `terms_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
