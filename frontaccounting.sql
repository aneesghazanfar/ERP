-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2023 at 05:39 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `frontaccounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `0_areas`
--

CREATE TABLE `0_areas` (
  `area_code` int(11) NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_areas`
--

INSERT INTO `0_areas` (`area_code`, `description`, `inactive`) VALUES
(1, 'Global', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_attachments`
--

CREATE TABLE `0_attachments` (
  `id` int(11) UNSIGNED NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type_no` int(11) NOT NULL DEFAULT 0,
  `trans_no` int(11) NOT NULL DEFAULT 0,
  `unique_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `filename` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT 0,
  `filetype` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_audit_trail`
--

CREATE TABLE `0_audit_trail` (
  `id` int(11) NOT NULL,
  `type` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `trans_no` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `stamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL DEFAULT 0,
  `gl_date` date NOT NULL DEFAULT '0000-00-00',
  `gl_seq` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_audit_trail`
--

INSERT INTO `0_audit_trail` (`id`, `type`, `trans_no`, `user`, `stamp`, `description`, `fiscal_year`, `gl_date`, `gl_seq`) VALUES
(1, 18, 1, 1, '2021-05-05 09:08:02', NULL, 1, '2021-05-05', 0),
(2, 25, 1, 1, '2021-05-05 09:08:14', NULL, 1, '2021-05-05', 1),
(3, 30, 1, 1, '2021-05-05 09:09:54', NULL, 1, '2021-05-10', 0),
(4, 13, 1, 1, '2021-05-05 09:09:55', NULL, 1, '2021-05-10', 13),
(5, 10, 1, 1, '2021-05-05 09:09:55', NULL, 1, '2021-05-10', 14),
(6, 12, 1, 1, '2021-05-05 09:09:55', NULL, 1, '2021-05-10', 15),
(7, 29, 1, 1, '2021-05-05 09:18:49', 'Quick production.', 1, '2021-05-05', 2),
(8, 18, 2, 1, '2021-05-05 09:22:32', NULL, 1, '2021-05-05', 0),
(9, 25, 2, 1, '2021-05-05 09:22:32', NULL, 1, '2021-05-05', 3),
(10, 20, 1, 1, '2021-05-05 09:22:32', NULL, 1, '2021-05-05', 4),
(11, 30, 2, 1, '2021-05-07 02:55:15', NULL, 1, '2021-05-07', 0),
(12, 13, 2, 1, '2021-05-07 02:55:16', NULL, 1, '2021-05-07', 7),
(13, 10, 2, 1, '2021-05-07 02:55:16', NULL, 1, '2021-05-07', 8),
(14, 12, 2, 1, '2021-05-07 02:55:16', NULL, 1, '2021-05-07', 9),
(15, 30, 3, 1, '2021-05-07 03:08:24', NULL, 1, '2021-05-07', 0),
(16, 30, 4, 1, '2021-05-07 04:18:44', NULL, 1, '2021-05-07', 0),
(17, 30, 5, 1, '2021-05-07 06:42:41', NULL, 1, '2021-05-07', 0),
(18, 13, 3, 1, '2021-05-07 06:42:41', NULL, 1, '2021-05-07', 10),
(19, 10, 3, 1, '2021-05-07 06:42:41', NULL, 1, '2021-05-07', 11),
(20, 30, 6, 1, '2021-05-07 09:02:35', NULL, 1, '2021-05-07', 0),
(21, 30, 7, 1, '2021-05-07 09:05:38', NULL, 1, '2021-05-07', 0),
(22, 13, 4, 1, '2021-05-07 09:05:38', NULL, 1, '2021-05-07', 0),
(23, 10, 4, 1, '2021-05-07 09:05:38', NULL, 1, '2021-05-07', 0),
(24, 12, 3, 1, '2021-05-07 09:05:38', NULL, 1, '2021-05-07', 0),
(25, 26, 1, 1, '2021-05-07 10:59:34', NULL, 1, '2021-05-07', NULL),
(26, 29, 1, 1, '2021-05-07 10:59:01', 'Production.', 1, '2021-05-07', 5),
(27, 26, 1, 1, '2021-05-07 10:59:34', 'Released.', 1, '2021-05-07', 6),
(28, 1, 1, 1, '2021-05-07 11:01:00', NULL, 1, '2021-05-07', 12),
(29, 30, 8, 1, '2022-01-21 06:13:06', NULL, 2, '2022-01-21', 0),
(30, 13, 5, 1, '2022-01-21 06:13:06', NULL, 2, '2022-01-21', 0),
(31, 10, 5, 1, '2022-01-21 06:13:06', NULL, 2, '2022-01-21', 0),
(32, 12, 4, 1, '2022-01-21 06:13:06', NULL, 2, '2022-01-21', 0),
(33, 18, 3, 1, '2022-01-21 06:14:14', NULL, 2, '2022-01-21', 0),
(34, 25, 3, 1, '2022-01-21 06:14:14', NULL, 2, '2022-01-21', 0),
(35, 20, 2, 1, '2022-01-21 06:14:14', NULL, 2, '2022-01-21', 0),
(36, 0, 1, 1, '2022-01-21 06:15:35', NULL, 1, '2021-12-31', 16),
(37, 18, 4, 1, '2023-02-13 04:36:54', '', 2, '2022-12-31', 0),
(38, 18, 5, 1, '2023-02-13 04:37:36', '', 2, '2022-12-31', 0),
(39, 30, 9, 1, '2023-02-14 11:06:10', '', 2, '2022-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_accounts`
--

CREATE TABLE `0_bank_accounts` (
  `account_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_type` smallint(6) NOT NULL DEFAULT 0,
  `bank_account_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bank_account_number` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bank_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bank_address` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_curr_code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_curr_act` tinyint(1) NOT NULL DEFAULT 0,
  `id` smallint(6) NOT NULL,
  `bank_charge_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `last_reconciled_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ending_reconcile_balance` double NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_bank_accounts`
--

INSERT INTO `0_bank_accounts` (`account_code`, `account_type`, `bank_account_name`, `bank_account_number`, `bank_name`, `bank_address`, `bank_curr_code`, `dflt_curr_act`, `id`, `bank_charge_act`, `last_reconciled_date`, `ending_reconcile_balance`, `inactive`) VALUES
('1060', 0, 'Current account', 'N/A', 'N/A', NULL, 'USD', 1, 1, '5690', '0000-00-00 00:00:00', 0, 0),
('1065', 3, 'Petty Cash account', 'N/A', 'N/A', NULL, 'USD', 0, 2, '5690', '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_bank_trans`
--

CREATE TABLE `0_bank_trans` (
  `id` int(11) NOT NULL,
  `type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `bank_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ref` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `amount` double DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT 0,
  `dimension2_id` int(11) NOT NULL DEFAULT 0,
  `person_type_id` int(11) NOT NULL DEFAULT 0,
  `person_id` tinyblob DEFAULT NULL,
  `reconciled` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_bank_trans`
--

INSERT INTO `0_bank_trans` (`id`, `type`, `trans_no`, `bank_act`, `ref`, `trans_date`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`, `reconciled`) VALUES
(1, 12, 1, '2', '001/2021', '2021-05-10', 6240, 0, 0, 2, 0x31, NULL),
(2, 12, 2, '2', '002/2021', '2021-05-07', 300, 0, 0, 2, 0x31, NULL),
(3, 12, 3, '2', '003/2021', '2021-05-07', 0, 0, 0, 2, 0x31, NULL),
(4, 1, 1, '1', '001/2021', '2021-05-07', -5, 0, 0, 0, 0x476f6f6473207265636569766564, NULL),
(5, 12, 4, '2', '001/2022', '2022-01-21', 1250, 0, 0, 2, 0x31, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_bom`
--

CREATE TABLE `0_bom` (
  `id` int(11) NOT NULL,
  `parent` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `component` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `workcentre_added` int(11) NOT NULL DEFAULT 0,
  `loc_code` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_bom`
--

INSERT INTO `0_bom` (`id`, `parent`, `component`, `workcentre_added`, `loc_code`, `quantity`) VALUES
(1, '201', '101', 1, 'DEF', 1),
(2, '201', '102', 1, 'DEF', 1),
(3, '201', '103', 1, 'DEF', 1),
(4, '201', '301', 1, 'DEF', 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_budget_trans`
--

CREATE TABLE `0_budget_trans` (
  `id` int(11) NOT NULL,
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `memo_` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `dimension_id` int(11) DEFAULT 0,
  `dimension2_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_class`
--

CREATE TABLE `0_chart_class` (
  `cid` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `class_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ctype` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_chart_class`
--

INSERT INTO `0_chart_class` (`cid`, `class_name`, `ctype`, `inactive`) VALUES
('1', 'Assets', 1, 0),
('2', 'Liabilities', 2, 0),
('3', 'Income', 4, 0),
('4', 'Costs', 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_master`
--

CREATE TABLE `0_chart_master` (
  `account_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_code2` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `account_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_chart_master`
--

INSERT INTO `0_chart_master` (`account_code`, `account_code2`, `account_name`, `account_type`, `inactive`) VALUES
('1060', '', 'Checking Account', '1', 0),
('1065', '', 'Petty Cash', '1', 0),
('1200', '', 'Accounts Receivables', '1', 0),
('1205', '', 'Allowance for doubtful accounts', '1', 0),
('1510', '', 'Inventory', '2', 0),
('1520', '', 'Stocks of Raw Materials', '2', 0),
('1530', '', 'Stocks of Work In Progress', '2', 0),
('1540', '', 'Stocks of Finished Goods', '2', 0),
('1550', '', 'Goods Received Clearing account', '2', 0),
('1820', '', 'Office Furniture &amp; Equipment', '3', 0),
('1825', '', 'Accum. Amort. -Furn. &amp; Equip.', '3', 0),
('1840', '', 'Vehicle', '3', 0),
('1845', '', 'Accum. Amort. -Vehicle', '3', 0),
('2100', '', 'Accounts Payable', '4', 0),
('2105', '', 'Deferred Income', '4', 0),
('2110', '', 'Accrued Income Tax - Federal', '4', 0),
('2120', '', 'Accrued Income Tax - State', '4', 0),
('2130', '', 'Accrued Franchise Tax', '4', 0),
('2140', '', 'Accrued Real &amp; Personal Prop Tax', '4', 0),
('2150', '', 'Sales Tax', '4', 0),
('2160', '', 'Accrued Use Tax Payable', '4', 0),
('2210', '', 'Accrued Wages', '4', 0),
('2220', '', 'Accrued Comp Time', '4', 0),
('2230', '', 'Accrued Holiday Pay', '4', 0),
('2240', '', 'Accrued Vacation Pay', '4', 0),
('2310', '', 'Accr. Benefits - 401K', '4', 0),
('2320', '', 'Accr. Benefits - Stock Purchase', '4', 0),
('2330', '', 'Accr. Benefits - Med, Den', '4', 0),
('2340', '', 'Accr. Benefits - Payroll Taxes', '4', 0),
('2350', '', 'Accr. Benefits - Credit Union', '4', 0),
('2360', '', 'Accr. Benefits - Savings Bond', '4', 0),
('2370', '', 'Accr. Benefits - Garnish', '4', 0),
('2380', '', 'Accr. Benefits - Charity Cont.', '4', 0),
('2620', '', 'Bank Loans', '5', 0),
('2680', '', 'Loans from Shareholders', '5', 0),
('3350', '', 'Common Shares', '6', 0),
('3590', '', 'Retained Earnings - prior years', '7', 0),
('4010', '', 'Sales', '8', 0),
('4430', '', 'Shipping &amp; Handling', '9', 0),
('4440', '', 'Interest', '9', 0),
('4450', '', 'Foreign Exchange Gain', '9', 0),
('4500', '', 'Prompt Payment Discounts', '9', 0),
('4510', '', 'Discounts Given', '9', 0),
('5010', '', 'Cost of Goods Sold - Retail', '10', 0),
('5020', '', 'Material Usage Varaiance', '10', 0),
('5030', '', 'Consumable Materials', '10', 0),
('5040', '', 'Purchase price Variance', '10', 0),
('5050', '', 'Purchases of materials', '10', 0),
('5060', '', 'Discounts Received', '10', 0),
('5100', '', 'Freight', '10', 0),
('5410', '', 'Wages &amp; Salaries', '11', 0),
('5420', '', 'Wages - Overtime', '11', 0),
('5430', '', 'Benefits - Comp Time', '11', 0),
('5440', '', 'Benefits - Payroll Taxes', '11', 0),
('5450', '', 'Benefits - Workers Comp', '11', 0),
('5460', '', 'Benefits - Pension', '11', 0),
('5470', '', 'Benefits - General Benefits', '11', 0),
('5510', '', 'Inc Tax Exp - Federal', '11', 0),
('5520', '', 'Inc Tax Exp - State', '11', 0),
('5530', '', 'Taxes - Real Estate', '11', 0),
('5540', '', 'Taxes - Personal Property', '11', 0),
('5550', '', 'Taxes - Franchise', '11', 0),
('5560', '', 'Taxes - Foreign Withholding', '11', 0),
('5610', '', 'Accounting &amp; Legal', '12', 0),
('5615', '', 'Advertising &amp; Promotions', '12', 0),
('5620', '', 'Bad Debts', '12', 0),
('5660', '', 'Amortization Expense', '12', 0),
('5685', '', 'Insurance', '12', 0),
('5690', '', 'Interest &amp; Bank Charges', '12', 0),
('5700', '', 'Office Supplies', '12', 0),
('5760', '', 'Rent', '12', 0),
('5765', '', 'Repair &amp; Maintenance', '12', 0),
('5780', '', 'Telephone', '12', 0),
('5785', '', 'Travel &amp; Entertainment', '12', 0),
('5790', '', 'Utilities', '12', 0),
('5795', '', 'Registrations', '12', 0),
('5800', '', 'Licenses', '12', 0),
('5810', '', 'Foreign Exchange Loss', '12', 0),
('9990', '', 'Year Profit/Loss', '12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_chart_types`
--

CREATE TABLE `0_chart_types` (
  `id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `class_id` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parent` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-1',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_chart_types`
--

INSERT INTO `0_chart_types` (`id`, `name`, `class_id`, `parent`, `inactive`) VALUES
('1', 'Current Assets', '1', '-1', 0),
('10', 'Cost of Goods Sold', '4', '-1', 0),
('11', 'Payroll Expenses', '4', '-1', 0),
('12', 'General &amp; Administrative expenses', '4', '-1', 0),
('2', 'Inventory Assets', '1', '-1', 0),
('3', 'Capital Assets', '1', '-1', 0),
('4', 'Current Liabilities', '2', '-1', 0),
('5', 'Long Term Liabilities', '2', '-1', 0),
('6', 'Share Capital', '2', '-1', 0),
('7', 'Retained Earnings', '2', '-1', 0),
('8', 'Sales Revenue', '3', '-1', 0),
('9', 'Other Revenue', '3', '-1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_comments`
--

CREATE TABLE `0_comments` (
  `type` int(11) NOT NULL DEFAULT 0,
  `id` int(11) NOT NULL DEFAULT 0,
  `date_` date DEFAULT '0000-00-00',
  `memo_` tinytext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_comments`
--

INSERT INTO `0_comments` (`type`, `id`, `date_`, `memo_`) VALUES
(12, 1, '2021-05-10', 'Cash invoice 1'),
(12, 2, '2021-05-07', 'Cash invoice 2'),
(13, 4, '2021-05-07', 'Recurrent Invoice covers period 04/01/2021 - 04/07/2021.'),
(10, 4, '2021-05-07', 'Recurrent Invoice covers period 04/01/2021 - 04/07/2021.'),
(12, 3, '2021-05-07', 'Cash invoice 4'),
(12, 4, '2022-01-21', 'Default #5'),
(0, 1, '2021-12-31', 'Closing Year');

-- --------------------------------------------------------

--
-- Table structure for table `0_credit_status`
--

CREATE TABLE `0_credit_status` (
  `id` int(11) NOT NULL,
  `reason_description` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dissallow_invoices` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_credit_status`
--

INSERT INTO `0_credit_status` (`id`, `reason_description`, `dissallow_invoices`, `inactive`) VALUES
(1, 'Good History', 0, 0),
(3, 'No more work until payment received', 1, 0),
(4, 'In liquidation', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_crm_categories`
--

CREATE TABLE `0_crm_categories` (
  `id` int(11) NOT NULL COMMENT 'pure technical key',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contact type e.g. customer',
  `action` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'detailed usage e.g. department',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for category selector',
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL COMMENT 'usage description',
  `system` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'nonzero for core system usage',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_crm_categories`
--

INSERT INTO `0_crm_categories` (`id`, `type`, `action`, `name`, `description`, `system`, `inactive`) VALUES
(1, 'cust_branch', 'general', 'General', 'General contact data for customer branch (overrides company setting)', 1, 0),
(2, 'cust_branch', 'invoice', 'Invoices', 'Invoice posting (overrides company setting)', 1, 0),
(3, 'cust_branch', 'order', 'Orders', 'Order confirmation (overrides company setting)', 1, 0),
(4, 'cust_branch', 'delivery', 'Deliveries', 'Delivery coordination (overrides company setting)', 1, 0),
(5, 'customer', 'general', 'General', 'General contact data for customer', 1, 0),
(6, 'customer', 'order', 'Orders', 'Order confirmation', 1, 0),
(7, 'customer', 'delivery', 'Deliveries', 'Delivery coordination', 1, 0),
(8, 'customer', 'invoice', 'Invoices', 'Invoice posting', 1, 0),
(9, 'supplier', 'general', 'General', 'General contact data for supplier', 1, 0),
(10, 'supplier', 'order', 'Orders', 'Order confirmation', 1, 0),
(11, 'supplier', 'delivery', 'Deliveries', 'Delivery coordination', 1, 0),
(12, 'supplier', 'invoice', 'Invoices', 'Invoice posting', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_crm_contacts`
--

CREATE TABLE `0_crm_contacts` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL DEFAULT 0 COMMENT 'foreign key to crm_persons',
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'foreign key to crm_categories',
  `action` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'foreign key to crm_categories',
  `entity_id` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'entity id in related class table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_crm_contacts`
--

INSERT INTO `0_crm_contacts` (`id`, `person_id`, `type`, `action`, `entity_id`) VALUES
(4, 2, 'supplier', 'general', '2'),
(5, 3, 'cust_branch', 'general', '1'),
(7, 3, 'customer', 'general', '1'),
(8, 1, 'supplier', 'general', '1'),
(9, 4, 'cust_branch', 'general', '2'),
(10, 4, 'customer', 'general', '2');

-- --------------------------------------------------------

--
-- Table structure for table `0_crm_persons`
--

CREATE TABLE `0_crm_persons` (
  `id` int(11) NOT NULL,
  `ref` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name2` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone2` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_crm_persons`
--

INSERT INTO `0_crm_persons` (`id`, `ref`, `name`, `name2`, `address`, `phone`, `phone2`, `fax`, `email`, `lang`, `notes`, `inactive`) VALUES
(1, 'Dino Saurius', 'John Doe', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, '', 0),
(2, 'Beefeater', 'Joe Oversea', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, '', 0),
(3, 'Donald Easter', 'Donald Easter LLC', NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, '', 0),
(4, 'MoneyMaker', 'MoneyMaker Ltd.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_currencies`
--

CREATE TABLE `0_currencies` (
  `currency` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `curr_abrev` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `curr_symbol` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `hundreds_name` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `auto_update` tinyint(1) NOT NULL DEFAULT 1,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_currencies`
--

INSERT INTO `0_currencies` (`currency`, `curr_abrev`, `curr_symbol`, `country`, `hundreds_name`, `auto_update`, `inactive`) VALUES
('CA Dollars', 'CAD', '$', 'Canada', 'Cents', 1, 0),
('Euro', 'EUR', '€', 'Europe', 'Cents', 1, 0),
('Pounds', 'GBP', '£', 'England', 'Pence', 1, 0),
('US Dollars', 'USD', '$', 'United States', 'Cents', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_cust_allocations`
--

CREATE TABLE `0_cust_allocations` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `amt` double UNSIGNED DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_cust_allocations`
--

INSERT INTO `0_cust_allocations` (`id`, `person_id`, `amt`, `date_alloc`, `trans_no_from`, `trans_type_from`, `trans_no_to`, `trans_type_to`) VALUES
(1, 1, 6240, '2021-05-10', 1, 12, 1, 10),
(2, 1, 300, '2021-05-07', 2, 12, 2, 10),
(3, 1, 0, '2021-05-07', 3, 12, 4, 10),
(4, 1, 1250, '2022-01-21', 4, 12, 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `0_cust_branch`
--

CREATE TABLE `0_cust_branch` (
  `branch_code` int(11) NOT NULL,
  `debtor_no` int(11) NOT NULL DEFAULT 0,
  `br_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `branch_ref` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `br_address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `area` int(11) DEFAULT NULL,
  `salesman` int(11) NOT NULL DEFAULT 0,
  `default_location` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_group_id` int(11) DEFAULT NULL,
  `sales_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sales_discount_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `receivables_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_discount_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `default_ship_via` int(11) NOT NULL DEFAULT 1,
  `br_post_address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `group_no` int(11) NOT NULL DEFAULT 0,
  `notes` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `bank_account` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_cust_branch`
--

INSERT INTO `0_cust_branch` (`branch_code`, `debtor_no`, `br_name`, `branch_ref`, `br_address`, `area`, `salesman`, `default_location`, `tax_group_id`, `sales_account`, `sales_discount_account`, `receivables_account`, `payment_discount_account`, `default_ship_via`, `br_post_address`, `group_no`, `notes`, `bank_account`, `inactive`) VALUES
(1, 1, 'Donald Easter LLC', 'Donald Easter', 'N/A', 1, 1, 'DEF', 1, '', '4510', '1200', '4500', 1, 'N/A', 0, '', NULL, 0),
(2, 2, 'MoneyMaker Ltd.', 'MoneyMaker', '', 1, 1, 'DEF', 2, '', '4510', '1200', '4500', 1, '', 0, '', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_debtors_master`
--

CREATE TABLE `0_debtors_master` (
  `debtor_no` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `debtor_ref` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_id` varchar(55) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `curr_code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sales_type` int(11) NOT NULL DEFAULT 1,
  `dimension_id` int(11) NOT NULL DEFAULT 0,
  `dimension2_id` int(11) NOT NULL DEFAULT 0,
  `credit_status` int(11) NOT NULL DEFAULT 0,
  `payment_terms` int(11) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT 0,
  `pymt_discount` double NOT NULL DEFAULT 0,
  `credit_limit` float NOT NULL DEFAULT 1000,
  `notes` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_debtors_master`
--

INSERT INTO `0_debtors_master` (`debtor_no`, `name`, `debtor_ref`, `address`, `tax_id`, `curr_code`, `sales_type`, `dimension_id`, `dimension2_id`, `credit_status`, `payment_terms`, `discount`, `pymt_discount`, `credit_limit`, `notes`, `inactive`) VALUES
(1, 'Donald Easter LLC', 'Donald Easter', 'N/A', '123456789', 'USD', 1, 0, 0, 1, 4, 0, 0, 1000, '', 0),
(2, 'MoneyMaker Ltd.', 'MoneyMaker', 'N/A', '54354333', 'EUR', 1, 1, 0, 1, 1, 0, 0, 1000, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_debtor_trans`
--

CREATE TABLE `0_debtor_trans` (
  `trans_no` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `type` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `version` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `debtor_no` int(11) UNSIGNED NOT NULL,
  `branch_code` int(11) NOT NULL DEFAULT -1,
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tpe` int(11) NOT NULL DEFAULT 0,
  `order_` int(11) NOT NULL DEFAULT 0,
  `ov_amount` double NOT NULL DEFAULT 0,
  `ov_gst` double NOT NULL DEFAULT 0,
  `ov_freight` double NOT NULL DEFAULT 0,
  `ov_freight_tax` double NOT NULL DEFAULT 0,
  `ov_discount` double NOT NULL DEFAULT 0,
  `alloc` double NOT NULL DEFAULT 0,
  `prep_amount` double NOT NULL DEFAULT 0,
  `rate` double NOT NULL DEFAULT 1,
  `ship_via` int(11) DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT 0,
  `dimension2_id` int(11) NOT NULL DEFAULT 0,
  `payment_terms` int(11) DEFAULT NULL,
  `tax_included` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_debtor_trans`
--

INSERT INTO `0_debtor_trans` (`trans_no`, `type`, `version`, `debtor_no`, `branch_code`, `tran_date`, `due_date`, `reference`, `tpe`, `order_`, `ov_amount`, `ov_gst`, `ov_freight`, `ov_freight_tax`, `ov_discount`, `alloc`, `prep_amount`, `rate`, `ship_via`, `dimension_id`, `dimension2_id`, `payment_terms`, `tax_included`) VALUES
(1, 10, 0, 1, 1, '2021-05-10', '2021-05-05', '001/2021', 1, 1, 6240, 0, 0, 0, 0, 6240, 0, 1, 1, 0, 0, 4, 1),
(2, 10, 0, 1, 1, '2021-05-07', '2021-05-07', '002/2021', 1, 2, 300, 0, 0, 0, 0, 300, 0, 1, 1, 0, 0, 4, 1),
(3, 10, 0, 2, 2, '2021-05-07', '2021-06-17', '003/2021', 1, 5, 267.14, 0, 0, 0, 0, 0, 0, 1.123, 1, 1, 0, 1, 1),
(4, 10, 0, 1, 1, '2021-05-07', '2021-05-07', '004/2021', 1, 7, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 1),
(5, 10, 0, 1, 1, '2022-01-21', '2022-01-21', '001/2022', 1, 8, 1250, 0, 0, 0, 0, 1250, 0, 1, 1, 0, 0, 4, 1),
(1, 12, 0, 1, 1, '2021-05-10', '0000-00-00', '001/2021', 0, 0, 6240, 0, 0, 0, 0, 6240, 0, 1, 0, 0, 0, NULL, 0),
(2, 12, 0, 1, 1, '2021-05-07', '0000-00-00', '002/2021', 0, 0, 300, 0, 0, 0, 0, 300, 0, 1, 0, 0, 0, NULL, 0),
(3, 12, 0, 1, 1, '2021-05-07', '0000-00-00', '003/2021', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, NULL, 0),
(4, 12, 0, 1, 1, '2022-01-21', '0000-00-00', '001/2022', 0, 0, 1250, 0, 0, 0, 0, 1250, 0, 1, 0, 0, 0, NULL, 0),
(1, 13, 1, 1, 1, '2021-05-10', '2021-05-05', 'auto', 1, 1, 6240, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 1),
(2, 13, 1, 1, 1, '2021-05-07', '2021-05-07', 'auto', 1, 2, 300, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 1),
(3, 13, 1, 2, 2, '2021-05-07', '2021-06-17', 'auto', 1, 5, 267.14, 0, 0, 0, 0, 0, 0, 1.123, 1, 1, 0, 1, 1),
(4, 13, 1, 1, 1, '2021-05-07', '2021-05-07', 'auto', 1, 7, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 1),
(5, 13, 1, 1, 1, '2022-01-21', '2022-01-21', 'auto', 1, 8, 1250, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_debtor_trans_details`
--

CREATE TABLE `0_debtor_trans_details` (
  `id` int(11) NOT NULL,
  `debtor_trans_no` int(11) DEFAULT NULL,
  `debtor_trans_type` int(11) DEFAULT NULL,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_price` double NOT NULL DEFAULT 0,
  `unit_tax` double NOT NULL DEFAULT 0,
  `quantity` double NOT NULL DEFAULT 0,
  `discount_percent` double NOT NULL DEFAULT 0,
  `standard_cost` double NOT NULL DEFAULT 0,
  `qty_done` double NOT NULL DEFAULT 0,
  `src_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_debtor_trans_details`
--

INSERT INTO `0_debtor_trans_details` (`id`, `debtor_trans_no`, `debtor_trans_type`, `stock_id`, `description`, `unit_price`, `unit_tax`, `quantity`, `discount_percent`, `standard_cost`, `qty_done`, `src_id`) VALUES
(1, 1, 13, '101', 'Vsmart Aris 6GB-64GB', 300, 14.29, 20, 0, 200, 20, 1),
(2, 1, 13, '301', 'Support', 80, 3.81, 3, 0, 0, 3, 2),
(3, 1, 10, '101', 'Vsmart Aris 6GB-64GB', 300, 14.2855, 20, 0, 200, 0, 1),
(4, 1, 10, '301', 'Support', 80, 3.81, 3, 0, 0, 0, 2),
(5, 2, 13, '101', 'Vsmart Aris 6GB-64GB', 300, 14.29, 1, 0, 200, 1, 3),
(6, 2, 10, '101', 'Vsmart Aris 6GB-64GB', 300, 14.29, 1, 0, 200, 0, 5),
(7, 3, 13, '102', 'Vsmart Live 4 (6GB/64GB)', 222.62, 0, 1, 0, 150, 1, 7),
(8, 3, 13, '103', 'Vsmart Live 4 Cover Case', 44.52, 0, 1, 0, 10, 1, 8),
(9, 3, 10, '102', 'Vsmart Live 4 (6GB/64GB)', 222.62, 0, 1, 0, 150, 0, 7),
(10, 3, 10, '103', 'Vsmart Live 4 Cover Case', 44.52, 0, 1, 0, 10, 0, 8),
(11, 4, 13, '202', 'Maintenance', 0, 0, 5, 0, 0, 5, 10),
(12, 4, 10, '202', 'Maintenance', 0, 0, 5, 0, 0, 0, 11),
(13, 5, 13, '102', 'Vsmart Live 4 (6GB/64GB)', 250, 11.904, 5, 0, 150, 5, 11),
(14, 5, 10, '102', 'Vsmart Live 4 (6GB/64GB)', 250, 11.904, 5, 0, 150, 0, 13);

-- --------------------------------------------------------

--
-- Table structure for table `0_dimensions`
--

CREATE TABLE `0_dimensions` (
  `id` int(11) NOT NULL,
  `reference` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type_` tinyint(1) NOT NULL DEFAULT 1,
  `closed` tinyint(1) NOT NULL DEFAULT 0,
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_dimensions`
--

INSERT INTO `0_dimensions` (`id`, `reference`, `name`, `type_`, `closed`, `date_`, `due_date`) VALUES
(1, '001/2021', 'Cost Centre', 1, 0, '2021-05-05', '2021-05-25');

-- --------------------------------------------------------

--
-- Table structure for table `0_exchange_rates`
--

CREATE TABLE `0_exchange_rates` (
  `id` int(11) NOT NULL,
  `curr_code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate_buy` double NOT NULL DEFAULT 0,
  `rate_sell` double NOT NULL DEFAULT 0,
  `date_` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_exchange_rates`
--

INSERT INTO `0_exchange_rates` (`id`, `curr_code`, `rate_buy`, `rate_sell`, `date_`) VALUES
(1, 'EUR', 1.123, 1.123, '2021-05-07'),
(8, 'CAD', 0.75069773932459, 0.75069773932459, '2023-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `0_fiscal_year`
--

CREATE TABLE `0_fiscal_year` (
  `id` int(11) NOT NULL,
  `begin` date DEFAULT '0000-00-00',
  `end` date DEFAULT '0000-00-00',
  `closed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_fiscal_year`
--

INSERT INTO `0_fiscal_year` (`id`, `begin`, `end`, `closed`) VALUES
(1, '2021-01-01', '2021-12-31', 1),
(2, '2022-01-01', '2022-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_gl_trans`
--

CREATE TABLE `0_gl_trans` (
  `counter` int(11) NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT 0,
  `type_no` int(11) NOT NULL DEFAULT 0,
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `memo_` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `dimension_id` int(11) NOT NULL DEFAULT 0,
  `dimension2_id` int(11) NOT NULL DEFAULT 0,
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_gl_trans`
--

INSERT INTO `0_gl_trans` (`counter`, `type`, `type_no`, `tran_date`, `account`, `memo_`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`) VALUES
(1, 25, 1, '2021-05-05', '1510', '101', 20000, 0, 0, NULL, NULL),
(2, 25, 1, '2021-05-05', '1510', '102', 15000, 0, 0, NULL, NULL),
(3, 25, 1, '2021-05-05', '1510', '103', 1000, 0, 0, NULL, NULL),
(4, 25, 1, '2021-05-05', '1550', '', -36000, 0, 0, NULL, NULL),
(5, 13, 1, '2021-05-10', '5010', '', 4000, 0, 0, NULL, NULL),
(6, 13, 1, '2021-05-10', '1510', '', -4000, 0, 0, NULL, NULL),
(7, 10, 1, '2021-05-10', '4010', '', -5714.29, 0, 0, NULL, NULL),
(8, 10, 1, '2021-05-10', '4010', '', -228.57, 0, 0, NULL, NULL),
(9, 10, 1, '2021-05-10', '1200', '', 6240, 0, 0, 2, 0x31),
(10, 10, 1, '2021-05-10', '2150', '', -297.14, 0, 0, NULL, NULL),
(11, 12, 1, '2021-05-10', '1065', '', 6240, 0, 0, NULL, NULL),
(12, 12, 1, '2021-05-10', '1200', '', -6240, 0, 0, 2, 0x31),
(13, 29, 1, '2021-05-05', '1510', '1 * Vsmart Aris 6GB-64GB', -400, 0, 0, NULL, NULL),
(14, 29, 1, '2021-05-05', '1510', '1 * Vsmart Live 4 (6GB/64GB)', -300, 0, 0, NULL, NULL),
(15, 29, 1, '2021-05-05', '1510', '1 * Vsmart Live 4 Cover Case', -20, 0, 0, NULL, NULL),
(16, 29, 1, '2021-05-05', '1530', '1 * Support', 720, 0, 0, NULL, NULL),
(17, 26, 1, '2021-05-05', '1530', '', -720, 0, 0, NULL, NULL),
(18, 26, 1, '2021-05-05', '1510', '', 720, 0, 0, NULL, NULL),
(19, 25, 2, '2021-05-05', '1510', '101', 3000, 0, 0, NULL, NULL),
(20, 25, 2, '2021-05-05', '1550', '', -3000, 0, 0, NULL, NULL),
(21, 20, 1, '2021-05-05', '2150', '', 150, 0, 0, NULL, NULL),
(22, 20, 1, '2021-05-05', '2100', '', -3150, 0, 0, 3, 0x31),
(23, 20, 1, '2021-05-05', '1550', '', 3000, 0, 0, NULL, NULL),
(24, 13, 2, '2021-05-07', '5010', '', 200, 0, 0, NULL, NULL),
(25, 13, 2, '2021-05-07', '1510', '', -200, 0, 0, NULL, NULL),
(26, 10, 2, '2021-05-07', '4010', '', -285.71, 0, 0, NULL, NULL),
(27, 10, 2, '2021-05-07', '1200', '', 300, 0, 0, 2, 0x31),
(28, 10, 2, '2021-05-07', '2150', '', -14.29, 0, 0, NULL, NULL),
(29, 12, 2, '2021-05-07', '1065', '', 300, 0, 0, NULL, NULL),
(30, 12, 2, '2021-05-07', '1200', '', -300, 0, 0, 2, 0x31),
(31, 13, 3, '2021-05-07', '5010', '', 150, 1, 0, NULL, NULL),
(32, 13, 3, '2021-05-07', '1510', '', -150, 0, 0, NULL, NULL),
(33, 13, 3, '2021-05-07', '5010', '', 10, 1, 0, NULL, NULL),
(34, 13, 3, '2021-05-07', '1510', '', -10, 0, 0, NULL, NULL),
(35, 10, 3, '2021-05-07', '4010', '', -250, 1, 0, NULL, NULL),
(36, 10, 3, '2021-05-07', '4010', '', -50, 1, 0, NULL, NULL),
(37, 10, 3, '2021-05-07', '1200', '', 300, 0, 0, 2, 0x32),
(38, 12, 3, '2021-05-07', '1065', '', 0, 0, 0, NULL, NULL),
(39, 1, 1, '2021-05-07', '5010', '', 5, 1, 0, NULL, NULL),
(40, 1, 1, '2021-05-07', '1060', '', -5, 0, 0, NULL, NULL),
(41, 13, 5, '2022-01-21', '5010', '', 750, 0, 0, NULL, NULL),
(42, 13, 5, '2022-01-21', '1510', '', -750, 0, 0, NULL, NULL),
(43, 10, 5, '2022-01-21', '4010', '', -1190.48, 0, 0, NULL, NULL),
(44, 10, 5, '2022-01-21', '1200', '', 1250, 0, 0, 2, 0x31),
(45, 10, 5, '2022-01-21', '2150', '', -59.52, 0, 0, NULL, NULL),
(46, 12, 4, '2022-01-21', '1065', '', 1250, 0, 0, NULL, NULL),
(47, 12, 4, '2022-01-21', '1200', '', -1250, 0, 0, 2, 0x31),
(48, 25, 3, '2022-01-21', '1510', '102', 900, 0, 0, NULL, NULL),
(49, 25, 3, '2022-01-21', '1550', '', -900, 0, 0, NULL, NULL),
(50, 20, 2, '2022-01-21', '2150', '', 45, 0, 0, NULL, NULL),
(51, 20, 2, '2022-01-21', '2100', '', -945, 0, 0, 3, 0x31),
(52, 20, 2, '2022-01-21', '1550', '', 900, 0, 0, NULL, NULL),
(53, 0, 1, '2021-12-31', '3590', 'Closing Year', -2163.57, 0, 0, NULL, NULL),
(54, 0, 1, '2021-12-31', '9990', 'Closing Year', 2163.57, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `0_grn_batch`
--

CREATE TABLE `0_grn_batch` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT 0,
  `purch_order_no` int(11) DEFAULT NULL,
  `reference` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `loc_code` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rate` double DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_grn_batch`
--

INSERT INTO `0_grn_batch` (`id`, `supplier_id`, `purch_order_no`, `reference`, `delivery_date`, `loc_code`, `rate`) VALUES
(1, 1, 1, '001/2021', '2021-05-05', 'DEF', 1),
(2, 1, 2, 'auto', '2021-05-05', 'DEF', 1),
(3, 1, 3, 'auto', '2022-01-21', 'DEF', 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_grn_items`
--

CREATE TABLE `0_grn_items` (
  `id` int(11) NOT NULL,
  `grn_batch_id` int(11) DEFAULT NULL,
  `po_detail_item` int(11) NOT NULL DEFAULT 0,
  `item_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `qty_recd` double NOT NULL DEFAULT 0,
  `quantity_inv` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_grn_items`
--

INSERT INTO `0_grn_items` (`id`, `grn_batch_id`, `po_detail_item`, `item_code`, `description`, `qty_recd`, `quantity_inv`) VALUES
(1, 1, 1, '101', 'Vsmart Aris 6GB-64GB', 100, 0),
(2, 1, 2, '102', 'Vsmart Live 4 (6GB/64GB)', 100, 0),
(3, 1, 3, '103', 'Vsmart Live 4 Cover Case', 100, 0),
(4, 2, 4, '101', 'Vsmart Aris 6GB-64GB', 15, 15),
(5, 3, 5, '102', 'Vsmart Live 4 (6GB/64GB)', 6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `0_groups`
--

CREATE TABLE `0_groups` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_groups`
--

INSERT INTO `0_groups` (`id`, `description`, `inactive`) VALUES
(1, 'Small', 0),
(2, 'Medium', 0),
(3, 'Large', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_codes`
--

CREATE TABLE `0_item_codes` (
  `id` int(11) UNSIGNED NOT NULL,
  `item_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_id` smallint(6) UNSIGNED NOT NULL,
  `quantity` double NOT NULL DEFAULT 1,
  `is_foreign` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_item_codes`
--

INSERT INTO `0_item_codes` (`id`, `item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`, `inactive`) VALUES
(1, '101', '101', 'Vsmart Aris 6GB-64GB', 1, 1, 0, 0),
(2, '102', '102', 'Vsmart Live 4 (6GB/64GB)', 1, 1, 0, 0),
(3, '103', '103', 'Vsmart Live 4 Cover Case', 1, 1, 0, 0),
(4, '201', '201', 'AP Surf Set', 3, 1, 0, 0),
(5, '301', '301', 'Support', 4, 1, 0, 0),
(6, '501', '102', 'Live 4 Pack', 1, 1, 0, 0),
(7, '501', '103', 'Live 4 Pack', 2, 1, 0, 0),
(11, 'corner', 'corner', 'corner house', 10, 1, 0, 0),
(12, 'All', 'All', 'All FYP groups MUST be present at the Viva Starting ', 10, 1, 0, 0),
(13, 'allll', 'allll', 'allll', 10, 1, 0, 0),
(19, '-4', '-4', 'no', 10, 1, 0, 0),
(20, 'no', 'no', 'no', 10, 1, 0, 0),
(21, '-6', '-6', 'asasas', 10, 1, 0, 0),
(22, 'lkk', 'lkk', 'lkk', 10, 1, 0, 0),
(23, '10-10-10-10-ooo-8', '10-10-10-10-ooo-8', 'ooo', 10, 1, 0, 0),
(24, '10-10-10-10-lastT-9', '10-10-10-10-lastT-9', 'lastT', 10, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_tax_types`
--

CREATE TABLE `0_item_tax_types` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `exempt` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_item_tax_types`
--

INSERT INTO `0_item_tax_types` (`id`, `name`, `exempt`, `inactive`) VALUES
(1, 'Regular', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_item_tax_type_exemptions`
--

CREATE TABLE `0_item_tax_type_exemptions` (
  `item_tax_type_id` int(11) NOT NULL DEFAULT 0,
  `tax_type_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_item_units`
--

CREATE TABLE `0_item_units` (
  `abbr` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `decimals` tinyint(2) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_item_units`
--

INSERT INTO `0_item_units` (`abbr`, `name`, `decimals`, `inactive`) VALUES
('each', 'Each', 0, 0),
('hr', 'Hours', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_journal`
--

CREATE TABLE `0_journal` (
  `type` smallint(6) NOT NULL DEFAULT 0,
  `trans_no` int(11) NOT NULL DEFAULT 0,
  `tran_date` date DEFAULT '0000-00-00',
  `reference` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `source_ref` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `event_date` date DEFAULT '0000-00-00',
  `doc_date` date NOT NULL DEFAULT '0000-00-00',
  `currency` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT 0,
  `rate` double NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_journal`
--

INSERT INTO `0_journal` (`type`, `trans_no`, `tran_date`, `reference`, `source_ref`, `event_date`, `doc_date`, `currency`, `amount`, `rate`) VALUES
(0, 1, '2021-12-31', '001/2012', '', '2021-12-31', '2021-12-31', 'USD', 2163.57, 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_locations`
--

CREATE TABLE `0_locations` (
  `loc_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `location_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `delivery_address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone2` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fax` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `contact` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fixed_asset` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_locations`
--

INSERT INTO `0_locations` (`loc_code`, `location_name`, `delivery_address`, `phone`, `phone2`, `fax`, `email`, `contact`, `fixed_asset`, `inactive`) VALUES
('DEF', 'Default', 'N/A', '', '', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_loc_stock`
--

CREATE TABLE `0_loc_stock` (
  `loc_code` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `stock_id` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `reorder_level` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_loc_stock`
--

INSERT INTO `0_loc_stock` (`loc_code`, `stock_id`, `reorder_level`) VALUES
('DEF', '-1', 0),
('DEF', '-4', 0),
('DEF', '-6', 0),
('DEF', '-7', 0),
('DEF', '-8', 0),
('DEF', '1-1-2-2-ok-5', 0),
('DEF', '10-10-10-10-lastT-9', 0),
('DEF', '10-10-10-10-ooo-8', 0),
('DEF', '101', 0),
('DEF', '102', 0),
('DEF', '103', 0),
('DEF', '201', 0),
('DEF', '202', 0),
('DEF', '301', 0),
('DEF', 'All', 0),
('DEF', 'allll', 0),
('DEF', 'corner', 0),
('DEF', 'FYPGro', 0),
('DEF', 'gogoduc', 0),
('DEF', 'lkk', 0),
('DEF', 'myis', 0),
('DEF', 'no', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_payment_terms`
--

CREATE TABLE `0_payment_terms` (
  `terms_indicator` int(11) NOT NULL,
  `terms` char(80) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `days_before_due` smallint(6) NOT NULL DEFAULT 0,
  `day_in_following_month` smallint(6) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_payment_terms`
--

INSERT INTO `0_payment_terms` (`terms_indicator`, `terms`, `days_before_due`, `day_in_following_month`, `inactive`) VALUES
(1, 'Due 15th Of the Following Month', 0, 17, 0),
(2, 'Due By End Of The Following Month', 0, 30, 0),
(3, 'Payment due within 10 days', 10, 0, 0),
(4, 'Cash Only', 0, 0, 0),
(5, 'Prepaid', -1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_prices`
--

CREATE TABLE `0_prices` (
  `id` int(11) NOT NULL,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sales_type_id` int(11) NOT NULL DEFAULT 0,
  `curr_abrev` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_prices`
--

INSERT INTO `0_prices` (`id`, `stock_id`, `sales_type_id`, `curr_abrev`, `price`) VALUES
(1, '101', 1, 'USD', 300),
(2, '102', 1, 'USD', 250),
(3, '103', 1, 'USD', 50),
(4, '1-1-2-2-ok-5', 1, 'USD', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_printers`
--

CREATE TABLE `0_printers` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `queue` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `port` smallint(11) UNSIGNED NOT NULL,
  `timeout` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_printers`
--

INSERT INTO `0_printers` (`id`, `name`, `description`, `queue`, `host`, `port`, `timeout`) VALUES
(1, 'QL500', 'Label printer', 'QL500', 'server', 127, 20),
(2, 'Samsung', 'Main network printer', 'scx4521F', 'server', 515, 5),
(3, 'Local', 'Local print server at user IP', 'lp', '', 515, 10);

-- --------------------------------------------------------

--
-- Table structure for table `0_print_profiles`
--

CREATE TABLE `0_print_profiles` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `profile` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `report` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `printer` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_print_profiles`
--

INSERT INTO `0_print_profiles` (`id`, `profile`, `report`, `printer`) VALUES
(1, 'Out of office', NULL, 0),
(2, 'Sales Department', NULL, 0),
(3, 'Central', NULL, 2),
(4, 'Sales Department', '104', 2),
(5, 'Sales Department', '105', 2),
(6, 'Sales Department', '107', 2),
(7, 'Sales Department', '109', 2),
(8, 'Sales Department', '110', 2),
(9, 'Sales Department', '201', 2);

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_data`
--

CREATE TABLE `0_purch_data` (
  `supplier_id` int(11) NOT NULL DEFAULT 0,
  `stock_id` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT 0,
  `suppliers_uom` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `conversion_factor` double NOT NULL DEFAULT 1,
  `supplier_description` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_purch_data`
--

INSERT INTO `0_purch_data` (`supplier_id`, `stock_id`, `price`, `suppliers_uom`, `conversion_factor`, `supplier_description`) VALUES
(1, '101', 200, '', 1, 'Vsmart Aris 6GB-64GB'),
(1, '102', 150, '', 1, 'Vsmart Live 4 (6GB/64GB)'),
(1, '103', 10, '', 1, 'Vsmart Live 4 Cover Case');

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_orders`
--

CREATE TABLE `0_purch_orders` (
  `order_no` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT 0,
  `comments` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `requisition_no` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `into_stock_location` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `delivery_address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `total` double NOT NULL DEFAULT 0,
  `prep_amount` double NOT NULL DEFAULT 0,
  `alloc` double NOT NULL DEFAULT 0,
  `tax_included` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_purch_orders`
--

INSERT INTO `0_purch_orders` (`order_no`, `supplier_id`, `comments`, `ord_date`, `reference`, `requisition_no`, `into_stock_location`, `delivery_address`, `total`, `prep_amount`, `alloc`, `tax_included`) VALUES
(1, 1, NULL, '2021-05-05', '001/2021', NULL, 'DEF', 'N/A', 37800, 0, 0, 0),
(2, 1, NULL, '2021-05-05', 'auto', 'rr4', 'DEF', 'N/A', 3150, 0, 0, 0),
(3, 1, NULL, '2022-01-21', 'auto', 'asd5', 'DEF', 'N/A', 945, 0, 0, 0),
(4, 1, '', '2022-12-31', '001/2022', '', 'DEF', 'N/A', 210, 0, 0, 0),
(5, 1, '', '2022-12-31', '002/2022', '', 'DEF', 'na ji', 157.5, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_purch_order_details`
--

CREATE TABLE `0_purch_order_details` (
  `po_detail_item` int(11) NOT NULL,
  `order_no` int(11) NOT NULL DEFAULT 0,
  `sale_ord_no` int(11) NOT NULL,
  `item_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT 0,
  `unit_price` double NOT NULL DEFAULT 0,
  `act_price` double NOT NULL DEFAULT 0,
  `std_cost_unit` double NOT NULL DEFAULT 0,
  `quantity_ordered` double NOT NULL DEFAULT 0,
  `quantity_received` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_purch_order_details`
--

INSERT INTO `0_purch_order_details` (`po_detail_item`, `order_no`, `sale_ord_no`, `item_code`, `description`, `delivery_date`, `qty_invoiced`, `unit_price`, `act_price`, `std_cost_unit`, `quantity_ordered`, `quantity_received`) VALUES
(1, 1, 0, '101', 'Vsmart Aris 6GB-64GB', '2021-05-15', 0, 200, 200, 200, 100, 100),
(2, 1, 0, '102', 'Vsmart Live 4 (6GB/64GB)', '2021-05-15', 0, 150, 150, 150, 100, 100),
(3, 1, 0, '103', 'Vsmart Live 4 Cover Case', '2021-05-15', 0, 10, 10, 10, 100, 100),
(4, 2, 0, '101', 'Vsmart Aris 6GB-64GB', '2021-05-05', 15, 200, 200, 200, 15, 15),
(5, 3, 0, '102', 'Vsmart Live 4 (6GB/64GB)', '2022-01-21', 6, 150, 150, 150, 6, 6);

-- --------------------------------------------------------

--
-- Table structure for table `0_quick_entries`
--

CREATE TABLE `0_quick_entries` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `usage` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `base_amount` double NOT NULL DEFAULT 0,
  `base_desc` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bal_type` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_quick_entries`
--

INSERT INTO `0_quick_entries` (`id`, `type`, `description`, `usage`, `base_amount`, `base_desc`, `bal_type`) VALUES
(1, 1, 'Maintenance', NULL, 0, 'Amount', 0),
(2, 4, 'Phone', NULL, 0, 'Amount', 0),
(3, 2, 'Cash Sales', 'Retail sales without invoice', 0, 'Amount', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_quick_entry_lines`
--

CREATE TABLE `0_quick_entry_lines` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `qid` smallint(6) UNSIGNED NOT NULL,
  `amount` double DEFAULT 0,
  `memo` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `dest_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dimension_id` smallint(6) UNSIGNED DEFAULT NULL,
  `dimension2_id` smallint(6) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_quick_entry_lines`
--

INSERT INTO `0_quick_entry_lines` (`id`, `qid`, `amount`, `memo`, `action`, `dest_id`, `dimension_id`, `dimension2_id`) VALUES
(1, 1, 0, '', 't-', '1', 0, 0),
(2, 2, 0, '', 't-', '1', 0, 0),
(3, 3, 0, '', 't-', '1', 0, 0),
(4, 3, 0, '', '=', '4010', 0, 0),
(5, 1, 0, '', '=', '5765', 0, 0),
(6, 2, 0, '', '=', '5780', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_recurrent_invoices`
--

CREATE TABLE `0_recurrent_invoices` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `order_no` int(11) UNSIGNED NOT NULL,
  `debtor_no` int(11) UNSIGNED DEFAULT NULL,
  `group_no` smallint(6) UNSIGNED DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT 0,
  `monthly` int(11) NOT NULL DEFAULT 0,
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `last_sent` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_recurrent_invoices`
--

INSERT INTO `0_recurrent_invoices` (`id`, `description`, `order_no`, `debtor_no`, `group_no`, `days`, `monthly`, `begin`, `end`, `last_sent`) VALUES
(1, 'Weekly Maintenance', 6, 1, 1, 7, 0, '2021-04-01', '2022-05-07', '2021-04-08');

-- --------------------------------------------------------

--
-- Table structure for table `0_reflines`
--

CREATE TABLE `0_reflines` (
  `id` int(11) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `prefix` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pattern` varchar(35) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_reflines`
--

INSERT INTO `0_reflines` (`id`, `trans_type`, `prefix`, `pattern`, `description`, `default`, `inactive`) VALUES
(1, 0, '', '{001}/{YYYY}', '', 1, 0),
(2, 1, '', '{001}/{YYYY}', '', 1, 0),
(3, 2, '', '{001}/{YYYY}', '', 1, 0),
(4, 4, '', '{001}/{YYYY}', '', 1, 0),
(5, 10, '', '{001}/{YYYY}', '', 1, 0),
(6, 11, '', '{001}/{YYYY}', '', 1, 0),
(7, 12, '', '{001}/{YYYY}', '', 1, 0),
(8, 13, '', '{001}/{YYYY}', '', 1, 0),
(9, 16, '', '{001}/{YYYY}', '', 1, 0),
(10, 17, '', '{001}/{YYYY}', '', 1, 0),
(11, 18, '', '{001}/{YYYY}', '', 1, 0),
(12, 20, '', '{001}/{YYYY}', '', 1, 0),
(13, 21, '', '{001}/{YYYY}', '', 1, 0),
(14, 22, '', '{001}/{YYYY}', '', 1, 0),
(15, 25, '', '{001}/{YYYY}', '', 1, 0),
(16, 26, '', '{001}/{YYYY}', '', 1, 0),
(17, 28, '', '{001}/{YYYY}', '', 1, 0),
(18, 29, '', '{001}/{YYYY}', '', 1, 0),
(19, 30, '', '{001}/{YYYY}', '', 1, 0),
(20, 32, '', '{001}/{YYYY}', '', 1, 0),
(21, 35, '', '{001}/{YYYY}', '', 1, 0),
(22, 40, '', '{001}/{YYYY}', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_refs`
--

CREATE TABLE `0_refs` (
  `id` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL DEFAULT 0,
  `reference` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_refs`
--

INSERT INTO `0_refs` (`id`, `type`, `reference`) VALUES
(1, 1, '001/2021'),
(1, 10, '001/2021'),
(5, 10, '001/2022'),
(2, 10, '002/2021'),
(3, 10, '003/2021'),
(4, 10, '004/2021'),
(1, 12, '001/2021'),
(4, 12, '001/2022'),
(2, 12, '002/2021'),
(3, 12, '003/2021'),
(1, 18, '001/2021'),
(4, 18, '001/2022'),
(5, 18, '002/2022'),
(1, 20, '001/2021'),
(2, 20, '001/2022'),
(1, 25, '001/2021'),
(1, 26, '001/2021'),
(2, 26, '002/2021'),
(3, 26, '003/2021'),
(3, 30, '001/2021'),
(9, 30, '001/2022'),
(4, 30, '002/2021'),
(6, 30, '003/2021'),
(1, 40, '001/2021');

-- --------------------------------------------------------

--
-- Table structure for table `0_salesman`
--

CREATE TABLE `0_salesman` (
  `salesman_code` int(11) NOT NULL,
  `salesman_name` char(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salesman_phone` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salesman_fax` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salesman_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `provision` double NOT NULL DEFAULT 0,
  `break_pt` double NOT NULL DEFAULT 0,
  `provision2` double NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_salesman`
--

INSERT INTO `0_salesman` (`salesman_code`, `salesman_name`, `salesman_phone`, `salesman_fax`, `salesman_email`, `provision`, `break_pt`, `provision2`, `inactive`) VALUES
(1, 'Sales Person', '', '', '', 5, 1000, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_orders`
--

CREATE TABLE `0_sales_orders` (
  `order_no` int(11) NOT NULL,
  `trans_type` smallint(6) NOT NULL DEFAULT 30,
  `version` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `debtor_no` int(11) NOT NULL DEFAULT 0,
  `branch_code` int(11) NOT NULL DEFAULT 0,
  `reference` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `customer_ref` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `comments` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `order_type` int(11) NOT NULL DEFAULT 0,
  `ship_via` int(11) NOT NULL DEFAULT 0,
  `delivery_address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deliver_to` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `freight_cost` double NOT NULL DEFAULT 0,
  `from_stk_loc` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `payment_terms` int(11) DEFAULT NULL,
  `total` double NOT NULL DEFAULT 0,
  `prep_amount` double NOT NULL DEFAULT 0,
  `alloc` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_sales_orders`
--

INSERT INTO `0_sales_orders` (`order_no`, `trans_type`, `version`, `type`, `debtor_no`, `branch_code`, `reference`, `customer_ref`, `comments`, `ord_date`, `order_type`, `ship_via`, `delivery_address`, `contact_phone`, `contact_email`, `deliver_to`, `freight_cost`, `from_stk_loc`, `delivery_date`, `payment_terms`, `total`, `prep_amount`, `alloc`) VALUES
(1, 30, 1, 0, 1, 1, 'auto', '', NULL, '2021-05-10', 1, 1, 'N/A', NULL, NULL, 'Donald Easter LLC', 0, 'DEF', '2021-05-05', 4, 6240, 0, 0),
(2, 30, 1, 0, 1, 1, 'auto', '', NULL, '2021-05-07', 1, 1, 'N/A', NULL, NULL, 'Donald Easter LLC', 0, 'DEF', '2021-05-07', 4, 300, 0, 0),
(3, 30, 0, 0, 1, 1, '001/2021', '', NULL, '2021-05-07', 1, 1, 'N/A', NULL, NULL, 'Donald Easter LLC', 0, 'DEF', '2021-05-08', 4, 300, 0, 0),
(4, 30, 0, 0, 2, 2, '002/2021', '', NULL, '2021-05-07', 1, 1, 'N/A', NULL, NULL, 'MoneyMaker Ltd.', 0, 'DEF', '2021-05-08', 1, 267.14, 0, 0),
(5, 30, 1, 0, 2, 2, 'auto', '', NULL, '2021-05-07', 1, 1, 'N/A', NULL, NULL, 'MoneyMaker Ltd.', 0, 'DEF', '2021-06-17', 1, 267.14, 0, 0),
(6, 30, 0, 1, 1, 1, '003/2021', '', NULL, '2021-05-07', 1, 1, 'N/A', NULL, NULL, 'Donald Easter LLC', 0, 'DEF', '2021-05-08', 4, 450, 0, 0),
(7, 30, 1, 0, 1, 1, 'auto', '', 'Recurrent Invoice covers period 04/01/2021 - 04/07/2021.', '2021-05-07', 1, 1, 'N/A', NULL, NULL, 'Donald Easter LLC', 0, 'DEF', '2021-05-07', 4, 0, 0, 0),
(8, 30, 1, 0, 1, 1, 'auto', '', NULL, '2022-01-21', 1, 1, 'N/A', NULL, NULL, 'Donald Easter LLC', 0, 'DEF', '2022-01-21', 4, 1250, 0, 0),
(9, 30, 0, 0, 1, 1, '001/2022', '', '', '2022-12-31', 1, 1, 'N/A', '', NULL, 'Donald Easter LLC', 0, 'DEF', '2023-01-01', 4, 250, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_order_details`
--

CREATE TABLE `0_sales_order_details` (
  `id` int(11) NOT NULL,
  `order_no` int(11) NOT NULL DEFAULT 0,
  `trans_type` smallint(6) NOT NULL DEFAULT 30,
  `stk_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `qty_sent` double NOT NULL DEFAULT 0,
  `unit_price` double NOT NULL DEFAULT 0,
  `quantity` double NOT NULL DEFAULT 0,
  `invoiced` double NOT NULL DEFAULT 0,
  `discount_percent` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_sales_order_details`
--

INSERT INTO `0_sales_order_details` (`id`, `order_no`, `trans_type`, `stk_code`, `description`, `qty_sent`, `unit_price`, `quantity`, `invoiced`, `discount_percent`) VALUES
(1, 1, 30, '101', 'Vsmart Aris 6GB-64GB', 20, 300, 20, 0, 0),
(2, 1, 30, '301', 'Support', 3, 80, 3, 0, 0),
(3, 2, 30, '101', 'Vsmart Aris 6GB-64GB', 1, 300, 1, 0, 0),
(4, 3, 30, '102', 'Vsmart Live 4 (6GB/64GB)', 0, 250, 1, 0, 0),
(5, 3, 30, '103', 'Vsmart Live 4 Cover Case', 0, 50, 1, 0, 0),
(6, 4, 30, '101', 'Vsmart Aris 6GB-64GB', 0, 267.14, 1, 0, 0),
(7, 5, 30, '102', 'Vsmart Live 4 (6GB/64GB)', 1, 222.62, 1, 0, 0),
(8, 5, 30, '103', 'Vsmart Live 4 Cover Case', 1, 44.52, 1, 0, 0),
(9, 6, 30, '202', 'Maintenance', 0, 90, 5, 0, 0),
(10, 7, 30, '202', 'Maintenance', 5, 0, 5, 0, 0),
(11, 8, 30, '102', 'Vsmart Live 4 (6GB/64GB)', 5, 250, 5, 0, 0),
(12, 9, 30, '102', 'Vsmart Live 4 (6GB/64GB)', 0, 250, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_pos`
--

CREATE TABLE `0_sales_pos` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `pos_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `cash_sale` tinyint(1) NOT NULL,
  `credit_sale` tinyint(1) NOT NULL,
  `pos_location` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `pos_account` smallint(6) UNSIGNED NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_sales_pos`
--

INSERT INTO `0_sales_pos` (`id`, `pos_name`, `cash_sale`, `credit_sale`, `pos_location`, `pos_account`, `inactive`) VALUES
(1, 'Default', 1, 1, 'DEF', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sales_types`
--

CREATE TABLE `0_sales_types` (
  `id` int(11) NOT NULL,
  `sales_type` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT 0,
  `factor` double NOT NULL DEFAULT 1,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_sales_types`
--

INSERT INTO `0_sales_types` (`id`, `sales_type`, `tax_included`, `factor`, `inactive`) VALUES
(1, 'Retail', 0, 0.8, 0),
(2, 'Wholesale', 0, 0.7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_security_roles`
--

CREATE TABLE `0_security_roles` (
  `id` int(11) NOT NULL,
  `role` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sections` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `areas` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_security_roles`
--

INSERT INTO `0_security_roles` (`id`, `role`, `description`, `sections`, `areas`, `inactive`) VALUES
(1, 'Inquiries', 'Inquiries', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;773;774;2822;3073;3075;3076;3077;3329;3330;3331;3332;3333;3334;3335;5377;5633;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8450;8451;10497;10753;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15873;15882;16129;16130;16131;16132;775', 0),
(2, 'System Administrator', 'System Administrator', '256;512;768;2816;3072;3328;5376;5632;5888;7936;8192;8448;9472;9728;10496;10752;11008;13056;13312;15616;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;526;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;9217;9218;9220;9473;9474;9475;9476;9729;10497;10753;10754;10755;10756;10757;11009;11010;11011;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15628;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132;775', 0),
(3, 'Salesman', 'Salesman', '768;3072;5632;8192;15872', '773;774;3073;3075;3081;5633;8194;15873;775', 0),
(4, 'Stock Manager', 'Stock Manager', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '2818;2822;3073;3076;3077;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5889;5890;5891;8193;8194;8450;8451;10753;11009;11010;11012;13313;13315;15882;16129;16130;16131;16132;775', 0),
(5, 'Production Manager', 'Production Manager', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5640;5889;5890;5891;8193;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132;775', 0),
(6, 'Purchase Officer', 'Purchase Officer', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5377;5633;5635;5640;5640;5889;5890;5891;8193;8194;8196;8197;8449;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132;775', 0),
(7, 'AR Officer', 'AR Officer', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;771;773;774;2818;2819;2820;2821;2822;2823;3073;3073;3074;3075;3076;3077;3078;3079;3080;3081;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5633;5634;5637;5638;5639;5640;5640;5889;5890;5891;8193;8194;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132;775', 0),
(8, 'AP Officer', 'AP Officer', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5635;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132;775', 0),
(9, 'Accountant', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132;775', 0),
(10, 'Sub Admin', 'Sub Admin', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15873;15874;15876;15877;15878;15879;15880;15882;16129;16130;16131;16132;775', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_shippers`
--

CREATE TABLE `0_shippers` (
  `shipper_id` int(11) NOT NULL,
  `shipper_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone2` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `contact` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_shippers`
--

INSERT INTO `0_shippers` (`shipper_id`, `shipper_name`, `phone`, `phone2`, `contact`, `address`, `inactive`) VALUES
(1, 'Default', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sql_trail`
--

CREATE TABLE `0_sql_trail` (
  `id` int(11) UNSIGNED NOT NULL,
  `sql` text COLLATE utf8_unicode_ci NOT NULL,
  `result` tinyint(1) NOT NULL,
  `msg` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_category`
--

CREATE TABLE `0_stock_category` (
  `category_id` int(11) NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_tax_type` int(11) NOT NULL DEFAULT 1,
  `dflt_units` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'each',
  `dflt_mb_flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'B',
  `dflt_sales_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_cogs_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_inventory_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_adjustment_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_wip_act` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dflt_dim1` int(11) DEFAULT NULL,
  `dflt_dim2` int(11) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0,
  `dflt_no_sale` tinyint(1) NOT NULL DEFAULT 0,
  `dflt_no_purchase` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_category`
--

INSERT INTO `0_stock_category` (`category_id`, `description`, `dflt_tax_type`, `dflt_units`, `dflt_mb_flag`, `dflt_sales_act`, `dflt_cogs_act`, `dflt_inventory_act`, `dflt_adjustment_act`, `dflt_wip_act`, `dflt_dim1`, `dflt_dim2`, `inactive`, `dflt_no_sale`, `dflt_no_purchase`) VALUES
(10, 'Components', 1, 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0),
(20, 'Charges', 1, 'each', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0),
(30, 'Systems', 1, 'each', 'M', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0),
(40, 'Services', 1, 'hr', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_colour`
--

CREATE TABLE `0_stock_colour` (
  `color_id` int(11) NOT NULL,
  `description` varchar(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_colour`
--

INSERT INTO `0_stock_colour` (`color_id`, `description`) VALUES
(10, 'blue'),
(20, 'red');

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_fa_class`
--

CREATE TABLE `0_stock_fa_class` (
  `fa_class_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parent_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `long_description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `depreciation_rate` double NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_main_category`
--

CREATE TABLE `0_stock_main_category` (
  `maincat_id` int(11) NOT NULL,
  `description` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_main_category`
--

INSERT INTO `0_stock_main_category` (`maincat_id`, `description`) VALUES
(10, 'Job'),
(20, 'electronic');

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_master`
--

CREATE TABLE `0_stock_master` (
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT 0,
  `tax_type_id` int(11) NOT NULL DEFAULT 0,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `long_description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `units` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'each',
  `mb_flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'B',
  `sales_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cogs_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inventory_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adjustment_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `wip_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `purchase_cost` double NOT NULL DEFAULT 0,
  `material_cost` double NOT NULL DEFAULT 0,
  `labour_cost` double NOT NULL DEFAULT 0,
  `overhead_cost` double NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0,
  `no_sale` tinyint(1) NOT NULL DEFAULT 0,
  `no_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `editable` tinyint(1) NOT NULL DEFAULT 0,
  `depreciation_method` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `depreciation_rate` double NOT NULL DEFAULT 0,
  `depreciation_factor` double NOT NULL DEFAULT 1,
  `depreciation_start` date NOT NULL DEFAULT '0000-00-00',
  `depreciation_date` date NOT NULL DEFAULT '0000-00-00',
  `fa_class_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_master`
--

INSERT INTO `0_stock_master` (`stock_id`, `category_id`, `tax_type_id`, `description`, `long_description`, `units`, `mb_flag`, `sales_account`, `cogs_account`, `inventory_account`, `adjustment_account`, `wip_account`, `dimension_id`, `dimension2_id`, `purchase_cost`, `material_cost`, `labour_cost`, `overhead_cost`, `inactive`, `no_sale`, `no_purchase`, `editable`, `depreciation_method`, `depreciation_rate`, `depreciation_factor`, `depreciation_start`, `depreciation_date`, `fa_class_id`) VALUES
('-1', 20, 1, 'naji', '', 'each', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('-4', 10, 1, 'no', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('-6', 10, 1, 'asasas', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('-7', 10, 1, 'kl', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('-8', 10, 1, 'check', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('10-10-10-10-lastT-9', 10, 1, 'lastT', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('10-10-10-10-ooo-8', 10, 1, 'ooo', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('101', 1, 1, 'Vsmart Aris 6GB-64GB', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 200, 0, 0, 0, 0, 0, 0, 'S', 0, 1, '0000-00-00', '0000-00-00', ''),
('102', 1, 1, 'Vsmart Live 4 (6GB/64GB)', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 150, 150, 0, 0, 0, 0, 0, 0, 'S', 0, 1, '0000-00-00', '0000-00-00', ''),
('103', 1, 1, 'Vsmart Live 4 Cover Case', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 10, 0, 0, 0, 0, 0, 0, 'S', 0, 1, '0000-00-00', '0000-00-00', ''),
('201', 3, 1, 'AP Surf Set', '', 'each', 'M', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 360, 0, 0, 0, 0, 0, 0, 'S', 0, 1, '0000-00-00', '0000-00-00', ''),
('202', 4, 1, 'Maintenance', '', 'hr', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'S', 0, 1, '0000-00-00', '0000-00-00', ''),
('301', 4, 1, 'Support', '', 'hr', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'S', 0, 1, '0000-00-00', '0000-00-00', ''),
('lkk', 10, 1, 'lkk', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', ''),
('no', 10, 1, 'no', '', 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', 0, 0, '0000-00-00', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_moves`
--

CREATE TABLE `0_stock_moves` (
  `trans_id` int(11) NOT NULL,
  `trans_no` int(11) NOT NULL DEFAULT 0,
  `stock_id` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT 0,
  `loc_code` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `price` double NOT NULL DEFAULT 0,
  `reference` char(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT 1,
  `standard_cost` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_moves`
--

INSERT INTO `0_stock_moves` (`trans_id`, `trans_no`, `stock_id`, `type`, `loc_code`, `tran_date`, `price`, `reference`, `qty`, `standard_cost`) VALUES
(1, 1, '101', 25, 'DEF', '2021-05-05', 200, '', 100, 200),
(2, 1, '102', 25, 'DEF', '2021-05-05', 150, '', 100, 150),
(3, 1, '103', 25, 'DEF', '2021-05-05', 10, '', 100, 10),
(4, 1, '101', 13, 'DEF', '2021-05-10', 300, 'auto', -20, 200),
(5, 1, '301', 13, 'DEF', '2021-05-10', 80, 'auto', -3, 0),
(6, 1, '101', 29, 'DEF', '2021-05-05', 200, '001/2021', -2, 200),
(7, 1, '102', 29, 'DEF', '2021-05-05', 150, '001/2021', -2, 150),
(8, 1, '103', 29, 'DEF', '2021-05-05', 10, '001/2021', -2, 10),
(9, 1, '301', 29, 'DEF', '2021-05-05', 0, '001/2021', -2, 0),
(10, 1, '201', 26, 'DEF', '2021-05-05', 0, '001/2021', 2, 360),
(11, 2, '101', 25, 'DEF', '2021-05-05', 200, '', 15, 200),
(12, 2, '101', 13, 'DEF', '2021-05-07', 300, 'auto', -1, 200),
(13, 3, '102', 13, 'DEF', '2021-05-07', 222.62, 'auto', -1, 150),
(14, 3, '103', 13, 'DEF', '2021-05-07', 44.52, 'auto', -1, 10),
(15, 4, '202', 13, 'DEF', '2021-05-07', 0, 'auto', -5, 0),
(16, 5, '102', 13, 'DEF', '2022-01-21', 250, 'auto', -5, 150),
(17, 3, '102', 25, 'DEF', '2022-01-21', 150, '', 6, 150);

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_size`
--

CREATE TABLE `0_stock_size` (
  `size_id` int(11) NOT NULL,
  `description` varchar(4) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_size`
--

INSERT INTO `0_stock_size` (`size_id`, `description`) VALUES
(10, 's'),
(20, 'xl');

-- --------------------------------------------------------

--
-- Table structure for table `0_stock_style`
--

CREATE TABLE `0_stock_style` (
  `id` int(11) NOT NULL,
  `own_style` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_stock_style`
--

INSERT INTO `0_stock_style` (`id`, `own_style`) VALUES
(1, 'Attractive'),
(2, 'Simple');

-- --------------------------------------------------------

--
-- Table structure for table `0_suppliers`
--

CREATE TABLE `0_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supp_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `supp_ref` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `supp_address` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `gst_no` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `contact` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `supp_account_no` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `website` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bank_account` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `curr_code` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_terms` int(11) DEFAULT NULL,
  `tax_included` tinyint(1) NOT NULL DEFAULT 0,
  `dimension_id` int(11) DEFAULT 0,
  `dimension2_id` int(11) DEFAULT 0,
  `tax_group_id` int(11) DEFAULT NULL,
  `credit_limit` double NOT NULL DEFAULT 0,
  `purchase_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payable_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `payment_discount_account` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `notes` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_suppliers`
--

INSERT INTO `0_suppliers` (`supplier_id`, `supp_name`, `supp_ref`, `address`, `supp_address`, `gst_no`, `contact`, `supp_account_no`, `website`, `bank_account`, `curr_code`, `payment_terms`, `tax_included`, `dimension_id`, `dimension2_id`, `tax_group_id`, `credit_limit`, `purchase_account`, `payable_account`, `payment_discount_account`, `notes`, `inactive`) VALUES
(1, 'Dino Saurius Inc.', 'Dino Saurius', 'N/A', '', '987654321', '', '', '', '', 'USD', 3, 0, 0, 0, 1, 0, '', '2100', '5060', '', 0),
(2, 'Beefeater Ltd.', 'Beefeater', 'N/A', '', '67565590', '', '', '', '', 'GBP', 4, 0, 0, 0, 1, 0, '', '2100', '5060', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_allocations`
--

CREATE TABLE `0_supp_allocations` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `amt` double UNSIGNED DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_invoice_items`
--

CREATE TABLE `0_supp_invoice_items` (
  `id` int(11) NOT NULL,
  `supp_trans_no` int(11) DEFAULT NULL,
  `supp_trans_type` int(11) DEFAULT NULL,
  `gl_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `grn_item_id` int(11) DEFAULT NULL,
  `po_detail_item_id` int(11) DEFAULT NULL,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `unit_price` double NOT NULL DEFAULT 0,
  `unit_tax` double NOT NULL DEFAULT 0,
  `memo_` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT 0,
  `dimension2_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_supp_invoice_items`
--

INSERT INTO `0_supp_invoice_items` (`id`, `supp_trans_no`, `supp_trans_type`, `gl_code`, `grn_item_id`, `po_detail_item_id`, `stock_id`, `description`, `quantity`, `unit_price`, `unit_tax`, `memo_`, `dimension_id`, `dimension2_id`) VALUES
(1, 1, 20, '0', 4, 4, '101', 'Vsmart Aris 6GB-64GB', 15, 200, 10, NULL, 0, 0),
(2, 2, 20, '0', 5, 5, '102', 'Vsmart Live 4 (6GB/64GB)', 6, 150, 7.5, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_supp_trans`
--

CREATE TABLE `0_supp_trans` (
  `trans_no` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `type` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `supplier_id` int(11) UNSIGNED NOT NULL,
  `reference` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `supp_reference` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `ov_amount` double NOT NULL DEFAULT 0,
  `ov_discount` double NOT NULL DEFAULT 0,
  `ov_gst` double NOT NULL DEFAULT 0,
  `rate` double NOT NULL DEFAULT 1,
  `alloc` double NOT NULL DEFAULT 0,
  `tax_included` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_supp_trans`
--

INSERT INTO `0_supp_trans` (`trans_no`, `type`, `supplier_id`, `reference`, `supp_reference`, `tran_date`, `due_date`, `ov_amount`, `ov_discount`, `ov_gst`, `rate`, `alloc`, `tax_included`) VALUES
(1, 20, 1, '001/2021', 'rr4', '2021-05-05', '2021-05-15', 3000, 0, 150, 1, 0, 0),
(2, 20, 1, '001/2022', 'asd5', '2022-01-21', '2022-01-31', 900, 0, 45, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_sys_prefs`
--

CREATE TABLE `0_sys_prefs` (
  `name` varchar(35) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `length` smallint(6) DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_sys_prefs`
--

INSERT INTO `0_sys_prefs` (`name`, `category`, `type`, `length`, `value`) VALUES
('accounts_alpha', 'glsetup.general', 'tinyint', 1, '0'),
('accumulate_shipping', 'glsetup.customer', 'tinyint', 1, '0'),
('add_pct', 'setup.company', 'int', 5, '-1'),
('allow_negative_prices', 'glsetup.inventory', 'tinyint', 1, '1'),
('allow_negative_stock', 'glsetup.inventory', 'tinyint', 1, '0'),
('alternative_tax_include_on_docs', 'setup.company', 'tinyint', 1, '0'),
('auto_curr_reval', 'setup.company', 'smallint', 6, '1'),
('bank_charge_act', 'glsetup.general', 'varchar', 15, '5690'),
('barcodes_on_stock', 'setup.company', 'tinyint', 1, '0'),
('base_sales', 'setup.company', 'int', 11, '1'),
('bcc_email', 'setup.company', 'varchar', 100, ''),
('company_logo_report', 'setup.company', 'tinyint', 1, '0'),
('coy_logo', 'setup.company', 'varchar', 100, ''),
('coy_name', 'setup.company', 'varchar', 60, 'meethi zindagi'),
('coy_no', 'setup.company', 'varchar', 25, ''),
('creditors_act', 'glsetup.purchase', 'varchar', 15, '2100'),
('curr_default', 'setup.company', 'char', 3, 'USD'),
('debtors_act', 'glsetup.sales', 'varchar', 15, '1200'),
('default_adj_act', 'glsetup.items', 'varchar', 15, '5040'),
('default_cogs_act', 'glsetup.items', 'varchar', 15, '5010'),
('default_credit_limit', 'glsetup.customer', 'int', 11, '1000'),
('default_delivery_required', 'glsetup.sales', 'smallint', 6, '1'),
('default_dim_required', 'glsetup.dims', 'int', 11, '20'),
('default_inv_sales_act', 'glsetup.items', 'varchar', 15, '4010'),
('default_inventory_act', 'glsetup.items', 'varchar', 15, '1510'),
('default_loss_on_asset_disposal_act', 'glsetup.items', 'varchar', 15, '5660'),
('default_prompt_payment_act', 'glsetup.sales', 'varchar', 15, '4500'),
('default_quote_valid_days', 'glsetup.sales', 'smallint', 6, '30'),
('default_receival_required', 'glsetup.purchase', 'smallint', 6, '10'),
('default_sales_act', 'glsetup.sales', 'varchar', 15, '4010'),
('default_sales_discount_act', 'glsetup.sales', 'varchar', 15, '4510'),
('default_wip_act', 'glsetup.items', 'varchar', 15, '1530'),
('default_workorder_required', 'glsetup.manuf', 'int', 11, '20'),
('deferred_income_act', 'glsetup.sales', 'varchar', 15, '2105'),
('depreciation_period', 'glsetup.company', 'tinyint', 1, '1'),
('dim_on_recurrent_invoice', 'setup.company', 'tinyint', 1, '0'),
('domicile', 'setup.company', 'varchar', 55, ''),
('email', 'setup.company', 'varchar', 100, ''),
('exchange_diff_act', 'glsetup.general', 'varchar', 15, '4450'),
('f_year', 'setup.company', 'int', 11, '2'),
('fax', 'setup.company', 'varchar', 30, ''),
('freight_act', 'glsetup.customer', 'varchar', 15, '4430'),
('gl_closing_date', 'setup.closing_date', 'date', 8, ''),
('grn_clearing_act', 'glsetup.purchase', 'varchar', 15, '1550'),
('gst_no', 'setup.company', 'varchar', 25, ''),
('legal_text', 'glsetup.customer', 'tinytext', 0, ''),
('loc_notification', 'glsetup.inventory', 'tinyint', 1, '0'),
('login_tout', 'setup.company', 'smallint', 6, '600'),
('long_description_invoice', 'setup.company', 'tinyint', 1, '0'),
('max_days_in_docs', 'setup.company', 'smallint', 5, '180'),
('no_customer_list', 'setup.company', 'tinyint', 1, '0'),
('no_supplier_list', 'setup.company', 'tinyint', 1, '0'),
('no_zero_lines_amount', 'glsetup.sales', 'tinyint', 1, '1'),
('past_due_days', 'glsetup.general', 'int', 11, '30'),
('phone', 'setup.company', 'varchar', 30, ''),
('po_over_charge', 'glsetup.purchase', 'int', 11, '10'),
('po_over_receive', 'glsetup.purchase', 'int', 11, '10'),
('postal_address', 'setup.company', 'tinytext', 0, 'N/A'),
('print_dialog_direct', 'setup.company', 'tinyint', 1, '0'),
('print_invoice_no', 'glsetup.sales', 'tinyint', 1, '0'),
('print_item_images_on_quote', 'glsetup.inventory', 'tinyint', 1, '0'),
('profit_loss_year_act', 'glsetup.general', 'varchar', 15, '9990'),
('pyt_discount_act', 'glsetup.purchase', 'varchar', 15, '5060'),
('ref_no_auto_increase', 'setup.company', 'tinyint', 1, '0'),
('retained_earnings_act', 'glsetup.general', 'varchar', 15, '3590'),
('round_to', 'setup.company', 'int', 5, '1'),
('shortname_name_in_list', 'setup.company', 'tinyint', 1, '0'),
('show_po_item_codes', 'glsetup.purchase', 'tinyint', 1, '0'),
('suppress_tax_rates', 'setup.company', 'tinyint', 1, '0'),
('tax_algorithm', 'glsetup.customer', 'tinyint', 1, '1'),
('tax_last', 'setup.company', 'int', 11, '1'),
('tax_prd', 'setup.company', 'int', 11, '1'),
('time_zone', 'setup.company', 'tinyint', 1, '0'),
('use_dimension', 'setup.company', 'tinyint', 1, '1'),
('use_fixed_assets', 'setup.company', 'tinyint', 1, '1'),
('use_manufacturing', 'setup.company', 'tinyint', 1, '1'),
('version_id', 'system', 'varchar', 11, '0.1');

-- --------------------------------------------------------

--
-- Table structure for table `0_tags`
--

CREATE TABLE `0_tags` (
  `id` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_tag_associations`
--

CREATE TABLE `0_tag_associations` (
  `record_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_groups`
--

CREATE TABLE `0_tax_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_tax_groups`
--

INSERT INTO `0_tax_groups` (`id`, `name`, `inactive`) VALUES
(1, 'Tax', 0),
(2, 'Tax Exempt', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_group_items`
--

CREATE TABLE `0_tax_group_items` (
  `tax_group_id` int(11) NOT NULL DEFAULT 0,
  `tax_type_id` int(11) NOT NULL DEFAULT 0,
  `tax_shipping` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_tax_group_items`
--

INSERT INTO `0_tax_group_items` (`tax_group_id`, `tax_type_id`, `tax_shipping`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_tax_types`
--

CREATE TABLE `0_tax_types` (
  `id` int(11) NOT NULL,
  `rate` double NOT NULL DEFAULT 0,
  `sales_gl_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `purchasing_gl_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_tax_types`
--

INSERT INTO `0_tax_types` (`id`, `rate`, `sales_gl_code`, `purchasing_gl_code`, `name`, `inactive`) VALUES
(1, 5, '2150', '2150', 'Tax', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_trans_tax_details`
--

CREATE TABLE `0_trans_tax_details` (
  `id` int(11) NOT NULL,
  `trans_type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `tran_date` date NOT NULL,
  `tax_type_id` int(11) NOT NULL DEFAULT 0,
  `rate` double NOT NULL DEFAULT 0,
  `ex_rate` double NOT NULL DEFAULT 1,
  `included_in_price` tinyint(1) NOT NULL DEFAULT 0,
  `net_amount` double NOT NULL DEFAULT 0,
  `amount` double NOT NULL DEFAULT 0,
  `memo` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `reg_type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_trans_tax_details`
--

INSERT INTO `0_trans_tax_details` (`id`, `trans_type`, `trans_no`, `tran_date`, `tax_type_id`, `rate`, `ex_rate`, `included_in_price`, `net_amount`, `amount`, `memo`, `reg_type`) VALUES
(1, 13, 1, '2021-05-10', 1, 5, 1, 1, 5942.86, 297.14, 'auto', NULL),
(2, 10, 1, '2021-05-10', 1, 5, 1, 1, 5942.86, 297.14, '001/2021', 0),
(3, 20, 1, '2021-05-05', 1, 5, 1, 0, 3000, 150, 'rr4', 1),
(4, 13, 2, '2021-05-07', 1, 5, 1, 1, 285.71, 14.29, 'auto', NULL),
(5, 10, 2, '2021-05-07', 1, 5, 1, 1, 285.71, 14.29, '002/2021', 0),
(6, 13, 3, '2021-05-07', 0, 0, 1.123, 1, 267.14, 0, 'auto', NULL),
(7, 10, 3, '2021-05-07', 0, 0, 1.123, 1, 267.14, 0, '003/2021', 0),
(8, 13, 5, '2022-01-21', 1, 5, 1, 1, 1190.48, 59.52, 'auto', NULL),
(9, 10, 5, '2022-01-21', 1, 5, 1, 1, 1190.48, 59.52, '001/2022', 0),
(10, 20, 2, '2022-01-21', 1, 5, 1, 0, 900, 45, 'asd5', 1);

-- --------------------------------------------------------

--
-- Table structure for table `0_useronline`
--

CREATE TABLE `0_useronline` (
  `id` int(11) NOT NULL,
  `timestamp` int(15) NOT NULL DEFAULT 0,
  `ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `file` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_users`
--

CREATE TABLE `0_users` (
  `id` smallint(6) NOT NULL,
  `user_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `real_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT 1,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_format` tinyint(1) NOT NULL DEFAULT 0,
  `date_sep` tinyint(1) NOT NULL DEFAULT 0,
  `tho_sep` tinyint(1) NOT NULL DEFAULT 0,
  `dec_sep` tinyint(1) NOT NULL DEFAULT 0,
  `theme` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `page_size` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A4',
  `prices_dec` smallint(6) NOT NULL DEFAULT 2,
  `qty_dec` smallint(6) NOT NULL DEFAULT 2,
  `rates_dec` smallint(6) NOT NULL DEFAULT 4,
  `percent_dec` smallint(6) NOT NULL DEFAULT 1,
  `show_gl` tinyint(1) NOT NULL DEFAULT 1,
  `show_codes` tinyint(1) NOT NULL DEFAULT 0,
  `show_hints` tinyint(1) NOT NULL DEFAULT 0,
  `last_visit_date` datetime DEFAULT NULL,
  `query_size` tinyint(1) UNSIGNED NOT NULL DEFAULT 10,
  `graphic_links` tinyint(1) DEFAULT 1,
  `pos` smallint(6) DEFAULT 1,
  `print_profile` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rep_popup` tinyint(1) DEFAULT 1,
  `sticky_doc_date` tinyint(1) DEFAULT 0,
  `startup_tab` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `transaction_days` smallint(6) NOT NULL DEFAULT 30,
  `save_report_selections` smallint(6) NOT NULL DEFAULT 0,
  `use_date_picker` tinyint(1) NOT NULL DEFAULT 1,
  `def_print_destination` tinyint(1) NOT NULL DEFAULT 0,
  `def_print_orientation` tinyint(1) NOT NULL DEFAULT 0,
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_users`
--

INSERT INTO `0_users` (`id`, `user_id`, `password`, `real_name`, `role_id`, `phone`, `email`, `language`, `date_format`, `date_sep`, `tho_sep`, `dec_sep`, `theme`, `page_size`, `prices_dec`, `qty_dec`, `rates_dec`, `percent_dec`, `show_gl`, `show_codes`, `show_hints`, `last_visit_date`, `query_size`, `graphic_links`, `pos`, `print_profile`, `rep_popup`, `sticky_doc_date`, `startup_tab`, `transaction_days`, `save_report_selections`, `use_date_picker`, `def_print_destination`, `def_print_orientation`, `inactive`) VALUES
(1, 'admin', '$2y$10$TjVuRVfb.Dp2.B1pEleIROl1/zjV3.SnvqM51MxU5opKArtLIcKJG', 'Administrator', 2, '', 'adm@example.com', 'C', 0, 0, 0, 0, 'default', 'Letter', 2, 2, 4, 1, 1, 0, 0, '2023-02-17 05:29:21', 10, 1, 1, '1', 1, 0, 'orders', 30, 0, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_voided`
--

CREATE TABLE `0_voided` (
  `type` int(11) NOT NULL DEFAULT 0,
  `id` int(11) NOT NULL DEFAULT 0,
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `memo_` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_workcentres`
--

CREATE TABLE `0_workcentres` (
  `id` int(11) NOT NULL,
  `name` char(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_workcentres`
--

INSERT INTO `0_workcentres` (`id`, `name`, `description`, `inactive`) VALUES
(1, 'Work Centre', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_workorders`
--

CREATE TABLE `0_workorders` (
  `id` int(11) NOT NULL,
  `wo_ref` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `loc_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `units_reqd` double NOT NULL DEFAULT 1,
  `stock_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `required_by` date NOT NULL DEFAULT '0000-00-00',
  `released_date` date NOT NULL DEFAULT '0000-00-00',
  `units_issued` double NOT NULL DEFAULT 0,
  `closed` tinyint(1) NOT NULL DEFAULT 0,
  `released` tinyint(1) NOT NULL DEFAULT 0,
  `additional_costs` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_workorders`
--

INSERT INTO `0_workorders` (`id`, `wo_ref`, `loc_code`, `units_reqd`, `stock_id`, `date_`, `type`, `required_by`, `released_date`, `units_issued`, `closed`, `released`, `additional_costs`) VALUES
(1, '001/2021', 'DEF', 2, '201', '2021-05-05', 0, '2021-05-05', '2021-05-05', 2, 1, 1, 0),
(2, '002/2021', 'DEF', 5, '201', '2021-05-07', 2, '2021-05-27', '2021-05-07', 0, 0, 1, 0),
(3, '003/2021', 'DEF', 5, '201', '2021-05-07', 2, '2021-05-27', '0000-00-00', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `0_wo_costing`
--

CREATE TABLE `0_wo_costing` (
  `id` int(11) NOT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT 0,
  `cost_type` tinyint(1) NOT NULL DEFAULT 0,
  `trans_type` int(11) NOT NULL DEFAULT 0,
  `trans_no` int(11) NOT NULL DEFAULT 0,
  `factor` double NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_wo_issues`
--

CREATE TABLE `0_wo_issues` (
  `issue_no` int(11) NOT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT 0,
  `reference` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `loc_code` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workcentre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_wo_issue_items`
--

CREATE TABLE `0_wo_issue_items` (
  `id` int(11) NOT NULL,
  `stock_id` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `qty_issued` double DEFAULT NULL,
  `unit_cost` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `0_wo_manufacture`
--

CREATE TABLE `0_wo_manufacture` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT 0,
  `quantity` double NOT NULL DEFAULT 0,
  `date_` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_wo_manufacture`
--

INSERT INTO `0_wo_manufacture` (`id`, `reference`, `workorder_id`, `quantity`, `date_`) VALUES
(1, '001/2021', 1, 2, '2021-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `0_wo_requirements`
--

CREATE TABLE `0_wo_requirements` (
  `id` int(11) NOT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT 0,
  `stock_id` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `workcentre` int(11) NOT NULL DEFAULT 0,
  `units_req` double NOT NULL DEFAULT 1,
  `unit_cost` double NOT NULL DEFAULT 0,
  `loc_code` char(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `units_issued` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `0_wo_requirements`
--

INSERT INTO `0_wo_requirements` (`id`, `workorder_id`, `stock_id`, `workcentre`, `units_req`, `unit_cost`, `loc_code`, `units_issued`) VALUES
(1, 1, '101', 1, 1, 200, 'DEF', 2),
(2, 1, '102', 1, 1, 150, 'DEF', 2),
(3, 1, '103', 1, 1, 10, 'DEF', 2),
(4, 1, '301', 1, 1, 0, 'DEF', 2),
(5, 2, '101', 1, 1, 200, 'DEF', 0),
(6, 2, '102', 1, 1, 150, 'DEF', 0),
(7, 2, '103', 1, 1, 10, 'DEF', 0),
(8, 2, '301', 1, 1, 0, 'DEF', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `0_areas`
--
ALTER TABLE `0_areas`
  ADD PRIMARY KEY (`area_code`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `0_attachments`
--
ALTER TABLE `0_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_no` (`type_no`,`trans_no`);

--
-- Indexes for table `0_audit_trail`
--
ALTER TABLE `0_audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Seq` (`fiscal_year`,`gl_date`,`gl_seq`),
  ADD KEY `Type_and_Number` (`type`,`trans_no`);

--
-- Indexes for table `0_bank_accounts`
--
ALTER TABLE `0_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_account_name` (`bank_account_name`),
  ADD KEY `bank_account_number` (`bank_account_number`),
  ADD KEY `account_code` (`account_code`);

--
-- Indexes for table `0_bank_trans`
--
ALTER TABLE `0_bank_trans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_act` (`bank_act`,`ref`),
  ADD KEY `type` (`type`,`trans_no`),
  ADD KEY `bank_act_2` (`bank_act`,`reconciled`),
  ADD KEY `bank_act_3` (`bank_act`,`trans_date`);

--
-- Indexes for table `0_bom`
--
ALTER TABLE `0_bom`
  ADD PRIMARY KEY (`parent`,`component`,`workcentre_added`,`loc_code`),
  ADD KEY `component` (`component`),
  ADD KEY `id` (`id`),
  ADD KEY `loc_code` (`loc_code`),
  ADD KEY `parent` (`parent`,`loc_code`),
  ADD KEY `workcentre_added` (`workcentre_added`);

--
-- Indexes for table `0_budget_trans`
--
ALTER TABLE `0_budget_trans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Account` (`account`,`tran_date`,`dimension_id`,`dimension2_id`);

--
-- Indexes for table `0_chart_class`
--
ALTER TABLE `0_chart_class`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `0_chart_master`
--
ALTER TABLE `0_chart_master`
  ADD PRIMARY KEY (`account_code`),
  ADD KEY `account_name` (`account_name`),
  ADD KEY `accounts_by_type` (`account_type`,`account_code`);

--
-- Indexes for table `0_chart_types`
--
ALTER TABLE `0_chart_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `0_comments`
--
ALTER TABLE `0_comments`
  ADD UNIQUE KEY `type_id_date` (`type`,`id`,`date_`),
  ADD KEY `type_and_id` (`type`,`id`);

--
-- Indexes for table `0_credit_status`
--
ALTER TABLE `0_credit_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reason_description` (`reason_description`);

--
-- Indexes for table `0_crm_categories`
--
ALTER TABLE `0_crm_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`,`action`),
  ADD UNIQUE KEY `type_2` (`type`,`name`);

--
-- Indexes for table `0_crm_contacts`
--
ALTER TABLE `0_crm_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`,`action`);

--
-- Indexes for table `0_crm_persons`
--
ALTER TABLE `0_crm_persons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref` (`ref`);

--
-- Indexes for table `0_currencies`
--
ALTER TABLE `0_currencies`
  ADD PRIMARY KEY (`curr_abrev`);

--
-- Indexes for table `0_cust_allocations`
--
ALTER TABLE `0_cust_allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_type_from` (`person_id`,`trans_type_from`,`trans_no_from`,`trans_type_to`,`trans_no_to`),
  ADD KEY `From` (`trans_type_from`,`trans_no_from`),
  ADD KEY `To` (`trans_type_to`,`trans_no_to`);

--
-- Indexes for table `0_cust_branch`
--
ALTER TABLE `0_cust_branch`
  ADD PRIMARY KEY (`branch_code`,`debtor_no`),
  ADD KEY `branch_ref` (`branch_ref`),
  ADD KEY `group_no` (`group_no`);

--
-- Indexes for table `0_debtors_master`
--
ALTER TABLE `0_debtors_master`
  ADD PRIMARY KEY (`debtor_no`),
  ADD UNIQUE KEY `debtor_ref` (`debtor_ref`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `0_debtor_trans`
--
ALTER TABLE `0_debtor_trans`
  ADD PRIMARY KEY (`type`,`trans_no`,`debtor_no`),
  ADD KEY `debtor_no` (`debtor_no`,`branch_code`),
  ADD KEY `tran_date` (`tran_date`),
  ADD KEY `order_` (`order_`);

--
-- Indexes for table `0_debtor_trans_details`
--
ALTER TABLE `0_debtor_trans_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Transaction` (`debtor_trans_type`,`debtor_trans_no`),
  ADD KEY `src_id` (`src_id`);

--
-- Indexes for table `0_dimensions`
--
ALTER TABLE `0_dimensions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `date_` (`date_`),
  ADD KEY `due_date` (`due_date`),
  ADD KEY `type_` (`type_`);

--
-- Indexes for table `0_exchange_rates`
--
ALTER TABLE `0_exchange_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curr_code` (`curr_code`,`date_`);

--
-- Indexes for table `0_fiscal_year`
--
ALTER TABLE `0_fiscal_year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `begin` (`begin`),
  ADD UNIQUE KEY `end` (`end`);

--
-- Indexes for table `0_gl_trans`
--
ALTER TABLE `0_gl_trans`
  ADD PRIMARY KEY (`counter`),
  ADD KEY `Type_and_Number` (`type`,`type_no`),
  ADD KEY `dimension_id` (`dimension_id`),
  ADD KEY `dimension2_id` (`dimension2_id`),
  ADD KEY `tran_date` (`tran_date`),
  ADD KEY `account_and_tran_date` (`account`,`tran_date`);

--
-- Indexes for table `0_grn_batch`
--
ALTER TABLE `0_grn_batch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_date` (`delivery_date`),
  ADD KEY `purch_order_no` (`purch_order_no`);

--
-- Indexes for table `0_grn_items`
--
ALTER TABLE `0_grn_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `grn_batch_id` (`grn_batch_id`);

--
-- Indexes for table `0_groups`
--
ALTER TABLE `0_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `0_item_codes`
--
ALTER TABLE `0_item_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_id` (`stock_id`,`item_code`),
  ADD KEY `item_code` (`item_code`);

--
-- Indexes for table `0_item_tax_types`
--
ALTER TABLE `0_item_tax_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `0_item_tax_type_exemptions`
--
ALTER TABLE `0_item_tax_type_exemptions`
  ADD PRIMARY KEY (`item_tax_type_id`,`tax_type_id`);

--
-- Indexes for table `0_item_units`
--
ALTER TABLE `0_item_units`
  ADD PRIMARY KEY (`abbr`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `0_journal`
--
ALTER TABLE `0_journal`
  ADD PRIMARY KEY (`type`,`trans_no`),
  ADD KEY `tran_date` (`tran_date`);

--
-- Indexes for table `0_locations`
--
ALTER TABLE `0_locations`
  ADD PRIMARY KEY (`loc_code`);

--
-- Indexes for table `0_loc_stock`
--
ALTER TABLE `0_loc_stock`
  ADD PRIMARY KEY (`loc_code`,`stock_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indexes for table `0_payment_terms`
--
ALTER TABLE `0_payment_terms`
  ADD PRIMARY KEY (`terms_indicator`),
  ADD UNIQUE KEY `terms` (`terms`);

--
-- Indexes for table `0_prices`
--
ALTER TABLE `0_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `price` (`stock_id`,`sales_type_id`,`curr_abrev`);

--
-- Indexes for table `0_printers`
--
ALTER TABLE `0_printers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `0_print_profiles`
--
ALTER TABLE `0_print_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profile` (`profile`,`report`);

--
-- Indexes for table `0_purch_data`
--
ALTER TABLE `0_purch_data`
  ADD PRIMARY KEY (`supplier_id`,`stock_id`);

--
-- Indexes for table `0_purch_orders`
--
ALTER TABLE `0_purch_orders`
  ADD PRIMARY KEY (`order_no`),
  ADD KEY `ord_date` (`ord_date`);

--
-- Indexes for table `0_purch_order_details`
--
ALTER TABLE `0_purch_order_details`
  ADD PRIMARY KEY (`po_detail_item`),
  ADD KEY `order` (`order_no`,`po_detail_item`),
  ADD KEY `itemcode` (`item_code`);

--
-- Indexes for table `0_quick_entries`
--
ALTER TABLE `0_quick_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `description` (`description`);

--
-- Indexes for table `0_quick_entry_lines`
--
ALTER TABLE `0_quick_entry_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `0_recurrent_invoices`
--
ALTER TABLE `0_recurrent_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `0_reflines`
--
ALTER TABLE `0_reflines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prefix` (`trans_type`,`prefix`);

--
-- Indexes for table `0_refs`
--
ALTER TABLE `0_refs`
  ADD PRIMARY KEY (`id`,`type`),
  ADD KEY `Type_and_Reference` (`type`,`reference`);

--
-- Indexes for table `0_salesman`
--
ALTER TABLE `0_salesman`
  ADD PRIMARY KEY (`salesman_code`),
  ADD UNIQUE KEY `salesman_name` (`salesman_name`);

--
-- Indexes for table `0_sales_orders`
--
ALTER TABLE `0_sales_orders`
  ADD PRIMARY KEY (`trans_type`,`order_no`);

--
-- Indexes for table `0_sales_order_details`
--
ALTER TABLE `0_sales_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sorder` (`trans_type`,`order_no`),
  ADD KEY `stkcode` (`stk_code`);

--
-- Indexes for table `0_sales_pos`
--
ALTER TABLE `0_sales_pos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pos_name` (`pos_name`);

--
-- Indexes for table `0_sales_types`
--
ALTER TABLE `0_sales_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_type` (`sales_type`);

--
-- Indexes for table `0_security_roles`
--
ALTER TABLE `0_security_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role` (`role`);

--
-- Indexes for table `0_shippers`
--
ALTER TABLE `0_shippers`
  ADD PRIMARY KEY (`shipper_id`),
  ADD UNIQUE KEY `name` (`shipper_name`);

--
-- Indexes for table `0_sql_trail`
--
ALTER TABLE `0_sql_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `0_stock_category`
--
ALTER TABLE `0_stock_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Indexes for table `0_stock_colour`
--
ALTER TABLE `0_stock_colour`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `0_stock_fa_class`
--
ALTER TABLE `0_stock_fa_class`
  ADD PRIMARY KEY (`fa_class_id`);

--
-- Indexes for table `0_stock_main_category`
--
ALTER TABLE `0_stock_main_category`
  ADD PRIMARY KEY (`maincat_id`);

--
-- Indexes for table `0_stock_master`
--
ALTER TABLE `0_stock_master`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `0_stock_moves`
--
ALTER TABLE `0_stock_moves`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `type` (`type`,`trans_no`),
  ADD KEY `Move` (`stock_id`,`loc_code`,`tran_date`);

--
-- Indexes for table `0_stock_size`
--
ALTER TABLE `0_stock_size`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `0_stock_style`
--
ALTER TABLE `0_stock_style`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `0_suppliers`
--
ALTER TABLE `0_suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `supp_ref` (`supp_ref`);

--
-- Indexes for table `0_supp_allocations`
--
ALTER TABLE `0_supp_allocations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trans_type_from` (`person_id`,`trans_type_from`,`trans_no_from`,`trans_type_to`,`trans_no_to`),
  ADD KEY `From` (`trans_type_from`,`trans_no_from`),
  ADD KEY `To` (`trans_type_to`,`trans_no_to`);

--
-- Indexes for table `0_supp_invoice_items`
--
ALTER TABLE `0_supp_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Transaction` (`supp_trans_type`,`supp_trans_no`,`stock_id`);

--
-- Indexes for table `0_supp_trans`
--
ALTER TABLE `0_supp_trans`
  ADD PRIMARY KEY (`type`,`trans_no`,`supplier_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `tran_date` (`tran_date`);

--
-- Indexes for table `0_sys_prefs`
--
ALTER TABLE `0_sys_prefs`
  ADD PRIMARY KEY (`name`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `0_tags`
--
ALTER TABLE `0_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`,`name`);

--
-- Indexes for table `0_tag_associations`
--
ALTER TABLE `0_tag_associations`
  ADD PRIMARY KEY (`record_id`,`tag_id`);

--
-- Indexes for table `0_tax_groups`
--
ALTER TABLE `0_tax_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `0_tax_group_items`
--
ALTER TABLE `0_tax_group_items`
  ADD PRIMARY KEY (`tax_group_id`,`tax_type_id`);

--
-- Indexes for table `0_tax_types`
--
ALTER TABLE `0_tax_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `0_trans_tax_details`
--
ALTER TABLE `0_trans_tax_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Type_and_Number` (`trans_type`,`trans_no`),
  ADD KEY `tran_date` (`tran_date`);

--
-- Indexes for table `0_useronline`
--
ALTER TABLE `0_useronline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timestamp` (`timestamp`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `0_users`
--
ALTER TABLE `0_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `0_voided`
--
ALTER TABLE `0_voided`
  ADD UNIQUE KEY `id` (`type`,`id`);

--
-- Indexes for table `0_workcentres`
--
ALTER TABLE `0_workcentres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `0_workorders`
--
ALTER TABLE `0_workorders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wo_ref` (`wo_ref`);

--
-- Indexes for table `0_wo_costing`
--
ALTER TABLE `0_wo_costing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `0_wo_issues`
--
ALTER TABLE `0_wo_issues`
  ADD PRIMARY KEY (`issue_no`),
  ADD KEY `workorder_id` (`workorder_id`);

--
-- Indexes for table `0_wo_issue_items`
--
ALTER TABLE `0_wo_issue_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `0_wo_manufacture`
--
ALTER TABLE `0_wo_manufacture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workorder_id` (`workorder_id`);

--
-- Indexes for table `0_wo_requirements`
--
ALTER TABLE `0_wo_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workorder_id` (`workorder_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `0_areas`
--
ALTER TABLE `0_areas`
  MODIFY `area_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_attachments`
--
ALTER TABLE `0_attachments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_audit_trail`
--
ALTER TABLE `0_audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `0_bank_accounts`
--
ALTER TABLE `0_bank_accounts`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_bank_trans`
--
ALTER TABLE `0_bank_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `0_bom`
--
ALTER TABLE `0_bom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `0_budget_trans`
--
ALTER TABLE `0_budget_trans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_credit_status`
--
ALTER TABLE `0_credit_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `0_crm_categories`
--
ALTER TABLE `0_crm_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pure technical key', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `0_crm_contacts`
--
ALTER TABLE `0_crm_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `0_crm_persons`
--
ALTER TABLE `0_crm_persons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `0_cust_allocations`
--
ALTER TABLE `0_cust_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `0_cust_branch`
--
ALTER TABLE `0_cust_branch`
  MODIFY `branch_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_debtors_master`
--
ALTER TABLE `0_debtors_master`
  MODIFY `debtor_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_debtor_trans_details`
--
ALTER TABLE `0_debtor_trans_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `0_dimensions`
--
ALTER TABLE `0_dimensions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_exchange_rates`
--
ALTER TABLE `0_exchange_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `0_fiscal_year`
--
ALTER TABLE `0_fiscal_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_gl_trans`
--
ALTER TABLE `0_gl_trans`
  MODIFY `counter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `0_grn_batch`
--
ALTER TABLE `0_grn_batch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `0_grn_items`
--
ALTER TABLE `0_grn_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `0_groups`
--
ALTER TABLE `0_groups`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `0_item_codes`
--
ALTER TABLE `0_item_codes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `0_item_tax_types`
--
ALTER TABLE `0_item_tax_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_payment_terms`
--
ALTER TABLE `0_payment_terms`
  MODIFY `terms_indicator` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `0_prices`
--
ALTER TABLE `0_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `0_printers`
--
ALTER TABLE `0_printers`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `0_print_profiles`
--
ALTER TABLE `0_print_profiles`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `0_purch_orders`
--
ALTER TABLE `0_purch_orders`
  MODIFY `order_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `0_purch_order_details`
--
ALTER TABLE `0_purch_order_details`
  MODIFY `po_detail_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `0_quick_entries`
--
ALTER TABLE `0_quick_entries`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `0_quick_entry_lines`
--
ALTER TABLE `0_quick_entry_lines`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `0_recurrent_invoices`
--
ALTER TABLE `0_recurrent_invoices`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_reflines`
--
ALTER TABLE `0_reflines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `0_salesman`
--
ALTER TABLE `0_salesman`
  MODIFY `salesman_code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_sales_order_details`
--
ALTER TABLE `0_sales_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `0_sales_pos`
--
ALTER TABLE `0_sales_pos`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_sales_types`
--
ALTER TABLE `0_sales_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_security_roles`
--
ALTER TABLE `0_security_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `0_shippers`
--
ALTER TABLE `0_shippers`
  MODIFY `shipper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_sql_trail`
--
ALTER TABLE `0_sql_trail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_stock_category`
--
ALTER TABLE `0_stock_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `0_stock_colour`
--
ALTER TABLE `0_stock_colour`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=791;

--
-- AUTO_INCREMENT for table `0_stock_main_category`
--
ALTER TABLE `0_stock_main_category`
  MODIFY `maincat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `0_stock_moves`
--
ALTER TABLE `0_stock_moves`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `0_stock_size`
--
ALTER TABLE `0_stock_size`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `0_stock_style`
--
ALTER TABLE `0_stock_style`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_suppliers`
--
ALTER TABLE `0_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_supp_allocations`
--
ALTER TABLE `0_supp_allocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_supp_invoice_items`
--
ALTER TABLE `0_supp_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_tags`
--
ALTER TABLE `0_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_tax_groups`
--
ALTER TABLE `0_tax_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `0_tax_types`
--
ALTER TABLE `0_tax_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_trans_tax_details`
--
ALTER TABLE `0_trans_tax_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `0_useronline`
--
ALTER TABLE `0_useronline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_users`
--
ALTER TABLE `0_users`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_workcentres`
--
ALTER TABLE `0_workcentres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_workorders`
--
ALTER TABLE `0_workorders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `0_wo_costing`
--
ALTER TABLE `0_wo_costing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_wo_issues`
--
ALTER TABLE `0_wo_issues`
  MODIFY `issue_no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_wo_issue_items`
--
ALTER TABLE `0_wo_issue_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `0_wo_manufacture`
--
ALTER TABLE `0_wo_manufacture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `0_wo_requirements`
--
ALTER TABLE `0_wo_requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
