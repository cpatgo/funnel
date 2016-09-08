-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 09, 2016 at 07:41 PM
-- Server version: 5.5.48-MariaDB
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cielbleu_glcdev`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cr` float NOT NULL,
  `dr` float NOT NULL,
  `type` int(11) NOT NULL,
  `date` date NOT NULL,
  `account` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wallet_balance` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=51 ;

--
-- Truncate table before insert `account`
--

TRUNCATE TABLE `account`;
--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `user_id`, `cr`, `dr`, `type`, `date`, `account`, `wallet_balance`) VALUES
(1, 1, 150, 0, 3, '2016-01-05', 'cycles on level 1', 0),
(2, 1, 250, 0, 3, '2016-01-05', 'cycles on level 2', 0),
(3, 1, 500, 0, 3, '2016-01-05', 'cycles on level 3', 0),
(4, 2, 150, 0, 3, '2016-01-05', 'cycles on level 1', 0),
(5, 2, 250, 0, 3, '2016-01-05', 'cycles on level 2', 0),
(6, 2, 500, 0, 3, '2016-01-05', 'cycles on level 3', 0),
(7, 1, 1200, 0, 3, '2016-01-05', 'cycles on level 4', 0),
(8, 4, 150, 0, 3, '2016-01-05', 'cycles on level 1', 0),
(9, 4, 250, 0, 3, '2016-01-05', 'cycles on level 2', 0),
(10, 4, 500, 0, 3, '2016-01-05', 'cycles on level 3', 0),
(11, 2, 1200, 0, 3, '2016-01-06', 'cycles on level 4', 0),
(12, 5, 150, 0, 3, '2016-01-07', 'cycles on level 1', 0),
(13, 5, 250, 0, 3, '2016-01-07', 'cycles on level 2', 0),
(14, 3, 150, 0, 3, '2016-01-07', 'cycles on level 1', 0),
(15, 3, 250, 0, 3, '2016-01-07', 'cycles on level 2', 0),
(16, 1, 150, 0, 3, '2016-01-08', 'cycles on level 1', 0),
(17, 1, 250, 0, 3, '2016-01-08', 'cycles on level 2', 0),
(18, 3, 500, 0, 3, '2016-01-09', 'cycles on level 3', 0),
(19, 3, 1200, 0, 3, '2016-01-09', 'cycles on level 4', 0),
(20, 8, 150, 0, 3, '2016-01-12', 'cycles on level 1', 0),
(21, 8, 250, 0, 3, '2016-01-12', 'cycles on level 2', 0),
(22, 1, 500, 0, 3, '2016-01-13', 'cycles on level 3', 0),
(23, 9, 150, 0, 3, '2016-01-14', 'cycles on level 1', 0),
(24, 2, 150, 0, 3, '2016-01-15', 'cycles on level 1', 0),
(25, 2, 250, 0, 3, '2016-01-15', 'cycles on level 2', 0),
(26, 12, 150, 0, 3, '2016-01-18', 'cycles on level 1', 0),
(27, 11, 500, 0, 3, '2016-01-18', 'cycles on level 3', 0),
(28, 12, 250, 0, 3, '2016-01-20', 'cycles on level 2', 0),
(29, 6, 150, 0, 3, '2016-01-21', 'cycles on level 1', 0),
(30, 6, 250, 0, 3, '2016-01-21', 'cycles on level 2', 0),
(31, 6, 500, 0, 3, '2016-01-21', 'cycles on level 3', 0),
(32, 1, 1200, 0, 3, '2016-01-21', 'cycles on level 4', 0),
(33, 1, 150, 0, 3, '2016-01-25', 'cycles on level 1', 0),
(34, 13, 500, 0, 3, '2016-01-25', 'cycles on level 3', 0),
(35, 1, 3000, 0, 3, '2016-01-25', 'cycles on level 5', 0),
(36, 9, 250, 0, 3, '2016-01-26', 'cycles on level 2', 0),
(37, 1, 250, 0, 3, '2016-01-26', 'cycles on level 2', 0),
(38, 10, 150, 0, 3, '2016-01-27', 'cycles on level 1', 0),
(39, 10, 250, 0, 3, '2016-01-27', 'cycles on level 2', 0),
(40, 11, 150, 0, 3, '2016-01-28', 'cycles on level 1', 0),
(41, 11, 250, 0, 3, '2016-01-28', 'cycles on level 2', 0),
(42, 19, 150, 0, 3, '2016-01-29', 'cycles on level 1', 0),
(43, 7, 150, 0, 3, '2016-01-29', 'cycles on level 1', 0),
(44, 7, 250, 0, 3, '2016-01-29', 'cycles on level 2', 0),
(45, 18, 150, 0, 3, '2016-01-29', 'cycles on level 1', 0),
(46, 5, 150, 0, 3, '2016-01-30', 'cycles on level 1', 0),
(47, 3, 150, 0, 3, '2016-01-30', 'cycles on level 1', 0),
(48, 2, 500, 0, 3, '2016-02-03', 'cycles on level 3', 0),
(49, 1, 500, 0, 3, '2016-02-03', 'cycles on level 3', 0),
(50, 2, 150, 0, 3, '2016-02-06', 'cycles on level 1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `admin`
--

TRUNCATE TABLE `admin`;
--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_user`, `username`, `password`) VALUES
(0, 'Learncash', 'd1c6cafed17a28e6e85e8967a08c68d2e5897e60');

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE IF NOT EXISTS `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL,
  `parent_menu` varchar(255) NOT NULL,
  `menu_file` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

--
-- Truncate table before insert `admin_menu`
--

TRUNCATE TABLE `admin_menu`;
--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `menu`, `parent_menu`, `menu_file`, `active`) VALUES
(1, 'Dashboard', '0', 'project_summary', 1),
(2, 'Accounting', '0', 'accounting', 1),
(3, 'Members', '0', 'network', 1),
(4, 'Board', '0', 'tree_views', 1),
(5, 'Commissions', '0', 'payout', 1),
(6, 'Wallet', '0', 'wallet', 1),
(7, 'Vouchers', '0', 'vouchers', 1),
(8, 'Reports', '0', 'account', 1),
(9, 'Quick View', 'project_summary', 'projects_summary', 1),
(10, 'Logs', 'project_summary', 'project_logs', 1),
(11, 'Finiancial Logs', 'project_summary123', 'finiancial_logs1', 1),
(12, 'Joining Members', 'network', 'joining_report', 1),
(13, 'Add Shopping Products', 'shopping', 'add_shopping_products', 1),
(14, 'New Joinings', 'network123', 'new_joining', 1),
(15, 'Unpaid Members', 'network', 'unpaid_byprocessor', 0),
(16, 'Edit Member Profile', 'network', 'edit_profile', 1),
(17, 'Member Enrollees', 'network', 'direct_member', 1),
(18, 'Active Boards', 'tree_views', 'active_board', 1),
(19, 'Network Members', 'network', 'network_members', 1),
(20, 'Completed Boards', 'tree_views', 'inactive_board', 1),
(21, 'Search By User', 'payout', 'board_income', 1),
(22, 'Updates', 'setting123', 'updates_show', 1),
(23, 'Board Points', 'payout', 'board_point', 0),
(24, 'Account Balance', 'wallet', 'wallet_amount', 1),
(25, 'Add Balance', 'wallet12', 'add_funds', 1),
(26, 'Requested Order', 'shopping', 'requested_order', 1),
(27, 'Apporved Order', 'shopping', 'apporved_order', 1),
(28, 'Generate e-Voucher', 'e-pin', 'generate_pin', 1),
(29, 'Shopping Products', 'shopping', 'shopping_products', 1),
(30, 'General Setting', 'setting', 'general_setting', 1),
(31, 'Network Setting', 'setting', 'network_setting', 1),
(32, 'Alert Messages', 'setting123', 'alert_message_to_member', 1),
(33, 'Withdrawal Bal. Request', 'wallet', 'withdrawal_balance_request', 1),
(34, 'Approved Funds', 'wallet', 'approved_funds', 1),
(35, 'System On/Off ', 'setting', 'system_on_off', 1),
(36, 'Edit Balance', 'wallet', 'edit_wallet_amount', 1),
(37, 'User Investment', 'investment', 'user_investment', 1),
(38, 'Monthly Investment', 'investment', 'monthly_investment', 1),
(39, 'Investment Information', 'investment', 'investment_information', 1),
(40, 'Update Investment', 'investment', 'update_investment', 1),
(41, 'E - Vouchers', '0', 'e-pin', 1),
(42, 'Active Vouchers', 'vouchers', 'active_vouchers', 1),
(43, 'Inactive Vouchers', 'vouchers', 'inactive_vouchers', 1),
(44, 'Member List', 'network', 'member_list', 1),
(45, 'Pending Registration', 'setting123', 'pending_registration', 1),
(48, 'User Profile', 'profile123', 'user_profile', 1),
(46, 'Block Member', 'network', 'block_member', 1),
(47, 'Get Rewards', 'rewards', 'get_rewards', 1),
(49, 'Member Info', 'network', 'user_information', 1),
(50, 'Blocked Member List', 'network', 'block_member_list', 1),
(52, 'Network Logs', 'network123', 'network_logs', 1),
(53, 'Financial Logs', 'wallet', 'finiancial_logs', 1),
(54, 'Block member Logs', 'tree_views321', 'block_member_logs', 1),
(55, 'Investment History', 'investment', 'investment_logs', 1),
(56, 'Change Password', 'setting', 'change_password', 1),
(57, 'Add Rewards', 'rewards', 'add_rewards', 1),
(58, 'Show Rewards', 'rewards', 'show_rewards', 1),
(62, 'Generate for User', 'generate_pin2', 'generate_for_user', 1),
(59, 'Used e-Voucher', 'e-pin', 'used_epin', 1),
(60, 'UnUsed e-Voucher', 'e-pin', 'unused_epin', 1),
(61, 'Seacrh e-Voucher', 'e-pin', 'seacrh_epin', 1),
(63, 'Transfer e-Voucher', 'e-pin', 'transfer_epin', 1),
(64, 'Check e-Voucher', 'e-pin121', 'check_pin', 1),
(65, 'Finance', '0123', 'finance', 1),
(66, 'Board Vouchers Achievers', 'voucher_panel', 'get_user_vouchers', 1),
(67, 'System Date', 'setting', 'system_date', 1),
(68, 'Booked Products', 'products', 'booked_products', 1),
(69, 'Approved Board Vouchers', 'voucher_panel', 'approved_board_vouchers', 1),
(70, 'Block Ip Address List', 'setting213', 'block_ip_add_list', 1),
(71, 'Latest Update', 'updates_show', 'latest_update', 1),
(72, 'Previous Update', 'updates_show', 'previous_update', 1),
(73, 'Deduct Wallet Balance', 'wallet', 'deduct_wallet_balance', 1),
(74, 'My Account Balance', 'current_balance', 'account_balance', 1),
(75, 'Registration Pin', 'used_pin7', 'reg_used_pin', 1),
(76, 'TopUp Pin', 'used_pin1', 'upg_used_pin', 1),
(77, 'SMS', '0', 'sms', 1),
(78, 'Advertisement', '0', 'advert', 1),
(79, 'Setting', '0', 'setting', 1),
(80, 'Add Distributor', 'distributor_panel', 'add_distributor', 1),
(81, 'Distributor Sale', 'distributor_panel', 'distributor_sale', 1),
(82, 'Distributor List', 'distributor_panel', 'distributor_list', 1),
(83, 'Per Day Status', 'matching_status', 'current_matching_status', 1),
(84, 'Per Closing', 'matching_status', 'closing_status', 1),
(85, 'Add Products', 'setting123', 'add_products', 1),
(86, 'Edit Products', 'setting123', 'edit_products', 1),
(87, 'Cash Member', 'network', 'cash_member', 0),
(88, 'Customer', 'network', 'customer', 0),
(89, 'Help Customer', 'network', 'help_customer', 0),
(90, 'Board Positions', 'tree_views', 'board_position', 1),
(91, 'Search Enroller', 'tree_views', 'upline', 1),
(92, 'Leader Board', 'tree_views', 'leader_board', 1),
(93, 'Latest Member', 'tree_views', 'latest_member', 1),
(94, 'Compose Message', 'sms', 'compose', 1),
(95, 'Inbox', 'sms', 'inbox', 1),
(96, 'Sent', 'sms', 'sent_message', 1),
(97, 'Category', 'advert', 'category', 1),
(98, 'Add New Category', 'advert', 'add_category', 1),
(99, 'Current Month History', 'account', 'current_month_acc_history', 1),
(101, 'History By Day', 'account', 'day_history_acc', 1),
(102, 'History By Month', 'account', 'month_acc_history', 1),
(103, 'Payments', 'accounting', 'payments', 1),
(104, 'Members', 'accounting', 'members', 1),
(105, 'Pending Members', 'accounting', 'pending_members', 1),
(106, 'Commissions', 'accounting', 'commissions', 1),
(107, 'Pending Commissions', 'accounting', 'pending_commissions', 1),
(108, 'Denied Commissions', 'accounting', 'denied_commissions', 1),
(109, 'Documents', 'accounting', 'documents', 1),
(110, 'Mass Payment', 'accounting', 'mass_payment', 1),
(111, 'Email', 'accounting', 'email', 0),
(112, 'Export / Import', 'network', 'export_import', 1),
(113, 'Export Pending Payments', 'mass_payment', 'mass_payment_export', 1),
(114, 'Import Payments', 'mass_payment', 'mass_payment_import', 1),
(115, 'e-Voucher Pending Payments', 'e-pin', 'pending_payments', 1),
(116, 'e-Voucher Payments', 'e-pin', 'voucher_payments', 1),
(117, 'Merchant Settings', 'setting', 'merchant_settings', 1),
(118, 'Payment Transactions (Credit Card)', 'accounting', 'payment_transactions', 0),
(119, 'Payment Transactions (Upgrade Membership)',  'accounting', 'upgrade_transactions', 0),
(120, 'Upgrade Member', 'network',  'upgrade_member', 1),
(121, 'Pending Member Upgrade', 'accounting', 'pending_upgrade',  1),
(122, 'Membership Upgrades',  'accounting', 'membership_upgrades',  1);


-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `detail` text NOT NULL,
  `catg_id` int(11) NOT NULL,
  `img` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `ads`
--

TRUNCATE TABLE `ads`;
-- --------------------------------------------------------

--
-- Table structure for table `ads_category`
--

DROP TABLE IF EXISTS `ads_category`;
CREATE TABLE IF NOT EXISTS `ads_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catg_name` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `ads_category`
--

TRUNCATE TABLE `ads_category`;
-- --------------------------------------------------------

--
-- Table structure for table `advertisement`
--

DROP TABLE IF EXISTS `advertisement`;
CREATE TABLE IF NOT EXISTS `advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `advertisement`
--

TRUNCATE TABLE `advertisement`;
--
-- Dumping data for table `advertisement`
--

INSERT INTO `advertisement` (`id`, `title`, `content`, `img`) VALUES
(1, 'phpMyAdmin 3.1.3.1 -\n    192.168.1.101', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `app_members`
--

DROP TABLE IF EXISTS `app_members`;
CREATE TABLE IF NOT EXISTS `app_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `fname` varchar(200) NOT NULL DEFAULT '',
  `lname` varchar(200) NOT NULL DEFAULT '',
  `gender` int(11) DEFAULT '1',
  `street` varchar(200) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `app_members`
--

TRUNCATE TABLE `app_members`;
-- --------------------------------------------------------

--
-- Table structure for table `authorize_ipn`
--

DROP TABLE IF EXISTS `authorize_ipn`;
CREATE TABLE IF NOT EXISTS `authorize_ipn` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cc_fname` varchar(50) NOT NULL,
  `cc_lname` varchar(50) NOT NULL,
  `response` tinyint(2) NOT NULL,
  `responsetext` varchar(255) NOT NULL,
  `authcode` varchar(255) NOT NULL,
  `transactionid` varchar(50) NOT NULL,
  `avsresponse` varchar(100) NOT NULL,
  `cvvresponse` varchar(100) NOT NULL,
  `orderid` bigint(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `response_code` int(3) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` tinyint(2) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `authorize_ipn`
--

TRUNCATE TABLE `authorize_ipn`;
-- --------------------------------------------------------

--
-- Table structure for table `board`
--

DROP TABLE IF EXISTS `board`;
CREATE TABLE IF NOT EXISTS `board` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `pos1` int(5) NOT NULL,
  `pos2` int(5) NOT NULL,
  `pos3` int(5) NOT NULL,
  `pos4` int(5) NOT NULL,
  `pos5` int(5) NOT NULL,
  `pos6` int(5) NOT NULL,
  `pos7` int(5) NOT NULL,
  `level` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Truncate table before insert `board`
--

TRUNCATE TABLE `board`;
--
-- Dumping data for table `board`
--

INSERT INTO `board` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 4, 5, 6, 7, 0, '2016-01-05', 1452016488, 0),
(2, 1, 0, 2, 4, 5, 1, 8, 9, 10, 0, '2016-01-05', 1452016544, 0),
(3, 2, 0, 3, 6, 7, 4, 5, 21, 22, 0, '2016-01-07', 1452190450, 0),
(4, 0, 0, 4, 1, 8, 2, 11, 12, 13, 0, '2016-01-05', 1452024915, 0),
(5, 0, 0, 5, 9, 10, 15, 17, 18, 19, 0, '2016-01-07', 1452128193, 0),
(6, 0, 0, 1, 2, 11, 14, 3, 24, 26, 0, '2016-01-08', 1452276380, 0),
(7, 0, 0, 8, 12, 13, 16, 1, 29, 32, 0, '2016-01-12', 1452631762, 0),
(8, 0, 0, 9, 15, 17, 20, 35, 38, 39, 0, '2016-01-14', 1452791371, 0),
(9, 0, 0, 10, 18, 19, 25, 1, 56, 58, 0, '2016-01-27', 1453902386, 0),
(10, 0, 0, 6, 4, 5, 30, 8, 45, 51, 0, '2016-01-21', 1453394885, 0),
(11, 0, 0, 7, 21, 22, 23, 34, 41, 67, 0, '2016-01-29', 1454079286, 0),
(12, 1, 0, 2, 14, 3, 28, 33, 42, 43, 0, '2016-01-15', 1452899084, 0),
(13, 0, 0, 11, 24, 26, 40, 46, 48, 61, 0, '2016-01-28', 1453946410, 0),
(14, 0, 0, 12, 16, 1, 36, 9, 2, 47, 0, '2016-01-18', 1453134778, 0),
(15, 6, 0, 13, 29, 32, 37, 0, 0, 0, 0, '2016-01-13', 0, 1),
(16, 7, 0, 15, 20, 35, 54, 0, 0, 0, 0, '2016-01-24', 0, 1),
(17, 8, 0, 17, 38, 39, 57, 0, 0, 0, 0, '2016-01-26', 0, 1),
(18, 7, 0, 14, 28, 33, 0, 0, 0, 0, 0, '2016-01-15', 0, 1),
(19, 2, 0, 3, 42, 43, 44, 49, 63, 5, 0, '2016-01-30', 1454185070, 0),
(20, 8, 0, 16, 36, 9, 19, 0, 0, 0, 0, '2016-01-29', 0, 1),
(21, 0, 0, 1, 2, 47, 12, 50, 6, 55, 0, '2016-01-25', 1453762173, 0),
(22, 0, 0, 4, 30, 8, 0, 0, 0, 0, 0, '2016-01-21', 0, 1),
(23, 0, 0, 5, 45, 51, 52, 18, 71, 72, 0, '2016-01-30', 1454185070, 0),
(24, 1, 0, 2, 12, 50, 11, 3, 75, 78, 0, '2016-02-06', 1454800320, 0),
(25, 23, 0, 47, 6, 55, 0, 0, 0, 0, 0, '2016-01-25', 0, 1),
(26, 9, 0, 18, 25, 1, 10, 7, 68, 69, 0, '2016-01-29', 1454095854, 0),
(27, 9, 0, 19, 56, 58, 59, 60, 65, 66, 0, '2016-01-29', 1454076066, 0),
(28, 12, 0, 24, 40, 46, 62, 80, 82, 0, 0, '2016-02-25', 0, 1),
(29, 13, 0, 26, 48, 61, 0, 0, 0, 0, 0, '2016-01-28', 0, 1),
(30, 0, 0, 56, 59, 60, 70, 79, 0, 0, 0, '2016-02-11', 0, 1),
(31, 0, 0, 58, 65, 66, 0, 0, 0, 0, 0, '2016-01-29', 0, 1),
(32, 10, 0, 21, 23, 34, 0, 0, 0, 0, 0, '2016-01-29', 0, 1),
(33, 11, 0, 22, 41, 67, 0, 0, 0, 0, 0, '2016-01-29', 0, 1),
(34, 0, 0, 25, 10, 7, 77, 0, 0, 0, 0, '2016-02-04', 0, 1),
(35, 0, 0, 1, 68, 69, 2, 0, 0, 0, 0, '2016-02-06', 0, 1),
(36, 22, 0, 45, 52, 18, 81, 0, 0, 0, 0, '2016-02-22', 0, 1),
(37, 25, 0, 51, 71, 72, 0, 0, 0, 0, 0, '2016-01-30', 0, 1),
(38, 21, 0, 42, 44, 49, 0, 0, 0, 0, 0, '2016-01-30', 0, 1),
(39, 21, 0, 43, 63, 5, 76, 0, 0, 0, 0, '2016-02-04', 0, 1),
(40, 0, 0, 12, 11, 3, 0, 0, 0, 0, 0, '2016-02-06', 0, 1),
(41, 0, 0, 50, 75, 78, 0, 0, 0, 0, 0, '2016-02-06', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_break`
--

DROP TABLE IF EXISTS `board_break`;
CREATE TABLE IF NOT EXISTS `board_break` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_b_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `qualified_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=217 ;

--
-- Truncate table before insert `board_break`
--

TRUNCATE TABLE `board_break`;
--
-- Dumping data for table `board_break`
--

INSERT INTO `board_break` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600),
(4, 4, 1, 3, 0, 0, '2016-01-05', 1452014356),
(5, 5, 1, 3, 0, 0, '2016-01-05', 1452014632),
(6, 6, 1, 1, 0, 0, '2016-01-05', 1452016457),
(7, 7, 1, 1, 0, 0, '2016-01-05', 1452016488),
(8, 2, 2, 1, 0, 0, '2016-01-05', 1452016488),
(9, 4, 2, 3, 0, 0, '2016-01-05', 1452016488),
(10, 5, 2, 3, 0, 0, '2016-01-05', 1452016488),
(11, 3, 3, 2, 0, 0, '2016-01-05', 1452016488),
(12, 6, 3, 1, 0, 0, '2016-01-05', 1452016488),
(13, 7, 3, 1, 0, 0, '2016-01-05', 1452016488),
(14, 1, 2, 0, 0, 0, '2016-01-05', 1452016488),
(15, 8, 2, 5, 0, 0, '2016-01-05', 1452016509),
(16, 9, 2, 1, 0, 0, '2016-01-05', 1452016529),
(17, 10, 2, 1, 0, 0, '2016-01-05', 1452016544),
(18, 4, 4, 3, 0, 0, '2016-01-05', 1452016544),
(19, 1, 4, 0, 0, 0, '2016-01-05', 1452016544),
(20, 8, 4, 5, 0, 0, '2016-01-05', 1452016544),
(21, 5, 5, 3, 0, 0, '2016-01-05', 1452016544),
(22, 9, 5, 1, 0, 0, '2016-01-05', 1452016544),
(23, 10, 5, 1, 0, 0, '2016-01-05', 1452016544),
(24, 2, 4, 1, 0, 0, '2016-01-05', 1452016544),
(25, 11, 4, 2, 0, 0, '2016-01-05', 1452016551),
(26, 12, 4, 2, 0, 0, '2016-01-05', 1452016562),
(27, 13, 4, 2, 0, 0, '2016-01-05', 1452024915),
(28, 1, 6, 0, 0, 0, '2016-01-05', 1452024915),
(29, 2, 6, 1, 0, 0, '2016-01-05', 1452024915),
(30, 11, 6, 2, 0, 0, '2016-01-05', 1452024915),
(31, 8, 7, 5, 0, 0, '2016-01-05', 1452024915),
(32, 12, 7, 2, 0, 0, '2016-01-05', 1452024915),
(33, 13, 7, 2, 0, 0, '2016-01-05', 1452024915),
(34, 4, 3, 3, 0, 0, '2016-01-05', 1452024915),
(35, 14, 6, 1, 0, 0, '2016-01-06', 1452095490),
(36, 15, 5, 9, 0, 0, '2016-01-06', 1452113433),
(37, 16, 7, 12, 0, 0, '2016-01-06', 1452118582),
(38, 17, 5, 9, 0, 0, '2016-01-07', 1452125334),
(39, 18, 5, 5, 0, 0, '2016-01-07', 1452126796),
(40, 19, 5, 9, 0, 0, '2016-01-07', 1452128193),
(41, 9, 8, 1, 0, 0, '2016-01-07', 1452128193),
(42, 15, 8, 9, 0, 0, '2016-01-07', 1452128193),
(43, 17, 8, 9, 0, 0, '2016-01-07', 1452128193),
(44, 10, 9, 1, 0, 0, '2016-01-07', 1452128193),
(45, 18, 9, 5, 0, 0, '2016-01-07', 1452128193),
(46, 19, 9, 9, 0, 0, '2016-01-07', 1452128193),
(47, 5, 3, 3, 0, 0, '2016-01-07', 1452128193),
(48, 20, 8, 9, 0, 0, '2016-01-07', 1452128604),
(49, 21, 3, 5, 0, 0, '2016-01-07', 1452190032),
(50, 22, 3, 21, 0, 0, '2016-01-07', 1452190450),
(51, 6, 10, 1, 0, 0, '2016-01-07', 1452190450),
(52, 4, 10, 3, 0, 0, '2016-01-07', 1452190450),
(53, 5, 10, 3, 0, 0, '2016-01-07', 1452190450),
(54, 7, 11, 1, 0, 0, '2016-01-07', 1452190450),
(55, 21, 11, 5, 0, 0, '2016-01-07', 1452190450),
(56, 22, 11, 21, 0, 0, '2016-01-07', 1452190450),
(57, 3, 6, 2, 0, 0, '2016-01-07', 1452190450),
(58, 23, 11, 22, 0, 0, '2016-01-07', 1452192399),
(59, 24, 6, 3, 0, 0, '2016-01-07', 1452203309),
(60, 25, 9, 10, 0, 0, '2016-01-08', 1452274863),
(61, 26, 6, 1, 0, 0, '2016-01-08', 1452276380),
(62, 2, 12, 1, 0, 0, '2016-01-08', 1452276380),
(63, 14, 12, 1, 0, 0, '2016-01-08', 1452276380),
(64, 3, 12, 2, 0, 0, '2016-01-08', 1452276380),
(65, 11, 13, 2, 0, 0, '2016-01-08', 1452276380),
(66, 24, 13, 3, 0, 0, '2016-01-08', 1452276380),
(67, 26, 13, 1, 0, 0, '2016-01-08', 1452276380),
(68, 1, 7, 0, 0, 0, '2016-01-08', 1452276380),
(69, 28, 12, 3, 0, 0, '2016-01-09', 1452375149),
(70, 29, 7, 13, 0, 0, '2016-01-09', 1452384906),
(71, 30, 10, 5, 0, 0, '2016-01-11', 1452535480),
(72, 32, 7, 12, 0, 0, '2016-01-12', 1452631762),
(73, 12, 14, 2, 0, 0, '2016-01-12', 1452631762),
(74, 16, 14, 12, 0, 0, '2016-01-12', 1452631762),
(75, 1, 14, 0, 0, 0, '2016-01-12', 1452631762),
(76, 13, 15, 2, 0, 0, '2016-01-12', 1452631762),
(77, 29, 15, 13, 0, 0, '2016-01-12', 1452631762),
(78, 32, 15, 12, 0, 0, '2016-01-12', 1452631762),
(79, 8, 10, 5, 0, 0, '2016-01-12', 1452631762),
(80, 33, 12, 14, 0, 0, '2016-01-13', 1452700520),
(81, 34, 11, 7, 0, 0, '2016-01-13', 1452707614),
(82, 35, 8, 9, 0, 0, '2016-01-13', 1452709283),
(83, 36, 14, 1, 0, 0, '2016-01-13', 1452709561),
(84, 37, 15, 13, 0, 0, '2016-01-13', 1452717537),
(85, 38, 8, 9, 0, 0, '2016-01-13', 1452720499),
(86, 39, 8, 9, 0, 0, '2016-01-14', 1452791371),
(87, 15, 16, 9, 0, 0, '2016-01-14', 1452791371),
(88, 20, 16, 9, 0, 0, '2016-01-14', 1452791371),
(89, 35, 16, 9, 0, 0, '2016-01-14', 1452791371),
(90, 17, 17, 9, 0, 0, '2016-01-14', 1452791371),
(91, 38, 17, 9, 0, 0, '2016-01-14', 1452791371),
(92, 39, 17, 9, 0, 0, '2016-01-14', 1452791371),
(93, 9, 14, 1, 0, 0, '2016-01-14', 1452791371),
(94, 40, 13, 11, 0, 0, '2016-01-14', 1452796788),
(95, 41, 11, 34, 0, 0, '2016-01-15', 1452898929),
(96, 42, 12, 3, 0, 0, '2016-01-15', 1452899007),
(97, 43, 12, 42, 0, 0, '2016-01-15', 1452899084),
(98, 14, 18, 1, 0, 0, '2016-01-15', 1452899084),
(99, 28, 18, 3, 0, 0, '2016-01-15', 1452899084),
(100, 33, 18, 14, 0, 0, '2016-01-15', 1452899084),
(101, 3, 19, 2, 0, 0, '2016-01-15', 1452899084),
(102, 42, 19, 3, 0, 0, '2016-01-15', 1452899084),
(103, 43, 19, 42, 0, 0, '2016-01-15', 1452899084),
(104, 2, 14, 1, 0, 0, '2016-01-15', 1452899084),
(105, 44, 19, 3, 0, 0, '2016-01-15', 1452899314),
(106, 45, 10, 5, 0, 0, '2016-01-18', 1453134568),
(107, 46, 13, 24, 0, 0, '2016-01-18', 1453134688),
(108, 47, 14, 12, 0, 0, '2016-01-18', 1453134778),
(109, 16, 20, 12, 0, 0, '2016-01-18', 1453134778),
(110, 36, 20, 1, 0, 0, '2016-01-18', 1453134778),
(111, 9, 20, 1, 0, 0, '2016-01-18', 1453134778),
(112, 1, 21, 0, 0, 0, '2016-01-18', 1453134778),
(113, 2, 21, 1, 0, 0, '2016-01-18', 1453134778),
(114, 47, 21, 12, 0, 0, '2016-01-18', 1453134778),
(115, 12, 21, 2, 0, 0, '2016-01-18', 1453134778),
(116, 48, 13, 40, 0, 0, '2016-01-18', 1453135329),
(117, 49, 19, 3, 0, 0, '2016-01-20', 1453255190),
(118, 50, 21, 2, 0, 0, '2016-01-20', 1453303291),
(119, 51, 10, 5, 0, 0, '2016-01-21', 1453394885),
(120, 4, 22, 3, 0, 0, '2016-01-21', 1453394885),
(121, 30, 22, 5, 0, 0, '2016-01-21', 1453394885),
(122, 8, 22, 5, 0, 0, '2016-01-21', 1453394885),
(123, 5, 23, 3, 0, 0, '2016-01-21', 1453394885),
(124, 45, 23, 5, 0, 0, '2016-01-21', 1453394885),
(125, 51, 23, 5, 0, 0, '2016-01-21', 1453394885),
(126, 6, 21, 1, 0, 0, '2016-01-21', 1453394885),
(127, 52, 23, 51, 0, 0, '2016-01-21', 1453411928),
(128, 54, 16, 35, 0, 0, '2016-01-24', 1453644234),
(129, 55, 21, 1, 0, 0, '2016-01-25', 1453762173),
(130, 2, 24, 1, 0, 0, '2016-01-25', 1453762173),
(131, 12, 24, 2, 0, 0, '2016-01-25', 1453762173),
(132, 50, 24, 2, 0, 0, '2016-01-25', 1453762173),
(133, 47, 25, 12, 0, 0, '2016-01-25', 1453762173),
(134, 6, 25, 1, 0, 0, '2016-01-25', 1453762173),
(135, 55, 25, 1, 0, 0, '2016-01-25', 1453762173),
(136, 1, 9, 0, 0, 0, '2016-01-25', 1453762173),
(137, 56, 9, 1, 0, 0, '2016-01-25', 1453764054),
(138, 57, 17, 17, 0, 0, '2016-01-26', 1453845606),
(139, 58, 9, 1, 0, 0, '2016-01-27', 1453902386),
(140, 18, 26, 5, 0, 0, '2016-01-27', 1453902386),
(141, 25, 26, 10, 0, 0, '2016-01-27', 1453902386),
(142, 1, 26, 0, 0, 0, '2016-01-27', 1453902386),
(143, 19, 27, 9, 0, 0, '2016-01-27', 1453902386),
(144, 56, 27, 1, 0, 0, '2016-01-27', 1453902386),
(145, 58, 27, 1, 0, 0, '2016-01-27', 1453902386),
(146, 10, 26, 1, 0, 0, '2016-01-27', 1453902386),
(147, 59, 27, 58, 0, 0, '2016-01-27', 1453935208),
(148, 60, 27, 58, 0, 0, '2016-01-27', 1453935214),
(149, 61, 13, 46, 0, 0, '2016-01-28', 1453946410),
(150, 24, 28, 3, 0, 0, '2016-01-28', 1453946410),
(151, 40, 28, 11, 0, 0, '2016-01-28', 1453946410),
(152, 46, 28, 24, 0, 0, '2016-01-28', 1453946410),
(153, 26, 29, 1, 0, 0, '2016-01-28', 1453946410),
(154, 48, 29, 40, 0, 0, '2016-01-28', 1453946410),
(155, 61, 29, 46, 0, 0, '2016-01-28', 1453946410),
(156, 11, 24, 2, 0, 0, '2016-01-28', 1453946410),
(157, 62, 28, 46, 0, 0, '2016-01-28', 1453947599),
(158, 63, 19, 3, 0, 0, '2016-01-28', 1453992278),
(159, 65, 27, 19, 0, 0, '2016-01-29', 1454074523),
(160, 66, 27, 19, 0, 0, '2016-01-29', 1454076066),
(161, 56, 30, 1, 0, 0, '2016-01-29', 1454076066),
(162, 59, 30, 58, 0, 0, '2016-01-29', 1454076066),
(163, 60, 30, 58, 0, 0, '2016-01-29', 1454076066),
(164, 58, 31, 1, 0, 0, '2016-01-29', 1454076066),
(165, 65, 31, 19, 0, 0, '2016-01-29', 1454076066),
(166, 66, 31, 19, 0, 0, '2016-01-29', 1454076066),
(167, 19, 20, 9, 0, 0, '2016-01-29', 1454076066),
(168, 67, 11, 34, 0, 0, '2016-01-29', 1454079286),
(169, 21, 32, 5, 0, 0, '2016-01-29', 1454079286),
(170, 23, 32, 22, 0, 0, '2016-01-29', 1454079286),
(171, 34, 32, 7, 0, 0, '2016-01-29', 1454079286),
(172, 22, 33, 21, 0, 0, '2016-01-29', 1454079286),
(173, 41, 33, 34, 0, 0, '2016-01-29', 1454079286),
(174, 67, 33, 34, 0, 0, '2016-01-29', 1454079286),
(175, 7, 26, 1, 0, 0, '2016-01-29', 1454079286),
(176, 68, 26, 18, 0, 0, '2016-01-29', 1454092507),
(177, 69, 26, 18, 0, 0, '2016-01-29', 1454095854),
(178, 25, 34, 10, 0, 0, '2016-01-29', 1454095854),
(179, 10, 34, 1, 0, 0, '2016-01-29', 1454095854),
(180, 7, 34, 1, 0, 0, '2016-01-29', 1454095854),
(181, 1, 35, 0, 0, 0, '2016-01-29', 1454095854),
(182, 68, 35, 18, 0, 0, '2016-01-29', 1454095854),
(183, 69, 35, 18, 0, 0, '2016-01-29', 1454095854),
(184, 18, 23, 5, 0, 0, '2016-01-29', 1454095854),
(185, 70, 30, 60, 0, 0, '2016-01-29', 1454123429),
(186, 71, 23, 45, 0, 0, '2016-01-30', 1454183940),
(187, 72, 23, 45, 0, 0, '2016-01-30', 1454185070),
(188, 45, 36, 5, 0, 0, '2016-01-30', 1454185070),
(189, 52, 36, 51, 0, 0, '2016-01-30', 1454185070),
(190, 18, 36, 5, 0, 0, '2016-01-30', 1454185070),
(191, 51, 37, 5, 0, 0, '2016-01-30', 1454185070),
(192, 71, 37, 45, 0, 0, '2016-01-30', 1454185070),
(193, 72, 37, 45, 0, 0, '2016-01-30', 1454185070),
(194, 5, 19, 3, 0, 0, '2016-01-30', 1454185070),
(195, 42, 38, 3, 0, 0, '2016-01-30', 1454185070),
(196, 44, 38, 3, 0, 0, '2016-01-30', 1454185070),
(197, 49, 38, 3, 0, 0, '2016-01-30', 1454185070),
(198, 43, 39, 42, 0, 0, '2016-01-30', 1454185070),
(199, 63, 39, 3, 0, 0, '2016-01-30', 1454185070),
(200, 5, 39, 3, 0, 0, '2016-01-30', 1454185070),
(201, 3, 24, 2, 0, 0, '2016-01-30', 1454185070),
(202, 75, 24, 11, 0, 0, '2016-02-03', 1454513033),
(203, 76, 39, 5, 0, 0, '2016-02-04', 1454586953),
(204, 77, 34, 7, 0, 0, '2016-02-04', 1454616953),
(205, 78, 24, 12, 0, 0, '2016-02-06', 1454800320),
(206, 12, 40, 2, 0, 0, '2016-02-06', 1454800320),
(207, 11, 40, 2, 0, 0, '2016-02-06', 1454800320),
(208, 3, 40, 2, 0, 0, '2016-02-06', 1454800320),
(209, 50, 41, 2, 0, 0, '2016-02-06', 1454800320),
(210, 75, 41, 11, 0, 0, '2016-02-06', 1454800320),
(211, 78, 41, 12, 0, 0, '2016-02-06', 1454800320),
(212, 2, 35, 1, 0, 0, '2016-02-06', 1454800320),
(213, 79, 30, 59, 0, 0, '2016-02-11', 1455206240),
(214, 80, 28, 40, 0, 0, '2016-02-18', 1455829029),
(215, 81, 36, 18, 0, 0, '2016-02-22', 1456100600),
(216, 82, 28, 40, 0, 0, '2016-02-25', 1456439867);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_fifth`
--

DROP TABLE IF EXISTS `board_break_fifth`;
CREATE TABLE IF NOT EXISTS `board_break_fifth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_b_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `qualified_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Truncate table before insert `board_break_fifth`
--

TRUNCATE TABLE `board_break_fifth`;
--
-- Dumping data for table `board_break_fifth`
--

INSERT INTO `board_break_fifth` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600),
(4, 4, 1, 3, 0, 0, '2016-01-09', 1452375149),
(5, 12, 1, 2, 0, 0, '2016-01-20', 1453255190),
(6, 9, 1, 1, 0, 0, '2016-01-21', 1453411928),
(7, 6, 1, 1, 0, 0, '2016-01-25', 1453764054),
(8, 2, 2, 1, 0, 0, '2016-01-25', 1453764054),
(9, 4, 2, 3, 0, 0, '2016-01-25', 1453764054),
(10, 12, 2, 2, 0, 0, '2016-01-25', 1453764054),
(11, 3, 3, 2, 0, 0, '2016-01-25', 1453764054),
(12, 9, 3, 1, 0, 0, '2016-01-25', 1453764054),
(13, 6, 3, 1, 0, 0, '2016-01-25', 1453764054),
(14, 1, 2, 0, 0, 0, '2016-01-25', 1453764054),
(15, 11, 2, 2, 0, 0, '2016-02-18', 1455829029);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_fourth`
--

DROP TABLE IF EXISTS `board_break_fourth`;
CREATE TABLE IF NOT EXISTS `board_break_fourth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_b_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `qualified_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

--
-- Truncate table before insert `board_break_fourth`
--

TRUNCATE TABLE `board_break_fourth`;
--
-- Dumping data for table `board_break_fourth`
--

INSERT INTO `board_break_fourth` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600),
(4, 4, 1, 3, 0, 0, '2016-01-05', 1452014356),
(5, 6, 1, 1, 0, 0, '2016-01-05', 1452016457),
(6, 9, 1, 1, 0, 0, '2016-01-05', 1452016529),
(7, 11, 1, 2, 0, 0, '2016-01-05', 1452016551),
(8, 2, 2, 1, 0, 0, '2016-01-05', 1452016551),
(9, 4, 2, 3, 0, 0, '2016-01-05', 1452016551),
(10, 6, 2, 1, 0, 0, '2016-01-05', 1452016551),
(11, 3, 3, 2, 0, 0, '2016-01-05', 1452016551),
(12, 9, 3, 1, 0, 0, '2016-01-05', 1452016551),
(13, 11, 3, 2, 0, 0, '2016-01-05', 1452016551),
(14, 1, 2, 0, 0, 0, '2016-01-05', 1452016551),
(15, 12, 2, 2, 0, 0, '2016-01-05', 1452016562),
(16, 13, 2, 2, 0, 0, '2016-01-05', 1452024915),
(17, 15, 3, 9, 0, 0, '2016-01-06', 1452113433),
(18, 16, 2, 12, 0, 0, '2016-01-06', 1452118582),
(19, 4, 4, 3, 0, 0, '2016-01-06', 1452118582),
(20, 1, 4, 0, 0, 0, '2016-01-06', 1452118582),
(21, 12, 4, 2, 0, 0, '2016-01-06', 1452118582),
(22, 6, 5, 1, 0, 0, '2016-01-06', 1452118582),
(23, 13, 5, 2, 0, 0, '2016-01-06', 1452118582),
(24, 16, 5, 12, 0, 0, '2016-01-06', 1452118582),
(25, 2, 4, 1, 0, 0, '2016-01-06', 1452118582),
(26, 5, 3, 3, 0, 0, '2016-01-07', 1452192399),
(27, 24, 3, 3, 0, 0, '2016-01-07', 1452203309),
(28, 25, 4, 10, 0, 0, '2016-01-08', 1452274863),
(29, 26, 4, 1, 0, 0, '2016-01-08', 1452276380),
(30, 28, 3, 3, 0, 0, '2016-01-09', 1452375149),
(31, 9, 6, 1, 0, 0, '2016-01-09', 1452375149),
(32, 15, 6, 9, 0, 0, '2016-01-09', 1452375149),
(33, 5, 6, 3, 0, 0, '2016-01-09', 1452375149),
(34, 11, 7, 2, 0, 0, '2016-01-09', 1452375149),
(35, 24, 7, 3, 0, 0, '2016-01-09', 1452375149),
(36, 28, 7, 3, 0, 0, '2016-01-09', 1452375149),
(37, 3, 4, 2, 0, 0, '2016-01-09', 1452375149),
(38, 1, 8, 0, 0, 0, '2016-01-09', 1452375149),
(39, 2, 8, 1, 0, 0, '2016-01-09', 1452375149),
(40, 25, 8, 10, 0, 0, '2016-01-09', 1452375149),
(41, 12, 9, 2, 0, 0, '2016-01-09', 1452375149),
(42, 26, 9, 1, 0, 0, '2016-01-09', 1452375149),
(43, 3, 9, 2, 0, 0, '2016-01-09', 1452375149),
(44, 4, 9, 3, 0, 0, '2016-01-09', 1452375149),
(45, 29, 5, 13, 0, 0, '2016-01-09', 1452384906),
(46, 30, 6, 5, 0, 0, '2016-01-11', 1452535480),
(47, 32, 9, 12, 0, 0, '2016-01-12', 1452631762),
(48, 8, 6, 5, 0, 0, '2016-01-13', 1452700520),
(49, 36, 8, 1, 0, 0, '2016-01-13', 1452709561),
(50, 40, 7, 11, 0, 0, '2016-01-14', 1452796788),
(51, 47, 9, 12, 0, 0, '2016-01-18', 1453134778),
(52, 48, 7, 40, 0, 0, '2016-01-18', 1453135329),
(53, 49, 9, 3, 0, 0, '2016-01-20', 1453255190),
(54, 26, 10, 1, 0, 0, '2016-01-20', 1453255190),
(55, 4, 10, 3, 0, 0, '2016-01-20', 1453255190),
(56, 32, 10, 12, 0, 0, '2016-01-20', 1453255190),
(57, 3, 11, 2, 0, 0, '2016-01-20', 1453255190),
(58, 47, 11, 12, 0, 0, '2016-01-20', 1453255190),
(59, 49, 11, 3, 0, 0, '2016-01-20', 1453255190),
(60, 12, 8, 2, 0, 0, '2016-01-20', 1453255190),
(61, 50, 8, 2, 0, 0, '2016-01-20', 1453303291),
(62, 51, 6, 5, 0, 0, '2016-01-21', 1453394885),
(63, 52, 6, 51, 0, 0, '2016-01-21', 1453411928),
(64, 15, 12, 9, 0, 0, '2016-01-21', 1453411928),
(65, 30, 12, 5, 0, 0, '2016-01-21', 1453411928),
(66, 8, 12, 5, 0, 0, '2016-01-21', 1453411928),
(67, 5, 13, 3, 0, 0, '2016-01-21', 1453411928),
(68, 51, 13, 5, 0, 0, '2016-01-21', 1453411928),
(69, 52, 13, 51, 0, 0, '2016-01-21', 1453411928),
(70, 9, 8, 1, 0, 0, '2016-01-21', 1453411928),
(71, 2, 14, 1, 0, 0, '2016-01-21', 1453411928),
(72, 36, 14, 1, 0, 0, '2016-01-21', 1453411928),
(73, 12, 14, 2, 0, 0, '2016-01-21', 1453411928),
(74, 25, 15, 10, 0, 0, '2016-01-21', 1453411928),
(75, 50, 15, 2, 0, 0, '2016-01-21', 1453411928),
(76, 9, 15, 1, 0, 0, '2016-01-21', 1453411928),
(77, 1, 5, 0, 0, 0, '2016-01-21', 1453411928),
(78, 55, 5, 1, 0, 0, '2016-01-25', 1453762173),
(79, 56, 5, 1, 0, 0, '2016-01-25', 1453764054),
(80, 13, 16, 2, 0, 0, '2016-01-25', 1453764054),
(81, 29, 16, 13, 0, 0, '2016-01-25', 1453764054),
(82, 1, 16, 0, 0, 0, '2016-01-25', 1453764054),
(83, 16, 17, 12, 0, 0, '2016-01-25', 1453764054),
(84, 55, 17, 1, 0, 0, '2016-01-25', 1453764054),
(85, 56, 17, 1, 0, 0, '2016-01-25', 1453764054),
(86, 6, 16, 1, 0, 0, '2016-01-25', 1453764054),
(87, 57, 15, 17, 0, 0, '2016-01-26', 1453845606),
(88, 75, 7, 11, 0, 0, '2016-02-03', 1454513033),
(89, 78, 14, 12, 0, 0, '2016-02-06', 1454800320),
(90, 80, 7, 40, 0, 0, '2016-02-18', 1455829029),
(91, 24, 18, 3, 0, 0, '2016-02-18', 1455829029),
(92, 40, 18, 11, 0, 0, '2016-02-18', 1455829029),
(93, 48, 18, 40, 0, 0, '2016-02-18', 1455829029),
(94, 28, 19, 3, 0, 0, '2016-02-18', 1455829029),
(95, 75, 19, 11, 0, 0, '2016-02-18', 1455829029),
(96, 80, 19, 40, 0, 0, '2016-02-18', 1455829029),
(97, 11, 14, 2, 0, 0, '2016-02-18', 1455829029),
(98, 82, 18, 40, 0, 0, '2016-02-25', 1456439867);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_second`
--

DROP TABLE IF EXISTS `board_break_second`;
CREATE TABLE IF NOT EXISTS `board_break_second` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_b_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `qualified_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=186 ;

--
-- Truncate table before insert `board_break_second`
--

TRUNCATE TABLE `board_break_second`;
--
-- Dumping data for table `board_break_second`
--

INSERT INTO `board_break_second` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600),
(4, 4, 1, 3, 0, 0, '2016-01-05', 1452014356),
(5, 5, 1, 3, 0, 0, '2016-01-05', 1452014632),
(6, 6, 1, 1, 0, 0, '2016-01-05', 1452016457),
(7, 7, 1, 1, 0, 0, '2016-01-05', 1452016488),
(8, 2, 2, 1, 0, 0, '2016-01-05', 1452016488),
(9, 4, 2, 3, 0, 0, '2016-01-05', 1452016488),
(10, 5, 2, 3, 0, 0, '2016-01-05', 1452016488),
(11, 3, 3, 2, 0, 0, '2016-01-05', 1452016488),
(12, 6, 3, 1, 0, 0, '2016-01-05', 1452016488),
(13, 7, 3, 1, 0, 0, '2016-01-05', 1452016488),
(14, 1, 2, 0, 0, 0, '2016-01-05', 1452016488),
(15, 8, 2, 5, 0, 0, '2016-01-05', 1452016509),
(16, 9, 2, 1, 0, 0, '2016-01-05', 1452016529),
(17, 10, 2, 1, 0, 0, '2016-01-05', 1452016544),
(18, 4, 4, 3, 0, 0, '2016-01-05', 1452016544),
(19, 1, 4, 0, 0, 0, '2016-01-05', 1452016544),
(20, 8, 4, 5, 0, 0, '2016-01-05', 1452016544),
(21, 5, 5, 3, 0, 0, '2016-01-05', 1452016544),
(22, 9, 5, 1, 0, 0, '2016-01-05', 1452016544),
(23, 10, 5, 1, 0, 0, '2016-01-05', 1452016544),
(24, 2, 4, 1, 0, 0, '2016-01-05', 1452016544),
(25, 11, 4, 2, 0, 0, '2016-01-05', 1452016551),
(26, 12, 4, 2, 0, 0, '2016-01-05', 1452016562),
(27, 13, 4, 2, 0, 0, '2016-01-05', 1452024915),
(28, 1, 6, 0, 0, 0, '2016-01-05', 1452024915),
(29, 2, 6, 1, 0, 0, '2016-01-05', 1452024915),
(30, 11, 6, 2, 0, 0, '2016-01-05', 1452024915),
(31, 8, 7, 5, 0, 0, '2016-01-05', 1452024915),
(32, 12, 7, 2, 0, 0, '2016-01-05', 1452024915),
(33, 13, 7, 2, 0, 0, '2016-01-05', 1452024915),
(34, 4, 3, 3, 0, 0, '2016-01-05', 1452024915),
(35, 14, 6, 1, 0, 0, '2016-01-06', 1452095490),
(36, 15, 5, 9, 0, 0, '2016-01-06', 1452113433),
(37, 16, 7, 12, 0, 0, '2016-01-06', 1452118582),
(38, 17, 5, 9, 0, 0, '2016-01-07', 1452125334),
(39, 18, 5, 5, 0, 0, '2016-01-07', 1452126796),
(40, 19, 5, 9, 0, 0, '2016-01-07', 1452128193),
(41, 9, 8, 1, 0, 0, '2016-01-07', 1452128193),
(42, 15, 8, 9, 0, 0, '2016-01-07', 1452128193),
(43, 17, 8, 9, 0, 0, '2016-01-07', 1452128193),
(44, 10, 9, 1, 0, 0, '2016-01-07', 1452128193),
(45, 18, 9, 5, 0, 0, '2016-01-07', 1452128193),
(46, 19, 9, 9, 0, 0, '2016-01-07', 1452128193),
(47, 5, 3, 3, 0, 0, '2016-01-07', 1452128193),
(48, 21, 3, 5, 0, 0, '2016-01-07', 1452190032),
(49, 22, 3, 21, 0, 0, '2016-01-07', 1452190450),
(50, 6, 10, 1, 0, 0, '2016-01-07', 1452190450),
(51, 4, 10, 3, 0, 0, '2016-01-07', 1452190450),
(52, 5, 10, 3, 0, 0, '2016-01-07', 1452190450),
(53, 7, 11, 1, 0, 0, '2016-01-07', 1452190450),
(54, 21, 11, 5, 0, 0, '2016-01-07', 1452190450),
(55, 22, 11, 21, 0, 0, '2016-01-07', 1452190450),
(56, 3, 6, 2, 0, 0, '2016-01-07', 1452190450),
(57, 23, 11, 22, 0, 0, '2016-01-07', 1452192399),
(58, 24, 6, 3, 0, 0, '2016-01-07', 1452203309),
(59, 25, 9, 10, 0, 0, '2016-01-08', 1452274863),
(60, 26, 6, 1, 0, 0, '2016-01-08', 1452276380),
(61, 2, 12, 1, 0, 0, '2016-01-08', 1452276380),
(62, 14, 12, 1, 0, 0, '2016-01-08', 1452276380),
(63, 3, 12, 2, 0, 0, '2016-01-08', 1452276380),
(64, 11, 13, 2, 0, 0, '2016-01-08', 1452276380),
(65, 24, 13, 3, 0, 0, '2016-01-08', 1452276380),
(66, 26, 13, 1, 0, 0, '2016-01-08', 1452276380),
(67, 1, 7, 0, 0, 0, '2016-01-08', 1452276380),
(68, 28, 12, 3, 0, 0, '2016-01-09', 1452375149),
(69, 29, 7, 13, 0, 0, '2016-01-09', 1452384906),
(70, 30, 10, 5, 0, 0, '2016-01-11', 1452535480),
(71, 32, 7, 12, 0, 0, '2016-01-12', 1452631762),
(72, 12, 14, 2, 0, 0, '2016-01-12', 1452631762),
(73, 16, 14, 12, 0, 0, '2016-01-12', 1452631762),
(74, 1, 14, 0, 0, 0, '2016-01-12', 1452631762),
(75, 13, 15, 2, 0, 0, '2016-01-12', 1452631762),
(76, 29, 15, 13, 0, 0, '2016-01-12', 1452631762),
(77, 32, 15, 12, 0, 0, '2016-01-12', 1452631762),
(78, 8, 10, 5, 0, 0, '2016-01-12', 1452631762),
(79, 33, 12, 14, 0, 0, '2016-01-13', 1452700520),
(80, 34, 11, 7, 0, 0, '2016-01-13', 1452707614),
(81, 35, 8, 9, 0, 0, '2016-01-13', 1452709283),
(82, 36, 14, 1, 0, 0, '2016-01-13', 1452709561),
(83, 39, 8, 9, 0, 0, '2016-01-14', 1452791371),
(84, 40, 13, 11, 0, 0, '2016-01-14', 1452796788),
(85, 41, 11, 34, 0, 0, '2016-01-15', 1452898929),
(86, 42, 12, 3, 0, 0, '2016-01-15', 1452899007),
(87, 43, 12, 42, 0, 0, '2016-01-15', 1452899084),
(88, 14, 16, 1, 0, 0, '2016-01-15', 1452899084),
(89, 28, 16, 3, 0, 0, '2016-01-15', 1452899084),
(90, 33, 16, 14, 0, 0, '2016-01-15', 1452899084),
(91, 3, 17, 2, 0, 0, '2016-01-15', 1452899084),
(92, 42, 17, 3, 0, 0, '2016-01-15', 1452899084),
(93, 43, 17, 42, 0, 0, '2016-01-15', 1452899084),
(94, 2, 14, 1, 0, 0, '2016-01-15', 1452899084),
(95, 44, 17, 3, 0, 0, '2016-01-15', 1452899314),
(96, 45, 10, 5, 0, 0, '2016-01-18', 1453134568),
(97, 46, 13, 24, 0, 0, '2016-01-18', 1453134688),
(98, 47, 14, 12, 0, 0, '2016-01-18', 1453134778),
(99, 48, 13, 40, 0, 0, '2016-01-18', 1453135329),
(100, 49, 17, 3, 0, 0, '2016-01-20', 1453255190),
(101, 50, 14, 2, 0, 0, '2016-01-20', 1453303291),
(102, 16, 18, 12, 0, 0, '2016-01-20', 1453303291),
(103, 36, 18, 1, 0, 0, '2016-01-20', 1453303291),
(104, 2, 18, 1, 0, 0, '2016-01-20', 1453303291),
(105, 1, 19, 0, 0, 0, '2016-01-20', 1453303291),
(106, 47, 19, 12, 0, 0, '2016-01-20', 1453303291),
(107, 50, 19, 2, 0, 0, '2016-01-20', 1453303291),
(108, 12, 18, 2, 0, 0, '2016-01-20', 1453303291),
(109, 51, 10, 5, 0, 0, '2016-01-21', 1453394885),
(110, 4, 20, 3, 0, 0, '2016-01-21', 1453394885),
(111, 30, 20, 5, 0, 0, '2016-01-21', 1453394885),
(112, 8, 20, 5, 0, 0, '2016-01-21', 1453394885),
(113, 5, 21, 3, 0, 0, '2016-01-21', 1453394885),
(114, 45, 21, 5, 0, 0, '2016-01-21', 1453394885),
(115, 51, 21, 5, 0, 0, '2016-01-21', 1453394885),
(116, 6, 19, 1, 0, 0, '2016-01-21', 1453394885),
(117, 52, 21, 51, 0, 0, '2016-01-21', 1453411928),
(118, 54, 8, 35, 0, 0, '2016-01-24', 1453644234),
(119, 55, 19, 1, 0, 0, '2016-01-25', 1453762173),
(120, 56, 19, 1, 0, 0, '2016-01-25', 1453764054),
(121, 57, 8, 17, 0, 0, '2016-01-26', 1453845606),
(122, 15, 22, 9, 0, 0, '2016-01-26', 1453845606),
(123, 35, 22, 9, 0, 0, '2016-01-26', 1453845606),
(124, 39, 22, 9, 0, 0, '2016-01-26', 1453845606),
(125, 17, 23, 9, 0, 0, '2016-01-26', 1453845606),
(126, 54, 23, 35, 0, 0, '2016-01-26', 1453845606),
(127, 57, 23, 17, 0, 0, '2016-01-26', 1453845606),
(128, 9, 19, 1, 0, 0, '2016-01-26', 1453845606),
(129, 47, 24, 12, 0, 0, '2016-01-26', 1453845606),
(130, 6, 24, 1, 0, 0, '2016-01-26', 1453845606),
(131, 55, 24, 1, 0, 0, '2016-01-26', 1453845606),
(132, 50, 25, 2, 0, 0, '2016-01-26', 1453845606),
(133, 56, 25, 1, 0, 0, '2016-01-26', 1453845606),
(134, 9, 25, 1, 0, 0, '2016-01-26', 1453845606),
(135, 1, 9, 0, 0, 0, '2016-01-26', 1453845606),
(136, 58, 9, 1, 0, 0, '2016-01-27', 1453902386),
(137, 59, 9, 58, 0, 0, '2016-01-27', 1453935208),
(138, 18, 26, 5, 0, 0, '2016-01-27', 1453935208),
(139, 25, 26, 10, 0, 0, '2016-01-27', 1453935208),
(140, 1, 26, 0, 0, 0, '2016-01-27', 1453935208),
(141, 19, 27, 9, 0, 0, '2016-01-27', 1453935208),
(142, 58, 27, 1, 0, 0, '2016-01-27', 1453935208),
(143, 59, 27, 58, 0, 0, '2016-01-27', 1453935208),
(144, 10, 26, 1, 0, 0, '2016-01-27', 1453935208),
(145, 60, 27, 58, 0, 0, '2016-01-27', 1453935214),
(146, 61, 13, 46, 0, 0, '2016-01-28', 1453946410),
(147, 24, 28, 3, 0, 0, '2016-01-28', 1453946410),
(148, 40, 28, 11, 0, 0, '2016-01-28', 1453946410),
(149, 46, 28, 24, 0, 0, '2016-01-28', 1453946410),
(150, 26, 29, 1, 0, 0, '2016-01-28', 1453946410),
(151, 48, 29, 40, 0, 0, '2016-01-28', 1453946410),
(152, 61, 29, 46, 0, 0, '2016-01-28', 1453946410),
(153, 11, 18, 2, 0, 0, '2016-01-28', 1453946410),
(154, 62, 28, 46, 0, 0, '2016-01-28', 1453947599),
(155, 63, 17, 3, 0, 0, '2016-01-28', 1453992278),
(156, 66, 27, 19, 0, 0, '2016-01-29', 1454076066),
(157, 67, 11, 34, 0, 0, '2016-01-29', 1454079286),
(158, 21, 30, 5, 0, 0, '2016-01-29', 1454079286),
(159, 23, 30, 22, 0, 0, '2016-01-29', 1454079286),
(160, 34, 30, 7, 0, 0, '2016-01-29', 1454079286),
(161, 22, 31, 21, 0, 0, '2016-01-29', 1454079286),
(162, 41, 31, 34, 0, 0, '2016-01-29', 1454079286),
(163, 67, 31, 34, 0, 0, '2016-01-29', 1454079286),
(164, 7, 26, 1, 0, 0, '2016-01-29', 1454079286),
(165, 71, 21, 45, 0, 0, '2016-01-30', 1454183940),
(166, 75, 18, 11, 0, 0, '2016-02-03', 1454513033),
(167, 77, 26, 7, 0, 0, '2016-02-04', 1454616953),
(168, 78, 18, 12, 0, 0, '2016-02-06', 1454800320),
(169, 36, 32, 1, 0, 0, '2016-02-06', 1454800320),
(170, 12, 32, 2, 0, 0, '2016-02-06', 1454800320),
(171, 11, 32, 2, 0, 0, '2016-02-06', 1454800320),
(172, 2, 33, 1, 0, 0, '2016-02-06', 1454800320),
(173, 75, 33, 11, 0, 0, '2016-02-06', 1454800320),
(174, 78, 33, 12, 0, 0, '2016-02-06', 1454800320),
(175, 16, 32, 12, 0, 0, '2016-02-06', 1454800320),
(176, 80, 28, 40, 0, 0, '2016-02-18', 1455829029),
(177, 81, 26, 18, 0, 0, '2016-02-22', 1456100600),
(178, 25, 34, 10, 0, 0, '2016-02-22', 1456100600),
(179, 10, 34, 1, 0, 0, '2016-02-22', 1456100600),
(180, 7, 34, 1, 0, 0, '2016-02-22', 1456100600),
(181, 1, 35, 0, 0, 0, '2016-02-22', 1456100600),
(182, 77, 35, 7, 0, 0, '2016-02-22', 1456100600),
(183, 81, 35, 18, 0, 0, '2016-02-22', 1456100600),
(184, 18, 21, 5, 0, 0, '2016-02-22', 1456100600),
(185, 82, 28, 40, 0, 0, '2016-02-25', 1456439867);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_sixth`
--

DROP TABLE IF EXISTS `board_break_sixth`;
CREATE TABLE IF NOT EXISTS `board_break_sixth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_b_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `qualified_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `board_break_sixth`
--

TRUNCATE TABLE `board_break_sixth`;
-- --------------------------------------------------------

--
-- Table structure for table `board_break_third`
--

DROP TABLE IF EXISTS `board_break_third`;
CREATE TABLE IF NOT EXISTS `board_break_third` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_b_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `qualified_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

--
-- Truncate table before insert `board_break_third`
--

TRUNCATE TABLE `board_break_third`;
--
-- Dumping data for table `board_break_third`
--

INSERT INTO `board_break_third` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600),
(4, 4, 1, 3, 0, 0, '2016-01-05', 1452014356),
(5, 5, 1, 3, 0, 0, '2016-01-05', 1452014632),
(6, 6, 1, 1, 0, 0, '2016-01-05', 1452016457),
(7, 7, 1, 1, 0, 0, '2016-01-05', 1452016488),
(8, 2, 2, 1, 0, 0, '2016-01-05', 1452016488),
(9, 4, 2, 3, 0, 0, '2016-01-05', 1452016488),
(10, 5, 2, 3, 0, 0, '2016-01-05', 1452016488),
(11, 3, 3, 2, 0, 0, '2016-01-05', 1452016488),
(12, 6, 3, 1, 0, 0, '2016-01-05', 1452016488),
(13, 7, 3, 1, 0, 0, '2016-01-05', 1452016488),
(14, 1, 2, 0, 0, 0, '2016-01-05', 1452016488),
(15, 8, 2, 5, 0, 0, '2016-01-05', 1452016509),
(16, 9, 2, 1, 0, 0, '2016-01-05', 1452016529),
(17, 10, 2, 1, 0, 0, '2016-01-05', 1452016544),
(18, 4, 4, 3, 0, 0, '2016-01-05', 1452016544),
(19, 1, 4, 0, 0, 0, '2016-01-05', 1452016544),
(20, 8, 4, 5, 0, 0, '2016-01-05', 1452016544),
(21, 5, 5, 3, 0, 0, '2016-01-05', 1452016544),
(22, 9, 5, 1, 0, 0, '2016-01-05', 1452016544),
(23, 10, 5, 1, 0, 0, '2016-01-05', 1452016544),
(24, 2, 4, 1, 0, 0, '2016-01-05', 1452016544),
(25, 11, 4, 2, 0, 0, '2016-01-05', 1452016551),
(26, 12, 4, 2, 0, 0, '2016-01-05', 1452016562),
(27, 13, 4, 2, 0, 0, '2016-01-05', 1452024915),
(28, 1, 6, 0, 0, 0, '2016-01-05', 1452024915),
(29, 2, 6, 1, 0, 0, '2016-01-05', 1452024915),
(30, 11, 6, 2, 0, 0, '2016-01-05', 1452024915),
(31, 8, 7, 5, 0, 0, '2016-01-05', 1452024915),
(32, 12, 7, 2, 0, 0, '2016-01-05', 1452024915),
(33, 13, 7, 2, 0, 0, '2016-01-05', 1452024915),
(34, 4, 3, 3, 0, 0, '2016-01-05', 1452024915),
(35, 14, 6, 1, 0, 0, '2016-01-06', 1452095490),
(36, 15, 5, 9, 0, 0, '2016-01-06', 1452113433),
(37, 16, 7, 12, 0, 0, '2016-01-06', 1452118582),
(38, 21, 5, 5, 0, 0, '2016-01-07', 1452190032),
(39, 22, 5, 21, 0, 0, '2016-01-07', 1452190450),
(40, 23, 5, 22, 0, 0, '2016-01-07', 1452192399),
(41, 9, 8, 1, 0, 0, '2016-01-07', 1452192399),
(42, 15, 8, 9, 0, 0, '2016-01-07', 1452192399),
(43, 21, 8, 5, 0, 0, '2016-01-07', 1452192399),
(44, 10, 9, 1, 0, 0, '2016-01-07', 1452192399),
(45, 22, 9, 21, 0, 0, '2016-01-07', 1452192399),
(46, 23, 9, 22, 0, 0, '2016-01-07', 1452192399),
(47, 5, 3, 3, 0, 0, '2016-01-07', 1452192399),
(48, 24, 3, 3, 0, 0, '2016-01-07', 1452203309),
(49, 25, 9, 10, 0, 0, '2016-01-08', 1452274863),
(50, 26, 6, 1, 0, 0, '2016-01-08', 1452276380),
(51, 28, 3, 3, 0, 0, '2016-01-09', 1452375149),
(52, 6, 10, 1, 0, 0, '2016-01-09', 1452375149),
(53, 4, 10, 3, 0, 0, '2016-01-09', 1452375149),
(54, 5, 10, 3, 0, 0, '2016-01-09', 1452375149),
(55, 7, 11, 1, 0, 0, '2016-01-09', 1452375149),
(56, 24, 11, 3, 0, 0, '2016-01-09', 1452375149),
(57, 28, 11, 3, 0, 0, '2016-01-09', 1452375149),
(58, 3, 6, 2, 0, 0, '2016-01-09', 1452375149),
(59, 29, 7, 13, 0, 0, '2016-01-09', 1452384906),
(60, 30, 10, 5, 0, 0, '2016-01-11', 1452535480),
(61, 32, 7, 12, 0, 0, '2016-01-12', 1452631762),
(62, 33, 6, 14, 0, 0, '2016-01-13', 1452700520),
(63, 2, 12, 1, 0, 0, '2016-01-13', 1452700520),
(64, 14, 12, 1, 0, 0, '2016-01-13', 1452700520),
(65, 26, 12, 1, 0, 0, '2016-01-13', 1452700520),
(66, 11, 13, 2, 0, 0, '2016-01-13', 1452700520),
(67, 3, 13, 2, 0, 0, '2016-01-13', 1452700520),
(68, 33, 13, 14, 0, 0, '2016-01-13', 1452700520),
(69, 1, 7, 0, 0, 0, '2016-01-13', 1452700520),
(70, 12, 14, 2, 0, 0, '2016-01-13', 1452700520),
(71, 16, 14, 12, 0, 0, '2016-01-13', 1452700520),
(72, 29, 14, 13, 0, 0, '2016-01-13', 1452700520),
(73, 13, 15, 2, 0, 0, '2016-01-13', 1452700520),
(74, 32, 15, 12, 0, 0, '2016-01-13', 1452700520),
(75, 1, 15, 0, 0, 0, '2016-01-13', 1452700520),
(76, 8, 10, 5, 0, 0, '2016-01-13', 1452700520),
(77, 36, 15, 1, 0, 0, '2016-01-13', 1452709561),
(78, 40, 13, 11, 0, 0, '2016-01-14', 1452796788),
(79, 43, 13, 42, 0, 0, '2016-01-15', 1452899084),
(80, 44, 13, 3, 0, 0, '2016-01-15', 1452899314),
(81, 45, 10, 5, 0, 0, '2016-01-18', 1453134568),
(82, 47, 14, 12, 0, 0, '2016-01-18', 1453134778),
(83, 48, 13, 40, 0, 0, '2016-01-18', 1453135329),
(84, 3, 16, 2, 0, 0, '2016-01-18', 1453135329),
(85, 40, 16, 11, 0, 0, '2016-01-18', 1453135329),
(86, 43, 16, 42, 0, 0, '2016-01-18', 1453135329),
(87, 33, 17, 14, 0, 0, '2016-01-18', 1453135329),
(88, 44, 17, 3, 0, 0, '2016-01-18', 1453135329),
(89, 48, 17, 40, 0, 0, '2016-01-18', 1453135329),
(90, 11, 12, 2, 0, 0, '2016-01-18', 1453135329),
(91, 49, 16, 3, 0, 0, '2016-01-20', 1453255190),
(92, 50, 12, 2, 0, 0, '2016-01-20', 1453303291),
(93, 51, 10, 5, 0, 0, '2016-01-21', 1453394885),
(94, 4, 18, 3, 0, 0, '2016-01-21', 1453394885),
(95, 30, 18, 5, 0, 0, '2016-01-21', 1453394885),
(96, 8, 18, 5, 0, 0, '2016-01-21', 1453394885),
(97, 5, 19, 3, 0, 0, '2016-01-21', 1453394885),
(98, 45, 19, 5, 0, 0, '2016-01-21', 1453394885),
(99, 51, 19, 5, 0, 0, '2016-01-21', 1453394885),
(100, 6, 15, 1, 0, 0, '2016-01-21', 1453394885),
(101, 52, 19, 51, 0, 0, '2016-01-21', 1453411928),
(102, 55, 15, 1, 0, 0, '2016-01-25', 1453762173),
(103, 56, 15, 1, 0, 0, '2016-01-25', 1453764054),
(104, 32, 20, 12, 0, 0, '2016-01-25', 1453764054),
(105, 36, 20, 1, 0, 0, '2016-01-25', 1453764054),
(106, 6, 20, 1, 0, 0, '2016-01-25', 1453764054),
(107, 1, 21, 0, 0, 0, '2016-01-25', 1453764054),
(108, 55, 21, 1, 0, 0, '2016-01-25', 1453764054),
(109, 56, 21, 1, 0, 0, '2016-01-25', 1453764054),
(110, 13, 12, 2, 0, 0, '2016-01-25', 1453764054),
(111, 57, 8, 17, 0, 0, '2016-01-26', 1453845606),
(112, 58, 21, 1, 0, 0, '2016-01-27', 1453902386),
(113, 59, 21, 58, 0, 0, '2016-01-27', 1453935208),
(114, 60, 21, 58, 0, 0, '2016-01-27', 1453935214),
(115, 75, 12, 11, 0, 0, '2016-02-03', 1454513033),
(116, 14, 22, 1, 0, 0, '2016-02-03', 1454513033),
(117, 11, 22, 2, 0, 0, '2016-02-03', 1454513033),
(118, 50, 22, 2, 0, 0, '2016-02-03', 1454513033),
(119, 26, 23, 1, 0, 0, '2016-02-03', 1454513033),
(120, 13, 23, 2, 0, 0, '2016-02-03', 1454513033),
(121, 75, 23, 11, 0, 0, '2016-02-03', 1454513033),
(122, 2, 21, 1, 0, 0, '2016-02-03', 1454513033),
(123, 55, 24, 1, 0, 0, '2016-02-03', 1454513033),
(124, 58, 24, 1, 0, 0, '2016-02-03', 1454513033),
(125, 59, 24, 58, 0, 0, '2016-02-03', 1454513033),
(126, 56, 25, 1, 0, 0, '2016-02-03', 1454513033),
(127, 60, 25, 58, 0, 0, '2016-02-03', 1454513033),
(128, 2, 25, 1, 0, 0, '2016-02-03', 1454513033),
(129, 1, 8, 0, 0, 0, '2016-02-03', 1454513033),
(130, 78, 14, 12, 0, 0, '2016-02-06', 1454800320),
(131, 80, 16, 40, 0, 0, '2016-02-18', 1455829029),
(132, 18, 19, 5, 0, 0, '2016-02-22', 1456100600),
(133, 82, 16, 40, 0, 0, '2016-02-25', 1456439867);

-- --------------------------------------------------------

--
-- Table structure for table `board_fifth`
--

DROP TABLE IF EXISTS `board_fifth`;
CREATE TABLE IF NOT EXISTS `board_fifth` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `pos1` int(5) NOT NULL,
  `pos2` int(5) NOT NULL,
  `pos3` int(5) NOT NULL,
  `pos4` int(5) NOT NULL,
  `pos5` int(5) NOT NULL,
  `pos6` int(5) NOT NULL,
  `pos7` int(5) NOT NULL,
  `level` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Truncate table before insert `board_fifth`
--

TRUNCATE TABLE `board_fifth`;
--
-- Dumping data for table `board_fifth`
--

INSERT INTO `board_fifth` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 4, 12, 9, 6, 0, '2016-01-25', 1453764054, 0),
(2, 1, 0, 2, 4, 12, 1, 11, 0, 0, 0, '2016-02-18', 0, 1),
(3, 2, 0, 3, 9, 6, 0, 0, 0, 0, 0, '2016-01-25', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_fourth`
--

DROP TABLE IF EXISTS `board_fourth`;
CREATE TABLE IF NOT EXISTS `board_fourth` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `pos1` int(5) NOT NULL,
  `pos2` int(5) NOT NULL,
  `pos3` int(5) NOT NULL,
  `pos4` int(5) NOT NULL,
  `pos5` int(5) NOT NULL,
  `pos6` int(5) NOT NULL,
  `pos7` int(5) NOT NULL,
  `level` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Truncate table before insert `board_fourth`
--

TRUNCATE TABLE `board_fourth`;
--
-- Dumping data for table `board_fourth`
--

INSERT INTO `board_fourth` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 4, 6, 9, 11, 0, '2016-01-05', 1452016551, 0),
(2, 1, 0, 2, 4, 6, 1, 12, 13, 16, 0, '2016-01-06', 1452118582, 0),
(3, 2, 0, 3, 9, 11, 15, 5, 24, 28, 0, '2016-01-09', 1452375149, 0),
(4, 0, 0, 4, 1, 12, 2, 25, 26, 3, 0, '2016-01-09', 1452375149, 0),
(5, 0, 0, 6, 13, 16, 29, 1, 55, 56, 0, '2016-01-25', 1453764054, 0),
(6, 0, 0, 9, 15, 5, 30, 8, 51, 52, 0, '2016-01-21', 1453411928, 0),
(7, 0, 0, 11, 24, 28, 40, 48, 75, 80, 0, '2016-02-18', 1455829029, 0),
(8, 0, 0, 1, 2, 25, 36, 12, 50, 9, 0, '2016-01-21', 1453411928, 0),
(9, 0, 0, 12, 26, 3, 4, 32, 47, 49, 0, '2016-01-20', 1453255190, 0),
(10, 13, 0, 26, 4, 32, 0, 0, 0, 0, 0, '2016-01-20', 0, 1),
(11, 2, 0, 3, 47, 49, 0, 0, 0, 0, 0, '2016-01-20', 0, 1),
(12, 7, 0, 15, 30, 8, 0, 0, 0, 0, 0, '2016-01-21', 0, 1),
(13, 0, 0, 5, 51, 52, 0, 0, 0, 0, 0, '2016-01-21', 0, 1),
(14, 1, 0, 2, 36, 12, 78, 11, 0, 0, 0, '2016-02-18', 0, 1),
(15, 0, 0, 25, 50, 9, 57, 0, 0, 0, 0, '2016-01-26', 0, 1),
(16, 6, 0, 13, 29, 1, 6, 0, 0, 0, 0, '2016-01-25', 0, 1),
(17, 8, 0, 16, 55, 56, 0, 0, 0, 0, 0, '2016-01-25', 0, 1),
(18, 12, 0, 24, 40, 48, 82, 0, 0, 0, 0, '2016-02-25', 0, 1),
(19, 14, 0, 28, 75, 80, 0, 0, 0, 0, 0, '2016-02-18', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_second`
--

DROP TABLE IF EXISTS `board_second`;
CREATE TABLE IF NOT EXISTS `board_second` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `pos1` int(5) NOT NULL,
  `pos2` int(5) NOT NULL,
  `pos3` int(5) NOT NULL,
  `pos4` int(5) NOT NULL,
  `pos5` int(5) NOT NULL,
  `pos6` int(5) NOT NULL,
  `pos7` int(5) NOT NULL,
  `level` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Truncate table before insert `board_second`
--

TRUNCATE TABLE `board_second`;
--
-- Dumping data for table `board_second`
--

INSERT INTO `board_second` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 4, 5, 6, 7, 0, '2016-01-05', 1452016488, 0),
(2, 1, 0, 2, 4, 5, 1, 8, 9, 10, 0, '2016-01-05', 1452016544, 0),
(3, 2, 0, 3, 6, 7, 4, 5, 21, 22, 0, '2016-01-07', 1452190450, 0),
(4, 0, 0, 4, 1, 8, 2, 11, 12, 13, 0, '2016-01-05', 1452024915, 0),
(5, 0, 0, 5, 9, 10, 15, 17, 18, 19, 0, '2016-01-07', 1452128193, 0),
(6, 0, 0, 1, 2, 11, 14, 3, 24, 26, 0, '2016-01-08', 1452276380, 0),
(7, 0, 0, 8, 12, 13, 16, 1, 29, 32, 0, '2016-01-12', 1452631762, 0),
(8, 0, 0, 9, 15, 17, 35, 39, 54, 57, 0, '2016-01-26', 1453845606, 0),
(9, 0, 0, 10, 18, 19, 25, 1, 58, 59, 0, '2016-01-27', 1453935208, 0),
(10, 0, 0, 6, 4, 5, 30, 8, 45, 51, 0, '2016-01-21', 1453394885, 0),
(11, 0, 0, 7, 21, 22, 23, 34, 41, 67, 0, '2016-01-29', 1454079286, 0),
(12, 1, 0, 2, 14, 3, 28, 33, 42, 43, 0, '2016-01-15', 1452899084, 0),
(13, 0, 0, 11, 24, 26, 40, 46, 48, 61, 0, '2016-01-28', 1453946410, 0),
(14, 0, 0, 12, 16, 1, 36, 2, 47, 50, 0, '2016-01-20', 1453303291, 0),
(15, 6, 0, 13, 29, 32, 0, 0, 0, 0, 0, '2016-01-12', 0, 1),
(16, 7, 0, 14, 28, 33, 0, 0, 0, 0, 0, '2016-01-15', 0, 1),
(17, 2, 0, 3, 42, 43, 44, 49, 63, 0, 0, '2016-01-28', 0, 1),
(18, 8, 0, 16, 36, 2, 12, 11, 75, 78, 0, '2016-02-06', 1454800320, 0),
(19, 0, 0, 1, 47, 50, 6, 55, 56, 9, 0, '2016-01-26', 1453845606, 0),
(20, 0, 0, 4, 30, 8, 0, 0, 0, 0, 0, '2016-01-21', 0, 1),
(21, 0, 0, 5, 45, 51, 52, 71, 18, 0, 0, '2016-02-22', 0, 1),
(22, 7, 0, 15, 35, 39, 0, 0, 0, 0, 0, '2016-01-26', 0, 1),
(23, 8, 0, 17, 54, 57, 0, 0, 0, 0, 0, '2016-01-26', 0, 1),
(24, 23, 0, 47, 6, 55, 0, 0, 0, 0, 0, '2016-01-26', 0, 1),
(25, 0, 0, 50, 56, 9, 0, 0, 0, 0, 0, '2016-01-26', 0, 1),
(26, 9, 0, 18, 25, 1, 10, 7, 77, 81, 0, '2016-02-22', 1456100600, 0),
(27, 9, 0, 19, 58, 59, 60, 66, 0, 0, 0, '2016-01-29', 0, 1),
(28, 12, 0, 24, 40, 46, 62, 80, 82, 0, 0, '2016-02-25', 0, 1),
(29, 13, 0, 26, 48, 61, 0, 0, 0, 0, 0, '2016-01-28', 0, 1),
(30, 10, 0, 21, 23, 34, 0, 0, 0, 0, 0, '2016-01-29', 0, 1),
(31, 11, 0, 22, 41, 67, 0, 0, 0, 0, 0, '2016-01-29', 0, 1),
(32, 18, 0, 36, 12, 11, 16, 0, 0, 0, 0, '2016-02-06', 0, 1),
(33, 1, 0, 2, 75, 78, 0, 0, 0, 0, 0, '2016-02-06', 0, 1),
(34, 0, 0, 25, 10, 7, 0, 0, 0, 0, 0, '2016-02-22', 0, 1),
(35, 0, 0, 1, 77, 81, 0, 0, 0, 0, 0, '2016-02-22', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_seven`
--

DROP TABLE IF EXISTS `board_seven`;
CREATE TABLE IF NOT EXISTS `board_seven` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `board_seven`
--

TRUNCATE TABLE `board_seven`;
-- --------------------------------------------------------

--
-- Table structure for table `board_sixth`
--

DROP TABLE IF EXISTS `board_sixth`;
CREATE TABLE IF NOT EXISTS `board_sixth` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `pos1` int(5) NOT NULL,
  `pos2` int(5) NOT NULL,
  `pos3` int(5) NOT NULL,
  `pos4` int(5) NOT NULL,
  `pos5` int(5) NOT NULL,
  `pos6` int(5) NOT NULL,
  `pos7` int(5) NOT NULL,
  `level` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `board_sixth`
--

TRUNCATE TABLE `board_sixth`;
-- --------------------------------------------------------

--
-- Table structure for table `board_third`
--

DROP TABLE IF EXISTS `board_third`;
CREATE TABLE IF NOT EXISTS `board_third` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(5) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `pos1` int(5) NOT NULL,
  `pos2` int(5) NOT NULL,
  `pos3` int(5) NOT NULL,
  `pos4` int(5) NOT NULL,
  `pos5` int(5) NOT NULL,
  `pos6` int(5) NOT NULL,
  `pos7` int(5) NOT NULL,
  `level` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Truncate table before insert `board_third`
--

TRUNCATE TABLE `board_third`;
--
-- Dumping data for table `board_third`
--

INSERT INTO `board_third` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 4, 5, 6, 7, 0, '2016-01-05', 1452016488, 0),
(2, 1, 0, 2, 4, 5, 1, 8, 9, 10, 0, '2016-01-05', 1452016544, 0),
(3, 2, 0, 3, 6, 7, 4, 5, 24, 28, 0, '2016-01-09', 1452375149, 0),
(4, 0, 0, 4, 1, 8, 2, 11, 12, 13, 0, '2016-01-05', 1452024915, 0),
(5, 0, 0, 5, 9, 10, 15, 21, 22, 23, 0, '2016-01-07', 1452192399, 0),
(6, 0, 0, 1, 2, 11, 14, 26, 3, 33, 0, '2016-01-13', 1452700520, 0),
(7, 0, 0, 8, 12, 13, 16, 29, 32, 1, 0, '2016-01-13', 1452700520, 0),
(8, 0, 0, 9, 15, 21, 57, 1, 0, 0, 0, '2016-02-03', 0, 1),
(9, 0, 0, 10, 22, 23, 25, 0, 0, 0, 0, '2016-01-08', 0, 1),
(10, 0, 0, 6, 4, 5, 30, 8, 45, 51, 0, '2016-01-21', 1453394885, 0),
(11, 0, 0, 7, 24, 28, 0, 0, 0, 0, 0, '2016-01-09', 0, 1),
(12, 1, 0, 2, 14, 26, 11, 50, 13, 75, 0, '2016-02-03', 1454513033, 0),
(13, 0, 0, 11, 3, 33, 40, 43, 44, 48, 0, '2016-01-18', 1453135329, 0),
(14, 0, 0, 12, 16, 29, 47, 78, 0, 0, 0, '2016-01-18', 0, 1),
(15, 6, 0, 13, 32, 1, 36, 6, 55, 56, 0, '2016-01-25', 1453764054, 0),
(16, 2, 0, 3, 40, 43, 49, 80, 82, 0, 0, '2016-02-25', 0, 1),
(17, 16, 0, 33, 44, 48, 0, 0, 0, 0, 0, '2016-01-18', 0, 1),
(18, 0, 0, 4, 30, 8, 0, 0, 0, 0, 0, '2016-01-21', 0, 1),
(19, 0, 0, 5, 45, 51, 52, 18, 0, 0, 0, '2016-02-22', 0, 1),
(20, 16, 0, 32, 36, 6, 0, 0, 0, 0, 0, '2016-01-25', 0, 1),
(21, 0, 0, 1, 55, 56, 58, 59, 60, 2, 0, '2016-02-03', 1454513033, 0),
(22, 7, 0, 14, 11, 50, 0, 0, 0, 0, 0, '2016-02-03', 0, 1),
(23, 13, 0, 26, 13, 75, 0, 0, 0, 0, 0, '2016-02-03', 0, 1),
(24, 0, 0, 55, 58, 59, 0, 0, 0, 0, 0, '2016-02-03', 0, 1),
(25, 0, 0, 56, 60, 2, 0, 0, 0, 0, 0, '2016-02-06', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `Country` varchar(100) NOT NULL,
  `State` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `cities`
--

TRUNCATE TABLE `cities`;
-- --------------------------------------------------------

--
-- Table structure for table `classified_info`
--

DROP TABLE IF EXISTS `classified_info`;
CREATE TABLE IF NOT EXISTS `classified_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `point` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `classified_info`
--

TRUNCATE TABLE `classified_info`;
-- --------------------------------------------------------

--
-- Table structure for table `deduct_amount`
--

DROP TABLE IF EXISTS `deduct_amount`;
CREATE TABLE IF NOT EXISTS `deduct_amount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` text,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `no_of_user` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `deduct_amount`
--

TRUNCATE TABLE `deduct_amount`;
-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `image_id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `image_type` varchar(25) NOT NULL,
  `image` mediumblob NOT NULL,
  `image_size` varchar(25) NOT NULL,
  `image_ctgy` varchar(25) NOT NULL,
  `image_name` varchar(50) NOT NULL,
  `doctype` int(1) DEFAULT NULL,
  `approved` int(1) DEFAULT '0',
  `date` varchar(11) DEFAULT '0',
  `dateapproved` varchar(11) DEFAULT '0',
  PRIMARY KEY (`image_id`),
  UNIQUE KEY `image_id_UNIQUE` (`image_id`),
  KEY `image_id` (`image_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Truncate table before insert `documents`
--

TRUNCATE TABLE `documents`;
--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`image_id`, `user_id`, `image_type`, `image`, `image_size`, `image_ctgy`, `image_name`, `doctype`, `approved`, `date`, `dateapproved`) VALUES
(1, 1, 'image/jpeg', '', '', '', 'document_user_1_identification_1452532116.jpg', 1, 1, '1452532116', '1452717551'),
(2, 9, 'application/pdf', '', '', '', 'document_user_9_taxinfo_1453326155.pdf', 2, 1, '1453326155', '1453989187'),
(3, 10, 'application/pdf', '', '', '', 'document_user_10_taxinfo_1454537743.pdf', 2, 1, '1454537743', '1454682366');

-- --------------------------------------------------------

--
-- Table structure for table `dwolla`
--

DROP TABLE IF EXISTS `dwolla`;
CREATE TABLE IF NOT EXISTS `dwolla` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `checkoutId` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `clearingDate` varchar(100) NOT NULL,
  `transaction` varchar(100) NOT NULL,
  `destinationTransaction` varchar(100) NOT NULL,
  `postback` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `dwolla`
--

TRUNCATE TABLE `dwolla`;
-- --------------------------------------------------------

--
-- Table structure for table `edata_ipn`
--

DROP TABLE IF EXISTS `edata_ipn`;
CREATE TABLE IF NOT EXISTS `edata_ipn` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cc_fname` varchar(50) NOT NULL,
  `cc_lname` varchar(50) NOT NULL,
  `response` tinyint(2) NOT NULL,
  `responsetext` varchar(255) NOT NULL,
  `authcode` int(11) NOT NULL,
  `transactionid` varchar(50) NOT NULL,
  `avsresponse` varchar(100) NOT NULL,
  `cvvresponse` varchar(100) NOT NULL,
  `orderid` bigint(11) NOT NULL,
  `type` varchar(25) NOT NULL,
  `response_code` int(3) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `edata_ipn`
--

TRUNCATE TABLE `edata_ipn`;
-- --------------------------------------------------------

--
-- Table structure for table `e_voucher`
--

DROP TABLE IF EXISTS `e_voucher`;
CREATE TABLE IF NOT EXISTS `e_voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `generate_id` int(11) NOT NULL,
  `epin_amount` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `voucher` text NOT NULL,
  `voucher_type` varchar(11) NOT NULL,
  `date` date NOT NULL,
  `mode` int(5) NOT NULL,
  `used_id` int(11) NOT NULL,
  `used_date` date NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `voucher` (`voucher`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=281 ;

--
-- Truncate table before insert `e_voucher`
--

TRUNCATE TABLE `e_voucher`;
--
-- Dumping data for table `e_voucher`
--

INSERT INTO `e_voucher` (`id`, `generate_id`, `epin_amount`, `user_id`, `voucher`, `voucher_type`, `date`, `mode`, `used_id`, `used_date`) VALUES
(1, 0, 99, 0, '6f3ef2feab', '1', '2016-01-05', 1, 0, '0000-00-00'),
(2, 0, 99, 0, 'b35d712503', '1', '2016-01-05', 1, 0, '0000-00-00'),
(3, 0, 99, 0, '19d5254bda', '1', '2016-01-05', 1, 0, '0000-00-00'),
(4, 0, 99, 0, '81198b8898', '1', '2016-01-05', 1, 0, '0000-00-00'),
(5, 0, 99, 0, '398304b637', '1', '2016-01-05', 1, 0, '0000-00-00'),
(6, 0, 99, 0, '3aaf107868', '1', '2016-01-05', 1, 0, '0000-00-00'),
(7, 0, 99, 0, 'dd6dba44b3', '1', '2016-01-05', 1, 0, '0000-00-00'),
(8, 0, 99, 0, '8878a8c166', '1', '2016-01-05', 0, 72, '2016-01-30'),
(9, 0, 99, 0, 'f5c80b20fd', '1', '2016-01-05', 1, 0, '0000-00-00'),
(10, 0, 99, 0, 'f9ef6bf98e', '1', '2016-01-05', 1, 0, '0000-00-00'),
(11, 0, 99, 0, 'fd581ff486', '1', '2016-01-05', 1, 0, '0000-00-00'),
(12, 0, 99, 0, 'b8a20bf368', '1', '2016-01-05', 1, 0, '0000-00-00'),
(13, 0, 99, 0, '47948b6665', '1', '2016-01-05', 1, 0, '0000-00-00'),
(14, 0, 99, 0, '0083bf1bc6', '1', '2016-01-05', 1, 0, '0000-00-00'),
(15, 0, 99, 0, '9b78f44329', '1', '2016-01-05', 0, 76, '2016-02-04'),
(16, 0, 99, 0, '839da70edc', '1', '2016-01-05', 0, 68, '2016-01-29'),
(17, 0, 99, 0, '46ea33cd92', '1', '2016-01-05', 1, 0, '0000-00-00'),
(18, 0, 99, 0, '2bb3f106ea', '1', '2016-01-05', 1, 0, '0000-00-00'),
(19, 0, 99, 0, '860fe42456', '1', '2016-01-05', 0, 69, '2016-01-29'),
(20, 0, 99, 0, '0926aee229', '1', '2016-01-05', 1, 0, '0000-00-00'),
(21, 0, 249, 0, '1a24e245b6', '2', '2016-01-05', 0, 41, '2016-01-15'),
(22, 0, 249, 0, '3046fd0302', '2', '2016-01-05', 0, 77, '2016-02-04'),
(23, 0, 249, 0, 'eb85a25869', '2', '2016-01-05', 1, 0, '0000-00-00'),
(24, 0, 249, 0, 'b659336581', '2', '2016-01-05', 0, 46, '2016-01-18'),
(25, 0, 249, 0, '1739876299', '2', '2016-01-05', 0, 34, '2016-01-13'),
(26, 0, 249, 0, 'ee20117357', '2', '2016-01-05', 1, 0, '0000-00-00'),
(27, 0, 249, 0, '797fe8da31', '2', '2016-01-05', 1, 0, '0000-00-00'),
(28, 0, 249, 0, '475bbc1276', '2', '2016-01-05', 1, 0, '0000-00-00'),
(29, 0, 249, 0, '57b01adc7e', '2', '2016-01-05', 1, 0, '0000-00-00'),
(30, 0, 999, 0, 'a2ee866e28', '2', '2016-01-05', 0, 24, '2016-01-07'),
(31, 0, 249, 0, 'c6115b36f8', '2', '2016-01-05', 0, 61, '2016-01-27'),
(32, 0, 249, 0, '43ecd7d766', '2', '2016-01-05', 1, 0, '0000-00-00'),
(33, 0, 249, 0, '9e0d45101f', '2', '2016-01-05', 0, 18, '2016-01-06'),
(34, 0, 249, 0, 'e3e21ff1f0', '2', '2016-01-05', 1, 0, '0000-00-00'),
(35, 0, 249, 0, '50a3ab6f3f', '2', '2016-01-05', 1, 0, '0000-00-00'),
(36, 0, 249, 0, '27bd8c5560', '2', '2016-01-05', 0, 67, '2016-01-29'),
(37, 0, 249, 0, 'dca90149da', '2', '2016-01-05', 1, 0, '0000-00-00'),
(38, 0, 249, 0, '5db158e264', '2', '2016-01-05', 1, 0, '0000-00-00'),
(39, 0, 249, 0, 'aa20f2324e', '2', '2016-01-05', 0, 42, '2016-01-15'),
(40, 0, 249, 0, 'ec84adb67a', '2', '2016-01-05', 1, 0, '0000-00-00'),
(41, 0, 499, 0, 'a1de6c427f', '3', '2016-01-05', 0, 23, '2016-01-07'),
(42, 0, 499, 0, 'af51d242a6', '3', '2016-01-05', 1, 0, '0000-00-00'),
(43, 0, 499, 0, 'a0f9a788fe', '3', '2016-01-05', 0, 22, '2016-01-07'),
(44, 0, 499, 0, '88bde561cc', '3', '2016-01-05', 0, 33, '2016-01-13'),
(45, 0, 499, 0, '950da343b7', '3', '2016-01-05', 1, 0, '0000-00-00'),
(46, 0, 499, 0, '72365005d5', '3', '2016-01-05', 1, 0, '0000-00-00'),
(47, 0, 499, 0, 'a9a3032089', '3', '2016-01-05', 0, 44, '2016-01-15'),
(48, 0, 499, 0, '8ec242a184', '3', '2016-01-05', 1, 0, '0000-00-00'),
(49, 0, 499, 0, 'a0f1fcc963', '3', '2016-01-05', 0, 21, '2016-01-07'),
(50, 0, 499, 0, 'de8a628b93', '3', '2016-01-05', 1, 0, '0000-00-00'),
(51, 0, 499, 0, 'e22e00ebe9', '3', '2016-01-05', 1, 0, '0000-00-00'),
(52, 0, 499, 0, 'a24453e393', '3', '2016-01-05', 0, 43, '2016-01-15'),
(53, 0, 499, 0, '687b5768a7', '3', '2016-01-05', 1, 0, '0000-00-00'),
(54, 0, 499, 0, '26ce65d41e', '3', '2016-01-05', 1, 0, '0000-00-00'),
(55, 0, 499, 0, '736e85eab6', '3', '2016-01-05', 0, 14, '2016-01-06'),
(56, 0, 499, 0, '49777ceb45', '3', '2016-01-05', 1, 0, '0000-00-00'),
(57, 0, 499, 0, 'e98fd4db56', '3', '2016-01-05', 1, 0, '0000-00-00'),
(58, 0, 499, 0, '8dc2521a11', '3', '2016-01-05', 1, 0, '0000-00-00'),
(59, 0, 499, 0, 'b6b48639e4', '3', '2016-01-05', 1, 0, '0000-00-00'),
(60, 0, 499, 0, '3923b64258', '3', '2016-01-05', 1, 0, '0000-00-00'),
(61, 0, 999, 0, 'c677e80728', '4', '2016-01-05', 0, 13, '2016-01-05'),
(62, 0, 999, 0, '5f8b9fc5ad', '4', '2016-01-05', 0, 26, '2016-01-08'),
(63, 0, 999, 0, '1f239ed9e6', '4', '2016-01-05', 0, 15, '2016-01-06'),
(64, 0, 999, 0, 'ebde53e879', '4', '2016-01-05', 0, 48, '2016-01-18'),
(65, 0, 999, 0, 'fc05c41c81', '4', '2016-01-05', 1, 0, '0000-00-00'),
(66, 0, 999, 0, '3142cfbe4e', '4', '2016-01-05', 1, 0, '0000-00-00'),
(67, 0, 999, 0, 'b62d83827d', '4', '2016-01-05', 0, 51, '2016-01-21'),
(68, 0, 999, 0, '85dc41fce3', '4', '2016-01-05', 1, 0, '0000-00-00'),
(69, 0, 999, 0, '32f14bac32', '4', '2016-01-05', 1, 0, '0000-00-00'),
(70, 0, 999, 0, 'b1608c69d2', '4', '2016-01-05', 0, 49, '2016-01-19'),
(71, 0, 999, 0, '8f76339ce5', '4', '2016-01-05', 0, 28, '2016-01-09'),
(72, 0, 999, 0, '41a80e3c55', '4', '2016-01-05', 1, 0, '0000-00-00'),
(73, 0, 999, 0, 'b932401650', '4', '2016-01-05', 0, 52, '2016-01-21'),
(74, 0, 999, 0, 'dfa128261a', '4', '2016-01-05', 0, 40, '2016-01-14'),
(75, 0, 999, 0, 'ec5e909936', '4', '2016-01-05', 0, 75, '2016-02-03'),
(76, 0, 999, 0, '6071d41e11', '4', '2016-01-05', 0, 36, '2016-01-13'),
(77, 0, 999, 0, '7457ae5d50', '4', '2016-01-05', 1, 0, '0000-00-00'),
(78, 0, 999, 0, '34c5e4b8f5', '4', '2016-01-05', 1, 0, '0000-00-00'),
(79, 0, 999, 0, '723d083de6', '4', '2016-01-05', 1, 0, '0000-00-00'),
(80, 0, 999, 0, 'a5ccefd23c', '4', '2016-01-05', 0, 30, '2016-01-11'),
(81, 0, 99, 0, '5b3a108b4d', '1', '2016-01-06', 1, 0, '0000-00-00'),
(82, 0, 99, 0, 'fe8211f52c', '1', '2016-01-06', 1, 0, '0000-00-00'),
(83, 0, 99, 0, '8344a14505', '1', '2016-01-06', 1, 0, '0000-00-00'),
(84, 0, 99, 0, '05f200e52a', '1', '2016-01-06', 0, 38, '2016-01-13'),
(85, 0, 99, 0, 'e626f9ea5a', '1', '2016-01-06', 1, 0, '0000-00-00'),
(86, 0, 99, 0, 'c63b64ef67', '1', '2016-01-06', 1, 0, '0000-00-00'),
(87, 0, 99, 0, '0690a8aa06', '1', '2016-01-06', 0, 65, '2016-01-29'),
(88, 0, 99, 0, 'f7fe146c4b', '1', '2016-01-06', 1, 0, '0000-00-00'),
(89, 0, 99, 0, 'a43d491db8', '1', '2016-01-06', 1, 0, '0000-00-00'),
(90, 0, 99, 0, 'c9198c9f5a', '1', '2016-01-06', 1, 0, '0000-00-00'),
(91, 0, 99, 0, 'db125caa4b', '1', '2016-01-06', 1, 0, '0000-00-00'),
(92, 0, 99, 0, 'a5286d90db', '1', '2016-01-06', 1, 0, '0000-00-00'),
(93, 0, 99, 0, '39d7399d54', '1', '2016-01-06', 1, 0, '0000-00-00'),
(94, 0, 99, 0, 'd9173d8eca', '1', '2016-01-06', 1, 0, '0000-00-00'),
(95, 0, 99, 0, '10d0cebbed', '1', '2016-01-06', 1, 0, '0000-00-00'),
(96, 0, 99, 0, '9cf2bd74e4', '1', '2016-01-06', 1, 0, '0000-00-00'),
(97, 0, 99, 0, 'c1099cd516', '1', '2016-01-06', 1, 0, '0000-00-00'),
(98, 0, 99, 0, 'a5d8ef80a0', '1', '2016-01-06', 1, 0, '0000-00-00'),
(99, 0, 99, 0, '55b7b6dfee', '1', '2016-01-06', 1, 0, '0000-00-00'),
(100, 0, 99, 0, 'e68a1bd22d', '1', '2016-01-06', 1, 0, '0000-00-00'),
(101, 0, 99, 0, '8948bca994', '1', '2016-01-06', 1, 0, '0000-00-00'),
(102, 0, 99, 0, '070783c093', '1', '2016-01-06', 1, 0, '0000-00-00'),
(103, 0, 99, 0, '9d8a9f493a', '1', '2016-01-06', 1, 0, '0000-00-00'),
(104, 0, 99, 0, '3b209db07f', '1', '2016-01-06', 1, 0, '0000-00-00'),
(105, 0, 99, 0, 'c895b216e1', '1', '2016-01-06', 1, 0, '0000-00-00'),
(106, 0, 99, 0, '01d6eb3030', '1', '2016-01-06', 0, 20, '2016-01-06'),
(107, 0, 99, 0, '7e9b4b6d9a', '1', '2016-01-06', 1, 0, '0000-00-00'),
(108, 0, 99, 0, '0f5ecb4a79', '1', '2016-01-06', 1, 0, '0000-00-00'),
(109, 0, 99, 0, '91f9737f0f', '1', '2016-01-06', 1, 0, '0000-00-00'),
(110, 0, 99, 0, 'ec33026251', '1', '2016-01-06', 1, 0, '0000-00-00'),
(111, 0, 99, 0, 'd3524404b9', '1', '2016-01-06', 1, 0, '0000-00-00'),
(112, 0, 99, 0, '645571467e', '1', '2016-01-06', 1, 0, '0000-00-00'),
(113, 0, 99, 0, '70745b44a2', '1', '2016-01-06', 1, 0, '0000-00-00'),
(114, 0, 99, 0, 'c928d38c5e', '1', '2016-01-06', 1, 0, '0000-00-00'),
(115, 0, 99, 0, 'c5013cc704', '1', '2016-01-06', 1, 0, '0000-00-00'),
(116, 0, 99, 0, 'ff213e889d', '1', '2016-01-06', 1, 0, '0000-00-00'),
(117, 0, 99, 0, '68b6365342', '1', '2016-01-06', 1, 0, '0000-00-00'),
(118, 0, 99, 0, '87cdbb9b1c', '1', '2016-01-06', 1, 0, '0000-00-00'),
(119, 0, 99, 0, 'd5888ba001', '1', '2016-01-06', 1, 0, '0000-00-00'),
(120, 0, 99, 0, '468a1ddd77', '1', '2016-01-06', 1, 0, '0000-00-00'),
(121, 0, 99, 0, '641061d2fd', '1', '2016-01-06', 1, 0, '0000-00-00'),
(122, 0, 99, 0, '64188a1c41', '1', '2016-01-06', 1, 0, '0000-00-00'),
(123, 0, 99, 0, '8b54aaeda2', '1', '2016-01-06', 1, 0, '0000-00-00'),
(124, 0, 99, 0, '1b31afa0b5', '1', '2016-01-06', 1, 0, '0000-00-00'),
(125, 0, 99, 0, 'dd80ef0436', '1', '2016-01-06', 1, 0, '0000-00-00'),
(126, 0, 99, 0, '3270269f27', '1', '2016-01-06', 1, 0, '0000-00-00'),
(127, 0, 99, 0, 'e03cba77a0', '1', '2016-01-06', 1, 0, '0000-00-00'),
(128, 0, 99, 0, '482ca8b278', '1', '2016-01-06', 1, 0, '0000-00-00'),
(129, 0, 99, 0, '6d19801fcb', '1', '2016-01-06', 1, 0, '0000-00-00'),
(130, 0, 99, 0, '1b751138ce', '1', '2016-01-06', 1, 0, '0000-00-00'),
(131, 0, 249, 0, 'a5fbed546f', '2', '2016-01-06', 1, 0, '0000-00-00'),
(132, 0, 249, 0, '793ec95284', '2', '2016-01-06', 0, 81, '2016-02-21'),
(133, 0, 249, 0, 'e96c21257f', '2', '2016-01-06', 1, 0, '0000-00-00'),
(134, 0, 249, 0, 'fe262a3539', '2', '2016-01-06', 0, 62, '2016-01-27'),
(135, 0, 249, 0, '9a6eb2e77f', '2', '2016-01-06', 1, 0, '0000-00-00'),
(136, 0, 249, 0, '1bfee9cddc', '2', '2016-01-06', 0, 39, '2016-01-14'),
(137, 0, 249, 0, '73d184e80c', '2', '2016-01-06', 0, 71, '2016-01-30'),
(138, 0, 249, 0, 'f8c3090b2a', '2', '2016-01-06', 1, 0, '0000-00-00'),
(139, 0, 249, 0, 'caf1389513', '2', '2016-01-06', 1, 0, '0000-00-00'),
(140, 0, 249, 0, 'f809436b64', '2', '2016-01-06', 1, 0, '0000-00-00'),
(141, 0, 249, 0, 'd4c3b722bf', '2', '2016-01-06', 1, 0, '0000-00-00'),
(142, 0, 249, 0, 'fddb483583', '2', '2016-01-06', 0, 66, '2016-01-29'),
(143, 0, 249, 0, '9cdb1f361f', '2', '2016-01-06', 1, 0, '0000-00-00'),
(144, 0, 249, 0, 'e39c09d250', '2', '2016-01-06', 1, 0, '0000-00-00'),
(145, 0, 249, 0, '0a140b33c0', '2', '2016-01-06', 0, 19, '2016-01-06'),
(146, 0, 249, 0, '909297261f', '2', '2016-01-06', 1, 0, '0000-00-00'),
(147, 0, 249, 0, '905885cb34', '2', '2016-01-06', 1, 0, '0000-00-00'),
(148, 0, 249, 0, 'c23460b6dc', '2', '2016-01-06', 1, 0, '0000-00-00'),
(149, 0, 249, 0, '9b5dc5f3eb', '2', '2016-01-06', 1, 0, '0000-00-00'),
(150, 0, 249, 0, 'c0be89068c', '2', '2016-01-06', 1, 0, '0000-00-00'),
(151, 0, 249, 0, '8310ddeff0', '2', '2016-01-06', 1, 0, '0000-00-00'),
(152, 0, 249, 0, '60a6c4002c', '2', '2016-01-06', 1, 0, '0000-00-00'),
(153, 0, 249, 0, 'a7822f76ab', '2', '2016-01-06', 1, 0, '0000-00-00'),
(154, 0, 249, 0, '398304b637', '2', '2016-01-06', 1, 0, '0000-00-00'),
(155, 0, 249, 0, 'bc922a5ae0', '2', '2016-01-06', 1, 0, '0000-00-00'),
(156, 0, 249, 0, '5d2c3c248a', '2', '2016-01-06', 1, 0, '0000-00-00'),
(157, 0, 249, 0, 'ae29604364', '2', '2016-01-06', 1, 0, '0000-00-00'),
(158, 0, 249, 0, 'f8bdb0add9', '2', '2016-01-06', 1, 0, '0000-00-00'),
(159, 0, 249, 0, 'f992afd487', '2', '2016-01-06', 1, 0, '0000-00-00'),
(160, 0, 249, 0, '73ac6f6a7b', '2', '2016-01-06', 1, 0, '0000-00-00'),
(161, 0, 249, 0, '3e036ef6ea', '2', '2016-01-06', 1, 0, '0000-00-00'),
(162, 0, 249, 0, '8e0653cd05', '2', '2016-01-06', 1, 0, '0000-00-00'),
(163, 0, 249, 0, '00d78d6582', '2', '2016-01-06', 0, 17, '2016-01-06'),
(164, 0, 249, 0, '966ce7870d', '2', '2016-01-06', 1, 0, '0000-00-00'),
(165, 0, 249, 0, '703527a660', '2', '2016-01-06', 1, 0, '0000-00-00'),
(166, 0, 249, 0, '2b69e7b428', '2', '2016-01-06', 0, 54, '2016-01-24'),
(167, 0, 249, 0, 'd0bd8f51bf', '2', '2016-01-06', 1, 0, '0000-00-00'),
(168, 0, 249, 0, 'f636ac6aca', '2', '2016-01-06', 1, 0, '0000-00-00'),
(169, 0, 249, 0, 'ee7221f22e', '2', '2016-01-06', 1, 0, '0000-00-00'),
(170, 0, 249, 0, '89939cc97d', '2', '2016-01-06', 1, 0, '0000-00-00'),
(171, 0, 249, 0, '508d4abd4a', '2', '2016-01-06', 1, 0, '0000-00-00'),
(172, 0, 249, 0, '93a57d492d', '2', '2016-01-06', 1, 0, '0000-00-00'),
(173, 0, 249, 0, '10c087244f', '2', '2016-01-06', 0, 35, '2016-01-13'),
(174, 0, 249, 0, '6fb58014bf', '2', '2016-01-06', 1, 0, '0000-00-00'),
(175, 0, 249, 0, 'fabd584691', '2', '2016-01-06', 1, 0, '0000-00-00'),
(176, 0, 249, 0, '5733401930', '2', '2016-01-06', 1, 0, '0000-00-00'),
(177, 0, 249, 0, 'd0f91173e9', '2', '2016-01-06', 1, 0, '0000-00-00'),
(178, 0, 249, 0, 'cfaaf68b01', '2', '2016-01-06', 1, 0, '0000-00-00'),
(179, 0, 249, 0, '54083e77c5', '2', '2016-01-06', 1, 0, '0000-00-00'),
(180, 0, 249, 0, 'ee2eef0e86', '2', '2016-01-06', 1, 0, '0000-00-00'),
(181, 0, 499, 0, 'a72b64a13b', '3', '2016-01-06', 1, 0, '0000-00-00'),
(182, 0, 499, 0, 'e62fe31754', '3', '2016-01-06', 1, 0, '0000-00-00'),
(183, 0, 499, 0, '63320b6ec5', '3', '2016-01-06', 1, 0, '0000-00-00'),
(184, 0, 499, 0, 'e85e811482', '3', '2016-01-06', 1, 0, '0000-00-00'),
(185, 0, 499, 0, 'ca6e8f8ef2', '3', '2016-01-06', 1, 0, '0000-00-00'),
(186, 0, 499, 0, '41871dba42', '3', '2016-01-06', 1, 0, '0000-00-00'),
(187, 0, 499, 0, 'cb8ccf69d6', '3', '2016-01-06', 1, 0, '0000-00-00'),
(188, 0, 499, 0, 'aa1c786272', '3', '2016-01-06', 1, 0, '0000-00-00'),
(189, 0, 499, 0, 'a630de45e0', '3', '2016-01-06', 1, 0, '0000-00-00'),
(190, 0, 499, 0, '7195b7b16a', '3', '2016-01-06', 1, 0, '0000-00-00'),
(191, 0, 499, 0, '2b7a4f25ab', '3', '2016-01-06', 1, 0, '0000-00-00'),
(192, 0, 499, 0, '847e3131c3', '3', '2016-01-06', 1, 0, '0000-00-00'),
(193, 0, 499, 0, '97a0ccbec6', '3', '2016-01-06', 1, 0, '0000-00-00'),
(194, 0, 499, 0, '8ab6446401', '3', '2016-01-06', 1, 0, '0000-00-00'),
(195, 0, 499, 0, '6179724498', '3', '2016-01-06', 1, 0, '0000-00-00'),
(196, 0, 499, 0, '4edf30eeab', '3', '2016-01-06', 1, 0, '0000-00-00'),
(197, 0, 499, 0, '19113771e8', '3', '2016-01-06', 1, 0, '0000-00-00'),
(198, 0, 499, 0, '88551c1278', '3', '2016-01-06', 0, 45, '2016-01-18'),
(199, 0, 499, 0, 'ea2a04bbe6', '3', '2016-01-06', 1, 0, '0000-00-00'),
(200, 0, 499, 0, 'd0855e33c8', '3', '2016-01-06', 1, 0, '0000-00-00'),
(201, 0, 499, 0, '519419df61', '3', '2016-01-06', 1, 0, '0000-00-00'),
(202, 0, 499, 0, '26269d202b', '3', '2016-01-06', 1, 0, '0000-00-00'),
(203, 0, 499, 0, '3c4fef8c33', '3', '2016-01-06', 1, 0, '0000-00-00'),
(204, 0, 499, 0, '76fbc1b267', '3', '2016-01-06', 1, 0, '0000-00-00'),
(205, 0, 499, 0, 'dbfd064469', '3', '2016-01-06', 1, 0, '0000-00-00'),
(206, 0, 499, 0, 'b10a14b2e6', '3', '2016-01-06', 1, 0, '0000-00-00'),
(207, 0, 499, 0, 'd36928c95b', '3', '2016-01-06', 1, 0, '0000-00-00'),
(208, 0, 499, 0, 'b44832dc77', '3', '2016-01-06', 1, 0, '0000-00-00'),
(209, 0, 499, 0, 'cabffce8f6', '3', '2016-01-06', 1, 0, '0000-00-00'),
(210, 0, 499, 0, '889198b375', '3', '2016-01-06', 1, 0, '0000-00-00'),
(211, 0, 499, 0, '3485e3f691', '3', '2016-01-06', 1, 0, '0000-00-00'),
(212, 0, 499, 0, 'ebca0bfa3e', '3', '2016-01-06', 1, 0, '0000-00-00'),
(213, 0, 499, 0, 'c50d02dee8', '3', '2016-01-06', 1, 0, '0000-00-00'),
(214, 0, 499, 0, '9b668ae6e2', '3', '2016-01-06', 1, 0, '0000-00-00'),
(215, 0, 499, 0, '414573d3fd', '3', '2016-01-06', 1, 0, '0000-00-00'),
(216, 0, 499, 0, '368513b725', '3', '2016-01-06', 1, 0, '0000-00-00'),
(217, 0, 499, 0, '4747ccd6f6', '3', '2016-01-06', 1, 0, '0000-00-00'),
(218, 0, 499, 0, '4131090cbd', '3', '2016-01-06', 1, 0, '0000-00-00'),
(219, 0, 499, 0, 'ffd130b59d', '3', '2016-01-06', 1, 0, '0000-00-00'),
(220, 0, 499, 0, 'b31871a6ab', '3', '2016-01-06', 1, 0, '0000-00-00'),
(221, 0, 499, 0, 'eba30bf99a', '3', '2016-01-06', 1, 0, '0000-00-00'),
(222, 0, 499, 0, 'ab59172215', '3', '2016-01-06', 1, 0, '0000-00-00'),
(223, 0, 499, 0, '71c22e78cc', '3', '2016-01-06', 1, 0, '0000-00-00'),
(224, 0, 499, 0, '0daf814e9a', '3', '2016-01-06', 1, 0, '0000-00-00'),
(225, 0, 499, 0, '080913799e', '3', '2016-01-06', 1, 0, '0000-00-00'),
(226, 0, 499, 0, '2d305e59ef', '3', '2016-01-06', 1, 0, '0000-00-00'),
(227, 0, 499, 0, 'afff34f5c8', '3', '2016-01-06', 1, 0, '0000-00-00'),
(228, 0, 499, 0, 'd72a6d7683', '3', '2016-01-06', 1, 0, '0000-00-00'),
(229, 0, 499, 0, 'e7baadbfb1', '3', '2016-01-06', 1, 0, '0000-00-00'),
(230, 0, 499, 0, 'f47abc33ae', '3', '2016-01-06', 1, 0, '0000-00-00'),
(231, 0, 999, 0, '714c58c815', '4', '2016-01-06', 1, 0, '0000-00-00'),
(232, 0, 999, 0, '9b61e20d2a', '4', '2016-01-06', 1, 0, '0000-00-00'),
(233, 0, 999, 0, 'c222b98098', '4', '2016-01-06', 1, 0, '0000-00-00'),
(234, 0, 999, 0, '96aadba91e', '4', '2016-01-06', 1, 0, '0000-00-00'),
(235, 0, 999, 0, '63ee89e8e2', '4', '2016-01-06', 1, 0, '0000-00-00'),
(236, 0, 999, 0, '7d4013adb7', '4', '2016-01-06', 1, 0, '0000-00-00'),
(237, 0, 999, 0, 'db2675993c', '4', '2016-01-06', 1, 0, '0000-00-00'),
(238, 0, 999, 0, '62c4f77505', '4', '2016-01-06', 1, 0, '0000-00-00'),
(239, 0, 999, 0, '654dd74cfe', '4', '2016-01-06', 1, 0, '0000-00-00'),
(240, 0, 999, 0, 'cbaf927430', '4', '2016-01-06', 1, 0, '0000-00-00'),
(241, 0, 999, 0, '423cf27d7a', '4', '2016-01-06', 1, 0, '0000-00-00'),
(242, 0, 999, 0, 'a1e7453d8c', '4', '2016-01-06', 1, 0, '0000-00-00'),
(243, 0, 999, 0, '5552d8338e', '4', '2016-01-06', 1, 0, '0000-00-00'),
(244, 0, 999, 0, '2e362d176c', '4', '2016-01-06', 0, 47, '2016-01-18'),
(245, 0, 999, 0, '85b26bc2ac', '4', '2016-01-06', 1, 0, '0000-00-00'),
(246, 0, 999, 0, 'e8726135a2', '4', '2016-01-06', 1, 0, '0000-00-00'),
(247, 0, 999, 0, '0257ef47ca', '4', '2016-01-06', 0, 57, '2016-01-26'),
(248, 0, 999, 0, 'b5d281f376', '4', '2016-01-06', 1, 0, '0000-00-00'),
(249, 0, 999, 0, 'da5ab2a267', '4', '2016-01-06', 1, 0, '0000-00-00'),
(250, 0, 999, 0, '78c544bdab', '4', '2016-01-06', 1, 0, '0000-00-00'),
(251, 0, 999, 0, 'd6bc0c2ca7', '4', '2016-01-06', 1, 0, '0000-00-00'),
(252, 0, 999, 0, 'aa100cc85f', '4', '2016-01-06', 1, 0, '0000-00-00'),
(253, 0, 999, 0, 'e8088b3aee', '4', '2016-01-06', 1, 0, '0000-00-00'),
(254, 0, 999, 0, '96483b00ce', '4', '2016-01-06', 1, 0, '0000-00-00'),
(255, 0, 999, 0, '56976d3473', '4', '2016-01-06', 1, 0, '0000-00-00'),
(256, 0, 999, 0, 'ec0081413f', '4', '2016-01-06', 1, 0, '0000-00-00'),
(257, 0, 999, 0, '96f2a8c127', '4', '2016-01-06', 1, 0, '0000-00-00'),
(258, 0, 999, 0, 'd439f5145f', '4', '2016-01-06', 1, 0, '0000-00-00'),
(259, 0, 999, 0, '1589813b41', '4', '2016-01-06', 1, 0, '0000-00-00'),
(260, 0, 999, 0, '73131f858f', '4', '2016-01-06', 1, 0, '0000-00-00'),
(261, 0, 999, 0, '8959f2d0ad', '4', '2016-01-06', 1, 0, '0000-00-00'),
(262, 0, 999, 0, '1b2bdcba14', '4', '2016-01-06', 1, 0, '0000-00-00'),
(263, 0, 999, 0, 'a0bbdd75b0', '4', '2016-01-06', 1, 0, '0000-00-00'),
(264, 0, 999, 0, '324ea3ce8a', '4', '2016-01-06', 0, 78, '2016-02-06'),
(265, 0, 999, 0, '581ddaca1e', '4', '2016-01-06', 1, 0, '0000-00-00'),
(266, 0, 999, 0, 'b95cbab3ab', '4', '2016-01-06', 1, 0, '0000-00-00'),
(267, 0, 999, 0, 'c58b391b9f', '4', '2016-01-06', 1, 0, '0000-00-00'),
(268, 0, 999, 0, '67f06a6e5c', '4', '2016-01-06', 1, 0, '0000-00-00'),
(269, 0, 999, 0, 'b6f71d26f5', '4', '2016-01-06', 1, 0, '0000-00-00'),
(270, 0, 999, 0, '0bcb925241', '4', '2016-01-06', 1, 0, '0000-00-00'),
(271, 0, 999, 0, '395fb48b91', '4', '2016-01-06', 1, 0, '0000-00-00'),
(272, 0, 999, 0, '981c5a612f', '4', '2016-01-06', 1, 0, '0000-00-00'),
(273, 0, 999, 0, '6a14d9353a', '4', '2016-01-06', 1, 0, '0000-00-00'),
(274, 0, 999, 0, '22e2fc3e5d', '4', '2016-01-06', 0, 16, '2016-01-06'),
(275, 0, 999, 0, '1569bf328e', '4', '2016-01-06', 1, 0, '0000-00-00'),
(276, 0, 999, 0, '3dae5801ff', '4', '2016-01-06', 1, 0, '0000-00-00'),
(277, 0, 999, 0, 'dd59adc132', '4', '2016-01-06', 1, 0, '0000-00-00'),
(278, 0, 999, 0, '5924061c4e', '4', '2016-01-06', 1, 0, '0000-00-00'),
(279, 0, 999, 0, 'c841b23266', '4', '2016-01-06', 1, 0, '0000-00-00'),
(280, 0, 999, 0, '2fbbf6a537', '4', '2016-01-06', 0, 32, '2016-01-12');

-- --------------------------------------------------------

--
-- Table structure for table `e_voucher_transfer`
--

DROP TABLE IF EXISTS `e_voucher_transfer`;
CREATE TABLE IF NOT EXISTS `e_voucher_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_id` int(11) NOT NULL,
  `e_voucher` int(11) NOT NULL,
  `date` date NOT NULL,
  `note` text NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `e_voucher_transfer`
--

TRUNCATE TABLE `e_voucher_transfer`;
-- --------------------------------------------------------

--
-- Table structure for table `form_data`
--

DROP TABLE IF EXISTS `form_data`;
CREATE TABLE IF NOT EXISTS `form_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `data1` varchar(255) NOT NULL,
  `data2` varchar(255) NOT NULL,
  `data3` varchar(255) NOT NULL,
  `data4` varchar(255) NOT NULL,
  `data5` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `form_data`
--

TRUNCATE TABLE `form_data`;
--
-- Dumping data for table `form_data`
--

INSERT INTO `form_data` (`id`, `user_id`, `data1`, `data2`, `data3`, `data4`, `data5`, `date`) VALUES
(1, 1, '', '', '', '', '', '2013-09-22');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

DROP TABLE IF EXISTS `income`;
CREATE TABLE IF NOT EXISTS `income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `other` int(11) NOT NULL DEFAULT '0',
  `other_type` varchar(100) NOT NULL,
  `reenter` int(11) NOT NULL DEFAULT '0',
  `co_comm` int(11) NOT NULL DEFAULT '0',
  `admin_tax` double NOT NULL,
  `left_income` double NOT NULL,
  `type` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `board_type` int(11) NOT NULL,
  `approved` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Truncate table before insert `income`
--

TRUNCATE TABLE `income`;
--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `user_id`, `amount`, `other`, `other_type`, `reenter`, `co_comm`, `admin_tax`, `left_income`, `type`, `date`, `time`, `level`, `board_type`, `approved`) VALUES
(1, 1, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-05', 1452016488, 1, 2, 1),
(2, 1, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-05', 1452016488, 2, 2, 1),
(3, 1, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-05', 1452016488, 3, 2, 1),
(4, 2, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-05', 1452016544, 1, 2, 1),
(5, 2, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-05', 1452016544, 2, 2, 1),
(6, 2, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-05', 1452016544, 3, 2, 1),
(7, 1, 1200, 0, '', 500, 300, 0, 0, 1, '2016-01-05', 1452016551, 4, 2, 1),
(8, 4, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-05', 1452024915, 1, 2, 1),
(9, 4, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-05', 1452024915, 2, 2, 1),
(10, 4, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-05', 1452024915, 3, 2, 1),
(11, 2, 1200, 0, '', 500, 300, 0, 0, 1, '2016-01-06', 1452118582, 4, 2, 1),
(12, 5, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-07', 1452128193, 1, 2, 1),
(13, 5, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-07', 1452128193, 2, 2, 1),
(14, 3, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-07', 1452190450, 1, 2, 1),
(15, 3, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-07', 1452190450, 2, 2, 1),
(16, 5, 0, 500, 'advanced comm', 250, 250, 0, 0, 2, '2016-01-07', 1452192399, 3, 3, 1),
(17, 1, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-08', 1452276380, 1, 2, 1),
(18, 1, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-08', 1452276380, 2, 2, 1),
(19, 3, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-09', 1452375149, 3, 2, 1),
(20, 3, 1200, 0, '', 500, 300, 0, 0, 1, '2016-01-09', 1452375149, 4, 2, 1),
(21, 4, 0, 1200, 'advanced comm', 500, 300, 0, 0, 2, '2016-01-09', 1452375149, 4, 4, 1),
(22, 8, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-12', 1452631762, 1, 2, 1),
(23, 8, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-12', 1452631762, 2, 2, 1),
(24, 1, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-13', 1452700520, 3, 2, 1),
(25, 8, 0, 500, 'advanced comm', 250, 250, 0, 0, 2, '2016-01-13', 1452700520, 3, 3, 1),
(26, 9, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-14', 1452791371, 1, 2, 1),
(27, 2, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-15', 1452899084, 1, 2, 1),
(28, 2, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-15', 1452899084, 2, 2, 1),
(29, 12, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-18', 1453134778, 1, 2, 1),
(30, 11, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-18', 1453135329, 3, 2, 1),
(31, 12, 0, 1200, 'advanced comm', 500, 300, 0, 0, 2, '2016-01-20', 1453255190, 4, 4, 1),
(32, 12, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-20', 1453303291, 2, 2, 1),
(33, 6, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-21', 1453394885, 1, 2, 1),
(34, 6, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-21', 1453394885, 2, 2, 1),
(35, 6, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-21', 1453394885, 3, 2, 1),
(36, 9, 0, 1200, 'advanced comm', 500, 300, 0, 0, 2, '2016-01-21', 1453411928, 4, 4, 1),
(37, 1, 1200, 0, '', 500, 300, 0, 0, 1, '2016-01-21', 1453411928, 4, 2, 1),
(38, 1, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-25', 1453762173, 1, 2, 1),
(39, 13, 500, 0, '', 250, 250, 0, 0, 1, '2016-01-25', 1453764054, 3, 2, 1),
(40, 6, 0, 1200, 'advanced comm', 500, 300, 0, 0, 2, '2016-01-25', 1453764054, 4, 4, 1),
(41, 1, 3000, 0, '', 1200, 600, 0, 0, 1, '2016-01-25', 1453764054, 5, 1, 1),
(42, 9, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-26', 1453845606, 2, 2, 1),
(43, 1, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-26', 1453845606, 2, 2, 1),
(44, 10, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-27', 1453902386, 1, 2, 1),
(45, 10, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-27', 1453935208, 2, 2, 1),
(46, 11, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-28', 1453946410, 1, 2, 1),
(47, 11, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-28', 1453946410, 2, 2, 1),
(48, 19, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-29', 1454076066, 1, 2, 1),
(49, 7, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-29', 1454079286, 1, 2, 1),
(50, 7, 250, 0, '', 150, 200, 0, 0, 1, '2016-01-29', 1454079286, 2, 2, 1),
(51, 18, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-29', 1454095854, 1, 2, 1),
(52, 5, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-30', 1454185070, 1, 2, 1),
(53, 3, 150, 0, '', 99, 147, 0, 0, 1, '2016-01-30', 1454185070, 1, 2, 1),
(54, 2, 500, 0, '', 250, 250, 0, 0, 1, '2016-02-03', 1454513033, 3, 2, 1),
(55, 1, 500, 0, '', 250, 250, 0, 0, 1, '2016-02-03', 1454513033, 3, 2, 1),
(56, 2, 150, 0, '', 99, 147, 0, 0, 1, '2016-02-06', 1454800320, 1, 2, 1),
(57, 16, 0, 250, 'less than 2 qp', 150, 200, 0, 0, 1, '2016-02-06', 1454800320, 2, 2, 1),
(58, 11, 0, 1200, 'advanced comm', 500, 300, 0, 0, 2, '2016-02-18', 1455829029, 4, 4, 1),
(59, 18, 0, 250, 'advanced comm', 150, 200, 0, 0, 2, '2016-02-22', 1456100600, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `income_process`
--

DROP TABLE IF EXISTS `income_process`;
CREATE TABLE IF NOT EXISTS `income_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `income_process`
--

TRUNCATE TABLE `income_process`;
--
-- Dumping data for table `income_process`
--

INSERT INTO `income_process` (`id`, `mode`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `income_reserve`
--

DROP TABLE IF EXISTS `income_reserve`;
CREATE TABLE IF NOT EXISTS `income_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `income_id` int(11) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `reserve` decimal(10,2) NOT NULL,
  `reserve_percentage` int(10) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `income_reserve`
--

TRUNCATE TABLE `income_reserve`;
-- --------------------------------------------------------

--
-- Table structure for table `level_board_income`
--

DROP TABLE IF EXISTS `level_board_income`;
CREATE TABLE IF NOT EXISTS `level_board_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL,
  `income` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `level_board_income`
--

TRUNCATE TABLE `level_board_income`;
-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(5) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `ip_add` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=774 ;

--
-- Truncate table before insert `logs`
--

TRUNCATE TABLE `logs`;
--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `title`, `message`, `user_id`, `type`, `date`, `time`, `ip_add`) VALUES
(1, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '838:59:59', ''),
(2, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '838:59:59', ''),
(3, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '838:59:59', ''),
(4, 'Insert into Board', 'User lizbeth  board on 2016-01-05', 5, 15, '2016-01-05', '838:59:59', ''),
(5, 'Insert into Board', 'User lizbeth  board on 2016-01-05', 5, 15, '2016-01-05', '838:59:59', ''),
(6, 'Insert into Board', 'User cashnow  board on 2016-01-05', 6, 15, '2016-01-05', '00:00:00', ''),
(7, 'Insert into Board', 'User cashnow  board on 2016-01-05', 6, 15, '2016-01-05', '00:00:00', ''),
(8, 'Insert into Board', 'User cashnow  board on 2016-01-05', 6, 15, '2016-01-05', '00:00:00', ''),
(9, 'Update wallet', 'Update wallet of joinnow by receiving amount 150 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 1, 5, '2016-01-05', '00:00:00', ''),
(10, 'Insert into Board', 'User earncash  board on 2016-01-05', 7, 15, '2016-01-05', '00:00:00', ''),
(11, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '00:00:00', ''),
(12, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '00:00:00', ''),
(13, 'Insert into Board', 'User lizbeth  board on 2016-01-05', 5, 15, '2016-01-05', '00:00:00', ''),
(14, 'Insert into Board', 'User Boston  board on 2016-01-05', 3, 15, '2016-01-05', '00:00:00', ''),
(15, 'Insert into Board', 'User cashnow  board on 2016-01-05', 6, 15, '2016-01-05', '00:00:00', ''),
(16, 'Insert into Board', 'User earncash  board on 2016-01-05', 7, 15, '2016-01-05', '00:00:00', ''),
(17, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '00:00:00', ''),
(18, 'Update wallet', 'Update wallet of joinnow by receiving amount 250 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 1, 5, '2016-01-05', '00:00:00', ''),
(19, 'Insert into Board', 'User earncash  board on 2016-01-05', 7, 15, '2016-01-05', '00:00:00', ''),
(20, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '00:00:00', ''),
(21, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '00:00:00', ''),
(22, 'Insert into Board', 'User lizbeth  board on 2016-01-05', 5, 15, '2016-01-05', '00:00:00', ''),
(23, 'Insert into Board', 'User Boston  board on 2016-01-05', 3, 15, '2016-01-05', '00:00:00', ''),
(24, 'Insert into Board', 'User cashnow  board on 2016-01-05', 6, 15, '2016-01-05', '00:00:00', ''),
(25, 'Insert into Board', 'User earncash  board on 2016-01-05', 7, 15, '2016-01-05', '00:00:00', ''),
(26, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '00:00:00', ''),
(27, 'Update wallet', 'Update wallet of joinnow by receiving amount 500 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 1, 5, '2016-01-05', '00:00:00', ''),
(28, 'Insert into Board', 'User johnyg  board on 2016-01-05', 8, 15, '2016-01-05', '00:00:00', ''),
(29, 'Insert into Board', 'User johnyg  board on 2016-01-05', 8, 15, '2016-01-05', '00:00:00', ''),
(30, 'Insert into Board', 'User justdoit  board on 2016-01-05', 9, 15, '2016-01-05', '00:00:00', ''),
(31, 'Insert into Board', 'User justdoit  board on 2016-01-05', 9, 15, '2016-01-05', '00:00:00', ''),
(32, 'Insert into Board', 'User justdoit  board on 2016-01-05', 9, 15, '2016-01-05', '00:00:00', ''),
(33, 'Update wallet', 'Update wallet of bkemper by receiving amount 150 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 2, 5, '2016-01-05', '00:00:00', ''),
(34, 'Insert into Board', 'User jointoday  board on 2016-01-05', 10, 15, '2016-01-05', '00:00:00', ''),
(35, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '00:00:00', ''),
(36, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '00:00:00', ''),
(37, 'Insert into Board', 'User johnyg  board on 2016-01-05', 8, 15, '2016-01-05', '00:00:00', ''),
(38, 'Insert into Board', 'User lizbeth  board on 2016-01-05', 5, 15, '2016-01-05', '00:00:00', ''),
(39, 'Insert into Board', 'User justdoit  board on 2016-01-05', 9, 15, '2016-01-05', '00:00:00', ''),
(40, 'Insert into Board', 'User jointoday  board on 2016-01-05', 10, 15, '2016-01-05', '00:00:00', ''),
(41, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '00:00:00', ''),
(42, 'Update wallet', 'Update wallet of bkemper by receiving amount 250 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 2, 5, '2016-01-05', '00:00:00', ''),
(43, 'Insert into Board', 'User jointoday  board on 2016-01-05', 10, 15, '2016-01-05', '00:00:00', ''),
(44, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '00:00:00', ''),
(45, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '00:00:00', ''),
(46, 'Insert into Board', 'User johnyg  board on 2016-01-05', 8, 15, '2016-01-05', '00:00:00', ''),
(47, 'Insert into Board', 'User lizbeth  board on 2016-01-05', 5, 15, '2016-01-05', '00:00:00', ''),
(48, 'Insert into Board', 'User justdoit  board on 2016-01-05', 9, 15, '2016-01-05', '00:00:00', ''),
(49, 'Insert into Board', 'User jointoday  board on 2016-01-05', 10, 15, '2016-01-05', '00:00:00', ''),
(50, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '00:00:00', ''),
(51, 'Update wallet', 'Update wallet of bkemper by receiving amount 500 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 2, 5, '2016-01-05', '00:00:00', ''),
(52, 'Insert into Board', 'User picky ticky  board on 2016-01-05', 11, 15, '2016-01-05', '00:00:00', ''),
(53, 'Insert into Board', 'User picky ticky  board on 2016-01-05', 11, 15, '2016-01-05', '00:00:00', ''),
(54, 'Insert into Board', 'User picky ticky  board on 2016-01-05', 11, 15, '2016-01-05', '00:00:00', ''),
(55, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '00:00:00', ''),
(56, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '00:00:00', ''),
(57, 'Insert into Board', 'User cashnow  board on 2016-01-05', 6, 15, '2016-01-05', '00:00:00', ''),
(58, 'Insert into Board', 'User Boston  board on 2016-01-05', 3, 15, '2016-01-05', '00:00:00', ''),
(59, 'Insert into Board', 'User justdoit  board on 2016-01-05', 9, 15, '2016-01-05', '00:00:00', ''),
(60, 'Insert into Board', 'User picky ticky  board on 2016-01-05', 11, 15, '2016-01-05', '00:00:00', ''),
(61, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '00:00:00', ''),
(62, 'Update wallet', 'Update wallet of joinnow by receiving amount 1200 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 1200 INR', 1, 5, '2016-01-05', '00:00:00', ''),
(63, 'Insert into Board', 'User jcarter  board on 2016-01-05', 12, 15, '2016-01-05', '00:00:00', ''),
(64, 'Insert into Board', 'User jcarter  board on 2016-01-05', 12, 15, '2016-01-05', '00:00:00', ''),
(65, 'Insert into Board', 'User jcarter  board on 2016-01-05', 12, 15, '2016-01-05', '00:00:00', ''),
(66, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 6f3ef2feab on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(67, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b35d712503 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(68, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 19d5254bda on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(69, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 81198b8898 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(70, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 398304b637 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(71, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3aaf107868 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(72, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : dd6dba44b3 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(73, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8878a8c166 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(74, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f5c80b20fd on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(75, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f9ef6bf98e on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(76, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : fd581ff486 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(77, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b8a20bf368 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(78, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 47948b6665 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(79, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0083bf1bc6 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(80, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9b78f44329 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(81, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 839da70edc on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(82, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 46ea33cd92 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(83, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 2bb3f106ea on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(84, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 860fe42456 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(85, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0926aee229 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(86, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1a24e245b6 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(87, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3046fd0302 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(88, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : eb85a25869 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(89, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b659336581 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(90, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1739876299 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(91, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ee20117357 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(92, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 797fe8da31 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(93, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 475bbc1276 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(94, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 57b01adc7e on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(95, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a2ee866e28 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(96, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c6115b36f8 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(97, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 43ecd7d766 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(98, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9e0d45101f on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(99, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e3e21ff1f0 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(100, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 50a3ab6f3f on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(101, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 27bd8c5560 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(102, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : dca90149da on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(103, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5db158e264 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(104, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : aa20f2324e on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(105, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ec84adb67a on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(106, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a1de6c427f on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(107, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : af51d242a6 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(108, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a0f9a788fe on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(109, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 88bde561cc on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(110, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 950da343b7 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(111, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 72365005d5 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(112, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a9a3032089 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(113, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8ec242a184 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(114, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a0f1fcc963 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(115, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : de8a628b93 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(116, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e22e00ebe9 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(117, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a24453e393 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(118, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 687b5768a7 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(119, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 26ce65d41e on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(120, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 736e85eab6 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(121, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 49777ceb45 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(122, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e98fd4db56 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(123, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8dc2521a11 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(124, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b6b48639e4 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(125, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3923b64258 on 2016-01-05', 0, 9, '2016-01-05', '00:00:00', ''),
(126, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c677e80728 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(127, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5f8b9fc5ad on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(128, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1f239ed9e6 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(129, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ebde53e879 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(130, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : fc05c41c81 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(131, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3142cfbe4e on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(132, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b62d83827d on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(133, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 85dc41fce3 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(134, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 32f14bac32 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(135, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b1608c69d2 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(136, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8f76339ce5 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(137, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 41a80e3c55 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(138, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b932401650 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(139, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : dfa128261a on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(140, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ec5e909936 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(141, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 6071d41e11 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(142, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 7457ae5d50 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(143, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 34c5e4b8f5 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(144, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 723d083de6 on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(145, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a5ccefd23c on 2016-01-05', 0, 9, '2016-01-05', '838:59:59', ''),
(146, 'Update wallet', 'Update wallet of snoopy by receiving amount 150 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 4, 5, '2016-01-05', '838:59:59', ''),
(147, 'Insert into Board', 'User nbruce  board on 2016-01-05', 13, 15, '2016-01-05', '838:59:59', ''),
(148, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '838:59:59', ''),
(149, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '838:59:59', ''),
(150, 'Insert into Board', 'User picky ticky  board on 2016-01-05', 11, 15, '2016-01-05', '838:59:59', ''),
(151, 'Insert into Board', 'User johnyg  board on 2016-01-05', 8, 15, '2016-01-05', '838:59:59', ''),
(152, 'Insert into Board', 'User jcarter  board on 2016-01-05', 12, 15, '2016-01-05', '838:59:59', ''),
(153, 'Insert into Board', 'User nbruce  board on 2016-01-05', 13, 15, '2016-01-05', '838:59:59', ''),
(154, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '838:59:59', ''),
(155, 'Update wallet', 'Update wallet of snoopy by receiving amount 250 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 4, 5, '2016-01-05', '838:59:59', ''),
(156, 'Insert into Board', 'User nbruce  board on 2016-01-05', 13, 15, '2016-01-05', '838:59:59', ''),
(157, 'Insert into Board', 'User joinnow  board on 2016-01-05', 1, 15, '2016-01-05', '838:59:59', ''),
(158, 'Insert into Board', 'User bkemper  board on 2016-01-05', 2, 15, '2016-01-05', '838:59:59', ''),
(159, 'Insert into Board', 'User picky ticky  board on 2016-01-05', 11, 15, '2016-01-05', '838:59:59', ''),
(160, 'Insert into Board', 'User johnyg  board on 2016-01-05', 8, 15, '2016-01-05', '838:59:59', ''),
(161, 'Insert into Board', 'User jcarter  board on 2016-01-05', 12, 15, '2016-01-05', '838:59:59', ''),
(162, 'Insert into Board', 'User nbruce  board on 2016-01-05', 13, 15, '2016-01-05', '838:59:59', ''),
(163, 'Insert into Board', 'User snoopy  board on 2016-01-05', 4, 15, '2016-01-05', '838:59:59', ''),
(164, 'Update wallet', 'Update wallet of snoopy by receiving amount 500 INR on 2016-01-05 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 4, 5, '2016-01-05', '838:59:59', ''),
(165, 'Insert into Board', 'User nbruce  board on 2016-01-05', 13, 15, '2016-01-05', '838:59:59', ''),
(166, 'Edit Password', 'Password updated of user  on 2016-01-06 by Ourself ', 9, 2, '2016-01-06', '838:59:59', ''),
(167, 'Insert into Board', 'User signupnow  board on 2016-01-06', 14, 15, '2016-01-06', '00:00:00', ''),
(168, 'Insert into Board', 'User signupnow  board on 2016-01-06', 14, 15, '2016-01-06', '00:00:00', ''),
(169, 'Insert into Board', 'User getrichnow  board on 2016-01-06', 15, 15, '2016-01-06', '838:59:59', ''),
(170, 'Insert into Board', 'User getrichnow  board on 2016-01-06', 15, 15, '2016-01-06', '838:59:59', ''),
(171, 'Insert into Board', 'User getrichnow  board on 2016-01-06', 15, 15, '2016-01-06', '838:59:59', ''),
(172, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5b3a108b4d on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(173, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : fe8211f52c on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(174, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8344a14505 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(175, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 05f200e52a on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(176, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e626f9ea5a on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(177, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c63b64ef67 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(178, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0690a8aa06 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(179, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f7fe146c4b on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(180, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a43d491db8 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(181, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c9198c9f5a on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(182, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : db125caa4b on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(183, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a5286d90db on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(184, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 39d7399d54 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(185, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d9173d8eca on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(186, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 10d0cebbed on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(187, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9cf2bd74e4 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(188, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c1099cd516 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(189, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a5d8ef80a0 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(190, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 55b7b6dfee on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(191, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e68a1bd22d on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(192, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8948bca994 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(193, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 070783c093 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(194, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9d8a9f493a on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(195, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3b209db07f on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(196, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c895b216e1 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(197, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 01d6eb3030 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(198, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 7e9b4b6d9a on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(199, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0f5ecb4a79 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(200, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 91f9737f0f on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(201, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ec33026251 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(202, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d3524404b9 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(203, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 645571467e on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(204, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 70745b44a2 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(205, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c928d38c5e on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(206, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c5013cc704 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(207, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ff213e889d on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(208, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 68b6365342 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(209, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 87cdbb9b1c on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(210, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d5888ba001 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(211, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 468a1ddd77 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(212, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 641061d2fd on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(213, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 64188a1c41 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(214, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8b54aaeda2 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(215, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1b31afa0b5 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(216, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : dd80ef0436 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(217, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3270269f27 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(218, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e03cba77a0 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(219, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 482ca8b278 on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(220, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 6d19801fcb on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(221, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1b751138ce on 2016-01-06', 0, 9, '2016-01-06', '00:00:00', ''),
(222, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a5fbed546f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(223, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 793ec95284 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(224, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e96c21257f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(225, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : fe262a3539 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(226, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9a6eb2e77f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(227, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1bfee9cddc on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(228, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 73d184e80c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(229, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f8c3090b2a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(230, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : caf1389513 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(231, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f809436b64 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(232, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d4c3b722bf on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(233, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : fddb483583 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(234, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9cdb1f361f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(235, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e39c09d250 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(236, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0a140b33c0 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(237, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 909297261f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(238, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 905885cb34 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(239, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c23460b6dc on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(240, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9b5dc5f3eb on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(241, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c0be89068c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(242, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8310ddeff0 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(243, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 60a6c4002c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(244, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a7822f76ab on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(245, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 398304b637 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(246, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : bc922a5ae0 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(247, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5d2c3c248a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(248, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ae29604364 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(249, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f8bdb0add9 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(250, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f992afd487 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(251, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 73ac6f6a7b on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(252, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3e036ef6ea on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(253, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8e0653cd05 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(254, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 00d78d6582 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(255, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 966ce7870d on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(256, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 703527a660 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(257, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 2b69e7b428 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(258, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d0bd8f51bf on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(259, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f636ac6aca on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(260, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ee7221f22e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(261, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 89939cc97d on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(262, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 508d4abd4a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(263, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 93a57d492d on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(264, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 10c087244f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(265, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 6fb58014bf on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(266, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : fabd584691 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(267, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5733401930 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(268, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d0f91173e9 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(269, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : cfaaf68b01 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(270, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 54083e77c5 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(271, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ee2eef0e86 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(272, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a72b64a13b on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(273, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e62fe31754 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(274, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 63320b6ec5 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(275, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e85e811482 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(276, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ca6e8f8ef2 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(277, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 41871dba42 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(278, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : cb8ccf69d6 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(279, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : aa1c786272 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(280, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a630de45e0 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(281, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 7195b7b16a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(282, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 2b7a4f25ab on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(283, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 847e3131c3 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(284, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 97a0ccbec6 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(285, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8ab6446401 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(286, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 6179724498 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(287, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 4edf30eeab on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(288, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 19113771e8 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(289, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 88551c1278 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(290, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ea2a04bbe6 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(291, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d0855e33c8 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(292, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 519419df61 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(293, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 26269d202b on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(294, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3c4fef8c33 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(295, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 76fbc1b267 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(296, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : dbfd064469 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(297, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b10a14b2e6 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(298, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d36928c95b on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(299, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b44832dc77 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(300, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : cabffce8f6 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(301, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 889198b375 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(302, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3485e3f691 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(303, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ebca0bfa3e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(304, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c50d02dee8 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(305, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9b668ae6e2 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(306, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 414573d3fd on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(307, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 368513b725 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(308, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 4747ccd6f6 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(309, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 4131090cbd on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(310, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ffd130b59d on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(311, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b31871a6ab on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(312, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : eba30bf99a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(313, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ab59172215 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(314, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 71c22e78cc on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(315, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0daf814e9a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(316, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 080913799e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(317, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 2d305e59ef on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(318, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : afff34f5c8 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(319, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d72a6d7683 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(320, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e7baadbfb1 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(321, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : f47abc33ae on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(322, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 714c58c815 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(323, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 9b61e20d2a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(324, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c222b98098 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(325, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 96aadba91e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(326, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 63ee89e8e2 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(327, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 7d4013adb7 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(328, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : db2675993c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(329, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 62c4f77505 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(330, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 654dd74cfe on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(331, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : cbaf927430 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(332, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 423cf27d7a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(333, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a1e7453d8c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(334, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5552d8338e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(335, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 2e362d176c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(336, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 85b26bc2ac on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(337, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e8726135a2 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(338, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0257ef47ca on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(339, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b5d281f376 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(340, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : da5ab2a267 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(341, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 78c544bdab on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(342, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d6bc0c2ca7 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(343, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : aa100cc85f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(344, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : e8088b3aee on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(345, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 96483b00ce on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(346, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 56976d3473 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(347, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : ec0081413f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(348, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 96f2a8c127 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(349, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : d439f5145f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(350, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1589813b41 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(351, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 73131f858f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(352, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 8959f2d0ad on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(353, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1b2bdcba14 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(354, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : a0bbdd75b0 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(355, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 324ea3ce8a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(356, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 581ddaca1e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(357, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b95cbab3ab on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(358, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c58b391b9f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(359, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 67f06a6e5c on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(360, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : b6f71d26f5 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(361, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 0bcb925241 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(362, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 395fb48b91 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(363, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 981c5a612f on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(364, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 6a14d9353a on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(365, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 22e2fc3e5d on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(366, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 1569bf328e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(367, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 3dae5801ff on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(368, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : dd59adc132 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(369, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 5924061c4e on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(370, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : c841b23266 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(371, 'e-Voucher generate', 'User (Dennisn ADMIN) Generate e-Voucher : 2fbbf6a537 on 2016-01-06', 0, 9, '2016-01-06', '838:59:59', ''),
(372, 'Insert into Board', 'User worldmoney  board on 2016-01-06', 16, 15, '2016-01-06', '00:00:00', ''),
(373, 'Insert into Board', 'User worldmoney  board on 2016-01-06', 16, 15, '2016-01-06', '00:00:00', ''),
(374, 'Insert into Board', 'User worldmoney  board on 2016-01-06', 16, 15, '2016-01-06', '00:00:00', ''),
(375, 'Insert into Board', 'User snoopy  board on 2016-01-06', 4, 15, '2016-01-06', '00:00:00', ''),
(376, 'Insert into Board', 'User joinnow  board on 2016-01-06', 1, 15, '2016-01-06', '00:00:00', ''),
(377, 'Insert into Board', 'User jcarter  board on 2016-01-06', 12, 15, '2016-01-06', '00:00:00', ''),
(378, 'Insert into Board', 'User cashnow  board on 2016-01-06', 6, 15, '2016-01-06', '00:00:00', ''),
(379, 'Insert into Board', 'User nbruce  board on 2016-01-06', 13, 15, '2016-01-06', '00:00:00', ''),
(380, 'Insert into Board', 'User worldmoney  board on 2016-01-06', 16, 15, '2016-01-06', '00:00:00', ''),
(381, 'Insert into Board', 'User bkemper  board on 2016-01-06', 2, 15, '2016-01-06', '00:00:00', ''),
(382, 'Update wallet', 'Update wallet of bkemper by receiving amount 1200 INR on 2016-01-06 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 1200 INR', 2, 5, '2016-01-06', '00:00:00', ''),
(383, 'Insert into Board', 'User gal1133  board on 2016-01-07', 17, 15, '2016-01-07', '838:59:59', ''),
(384, 'Insert into Board', 'User badausa  board on 2016-01-07', 18, 15, '2016-01-07', '00:00:00', ''),
(385, 'Update wallet', 'Update wallet of lizbeth by receiving amount 150 INR on 2016-01-07 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 5, 5, '2016-01-07', '00:00:00', ''),
(386, 'Insert into Board', 'User bill  board on 2016-01-07', 19, 15, '2016-01-07', '00:00:00', ''),
(387, 'Insert into Board', 'User enrollnow  board on 2016-01-07', 9, 15, '2016-01-07', '00:00:00', ''),
(388, 'Insert into Board', 'User getrichnow  board on 2016-01-07', 15, 15, '2016-01-07', '00:00:00', '');
INSERT INTO `logs` (`id`, `title`, `message`, `user_id`, `type`, `date`, `time`, `ip_add`) VALUES
(389, 'Insert into Board', 'User gal1133  board on 2016-01-07', 17, 15, '2016-01-07', '00:00:00', ''),
(390, 'Insert into Board', 'User jointoday  board on 2016-01-07', 10, 15, '2016-01-07', '00:00:00', ''),
(391, 'Insert into Board', 'User badausa  board on 2016-01-07', 18, 15, '2016-01-07', '00:00:00', ''),
(392, 'Insert into Board', 'User bill  board on 2016-01-07', 19, 15, '2016-01-07', '00:00:00', ''),
(393, 'Insert into Board', 'User lizbeth  board on 2016-01-07', 5, 15, '2016-01-07', '00:00:00', ''),
(394, 'Update wallet', 'Update wallet of lizbeth by receiving amount 250 INR on 2016-01-07 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 5, 5, '2016-01-07', '00:00:00', ''),
(395, 'Insert into Board', 'User newbeginning  board on 2016-01-07', 21, 15, '2016-01-07', '838:59:59', ''),
(396, 'Insert into Board', 'User newbeginning  board on 2016-01-07', 21, 15, '2016-01-07', '838:59:59', ''),
(397, 'Update wallet', 'Update wallet of Boston by receiving amount 150 INR on 2016-01-07 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 3, 5, '2016-01-07', '838:59:59', ''),
(398, 'Insert into Board', 'User yaerahoradeplata  board on 2016-01-07', 22, 15, '2016-01-07', '838:59:59', ''),
(399, 'Insert into Board', 'User cashnow  board on 2016-01-07', 6, 15, '2016-01-07', '838:59:59', ''),
(400, 'Insert into Board', 'User snoopy  board on 2016-01-07', 4, 15, '2016-01-07', '838:59:59', ''),
(401, 'Insert into Board', 'User lizbeth  board on 2016-01-07', 5, 15, '2016-01-07', '838:59:59', ''),
(402, 'Insert into Board', 'User earncash  board on 2016-01-07', 7, 15, '2016-01-07', '838:59:59', ''),
(403, 'Insert into Board', 'User newbeginning  board on 2016-01-07', 21, 15, '2016-01-07', '838:59:59', ''),
(404, 'Insert into Board', 'User yaerahoradeplata  board on 2016-01-07', 22, 15, '2016-01-07', '838:59:59', ''),
(405, 'Insert into Board', 'User Boston  board on 2016-01-07', 3, 15, '2016-01-07', '838:59:59', ''),
(406, 'Update wallet', 'Update wallet of Boston by receiving amount 250 INR on 2016-01-07 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 3, 5, '2016-01-07', '838:59:59', ''),
(407, 'Insert into Board', 'User yaerahoradeplata  board on 2016-01-07', 22, 15, '2016-01-07', '838:59:59', ''),
(408, 'Insert into Board', 'User milagrollego  board on 2016-01-07', 23, 15, '2016-01-07', '00:00:00', ''),
(409, 'Insert into Board', 'User milagrollego  board on 2016-01-07', 23, 15, '2016-01-07', '00:00:00', ''),
(410, 'Insert into Board', 'User enrollnow  board on 2016-01-07', 9, 15, '2016-01-07', '00:00:00', ''),
(411, 'Insert into Board', 'User getrichnow  board on 2016-01-07', 15, 15, '2016-01-07', '00:00:00', ''),
(412, 'Insert into Board', 'User newbeginning  board on 2016-01-07', 21, 15, '2016-01-07', '00:00:00', ''),
(413, 'Insert into Board', 'User jointoday  board on 2016-01-07', 10, 15, '2016-01-07', '00:00:00', ''),
(414, 'Insert into Board', 'User yaerahoradeplata  board on 2016-01-07', 22, 15, '2016-01-07', '00:00:00', ''),
(415, 'Insert into Board', 'User milagrollego  board on 2016-01-07', 23, 15, '2016-01-07', '00:00:00', ''),
(416, 'Insert into Board', 'User lizbeth  board on 2016-01-07', 5, 15, '2016-01-07', '00:00:00', ''),
(417, 'Insert into Board', 'User lizbeth  board on 2016-01-07', 5, 15, '2016-01-07', '00:00:00', ''),
(418, 'Insert into Board', 'User silvia  board on 2016-01-07', 24, 15, '2016-01-07', '838:59:59', ''),
(419, 'Edit Password', 'Password updated of user  on 2016-01-07 by Ourself ', 12, 2, '2016-01-07', '00:00:00', ''),
(420, 'Insert into Board', 'User touchdown  board on 2016-01-08', 25, 15, '2016-01-08', '00:00:00', ''),
(421, 'Insert into Board', 'User touchdown  board on 2016-01-08', 25, 15, '2016-01-08', '00:00:00', ''),
(422, 'Insert into Board', 'User touchdown  board on 2016-01-08', 25, 15, '2016-01-08', '00:00:00', ''),
(423, 'Update wallet', 'Update wallet of joinnow by receiving amount 150 INR on 2016-01-08 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 1, 5, '2016-01-08', '00:00:00', ''),
(424, 'Insert into Board', 'User enrolltoday  board on 2016-01-08', 26, 15, '2016-01-08', '00:00:00', ''),
(425, 'Insert into Board', 'User bkemper  board on 2016-01-08', 2, 15, '2016-01-08', '00:00:00', ''),
(426, 'Insert into Board', 'User signupnow  board on 2016-01-08', 14, 15, '2016-01-08', '00:00:00', ''),
(427, 'Insert into Board', 'User Boston  board on 2016-01-08', 3, 15, '2016-01-08', '00:00:00', ''),
(428, 'Insert into Board', 'User picky ticky  board on 2016-01-08', 11, 15, '2016-01-08', '00:00:00', ''),
(429, 'Insert into Board', 'User silvia  board on 2016-01-08', 24, 15, '2016-01-08', '00:00:00', ''),
(430, 'Insert into Board', 'User enrolltoday  board on 2016-01-08', 26, 15, '2016-01-08', '00:00:00', ''),
(431, 'Insert into Board', 'User joinnow  board on 2016-01-08', 1, 15, '2016-01-08', '00:00:00', ''),
(432, 'Update wallet', 'Update wallet of joinnow by receiving amount 250 INR on 2016-01-08 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 1, 5, '2016-01-08', '00:00:00', ''),
(433, 'Insert into Board', 'User enrolltoday  board on 2016-01-08', 26, 15, '2016-01-08', '00:00:00', ''),
(434, 'Insert into Board', 'User enrolltoday  board on 2016-01-08', 26, 15, '2016-01-08', '00:00:00', ''),
(435, 'Insert into Board', 'User lsummit  board on 2016-01-09', 28, 15, '2016-01-09', '838:59:59', ''),
(436, 'Insert into Board', 'User lsummit  board on 2016-01-09', 28, 15, '2016-01-09', '838:59:59', ''),
(437, 'Insert into Board', 'User cashnow  board on 2016-01-09', 6, 15, '2016-01-09', '838:59:59', ''),
(438, 'Insert into Board', 'User snoopy  board on 2016-01-09', 4, 15, '2016-01-09', '838:59:59', ''),
(439, 'Insert into Board', 'User lizbeth  board on 2016-01-09', 5, 15, '2016-01-09', '838:59:59', ''),
(440, 'Insert into Board', 'User earncash  board on 2016-01-09', 7, 15, '2016-01-09', '838:59:59', ''),
(441, 'Insert into Board', 'User silvia  board on 2016-01-09', 24, 15, '2016-01-09', '838:59:59', ''),
(442, 'Insert into Board', 'User lsummit  board on 2016-01-09', 28, 15, '2016-01-09', '838:59:59', ''),
(443, 'Insert into Board', 'User Boston  board on 2016-01-09', 3, 15, '2016-01-09', '838:59:59', ''),
(444, 'Update wallet', 'Update wallet of Boston by receiving amount 500 INR on 2016-01-09 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 3, 5, '2016-01-09', '838:59:59', ''),
(445, 'Insert into Board', 'User lsummit  board on 2016-01-09', 28, 15, '2016-01-09', '838:59:59', ''),
(446, 'Insert into Board', 'User enrollnow  board on 2016-01-09', 9, 15, '2016-01-09', '838:59:59', ''),
(447, 'Insert into Board', 'User getrichnow  board on 2016-01-09', 15, 15, '2016-01-09', '838:59:59', ''),
(448, 'Insert into Board', 'User lizbeth  board on 2016-01-09', 5, 15, '2016-01-09', '838:59:59', ''),
(449, 'Insert into Board', 'User picky ticky  board on 2016-01-09', 11, 15, '2016-01-09', '838:59:59', ''),
(450, 'Insert into Board', 'User silvia  board on 2016-01-09', 24, 15, '2016-01-09', '838:59:59', ''),
(451, 'Insert into Board', 'User lsummit  board on 2016-01-09', 28, 15, '2016-01-09', '838:59:59', ''),
(452, 'Insert into Board', 'User Boston  board on 2016-01-09', 3, 15, '2016-01-09', '838:59:59', ''),
(453, 'Insert into Board', 'User joinnow  board on 2016-01-09', 1, 15, '2016-01-09', '838:59:59', ''),
(454, 'Insert into Board', 'User bkemper  board on 2016-01-09', 2, 15, '2016-01-09', '838:59:59', ''),
(455, 'Insert into Board', 'User touchdown  board on 2016-01-09', 25, 15, '2016-01-09', '838:59:59', ''),
(456, 'Insert into Board', 'User jcarter  board on 2016-01-09', 12, 15, '2016-01-09', '838:59:59', ''),
(457, 'Insert into Board', 'User enrolltoday  board on 2016-01-09', 26, 15, '2016-01-09', '838:59:59', ''),
(458, 'Insert into Board', 'User Boston  board on 2016-01-09', 3, 15, '2016-01-09', '838:59:59', ''),
(459, 'Insert into Board', 'User snoopy  board on 2016-01-09', 4, 15, '2016-01-09', '838:59:59', ''),
(460, 'Update wallet', 'Update wallet of Boston by receiving amount 1200 INR on 2016-01-09 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 1200 INR', 3, 5, '2016-01-09', '838:59:59', ''),
(461, 'Insert into Board', 'User snoopy  board on 2016-01-09', 4, 15, '2016-01-09', '838:59:59', ''),
(462, 'Insert into Board', 'User jordano  board on 2016-01-09', 29, 15, '2016-01-09', '838:59:59', ''),
(463, 'Insert into Board', 'User jordano  board on 2016-01-09', 29, 15, '2016-01-09', '838:59:59', ''),
(464, 'Insert into Board', 'User jordano  board on 2016-01-09', 29, 15, '2016-01-09', '838:59:59', ''),
(465, 'Insert into Board', 'User orlando  board on 2016-01-11', 30, 15, '2016-01-11', '00:00:00', ''),
(466, 'Insert into Board', 'User orlando  board on 2016-01-11', 30, 15, '2016-01-11', '00:00:00', ''),
(467, 'Insert into Board', 'User orlando  board on 2016-01-11', 30, 15, '2016-01-11', '00:00:00', ''),
(468, 'edit profile', 'Profile updated of user joinnow on 2016-01-11 by joinnow Your self', 1, 1, '2016-01-11', '00:00:00', ''),
(469, 'edit profile', 'Profile updated of user joinnow on 2016-01-11 by joinnow Your self', 1, 1, '2016-01-11', '00:00:00', ''),
(470, 'Update wallet', 'Update wallet of johnyg by receiving amount 150 INR on 2016-01-12 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 8, 5, '2016-01-12', '00:00:00', ''),
(471, 'Insert into Board', 'User cayce4sure  board on 2016-01-12', 32, 15, '2016-01-12', '00:00:00', ''),
(472, 'Insert into Board', 'User jcarter  board on 2016-01-12', 12, 15, '2016-01-12', '00:00:00', ''),
(473, 'Insert into Board', 'User worldmoney  board on 2016-01-12', 16, 15, '2016-01-12', '00:00:00', ''),
(474, 'Insert into Board', 'User joinnow  board on 2016-01-12', 1, 15, '2016-01-12', '00:00:00', ''),
(475, 'Insert into Board', 'User nbruce  board on 2016-01-12', 13, 15, '2016-01-12', '00:00:00', ''),
(476, 'Insert into Board', 'User jordano  board on 2016-01-12', 29, 15, '2016-01-12', '00:00:00', ''),
(477, 'Insert into Board', 'User cayce4sure  board on 2016-01-12', 32, 15, '2016-01-12', '00:00:00', ''),
(478, 'Insert into Board', 'User johnyg  board on 2016-01-12', 8, 15, '2016-01-12', '00:00:00', ''),
(479, 'Update wallet', 'Update wallet of johnyg by receiving amount 250 INR on 2016-01-12 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 8, 5, '2016-01-12', '00:00:00', ''),
(480, 'Insert into Board', 'User cayce4sure  board on 2016-01-12', 32, 15, '2016-01-12', '00:00:00', ''),
(481, 'Insert into Board', 'User cayce4sure  board on 2016-01-12', 32, 15, '2016-01-12', '00:00:00', ''),
(482, 'Insert into Board', 'User signuptoday  board on 2016-01-13', 33, 15, '2016-01-13', '838:59:59', ''),
(483, 'Insert into Board', 'User signuptoday  board on 2016-01-13', 33, 15, '2016-01-13', '838:59:59', ''),
(484, 'Insert into Board', 'User bkemper  board on 2016-01-13', 2, 15, '2016-01-13', '838:59:59', ''),
(485, 'Insert into Board', 'User signupnow  board on 2016-01-13', 14, 15, '2016-01-13', '838:59:59', ''),
(486, 'Insert into Board', 'User enrolltoday  board on 2016-01-13', 26, 15, '2016-01-13', '838:59:59', ''),
(487, 'Insert into Board', 'User picky ticky  board on 2016-01-13', 11, 15, '2016-01-13', '838:59:59', ''),
(488, 'Insert into Board', 'User Boston  board on 2016-01-13', 3, 15, '2016-01-13', '838:59:59', ''),
(489, 'Insert into Board', 'User signuptoday  board on 2016-01-13', 33, 15, '2016-01-13', '838:59:59', ''),
(490, 'Insert into Board', 'User joinnow  board on 2016-01-13', 1, 15, '2016-01-13', '838:59:59', ''),
(491, 'Insert into Board', 'User jcarter  board on 2016-01-13', 12, 15, '2016-01-13', '838:59:59', ''),
(492, 'Insert into Board', 'User worldmoney  board on 2016-01-13', 16, 15, '2016-01-13', '838:59:59', ''),
(493, 'Insert into Board', 'User jordano  board on 2016-01-13', 29, 15, '2016-01-13', '838:59:59', ''),
(494, 'Insert into Board', 'User nbruce  board on 2016-01-13', 13, 15, '2016-01-13', '838:59:59', ''),
(495, 'Insert into Board', 'User cayce4sure  board on 2016-01-13', 32, 15, '2016-01-13', '838:59:59', ''),
(496, 'Insert into Board', 'User joinnow  board on 2016-01-13', 1, 15, '2016-01-13', '838:59:59', ''),
(497, 'Insert into Board', 'User johnyg  board on 2016-01-13', 8, 15, '2016-01-13', '838:59:59', ''),
(498, 'Update wallet', 'Update wallet of joinnow by receiving amount 500 INR on 2016-01-13 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 1, 5, '2016-01-13', '838:59:59', ''),
(499, 'Insert into Board', 'User johnyg  board on 2016-01-13', 8, 15, '2016-01-13', '838:59:59', ''),
(500, 'Insert into Board', 'User jcjane426  board on 2016-01-13', 34, 15, '2016-01-13', '00:00:00', ''),
(501, 'Insert into Board', 'User teddyb  board on 2016-01-13', 35, 15, '2016-01-13', '00:00:00', ''),
(502, 'Insert into Board', 'User earncashnow  board on 2016-01-13', 36, 15, '2016-01-13', '00:00:00', ''),
(503, 'Insert into Board', 'User earncashnow  board on 2016-01-13', 36, 15, '2016-01-13', '00:00:00', ''),
(504, 'Insert into Board', 'User earncashnow  board on 2016-01-13', 36, 15, '2016-01-13', '00:00:00', ''),
(505, 'Update wallet', 'Update wallet of enrollnow by receiving amount 150 INR on 2016-01-14 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 9, 5, '2016-01-14', '00:00:00', ''),
(506, 'Insert into Board', 'User bwitter1  board on 2016-01-14', 39, 15, '2016-01-14', '00:00:00', ''),
(507, 'Insert into Board', 'User good2go  board on 2016-01-14', 40, 15, '2016-01-14', '00:00:00', ''),
(508, 'Insert into Board', 'User good2go  board on 2016-01-14', 40, 15, '2016-01-14', '00:00:00', ''),
(509, 'Insert into Board', 'User good2go  board on 2016-01-14', 40, 15, '2016-01-14', '00:00:00', ''),
(510, 'Insert into Board', 'User cashmoney  board on 2016-01-15', 41, 15, '2016-01-15', '00:00:00', ''),
(511, 'Insert into Board', 'User jack22  board on 2016-01-15', 42, 15, '2016-01-15', '00:00:00', ''),
(512, 'Update wallet', 'Update wallet of bkemper by receiving amount 150 INR on 2016-01-15 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 2, 5, '2016-01-15', '00:00:00', ''),
(513, 'Insert into Board', 'User winni56  board on 2016-01-15', 43, 15, '2016-01-15', '00:00:00', ''),
(514, 'Insert into Board', 'User signupnow  board on 2016-01-15', 14, 15, '2016-01-15', '00:00:00', ''),
(515, 'Insert into Board', 'User lsummit  board on 2016-01-15', 28, 15, '2016-01-15', '00:00:00', ''),
(516, 'Insert into Board', 'User signuptoday  board on 2016-01-15', 33, 15, '2016-01-15', '00:00:00', ''),
(517, 'Insert into Board', 'User Boston  board on 2016-01-15', 3, 15, '2016-01-15', '00:00:00', ''),
(518, 'Insert into Board', 'User jack22  board on 2016-01-15', 42, 15, '2016-01-15', '00:00:00', ''),
(519, 'Insert into Board', 'User winni56  board on 2016-01-15', 43, 15, '2016-01-15', '00:00:00', ''),
(520, 'Insert into Board', 'User bkemper  board on 2016-01-15', 2, 15, '2016-01-15', '00:00:00', ''),
(521, 'Update wallet', 'Update wallet of bkemper by receiving amount 250 INR on 2016-01-15 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 2, 5, '2016-01-15', '00:00:00', ''),
(522, 'Insert into Board', 'User winni56  board on 2016-01-15', 43, 15, '2016-01-15', '00:00:00', ''),
(523, 'Insert into Board', 'User lori  board on 2016-01-15', 44, 15, '2016-01-15', '00:00:00', ''),
(524, 'Insert into Board', 'User lori  board on 2016-01-15', 44, 15, '2016-01-15', '00:00:00', ''),
(525, 'Insert into Board', 'User yola  board on 2016-01-18', 45, 15, '2016-01-18', '00:00:00', ''),
(526, 'Insert into Board', 'User yola  board on 2016-01-18', 45, 15, '2016-01-18', '00:00:00', ''),
(527, 'Insert into Board', 'User agentfaria  board on 2016-01-18', 46, 15, '2016-01-18', '00:00:00', ''),
(528, 'Update wallet', 'Update wallet of jcarter by receiving amount 150 INR on 2016-01-18 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 12, 5, '2016-01-18', '00:00:00', ''),
(529, 'Insert into Board', 'User coach  board on 2016-01-18', 47, 15, '2016-01-18', '00:00:00', ''),
(530, 'Insert into Board', 'User coach  board on 2016-01-18', 47, 15, '2016-01-18', '00:00:00', ''),
(531, 'Insert into Board', 'User coach  board on 2016-01-18', 47, 15, '2016-01-18', '00:00:00', ''),
(532, 'Insert into Board', 'User ucandoit2  board on 2016-01-18', 48, 15, '2016-01-18', '838:59:59', ''),
(533, 'Insert into Board', 'User ucandoit2  board on 2016-01-18', 48, 15, '2016-01-18', '838:59:59', ''),
(534, 'Insert into Board', 'User Boston  board on 2016-01-18', 3, 15, '2016-01-18', '838:59:59', ''),
(535, 'Insert into Board', 'User good2go  board on 2016-01-18', 40, 15, '2016-01-18', '838:59:59', ''),
(536, 'Insert into Board', 'User winni56  board on 2016-01-18', 43, 15, '2016-01-18', '838:59:59', ''),
(537, 'Insert into Board', 'User signuptoday  board on 2016-01-18', 33, 15, '2016-01-18', '838:59:59', ''),
(538, 'Insert into Board', 'User lori  board on 2016-01-18', 44, 15, '2016-01-18', '838:59:59', ''),
(539, 'Insert into Board', 'User ucandoit2  board on 2016-01-18', 48, 15, '2016-01-18', '838:59:59', ''),
(540, 'Insert into Board', 'User picky ticky  board on 2016-01-18', 11, 15, '2016-01-18', '838:59:59', ''),
(541, 'Update wallet', 'Update wallet of picky ticky by receiving amount 500 INR on 2016-01-18 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 11, 5, '2016-01-18', '838:59:59', ''),
(542, 'Insert into Board', 'User ucandoit2  board on 2016-01-18', 48, 15, '2016-01-18', '838:59:59', ''),
(543, 'edit profile', 'Profile updated of user Boston on  by Boston Your self', 3, 1, '2016-01-18', '838:59:59', ''),
(544, 'edit profile', 'Profile updated of user Boston on  by Boston Your self', 3, 1, '2016-01-18', '838:59:59', ''),
(545, 'edit profile', 'Profile updated of user lori on  by lori Your self', 44, 1, '2016-01-20', '00:00:00', ''),
(546, 'Insert into Board', 'User amy7  board on 2016-01-20', 49, 15, '2016-01-20', '00:00:00', ''),
(547, 'Insert into Board', 'User amy7  board on 2016-01-20', 49, 15, '2016-01-20', '00:00:00', ''),
(548, 'Insert into Board', 'User amy7  board on 2016-01-20', 49, 15, '2016-01-20', '00:00:00', ''),
(549, 'Insert into Board', 'User enrolltoday  board on 2016-01-20', 26, 15, '2016-01-20', '00:00:00', ''),
(550, 'Insert into Board', 'User snoopy  board on 2016-01-20', 4, 15, '2016-01-20', '00:00:00', ''),
(551, 'Insert into Board', 'User cayce4sure  board on 2016-01-20', 32, 15, '2016-01-20', '00:00:00', ''),
(552, 'Insert into Board', 'User Boston  board on 2016-01-20', 3, 15, '2016-01-20', '00:00:00', ''),
(553, 'Insert into Board', 'User coach  board on 2016-01-20', 47, 15, '2016-01-20', '00:00:00', ''),
(554, 'Insert into Board', 'User amy7  board on 2016-01-20', 49, 15, '2016-01-20', '00:00:00', ''),
(555, 'Insert into Board', 'User jcarter  board on 2016-01-20', 12, 15, '2016-01-20', '00:00:00', ''),
(556, 'Insert into Board', 'User jcarter  board on 2016-01-20', 12, 15, '2016-01-20', '00:00:00', ''),
(557, 'Edit Password', 'Password updated of user  on 2016-01-20 by Ourself ', 6, 2, '2016-01-20', '838:59:59', ''),
(558, 'Edit Password', 'Password updated of user  on 2016-01-20 by Ourself ', 6, 2, '2016-01-20', '00:00:00', ''),
(559, 'Insert into Board', 'User xolos  board on 2016-01-20', 50, 15, '2016-01-20', '00:00:00', ''),
(560, 'Insert into Board', 'User worldmoney  board on 2016-01-20', 16, 15, '2016-01-20', '00:00:00', ''),
(561, 'Insert into Board', 'User earncashnow  board on 2016-01-20', 36, 15, '2016-01-20', '00:00:00', ''),
(562, 'Insert into Board', 'User bkemper  board on 2016-01-20', 2, 15, '2016-01-20', '00:00:00', ''),
(563, 'Insert into Board', 'User joinnow  board on 2016-01-20', 1, 15, '2016-01-20', '00:00:00', ''),
(564, 'Insert into Board', 'User coach  board on 2016-01-20', 47, 15, '2016-01-20', '00:00:00', ''),
(565, 'Insert into Board', 'User xolos  board on 2016-01-20', 50, 15, '2016-01-20', '00:00:00', ''),
(566, 'Insert into Board', 'User jcarter  board on 2016-01-20', 12, 15, '2016-01-20', '00:00:00', ''),
(567, 'Update wallet', 'Update wallet of jcarter by receiving amount 250 INR on 2016-01-20 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 12, 5, '2016-01-20', '00:00:00', ''),
(568, 'Insert into Board', 'User xolos  board on 2016-01-20', 50, 15, '2016-01-20', '00:00:00', ''),
(569, 'Insert into Board', 'User xolos  board on 2016-01-20', 50, 15, '2016-01-20', '00:00:00', ''),
(570, 'Update wallet', 'Update wallet of cashnow by receiving amount 150 INR on 2016-01-21 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 6, 5, '2016-01-21', '00:00:00', ''),
(571, 'Insert into Board', 'User joefaz  board on 2016-01-21', 51, 15, '2016-01-21', '00:00:00', ''),
(572, 'Insert into Board', 'User snoopy  board on 2016-01-21', 4, 15, '2016-01-21', '00:00:00', ''),
(573, 'Insert into Board', 'User orlando  board on 2016-01-21', 30, 15, '2016-01-21', '00:00:00', ''),
(574, 'Insert into Board', 'User johnyg  board on 2016-01-21', 8, 15, '2016-01-21', '00:00:00', ''),
(575, 'Insert into Board', 'User lizbeth  board on 2016-01-21', 5, 15, '2016-01-21', '00:00:00', ''),
(576, 'Insert into Board', 'User yola  board on 2016-01-21', 45, 15, '2016-01-21', '00:00:00', ''),
(577, 'Insert into Board', 'User joefaz  board on 2016-01-21', 51, 15, '2016-01-21', '00:00:00', ''),
(578, 'Insert into Board', 'User cashnow  board on 2016-01-21', 6, 15, '2016-01-21', '00:00:00', ''),
(579, 'Update wallet', 'Update wallet of cashnow by receiving amount 250 INR on 2016-01-21 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 6, 5, '2016-01-21', '00:00:00', ''),
(580, 'Insert into Board', 'User joefaz  board on 2016-01-21', 51, 15, '2016-01-21', '00:00:00', ''),
(581, 'Insert into Board', 'User snoopy  board on 2016-01-21', 4, 15, '2016-01-21', '00:00:00', ''),
(582, 'Insert into Board', 'User orlando  board on 2016-01-21', 30, 15, '2016-01-21', '00:00:00', ''),
(583, 'Insert into Board', 'User johnyg  board on 2016-01-21', 8, 15, '2016-01-21', '00:00:00', ''),
(584, 'Insert into Board', 'User lizbeth  board on 2016-01-21', 5, 15, '2016-01-21', '00:00:00', ''),
(585, 'Insert into Board', 'User yola  board on 2016-01-21', 45, 15, '2016-01-21', '00:00:00', ''),
(586, 'Insert into Board', 'User joefaz  board on 2016-01-21', 51, 15, '2016-01-21', '00:00:00', ''),
(587, 'Insert into Board', 'User cashnow  board on 2016-01-21', 6, 15, '2016-01-21', '00:00:00', ''),
(588, 'Update wallet', 'Update wallet of cashnow by receiving amount 500 INR on 2016-01-21 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 6, 5, '2016-01-21', '00:00:00', ''),
(589, 'Insert into Board', 'User joefaz  board on 2016-01-21', 51, 15, '2016-01-21', '00:00:00', ''),
(590, 'Insert into Board', 'User mikevilardi  board on 2016-01-21', 52, 15, '2016-01-21', '838:59:59', ''),
(591, 'Insert into Board', 'User mikevilardi  board on 2016-01-21', 52, 15, '2016-01-21', '838:59:59', ''),
(592, 'Insert into Board', 'User mikevilardi  board on 2016-01-21', 52, 15, '2016-01-21', '838:59:59', ''),
(593, 'Insert into Board', 'User getrichnow  board on 2016-01-21', 15, 15, '2016-01-21', '838:59:59', ''),
(594, 'Insert into Board', 'User orlando  board on 2016-01-21', 30, 15, '2016-01-21', '838:59:59', ''),
(595, 'Insert into Board', 'User johnyg  board on 2016-01-21', 8, 15, '2016-01-21', '838:59:59', ''),
(596, 'Insert into Board', 'User lizbeth  board on 2016-01-21', 5, 15, '2016-01-21', '838:59:59', ''),
(597, 'Insert into Board', 'User joefaz  board on 2016-01-21', 51, 15, '2016-01-21', '838:59:59', ''),
(598, 'Insert into Board', 'User mikevilardi  board on 2016-01-21', 52, 15, '2016-01-21', '838:59:59', ''),
(599, 'Insert into Board', 'User enrollnow  board on 2016-01-21', 9, 15, '2016-01-21', '838:59:59', ''),
(600, 'Insert into Board', 'User bkemper  board on 2016-01-21', 2, 15, '2016-01-21', '838:59:59', ''),
(601, 'Insert into Board', 'User earncashnow  board on 2016-01-21', 36, 15, '2016-01-21', '838:59:59', ''),
(602, 'Insert into Board', 'User jcarter  board on 2016-01-21', 12, 15, '2016-01-21', '838:59:59', ''),
(603, 'Insert into Board', 'User touchdown  board on 2016-01-21', 25, 15, '2016-01-21', '838:59:59', ''),
(604, 'Insert into Board', 'User xolos  board on 2016-01-21', 50, 15, '2016-01-21', '838:59:59', ''),
(605, 'Insert into Board', 'User enrollnow  board on 2016-01-21', 9, 15, '2016-01-21', '838:59:59', ''),
(606, 'Insert into Board', 'User joinnow  board on 2016-01-21', 1, 15, '2016-01-21', '838:59:59', ''),
(607, 'Insert into Board', 'User enrollnow  board on 2016-01-21', 9, 15, '2016-01-21', '838:59:59', ''),
(608, 'Update wallet', 'Update wallet of joinnow by receiving amount 1200 INR on 2016-01-21 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 1200 INR', 1, 5, '2016-01-21', '838:59:59', ''),
(609, 'edit profile', 'Profile updated of user xolos on  by xolos Your self', 50, 1, '2016-01-23', '00:00:00', ''),
(610, 'Insert into Board', 'User rhino  board on 2016-01-24', 54, 15, '2016-01-24', '838:59:59', ''),
(611, 'Update wallet', 'Update wallet of joinnow by receiving amount 150 INR on 2016-01-25 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 1, 5, '2016-01-25', '00:00:00', ''),
(612, 'Insert into Board', 'User pauli  board on 2016-01-25', 55, 15, '2016-01-25', '00:00:00', ''),
(613, 'Insert into Board', 'User pauli  board on 2016-01-25', 55, 15, '2016-01-25', '00:00:00', ''),
(614, 'Insert into Board', 'User pauli  board on 2016-01-25', 55, 15, '2016-01-25', '00:00:00', ''),
(615, 'Insert into Board', 'User topdog  board on 2016-01-25', 56, 15, '2016-01-25', '838:59:59', ''),
(616, 'Insert into Board', 'User topdog  board on 2016-01-25', 56, 15, '2016-01-25', '838:59:59', ''),
(617, 'Insert into Board', 'User cayce4sure  board on 2016-01-25', 32, 15, '2016-01-25', '838:59:59', ''),
(618, 'Insert into Board', 'User earncashnow  board on 2016-01-25', 36, 15, '2016-01-25', '838:59:59', ''),
(619, 'Insert into Board', 'User cashnow  board on 2016-01-25', 6, 15, '2016-01-25', '838:59:59', ''),
(620, 'Insert into Board', 'User joinnow  board on 2016-01-25', 1, 15, '2016-01-25', '838:59:59', ''),
(621, 'Insert into Board', 'User pauli  board on 2016-01-25', 55, 15, '2016-01-25', '838:59:59', ''),
(622, 'Insert into Board', 'User topdog  board on 2016-01-25', 56, 15, '2016-01-25', '838:59:59', ''),
(623, 'Insert into Board', 'User nbruce  board on 2016-01-25', 13, 15, '2016-01-25', '838:59:59', ''),
(624, 'Update wallet', 'Update wallet of nbruce by receiving amount 500 INR on 2016-01-25 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 13, 5, '2016-01-25', '838:59:59', ''),
(625, 'Insert into Board', 'User topdog  board on 2016-01-25', 56, 15, '2016-01-25', '838:59:59', ''),
(626, 'Insert into Board', 'User nbruce  board on 2016-01-25', 13, 15, '2016-01-25', '838:59:59', ''),
(627, 'Insert into Board', 'User jordano  board on 2016-01-25', 29, 15, '2016-01-25', '838:59:59', ''),
(628, 'Insert into Board', 'User joinnow  board on 2016-01-25', 1, 15, '2016-01-25', '838:59:59', ''),
(629, 'Insert into Board', 'User worldmoney  board on 2016-01-25', 16, 15, '2016-01-25', '838:59:59', ''),
(630, 'Insert into Board', 'User pauli  board on 2016-01-25', 55, 15, '2016-01-25', '838:59:59', ''),
(631, 'Insert into Board', 'User topdog  board on 2016-01-25', 56, 15, '2016-01-25', '838:59:59', ''),
(632, 'Insert into Board', 'User cashnow  board on 2016-01-25', 6, 15, '2016-01-25', '838:59:59', ''),
(633, 'Insert into Board', 'User cashnow  board on 2016-01-25', 6, 15, '2016-01-25', '838:59:59', ''),
(634, 'Insert into Board', 'User bkemper  board on 2016-01-25', 2, 15, '2016-01-25', '838:59:59', ''),
(635, 'Insert into Board', 'User snoopy  board on 2016-01-25', 4, 15, '2016-01-25', '838:59:59', ''),
(636, 'Insert into Board', 'User jcarter  board on 2016-01-25', 12, 15, '2016-01-25', '838:59:59', ''),
(637, 'Insert into Board', 'User Boston  board on 2016-01-25', 3, 15, '2016-01-25', '838:59:59', ''),
(638, 'Insert into Board', 'User enrollnow  board on 2016-01-25', 9, 15, '2016-01-25', '838:59:59', ''),
(639, 'Insert into Board', 'User cashnow  board on 2016-01-25', 6, 15, '2016-01-25', '838:59:59', ''),
(640, 'Insert into Board', 'User joinnow  board on 2016-01-25', 1, 15, '2016-01-25', '838:59:59', ''),
(641, 'Update wallet', 'Update wallet of joinnow by receiving amount 3000 INR on 2016-01-25 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 3000 INR', 1, 5, '2016-01-25', '838:59:59', ''),
(642, 'edit profile', 'Profile updated of user bwitter1 on  by bwitter1 Your self', 39, 1, '2016-01-26', '838:59:59', ''),
(643, 'Insert into Board', 'User justgotpaid  board on 2016-01-26', 57, 15, '2016-01-26', '838:59:59', ''),
(644, 'Insert into Board', 'User getrichnow  board on 2016-01-26', 15, 15, '2016-01-26', '838:59:59', ''),
(645, 'Insert into Board', 'User teddyb  board on 2016-01-26', 35, 15, '2016-01-26', '838:59:59', ''),
(646, 'Insert into Board', 'User bwitter1  board on 2016-01-26', 39, 15, '2016-01-26', '838:59:59', ''),
(647, 'Insert into Board', 'User gal1133  board on 2016-01-26', 17, 15, '2016-01-26', '838:59:59', ''),
(648, 'Insert into Board', 'User rhino  board on 2016-01-26', 54, 15, '2016-01-26', '838:59:59', ''),
(649, 'Insert into Board', 'User justgotpaid  board on 2016-01-26', 57, 15, '2016-01-26', '838:59:59', ''),
(650, 'Insert into Board', 'User enrollnow  board on 2016-01-26', 9, 15, '2016-01-26', '838:59:59', ''),
(651, 'Insert into Board', 'User coach  board on 2016-01-26', 47, 15, '2016-01-26', '838:59:59', ''),
(652, 'Insert into Board', 'User cashnow  board on 2016-01-26', 6, 15, '2016-01-26', '838:59:59', ''),
(653, 'Insert into Board', 'User pauli  board on 2016-01-26', 55, 15, '2016-01-26', '838:59:59', ''),
(654, 'Insert into Board', 'User xolos  board on 2016-01-26', 50, 15, '2016-01-26', '838:59:59', ''),
(655, 'Insert into Board', 'User topdog  board on 2016-01-26', 56, 15, '2016-01-26', '838:59:59', ''),
(656, 'Insert into Board', 'User enrollnow  board on 2016-01-26', 9, 15, '2016-01-26', '838:59:59', ''),
(657, 'Insert into Board', 'User joinnow  board on 2016-01-26', 1, 15, '2016-01-26', '838:59:59', ''),
(658, 'Update wallet', 'Update wallet of enrollnow by receiving amount 250 INR on 2016-01-26 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 9, 5, '2016-01-26', '838:59:59', ''),
(659, 'Update wallet', 'Update wallet of joinnow by receiving amount 250 INR on 2016-01-26 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 1, 5, '2016-01-26', '838:59:59', ''),
(660, 'Insert into Board', 'User justgotpaid  board on 2016-01-26', 57, 15, '2016-01-26', '838:59:59', ''),
(661, 'Insert into Board', 'User justgotpaid  board on 2016-01-26', 57, 15, '2016-01-26', '838:59:59', ''),
(662, 'Update wallet', 'Update wallet of jointoday by receiving amount 150 INR on 2016-01-27 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 10, 5, '2016-01-27', '00:00:00', ''),
(663, 'Insert into Board', 'User mycash  board on 2016-01-27', 58, 15, '2016-01-27', '00:00:00', ''),
(664, 'Insert into Board', 'User mycash  board on 2016-01-27', 58, 15, '2016-01-27', '00:00:00', ''),
(665, 'Insert into Board', 'User kingkong  board on 2016-01-27', 59, 15, '2016-01-27', '838:59:59', ''),
(666, 'Insert into Board', 'User badausa  board on 2016-01-27', 18, 15, '2016-01-27', '838:59:59', ''),
(667, 'Insert into Board', 'User touchdown  board on 2016-01-27', 25, 15, '2016-01-27', '838:59:59', ''),
(668, 'Insert into Board', 'User joinnow  board on 2016-01-27', 1, 15, '2016-01-27', '838:59:59', ''),
(669, 'Insert into Board', 'User bill  board on 2016-01-27', 19, 15, '2016-01-27', '838:59:59', ''),
(670, 'Insert into Board', 'User mycash  board on 2016-01-27', 58, 15, '2016-01-27', '838:59:59', ''),
(671, 'Insert into Board', 'User kingkong  board on 2016-01-27', 59, 15, '2016-01-27', '838:59:59', ''),
(672, 'Insert into Board', 'User jointoday  board on 2016-01-27', 10, 15, '2016-01-27', '838:59:59', ''),
(673, 'Update wallet', 'Update wallet of jointoday by receiving amount 250 INR on 2016-01-27 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 10, 5, '2016-01-27', '838:59:59', ''),
(674, 'Insert into Board', 'User kingkong  board on 2016-01-27', 59, 15, '2016-01-27', '838:59:59', ''),
(675, 'Insert into Board', 'User motto  board on 2016-01-27', 60, 15, '2016-01-27', '838:59:59', ''),
(676, 'Insert into Board', 'User motto  board on 2016-01-27', 60, 15, '2016-01-27', '838:59:59', ''),
(677, 'edit profile', 'Profile updated of user mycash on  by mycash Your self', 58, 1, '2016-01-27', '00:00:00', ''),
(678, 'edit profile', 'Profile updated of user mycash on  by mycash Your self', 58, 1, '2016-01-27', '00:00:00', ''),
(679, 'Update wallet', 'Update wallet of picky ticky by receiving amount 150 INR on 2016-01-28 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 11, 5, '2016-01-28', '00:00:00', ''),
(680, 'Insert into Board', 'User roberto  board on 2016-01-28', 61, 15, '2016-01-28', '00:00:00', ''),
(681, 'Insert into Board', 'User silvia  board on 2016-01-28', 24, 15, '2016-01-28', '00:00:00', ''),
(682, 'Insert into Board', 'User good2go  board on 2016-01-28', 40, 15, '2016-01-28', '00:00:00', ''),
(683, 'Insert into Board', 'User agentfaria  board on 2016-01-28', 46, 15, '2016-01-28', '00:00:00', ''),
(684, 'Insert into Board', 'User enrolltoday  board on 2016-01-28', 26, 15, '2016-01-28', '00:00:00', ''),
(685, 'Insert into Board', 'User ucandoit2  board on 2016-01-28', 48, 15, '2016-01-28', '00:00:00', ''),
(686, 'Insert into Board', 'User roberto  board on 2016-01-28', 61, 15, '2016-01-28', '00:00:00', ''),
(687, 'Insert into Board', 'User picky ticky  board on 2016-01-28', 11, 15, '2016-01-28', '00:00:00', ''),
(688, 'Update wallet', 'Update wallet of picky ticky by receiving amount 250 INR on 2016-01-28 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 11, 5, '2016-01-28', '00:00:00', ''),
(689, 'Insert into Board', 'User mamita  board on 2016-01-28', 62, 15, '2016-01-28', '00:00:00', ''),
(690, 'edit profile', 'Profile updated of user gal1133 on  by gal1133 Your self', 17, 1, '2016-01-28', '838:59:59', ''),
(691, 'Insert into Board', 'User jojo  board on 2016-01-28', 63, 15, '2016-01-28', '00:00:00', ''),
(692, 'Update wallet', 'Update wallet of bill by receiving amount 150 INR on 2016-01-29 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 19, 5, '2016-01-29', '00:00:00', ''),
(693, 'Insert into Board', 'User drphonefix1  board on 2016-01-29', 66, 15, '2016-01-29', '00:00:00', ''),
(694, 'edit profile', 'Profile updated of user drphonefix1 on  by drphonefix1 Your self', 66, 1, '2016-01-29', '00:00:00', ''),
(695, 'Update wallet', 'Update wallet of earncash by receiving amount 150 INR on 2016-01-29 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 7, 5, '2016-01-29', '00:00:00', ''),
(696, 'Insert into Board', 'User mamabear  board on 2016-01-29', 67, 15, '2016-01-29', '00:00:00', ''),
(697, 'Insert into Board', 'User newbeginning  board on 2016-01-29', 21, 15, '2016-01-29', '00:00:00', ''),
(698, 'Insert into Board', 'User milagrollego  board on 2016-01-29', 23, 15, '2016-01-29', '00:00:00', ''),
(699, 'Insert into Board', 'User jcjane426  board on 2016-01-29', 34, 15, '2016-01-29', '00:00:00', ''),
(700, 'Insert into Board', 'User yaerahoradeplata  board on 2016-01-29', 22, 15, '2016-01-29', '00:00:00', ''),
(701, 'Insert into Board', 'User cashmoney  board on 2016-01-29', 41, 15, '2016-01-29', '00:00:00', ''),
(702, 'Insert into Board', 'User mamabear  board on 2016-01-29', 67, 15, '2016-01-29', '00:00:00', ''),
(703, 'Insert into Board', 'User earncash  board on 2016-01-29', 7, 15, '2016-01-29', '00:00:00', ''),
(704, 'Update wallet', 'Update wallet of earncash by receiving amount 250 INR on 2016-01-29 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 250 INR', 7, 5, '2016-01-29', '00:00:00', ''),
(705, 'edit profile', 'Profile updated of user enrolltoday on  by enrolltoday Your self', 26, 1, '2016-01-29', '838:59:59', ''),
(706, 'Update wallet', 'Update wallet of badausa by receiving amount 150 INR on 2016-01-29 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 18, 5, '2016-01-29', '838:59:59', ''),
(707, 'Insert into Board', 'User alvaro  board on 2016-01-30', 71, 15, '2016-01-30', '838:59:59', ''),
(708, 'Update wallet', 'Update wallet of lizbeth by receiving amount 150 INR on 2016-01-30 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 5, 5, '2016-01-30', '00:00:00', ''),
(709, 'Update wallet', 'Update wallet of Boston by receiving amount 150 INR on 2016-01-30 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 3, 5, '2016-01-30', '00:00:00', ''),
(710, 'edit profile', 'Profile updated of user kingkong on  by kingkong Your self', 59, 1, '2016-02-01', '00:00:00', ''),
(711, 'edit profile', 'Profile updated of user agentfaria on  by agentfaria Your self', 46, 1, '2016-02-02', '838:59:59', ''),
(712, 'Insert into Board', 'User frenchy  board on 2016-02-03', 75, 15, '2016-02-03', '838:59:59', ''),
(713, 'Insert into Board', 'User frenchy  board on 2016-02-03', 75, 15, '2016-02-03', '838:59:59', ''),
(714, 'Insert into Board', 'User signupnow  board on 2016-02-03', 14, 15, '2016-02-03', '838:59:59', ''),
(715, 'Insert into Board', 'User picky ticky  board on 2016-02-03', 11, 15, '2016-02-03', '838:59:59', ''),
(716, 'Insert into Board', 'User xolos  board on 2016-02-03', 50, 15, '2016-02-03', '838:59:59', ''),
(717, 'Insert into Board', 'User enrolltoday  board on 2016-02-03', 26, 15, '2016-02-03', '838:59:59', ''),
(718, 'Insert into Board', 'User nbruce  board on 2016-02-03', 13, 15, '2016-02-03', '838:59:59', ''),
(719, 'Insert into Board', 'User frenchy  board on 2016-02-03', 75, 15, '2016-02-03', '838:59:59', ''),
(720, 'Insert into Board', 'User bkemper  board on 2016-02-03', 2, 15, '2016-02-03', '838:59:59', ''),
(721, 'Insert into Board', 'User pauli  board on 2016-02-03', 55, 15, '2016-02-03', '838:59:59', ''),
(722, 'Insert into Board', 'User mycash  board on 2016-02-03', 58, 15, '2016-02-03', '838:59:59', ''),
(723, 'Insert into Board', 'User kingkong  board on 2016-02-03', 59, 15, '2016-02-03', '838:59:59', ''),
(724, 'Insert into Board', 'User topdog  board on 2016-02-03', 56, 15, '2016-02-03', '838:59:59', ''),
(725, 'Insert into Board', 'User motto  board on 2016-02-03', 60, 15, '2016-02-03', '838:59:59', ''),
(726, 'Insert into Board', 'User bkemper  board on 2016-02-03', 2, 15, '2016-02-03', '838:59:59', ''),
(727, 'Insert into Board', 'User joinnow  board on 2016-02-03', 1, 15, '2016-02-03', '838:59:59', ''),
(728, 'Update wallet', 'Update wallet of bkemper by receiving amount 500 INR on 2016-02-03 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 2, 5, '2016-02-03', '838:59:59', ''),
(729, 'Update wallet', 'Update wallet of joinnow by receiving amount 500 INR on 2016-02-03 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 500 INR', 1, 5, '2016-02-03', '838:59:59', ''),
(730, 'Insert into Board', 'User frenchy  board on 2016-02-03', 75, 15, '2016-02-03', '838:59:59', ''),
(731, 'edit profile', 'Profile updated of user earncash on  by earncash Your self', 7, 1, '2016-02-04', '00:00:00', ''),
(732, 'edit profile', 'Profile updated of user earncash on  by earncash Your self', 7, 1, '2016-02-04', '838:59:59', ''),
(733, 'Insert into Board', 'User primetimemtg  board on 2016-02-04', 77, 15, '2016-02-04', '00:00:00', ''),
(734, 'edit profile', 'Profile updated of user lori on  by lori Your self', 44, 1, '2016-02-04', '00:00:00', ''),
(735, 'edit profile', 'Profile updated of user lori on  by lori Your self', 44, 1, '2016-02-04', '00:00:00', ''),
(736, 'Update wallet', 'Update wallet of bkemper by receiving amount 150 INR on 2016-02-06 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 150 INR', 2, 5, '2016-02-06', '838:59:59', ''),
(737, 'Insert into Board', 'User banton  board on 2016-02-06', 78, 15, '2016-02-06', '838:59:59', ''),
(738, 'Insert into Board', 'User earncashnow  board on 2016-02-06', 36, 15, '2016-02-06', '838:59:59', ''),
(739, 'Insert into Board', 'User jcarter  board on 2016-02-06', 12, 15, '2016-02-06', '838:59:59', ''),
(740, 'Insert into Board', 'User picky ticky  board on 2016-02-06', 11, 15, '2016-02-06', '838:59:59', ''),
(741, 'Insert into Board', 'User bkemper  board on 2016-02-06', 2, 15, '2016-02-06', '838:59:59', ''),
(742, 'Insert into Board', 'User frenchy  board on 2016-02-06', 75, 15, '2016-02-06', '838:59:59', ''),
(743, 'Insert into Board', 'User banton  board on 2016-02-06', 78, 15, '2016-02-06', '838:59:59', ''),
(744, 'Insert into Board', 'User worldmoney  board on 2016-02-06', 16, 15, '2016-02-06', '838:59:59', ''),
(745, 'Update wallet', 'Update wallet of worldmoney by receiving amount 0 INR on 2016-02-06 As Board Break Income His Current Wallet Balance  INR has Changed. Now changed Wallet Balance Is : 0 INR', 16, 5, '2016-02-06', '838:59:59', ''),
(746, 'Insert into Board', 'User banton  board on 2016-02-06', 78, 15, '2016-02-06', '838:59:59', ''),
(747, 'Insert into Board', 'User banton  board on 2016-02-06', 78, 15, '2016-02-06', '838:59:59', ''),
(748, 'Insert into Board', 'User cutie  board on 2016-02-18', 80, 15, '2016-02-18', '00:00:00', ''),
(749, 'Insert into Board', 'User cutie  board on 2016-02-18', 80, 15, '2016-02-18', '00:00:00', ''),
(750, 'Insert into Board', 'User cutie  board on 2016-02-18', 80, 15, '2016-02-18', '00:00:00', ''),
(751, 'Insert into Board', 'User silvia  board on 2016-02-18', 24, 15, '2016-02-18', '00:00:00', ''),
(752, 'Insert into Board', 'User good2go  board on 2016-02-18', 40, 15, '2016-02-18', '00:00:00', ''),
(753, 'Insert into Board', 'User ucandoit2  board on 2016-02-18', 48, 15, '2016-02-18', '00:00:00', ''),
(754, 'Insert into Board', 'User lsummit  board on 2016-02-18', 28, 15, '2016-02-18', '00:00:00', ''),
(755, 'Insert into Board', 'User frenchy  board on 2016-02-18', 75, 15, '2016-02-18', '00:00:00', ''),
(756, 'Insert into Board', 'User cutie  board on 2016-02-18', 80, 15, '2016-02-18', '00:00:00', ''),
(757, 'Insert into Board', 'User picky ticky  board on 2016-02-18', 11, 15, '2016-02-18', '00:00:00', ''),
(758, 'Insert into Board', 'User picky ticky  board on 2016-02-18', 11, 15, '2016-02-18', '00:00:00', ''),
(759, 'Insert into Board', 'User faithsal  board on 2016-02-22', 81, 15, '2016-02-22', '838:59:59', ''),
(760, 'Insert into Board', 'User touchdown  board on 2016-02-22', 25, 15, '2016-02-22', '838:59:59', ''),
(761, 'Insert into Board', 'User jointoday  board on 2016-02-22', 10, 15, '2016-02-22', '838:59:59', ''),
(762, 'Insert into Board', 'User earncash  board on 2016-02-22', 7, 15, '2016-02-22', '838:59:59', ''),
(763, 'Insert into Board', 'User joinnow  board on 2016-02-22', 1, 15, '2016-02-22', '838:59:59', ''),
(764, 'Insert into Board', 'User primetimemtg  board on 2016-02-22', 77, 15, '2016-02-22', '838:59:59', ''),
(765, 'Insert into Board', 'User faithsal  board on 2016-02-22', 81, 15, '2016-02-22', '838:59:59', ''),
(766, 'Insert into Board', 'User badausa  board on 2016-02-22', 18, 15, '2016-02-22', '838:59:59', ''),
(767, 'Insert into Board', 'User badausa  board on 2016-02-22', 18, 15, '2016-02-22', '838:59:59', ''),
(768, 'block member', 'User earncashnow has been blocked by EDNET Admin on 2016-02-22', 36, 17, '2016-02-22', '00:00:00', ''),
(769, 'block member', 'User touchdown has been blocked by EDNET Admin on 2016-02-22', 25, 17, '2016-02-22', '838:59:59', ''),
(770, 'Insert into Board', 'User brain dr  board on 2016-02-25', 82, 15, '2016-02-25', '00:00:00', ''),
(771, 'Insert into Board', 'User brain dr  board on 2016-02-25', 82, 15, '2016-02-25', '00:00:00', ''),
(772, 'Insert into Board', 'User brain dr  board on 2016-02-25', 82, 15, '2016-02-25', '00:00:00', ''),
(773, 'Edit Password', 'Password updated of user  on 2016-02-27 by Ourself ', 24, 2, '2016-02-27', '00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
CREATE TABLE IF NOT EXISTS `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership` varchar(255) NOT NULL,
  `amount` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Truncate table before insert `memberships`
--

TRUNCATE TABLE `memberships`;
--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `membership`, `amount`) VALUES
(1, 'Free', 0),
(2, 'Executive', 99),
(3, 'Leadership', 299),
(4, 'Professional', 499),
(5, 'Masters', 999),
(6, 'Founder', 2499);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL,
  `parent_menu` varchar(255) NOT NULL,
  `menu_file` varchar(255) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- Truncate table before insert `menu`
--

TRUNCATE TABLE `menu`;
--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `menu`, `parent_menu`, `menu_file`, `active`) VALUES
(1, 'Dashboard', '0', 'welcome', 1),
(2, 'Pay Stages', '0', 'board_status', 1),
(3, 'My Earning', '0', 'income', 0),
(4, 'My Commissions', '0', 'wallet', 1),
(5, 'E-Pin Panel', '0', 'epin_panel', 0),
(8, 'My Team', '0', 'my_team', 1),
(7, 'Advertisiment', '0', 'advertisiment', 0),
(6, 'Refer a Friend', '0', 'refer_a_friend', 1),
(9, 'User Profile', 'profile', 'user_profile', 1),
(10, 'Edit Profile', 'profile', 'edit_profile', 1),
(11, 'Change Password', 'profile', 'change_password', 1),
(12, 'Stage 1', 'board_status', 'first_matrix_plan', 1),
(13, 'Stage 2', 'board_status', 'second_matrix_plan', 1),
(14, 'Stage 3', 'board_status', 'third_matrix_plan', 1),
(15, 'Stage 4', 'board_status', 'fourth_matrix_plan', 1),
(16, 'Stage 5', 'board_status', 'five_matrix_plan', 1),
(17, 'Earnings', 'wallet', 'matrix_income', 1),
(18, 'Classified Click', 'income', 'matrix_points', 0),
(19, 'Balance', 'wallet', 'wallet_amount', 0),
(20, 'Request Money', 'wallet', 'request_transfer', 1),
(21, 'Withdrawal Request', 'wallet', 'request_status', 0),
(22, '2x2 Balance Logs', 'wallet12', 'wallet_logs', 1),
(23, 'Transfer E-pin', 'epin_panel', 'transfer_pin', 1),
(24, 'Used Pin', 'epin_panel', 'used_pin', 1),
(25, 'UnUsed Pin', 'epin_panel', 'unused_pin', 1),
(26, 'Create Ticket', 'sms', 'compose', 0),
(27, 'Inbox', 'sms', 'inbox', 0),
(28, 'Sent Message', 'sms', 'sent_message', 0),
(29, 'Utilities', 'advertisiment', 'utilities', 1),
(31, 'History', 'first_matrix_plan', 'summary_first', 1),
(30, 'Current Board', 'first_matrix_plan', 'my_board_first', 1),
(32, 'Previous Matrix', 'first_matrix_planh', 'previous_board_first', 1),
(33, 'Search Board', 'first_matrix_plan', 'search_board_first', 0),
(35, 'History', 'second_matrix_plan', 'summary_second', 1),
(34, 'Current Board', 'second_matrix_plan', 'my_board_second', 1),
(36, 'Previous Matrix', 'second_matrix_planf', 'previous_board_second', 1),
(37, 'Search Board', 'second_matrix_plan', 'search_board_second', 0),
(39, 'History', 'third_matrix_plan', 'summary_third', 1),
(38, 'Current Board', 'third_matrix_plan', 'my_board_third', 1),
(40, 'Previous Matrix', 'third_matrix_planj', 'previous_board_third', 1),
(41, 'Search Board', 'third_matrix_plan', 'search_board_third', 0),
(43, 'History', 'fourth_matrix_plan', 'summary_fourth', 1),
(42, 'Current Board', 'fourth_matrix_plan', 'my_board_fourth', 1),
(44, 'Previous Matrix', 'fourth_matrix_plang', 'previous_board_fourth', 1),
(45, 'Search Board', 'fourth_matrix_plan', 'search_board_fourth', 0),
(47, 'History', 'five_matrix_plan', 'summary_fifth', 1),
(46, 'Current Board', 'five_matrix_plan', 'my_board_fifth', 1),
(48, 'Previous Matrix', 'five_matrix_planf', 'previous_board_five', 1),
(49, 'Search Board', 'five_matrix_plan', 'search_board_fifth', 0),
(50, 'Harvest Summary', 'six_matrix_plan', 'summary_sixth', 1),
(51, 'Harvest Board', 'six_matrix_plan', 'my_board_sixth', 1),
(55, 'Big Oak Classified', 'advertisiment', 'bigoakclassified', 1),
(53, 'Harvest Search Board', 'six_matrix_plan', 'search_board_sixth', 1),
(54, 'Harvest', 'board_status123', 'six_matrix_plan', 1),
(56, 'My Add Clicks', 'advertisiment', 'click_point', 1),
(57, 'Add Classified', 'advertisiment', 'add_classified', 1),
(58, 'My Ads', 'advertisiment', 'ads', 1),
(59, 'My Enrollees', 'my_team', 'direct_members', 1),
(60, 'Current Month History', 'wallet', 'current_month_acc_history', 0),
(61, 'History By Day', 'wallet', 'day_history_acc', 0),
(62, 'History By Month', 'wallet', 'month_acc_history', 0),
(67, 'Invite Friends', 'refer_a_friend', 'invite_friends', 1),
(68, 'Reports', 'my_team', 'reports', 1),
(69, 'Documents', 'wallet', 'documents', 1),
(70, 'FAQS', 'help', 'faq', 1),
(71, 'Contact Us', 'help', 'contact', 1),
(72, 'Support', '0', 'help', 1),
(73,  'Upgrade Account',  '0',  'upgrade_account',  1);

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

DROP TABLE IF EXISTS `merchants`;
CREATE TABLE IF NOT EXISTS `merchants` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `merchant` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Truncate table before insert `merchants`
--

TRUNCATE TABLE `merchants`;
--
-- Dumping data for table `merchants`
--

INSERT INTO `merchants` (`id`, `merchant`, `status`, `img_url`, `slug`) VALUES
(1, 'e-Data', 0, NULL, 'e_data'),
(2, 'Authorize.net', 0, NULL, 'authorize_net'),
(3, 'Authorize.net (Tom Pace)', 1, NULL, 'authorize_net_2'),
(4, 'XpressDrafts', 1, NULL, 'xpressdrafts');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_packages`
--

DROP TABLE IF EXISTS `merchant_packages`;
CREATE TABLE IF NOT EXISTS `merchant_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Truncate table before insert `merchant_packages`
--

TRUNCATE TABLE `merchant_packages`;
--
-- Dumping data for table `merchant_packages`
--

INSERT INTO `merchant_packages` (`id`, `merchant_id`, `membership_id`, `status`) VALUES
(1, 1, 2, 1),
(2, 1, 3, 1),
(3, 1, 4, 1),
(4, 1, 5, 1),
(5, 2, 2, 0),
(6, 2, 3, 0),
(7, 2, 4, 0),
(8, 2, 5, 0),
(9, 3, 2, 1),
(10, 3, 3, 1),
(11, 3, 4, 1),
(12, 3, 5, 1),
(13, 4, 2, 1),
(14, 4, 3, 1),
(15, 4, 4, 1),
(16, 4, 5, 1);
-- --------------------------------------------------------

--
-- Table structure for table `merchant_payment_methods`
--

DROP TABLE IF EXISTS `merchant_payment_methods`;
CREATE TABLE IF NOT EXISTS `merchant_payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL,
  `method` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `verbiage` varchar(150) DEFAULT NULL,
  `cutoff_time` varchar(10) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Truncate table before insert `merchant_payment_methods`
--

TRUNCATE TABLE `merchant_payment_methods`;
--
-- Dumping data for table `merchant_payment_methods`
--

INSERT INTO `merchant_payment_methods` (`id`, `merchant_id`, `method`, `slug`, `status`, `img_url`, `verbiage`, `cutoff_time`) VALUES
(1, 1, 'eCheck', 'echeck', 0, 'img/echeck.png', 'Pay via <b>e-Check</b>', ''),
(2, 1, 'Credit Card', 'creditcard', 0, 'img/cc_card.png', 'Pay via <b>Visa Mastercard</b>', ''),
(3, 2, 'eCheck', 'echeck', 0, 'img/echeck.png', 'Pay via <b>e-Check</b>', ''),
(4, 2, 'Credit Card', 'creditcard', 1, 'img/cc_card.png', 'Pay via <b>Visa Mastercard</b>', ''),
(5, 3, 'eCheck', 'echeck', 0, 'img/echeck.png', 'Pay via <b>e-Check</b>', ''),
(6, 3, 'Credit Card', 'creditcard', 1, 'img/cc_card.png', 'Pay via <b>Visa Mastercard</b>', ''),
(7, 4, 'eCheck', 'echeck', 1, 'img/echeck.png', 'Pay via <b>e-Check</b>', '');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_settings`
--

DROP TABLE IF EXISTS `merchant_settings`;
CREATE TABLE IF NOT EXISTS `merchant_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL,
  `setting_name` varchar(150) NOT NULL,
  `setting_value` varchar(150) NOT NULL,
  `setting_description` varchar(255) NOT NULL,
  `environment` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Truncate table before insert `merchant_settings`
--

TRUNCATE TABLE `merchant_settings`;
--
-- Dumping data for table `merchant_settings`
--

INSERT INTO `merchant_settings` (`id`, `merchant_id`, `setting_name`, `setting_value`, `setting_description`, `environment`) VALUES
(1, 2, 'authorize_id', '5GpNvt28n', 'Default Authorize.NET SANDBOX account.', 'sandbox'),
(2, 2, 'authorize_key', '7nccb6457M2UHg2d', 'Default Authorize.NET SANDBOX account.', 'sandbox'),
(3, 3, 'authorize_id', '55KtfW6b', 'Tom Pace Authorize.NET SANDBOX account.', 'sandbox'),
(4, 3, 'authorize_key', '6ym946Abq2Yt63Wr', 'Tom Pace Authorize.NET SANDBOX account.', 'sandbox'),
(5, 2, 'authorize_id', '86CZbA8nq9', 'Default Authorize.NET LIVE Account', 'live'),
(6, 2, 'authorize_key', '2z5J56uK3t292Pd5', 'Default Authorize.NET LIVE Account', 'live'),
(7, 3, 'authorize_id', '7e5r9RQk', 'Tom Pace Authorize.NET LIVE account.', 'live'),
(8, 3, 'authorize_key', '38QdtA263Wzr9YXK', 'Tom Pace Authorize.NET LIVE account.', 'live');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `receive_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `message_date` date NOT NULL,
  `mode` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `message`
--

TRUNCATE TABLE `message`;
-- --------------------------------------------------------

--
-- Table structure for table `money_transfer`
--

DROP TABLE IF EXISTS `money_transfer`;
CREATE TABLE IF NOT EXISTS `money_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `money_transfer`
--

TRUNCATE TABLE `money_transfer`;
-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(100) NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

--
-- Truncate table before insert `options`
--

TRUNCATE TABLE `options`;
--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'default_merchant_provider', '3'),
(2, 'welcome_message', '                                               <p>WELCOME TO <strong><strong>Global Learning Centerï¿½ï¿½</strong></strong> TEAM.</p>\r\n\r\n<p>Congratulations upon your decision to join our team.</p>\r\n\r\n<p>Our goal is to provide public ads with outstanding ad appreciation. You were accepted in with other applicants because we feel that your qualifications and personality will contribute more to our goal of representing outstanding excellence. Equally important, we think that you will be an enthusiastic, friendly and energetic Associate who will help us bring distinction to <strong>Global Learning Centerï¿½ï¿½</strong> team.</p>\r\n\r\n<p>Each of usï¿½your Co-Associates, General Management and Ownersï¿½want you to succeed in your new ads. We extend to you a pledge of 100% cooperation in gaining your trust, loyalty and friendship. Just meet us halfway. That adds up to a total of 150%. With that much input from both of us, there is no way we can fail.</p>\r\n\r\n<p>We want your introduction to <strong>Global Learning Centerï¿½ï¿½</strong> to be a very personal one. You will be taken on a tour of the back-office, enjoy a talk with your Sponsor to review the rules of your particular the introduced to your Associates, including your General Management. Throughout this introductory period, feel free to ask questions on any point that is not clear to you.</p>\r\n\r\n<p><strong>Global Learning Centerï¿½ï¿½</strong> has given rise to many new corporations ads, one of which is yours. Although you may not be employed directly by <strong>Global Learning Centerï¿½ï¿½</strong>, you adhere to the policies specified on this site.</p>\r\n\r\n<p>You will be meeting and working with a team of skilled and talented individuals who all have a common interest ï¿½ helping care for the customerï¿½s needs. In the eyes of the customer you represent the ads. Your appearance, actions and personality reflect the commitment we share in taking care of the needs of our customers and associates. Together we can make things happen because we care about you and your customers.</p>\r\n\r\n<p>On <strong>Global Learning Centerï¿½ï¿½</strong> website is the basic information you will need to review. Read it carefully and refer to it often. However, <strong>Global Learning Centerï¿½ï¿½</strong> Rules Tab is the source of up-to-date policy. It takes precedence over shared information. <strong>Global Learning Centerï¿½ï¿½</strong> will advise you of policy changes affecting site. If you have any unanswered questions, please do not hesitate to make them known to us.</p>\r\n\r\n<p>Updated June 2014<br />\r\nSincerely, <br />\r\n<strong>Global Learning Centerï¿½ï¿½</strong> 1001 RTE. 636, Harvey NB E6K3G5, <br />\r\nSkype Shirleyadams60, <br />\r\nE-mail info@Kenect.org</p>                                   '),
(3, 'forget_password_message', '                                               Welcome User #username , Your Password Is: #password , thanks.                                    '),
(4, 'payout_generate_message', '                                               Hello user #pay_generate_username , Your payment of amount #amount has generated.                                   '),
(5, 'email_welcome_message', '                                               Welcome #username !\r\nYour Pin is : #user_pin .                                    '),
(6, 'direct_member_message', '                                               Hello Username #real_parent_username , You have added new user #new_username as Direct Member.                                    '),
(7, 'payment_request_message', '                                               Hello Username #pay_request_username , You have request for amount #request_amount to Global Learning Center.                                   '),
(8, 'payment_transfer_message', '                                               Hello Username #pay_request_username , Your payment of amount $ #request_amount USD has been transferred by Global Learning Center.                                   '),
(9, 'member_to_member_message', 'Hello User #requested_user , You have received amount of $ #request_amount USD from the User #payee_username !'),
(10, 'epin_generate_message', '                                               Hello Username #payee_epin_username  , You have received e-Voucher of amount #epin_amount from #epin_generate_username and your e-Voucher is #epin .                                    '),
(11, 'user_pin_generate_message', '       Hello User #requested_user , You have received amount of $ #request_amount USD from the User #payee_username !      asdfasdfa'),
(12, 'parent_limit', '5'),
(13, 'registration_fees', '20'),
(14, 'min_transfer', '10'),
(15, 'min_withdrawal', ''),
(16, 'upgrade_membership_fees', '50'),
(17, 'direct_member_income', '0'),
(18, 'pin_cost', ''),
(19, 'admin_tax', '0'),
(20, 'withdrawal_tax', '0'),
(21, 'first_board_name', 'Level 1'),
(22, 'first_board_income_1', '0'),
(23, 'first_board_income_2', '125'),
(24, 'first_board_point', ''),
(25, 'first_board_join', '99'),
(26, 'second_board_name', 'Level 2'),
(27, 'second_board_income_1', '0'),
(28, 'second_board_income_2', '200'),
(29, 'second_board_point', ''),
(30, 'second_board_join', '299'),
(31, 'third_board_name', 'Level 3'),
(32, 'third_board_income_1', '0'),
(33, 'third_board_income_2', '400'),
(34, 'third_board_point', ''),
(35, 'third_board_join', '499'),
(36, 'fourth_board_name', 'Level 4'),
(37, 'fourth_board_income_1', '0'),
(38, 'fourth_board_income_2', '1000'),
(39, 'fourth_board_point', ''),
(40, 'fourth_board_join', '999'),
(41, 'five_board_name', 'Level 5'),
(42, 'five_board_income_1', '2500'),
(43, 'five_board_income_2', '2500'),
(44, 'five_board_point', ''),
(45, 'five_board_join', '2499'),
(46, 'six_board_name', ''),
(47, 'six_board_income_1', '0'),
(48, 'six_board_income_2', '0'),
(49, 'six_board_point', '0'),
(50, 'min_q_referrals', '2'),
(51, 'min_free_referrals', '4'),
(52, 'q_time', '6'),
(53, 'first_reenter', '99'),
(54, 'first_cocomm', '99'),
(55, 'first_cocomm_cycle1', '99'),
(56, 'second_reenter', '125'),
(57, 'second_cocomm', '125'),
(58, 'second_cocomm_cycle1', '200'),
(59, 'third_reenter', '200'),
(60, 'third_cocomm', '200'),
(61, 'third_cocomm_cycle1', '200'),
(62, 'fourth_reenter', '400'),
(63, 'fourth_cocomm', '400'),
(64, 'fourth_cocomm_cycle1', '500'),
(65, 'fifth_reenter', '1000'),
(66, 'fifth_cocomm', '1000'),
(67, 'fifth_cocomm_cycle1', '1000'),
(68, 'five_reenter', '1000'),
(69, 'five_cocomm', '1000'),
(70, 'five_cocomm_cycle1', '1000'),
(71, 'reserve_percentage', '50'),
(72, 'reserve_month', '6'),
(73, 'selected_merchant', '1'),
(74, 'default_merchant_environment', 'sandbox'),
(78,  'second_step_income_1', '50'),
(79,  'second_step_income_2', '80'),
(80,  'second_step_income_3', '160'),
(81,  'second_step_income_4', '400'),
(82,  'second_step_income_5', '1000'),
(83,  'third_step_income_1',  '75'),
(84,  'third_step_income_2',  '120'),
(85,  'third_step_income_3',  '240'),
(86,  'third_step_income_4',  '600'),
(87,  'third_step_income_5',  '1500');

-- --------------------------------------------------------

--
-- Table structure for table `paid_unpaid`
--

DROP TABLE IF EXISTS `paid_unpaid`;
CREATE TABLE IF NOT EXISTS `paid_unpaid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `paid_date` date NOT NULL,
  `pay_mode` varchar(255) NOT NULL,
  `paid_inform` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `paid_unpaid`
--

TRUNCATE TABLE `paid_unpaid`;
-- --------------------------------------------------------

--
-- Table structure for table `payment_info`
--

DROP TABLE IF EXISTS `payment_info`;
CREATE TABLE IF NOT EXISTS `payment_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `sponsor_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `pay_mode` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `payment_info`
--

TRUNCATE TABLE `payment_info`;
-- --------------------------------------------------------

--
-- Table structure for table `payment_information`
--

DROP TABLE IF EXISTS `payment_information`;
CREATE TABLE IF NOT EXISTS `payment_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `income` double NOT NULL,
  `tax` double NOT NULL,
  `tds` double NOT NULL,
  `date` date NOT NULL,
  `amount` double NOT NULL,
  `mode` int(5) NOT NULL,
  `pay_mode` text NOT NULL,
  `pay_information` text NOT NULL,
  `paid_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `payment_information`
--

TRUNCATE TABLE `payment_information`;
-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_mode` varchar(50) NOT NULL,
  `logo_filename` varchar(100) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Truncate table before insert `payment_methods`
--

TRUNCATE TABLE `payment_methods`;
--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `pay_mode`, `logo_filename`, `status`) VALUES
(1, 'Commission', '', 1),
(2, 'Bank', '', 1),
(3, 'Wire', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payza_ipn`
--

DROP TABLE IF EXISTS `payza_ipn`;
CREATE TABLE IF NOT EXISTS `payza_ipn` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `ap_merchant` varchar(100) NOT NULL,
  `ap_custfirstname` varchar(100) NOT NULL,
  `ap_custlastname` varchar(100) NOT NULL,
  `ap_custaddress` varchar(255) NOT NULL,
  `ap_custcity` varchar(100) NOT NULL,
  `ap_custstate` varchar(100) NOT NULL,
  `ap_custcountry` varchar(100) NOT NULL,
  `ap_custzip` int(11) NOT NULL,
  `ap_custemailaddress` varchar(100) NOT NULL,
  `ap_shipaddress` varchar(100) NOT NULL,
  `ap_shipcity` varchar(100) NOT NULL,
  `ap_shipstate` varchar(100) NOT NULL,
  `ap_shipcountry` varchar(100) NOT NULL,
  `ap_shipzip` int(11) NOT NULL,
  `ap_receiveremail` varchar(100) NOT NULL,
  `ap_mpcustom` varchar(255) NOT NULL,
  `ap_batchnumber` varchar(100) NOT NULL,
  `ap_returncode` varchar(100) NOT NULL,
  `ap_returncodedescription` varchar(100) NOT NULL,
  `apc_1` varchar(255) NOT NULL,
  `apc_2` varchar(255) NOT NULL,
  `apc_3` varchar(255) NOT NULL,
  `apc_4` varchar(255) NOT NULL,
  `apc_5` varchar(255) NOT NULL,
  `apc_6` varchar(255) NOT NULL,
  `ap_test` int(10) NOT NULL,
  `ap_purchasetype` varchar(100) NOT NULL,
  `ap_referencenumber` varchar(100) NOT NULL,
  `ap_amount` decimal(10,2) NOT NULL,
  `ap_quantity` int(11) NOT NULL,
  `ap_currency` varchar(10) NOT NULL,
  `ap_description` text NOT NULL,
  `ap_itemcode` varchar(100) NOT NULL,
  `ap_itemname` varchar(100) NOT NULL,
  `ap_shippingcharges` decimal(10,2) NOT NULL,
  `ap_additionalcharges` decimal(10,2) NOT NULL,
  `ap_taxamount` decimal(10,2) NOT NULL,
  `ap_discountamount` decimal(10,2) NOT NULL,
  `ap_totalamount` decimal(10,2) NOT NULL,
  `ap_transactionstate` varchar(50) NOT NULL,
  `ap_notificationtype` varchar(50) NOT NULL,
  `ap_customeruniqueid` varchar(50) NOT NULL,
  `ap_ipnversion` varchar(10) NOT NULL,
  `ap_feeamount` decimal(10,2) NOT NULL,
  `ap_netamount` decimal(10,2) NOT NULL,
  `ap_transactiontype` varchar(50) NOT NULL,
  `ap_alerturl` varchar(255) NOT NULL,
  `ap_transactiondate` datetime NOT NULL,
  `ap_status` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `payza_ipn`
--

TRUNCATE TABLE `payza_ipn`;
-- --------------------------------------------------------

--
-- Table structure for table `plan_setting`
--

DROP TABLE IF EXISTS `plan_setting`;
CREATE TABLE IF NOT EXISTS `plan_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `profit` float NOT NULL,
  `profit_2` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `direct_spon_per` int(11) NOT NULL,
  `direct_spon_per_month` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Truncate table before insert `plan_setting`
--

TRUNCATE TABLE `plan_setting`;
--
-- Dumping data for table `plan_setting`
--

INSERT INTO `plan_setting` (`id`, `plan_name`, `amount`, `profit`, `profit_2`, `days`, `direct_spon_per`, `direct_spon_per_month`) VALUES
(1, 'Silver', 10000, 5, 1, 40, 1, 12),
(2, 'Gold', 20000, 5, 1, 40, 1, 12),
(3, 'Diamond', 50000, 5, 1, 40, 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `point_wallet`
--

DROP TABLE IF EXISTS `point_wallet`;
CREATE TABLE IF NOT EXISTS `point_wallet` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_point` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Truncate table before insert `point_wallet`
--

TRUNCATE TABLE `point_wallet`;
--
-- Dumping data for table `point_wallet`
--

INSERT INTO `point_wallet` (`user_id`, `user_point`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `products_name` varchar(255) NOT NULL,
  `prod_amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `products`
--

TRUNCATE TABLE `products`;
--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `products_name`, `prod_amount`) VALUES
(1, 'Training centre', 500);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Truncate table before insert `purchases`
--

TRUNCATE TABLE `purchases`;
--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `total`, `date_created`) VALUES
(1, 1, '999.00', '2016-02-12 10:21:07'),
(2, 78, '299.00', '2016-02-12 10:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

DROP TABLE IF EXISTS `purchase_details`;
CREATE TABLE IF NOT EXISTS `purchase_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `date_approved` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Truncate table before insert `purchase_details`
--

TRUNCATE TABLE `purchase_details`;
--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`id`, `purchase_id`, `payment_method`, `amount`, `status`, `date_approved`) VALUES
(1, 1, 2, '449.00', 0, '2016-02-12 16:20:24'),
(2, 1, 3, '500.00', 1, '2016-02-12 16:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_vouchers`
--

DROP TABLE IF EXISTS `purchase_vouchers`;
CREATE TABLE IF NOT EXISTS `purchase_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Truncate table before insert `purchase_vouchers`
--

TRUNCATE TABLE `purchase_vouchers`;
--
-- Dumping data for table `purchase_vouchers`
--

INSERT INTO `purchase_vouchers` (`id`, `purchase_id`, `voucher_id`) VALUES
(1, 1, 1),
(2, 2, 264);

-- --------------------------------------------------------

--
-- Table structure for table `reg_voucher`
--

DROP TABLE IF EXISTS `reg_voucher`;
CREATE TABLE IF NOT EXISTS `reg_voucher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `voucher` text NOT NULL,
  `voucher_amount` double NOT NULL,
  `date` date NOT NULL,
  `mode` int(5) NOT NULL,
  `used_id` int(11) NOT NULL,
  `used_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `reg_voucher`
--

TRUNCATE TABLE `reg_voucher`;
-- --------------------------------------------------------

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `request` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `request`
--

TRUNCATE TABLE `request`;
-- --------------------------------------------------------

--
-- Table structure for table `security_password`
--

DROP TABLE IF EXISTS `security_password`;
CREATE TABLE IF NOT EXISTS `security_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `security_password` int(11) NOT NULL,
  `date` date NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `security_password`
--

TRUNCATE TABLE `security_password`;
-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `welcome_message` text NOT NULL,
  `forget_password_message` text NOT NULL,
  `payout_generate_message` text NOT NULL,
  `email_welcome_message` text NOT NULL,
  `direct_member_message` text NOT NULL,
  `payment_request_message` text NOT NULL,
  `payment_transfer_message` text NOT NULL,
  `member_to_member_message` text NOT NULL,
  `epin_generate_message` text NOT NULL,
  `user_pin_generate_message` text NOT NULL,
  `parent_limit` int(5) NOT NULL,
  `registration_fees` float NOT NULL,
  `min_transfer` double NOT NULL,
  `min_withdrawal` double NOT NULL,
  `upgrade_membership_fees` float NOT NULL,
  `direct_member_income` float NOT NULL,
  `pin_cost` float NOT NULL,
  `admin_tax` double NOT NULL,
  `withdrawal_tax` double NOT NULL,
  `first_board_name` varchar(255) NOT NULL,
  `first_board_income_1` double NOT NULL,
  `first_board_income_2` double NOT NULL,
  `first_board_point` double NOT NULL,
  `first_board_join` int(11) NOT NULL,
  `second_board_name` varchar(255) NOT NULL,
  `second_board_income_1` double NOT NULL,
  `second_board_income_2` double NOT NULL,
  `second_board_point` double NOT NULL,
  `second_board_join` int(11) NOT NULL,
  `third_board_name` varchar(255) NOT NULL,
  `third_board_income_1` double NOT NULL,
  `third_board_income_2` double NOT NULL,
  `third_board_point` double NOT NULL,
  `third_board_join` int(11) NOT NULL,
  `fourth_board_name` varchar(255) NOT NULL,
  `fourth_board_income_1` double NOT NULL,
  `fourth_board_income_2` double NOT NULL,
  `fourth_board_point` double NOT NULL,
  `fourth_board_join` int(11) NOT NULL,
  `five_board_name` varchar(255) NOT NULL,
  `five_board_income_1` double NOT NULL,
  `five_board_income_2` double NOT NULL,
  `five_board_point` double NOT NULL,
  `five_board_join` int(11) NOT NULL,
  `six_board_name` varchar(255) NOT NULL,
  `six_board_income_1` double NOT NULL,
  `six_board_income_2` double NOT NULL,
  `six_board_point` double NOT NULL,
  `min_q_referrals` int(4) NOT NULL DEFAULT '2',
  `min_free_referrals` int(11) NOT NULL DEFAULT '4',
  `q_time` int(2) NOT NULL DEFAULT '6' COMMENT 'months',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `setting`
--

TRUNCATE TABLE `setting`;
--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `welcome_message`, `forget_password_message`, `payout_generate_message`, `email_welcome_message`, `direct_member_message`, `payment_request_message`, `payment_transfer_message`, `member_to_member_message`, `epin_generate_message`, `user_pin_generate_message`, `parent_limit`, `registration_fees`, `min_transfer`, `min_withdrawal`, `upgrade_membership_fees`, `direct_member_income`, `pin_cost`, `admin_tax`, `withdrawal_tax`, `first_board_name`, `first_board_income_1`, `first_board_income_2`, `first_board_point`, `first_board_join`, `second_board_name`, `second_board_income_1`, `second_board_income_2`, `second_board_point`, `second_board_join`, `third_board_name`, `third_board_income_1`, `third_board_income_2`, `third_board_point`, `third_board_join`, `fourth_board_name`, `fourth_board_income_1`, `fourth_board_income_2`, `fourth_board_point`, `fourth_board_join`, `five_board_name`, `five_board_income_1`, `five_board_income_2`, `five_board_point`, `five_board_join`, `six_board_name`, `six_board_income_1`, `six_board_income_2`, `six_board_point`, `min_q_referrals`, `min_free_referrals`, `q_time`) VALUES
(1, '<p>WELCOME TO <strong><strong>Global Learning Center®™</strong></strong> TEAM.</p>\r\n\r\n<p>Congratulations upon your decision to join our team.</p>\r\n\r\n<p>Our goal is to provide public ads with outstanding ad appreciation. You were accepted in with other applicants because we feel that your qualifications and personality will contribute more to our goal of representing outstanding excellence. Equally important, we think that you will be an enthusiastic, friendly and energetic Associate who will help us bring distinction to <strong>Global Learning Center®™</strong> team.</p>\r\n\r\n<p>Each of us—your Co-Associates, General Management and Owners—want you to succeed in your new ads. We extend to you a pledge of 100% cooperation in gaining your trust, loyalty and friendship. Just meet us halfway. That adds up to a total of 150%. With that much input from both of us, there is no way we can fail.</p>\r\n\r\n<p>We want your introduction to <strong>Global Learning Center®™</strong> to be a very personal one. You will be taken on a tour of the back-office, enjoy a talk with your Sponsor to review the rules of your particular the introduced to your Associates, including your General Management. Throughout this introductory period, feel free to ask questions on any point that is not clear to you.</p>\r\n\r\n<p><strong>Global Learning Center®™</strong> has given rise to many new corporations ads, one of which is yours. Although you may not be employed directly by <strong>Global Learning Center®™</strong>, you adhere to the policies specified on this site.</p>\r\n\r\n<p>You will be meeting and working with a team of skilled and talented individuals who all have a common interest – helping care for the customer’s needs. In the eyes of the customer you represent the ads. Your appearance, actions and personality reflect the commitment we share in taking care of the needs of our customers and associates. Together we can make things happen because we care about you and your customers.</p>\r\n\r\n<p>On <strong>Global Learning Center®™</strong> website is the basic information you will need to review. Read it carefully and refer to it often. However, <strong>Global Learning Center®™</strong> Rules Tab is the source of up-to-date policy. It takes precedence over shared information. <strong>Global Learning Center®™</strong> will advise you of policy changes affecting site. If you have any unanswered questions, please do not hesitate to make them known to us.</p>\r\n\r\n<p>Updated June 2014<br />\r\nSincerely, <br />\r\n<strong>Global Learning Center®™</strong> 1001 RTE. 636, Harvey NB E6K3G5, <br />\r\nSkype Shirleyadams60, <br />\r\nE-mail info@Kenect.org</p>', 'Welcome User #username , Your Password Is: #password , thanks. ', 'Hello user #pay_generate_username , Your payment of amount #amount has generated.', 'Welcome #username !\r\nYour Pin is : #user_pin .', 'Hello Username #real_parent_username , You have added new user #new_username as Direct Member.', 'Hello Username #pay_request_username , You have request for amount #request_amount to Global Learning Center.', 'Hello Username #pay_request_username , Your payment of amount $ #request_amount USD has been transferred by Global Learning Center.', 'Hello User #requested_user , You have received amount of $ #request_amount USD from the User #payee_username !', 'Hello Username #payee_epin_username  , You have received e-Voucher of amount #epin_amount from #epin_generate_username and your e-Voucher is #epin .', 'Hello User #requested_user , You have received amount of $ #request_amount USD from the User #payee_username !', 5, 20, 10, 0, 50, 0, 0, 0, 0, 'Level 1', 0, 150, 0, 99, 'Level 2', 0, 250, 0, 299, 'Level 3', 0, 500, 0, 499, 'Level 4', 0, 1200, 0, 999, 'Level 5', 3000, 3000, 0, 2499, '', 0, 0, 0, 2, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `system_date`
--

DROP TABLE IF EXISTS `system_date`;
CREATE TABLE IF NOT EXISTS `system_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Truncate table before insert `system_date`
--

TRUNCATE TABLE `system_date`;
--
-- Dumping data for table `system_date`
--

INSERT INTO `system_date` (`id`, `sys_date`) VALUES
(1, '2015-06-10');

-- --------------------------------------------------------

--
-- Table structure for table `temp_stp`
--

DROP TABLE IF EXISTS `temp_stp`;
CREATE TABLE IF NOT EXISTS `temp_stp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `temp_stp`
--

TRUNCATE TABLE `temp_stp`;
-- --------------------------------------------------------

--
-- Table structure for table `temp_users`
--

DROP TABLE IF EXISTS `temp_users`;
CREATE TABLE IF NOT EXISTS `temp_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `date` date NOT NULL,
  `activate_date` date NOT NULL,
  `time` int(11) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `user_img` varchar(255) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `address` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `user_pin` int(11) NOT NULL,
  `beneficiery_name` varchar(255) NOT NULL,
  `ac_no` text NOT NULL,
  `bank` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `bank_code` text NOT NULL,
  `payza_account` varchar(255) NOT NULL,
  `tax_id` varchar(255) NOT NULL,
  `pan_no` varchar(100) NOT NULL,
  `pin_code` int(11) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `provience` varchar(20) NOT NULL,
  `reg_way` varchar(255) NOT NULL,
  `paid` int(11) NOT NULL,
  `membership` varchar(150) NOT NULL,
  `optin_affiliate` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `temp_users`
--

TRUNCATE TABLE `temp_users`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `date` date NOT NULL,
  `activate_date` date NULL,
  `time` int(11) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `user_img` varchar(255) NOT NULL,
  `gender` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `address` text NOT NULL,
  `country` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `user_pin` int(11) NOT NULL,
  `beneficiery_name` varchar(255) NOT NULL,
  `ac_no` text NOT NULL,
  `bank` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `bank_code` text NOT NULL,
  `payza_account` varchar(255) NOT NULL,
  `tax_id` varchar(255) NOT NULL,
  `pan_no` varchar(100) NOT NULL,
  `pin_code` int(11) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `provience` varchar(20) NOT NULL,
  `optin_affiliate` int(11) NOT NULL DEFAULT '0',
  `dwolla_id` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `parent_id`, `real_parent`, `position`, `date`, `activate_date`, `time`, `f_name`, `l_name`, `user_img`, `gender`, `email`, `phone_no`, `city`, `username`, `password`, `dob`, `address`, `country`, `type`, `user_pin`, `beneficiery_name`, `ac_no`, `bank`, `branch`, `bank_code`, `payza_account`, `tax_id`, `pan_no`, `pin_code`, `father_name`, `district`, `state`, `provience`, `optin_affiliate`, `dwolla_id`, `description`) VALUES
(1, 0, 0, 0, '2015-12-23', '0000-00-00', 1450803600, 'Kage Enterprises', 'Inc.', '13458848761', 'male', 'LuckyClub7@gmail.com', '9548028826', 'Covington', 'joinnow', '0456d7eb01e9236d47898b09bc9c8f5ea3df8054', '1970-05-13', '519 E 19th Avenue Covington, LA 70433', 'United States', 'B', 286027, '', '2147483647', '', '', '', 'christopher.cowart@gmail.com', '', '', 175001, '', '', 'California', '', 0, '', NULL),
(2, 1, 1, 0, '2015-12-23', '0000-00-00', 1450818000, 'Robert', 'Kemper', '', 'male', 'rwkemper@gmail.com', '3522557717', 'MANDI', 'bkemper', '50eca6dc968d31bd0b2cb9d49cf2f91072c3f8da', '1975-06-06', '1385 Lake Avenue Clermont, FL. 34711', 'US', 'B', 933023, '', '2147483648', '', '', '', '', '', '', 175002, '', '', 'California', '', 0, '', NULL),
(3, 2, 2, 0, '2015-12-23', '0000-00-00', 1450836000, 'max', 'well', '', 'male', 'BCMKTAMER@aol.com', '5613066999', 'boca raton', 'Boston', '20a54b10d3333ded6355fdecd0c0160058873848', '0000-00-00', '3100 South Dixie Highway G76\r\nBoca Raton, FL.33432', 'United States', 'B', 933023, '', '2147483649', '', '', '', '', '', '', 175003, '', '3100 s dixie highway', 'California', '', 0, '', NULL),
(4, 0, 3, 0, '2016-01-05', '2016-01-05', 1452013609, 'Katherine', 'Brown', '', '', 'bobbyccb@gmail.com', '', 'Palm Harbor', 'snoopy', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(5, 0, 3, 0, '2016-01-05', '2016-01-05', 1452013786, 'Lizbeth', 'Cannata', '', '', 'lizbeth142009@live.com', '', 'Delray Beach', 'lizbeth', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(6, 0, 1, 0, '2016-01-05', '2016-01-05', 1452014009, 'Jim', 'OBrien', '', '', 'jobcpa1@yahoo.com', '', 'Pompano Beach', 'cashnow', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(7, 0, 1, 0, '2016-01-05', '2016-01-05', 1452014227, 'Chris', 'Cowart', '', 'male', 'zengolf67@gmail.com', '', 'Fort Lauderdale', 'earncash', '7c4a8d09ca3762af61e59520943dc26494f8941b', '1961-09-18', 'P.O. Box 461060', 'United States', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(8, 0, 5, 0, '2016-01-05', '2016-01-05', 1452014694, 'Jonathan', 'Ramirez', '', '', 'johny940725@live.com', '', 'Boca Raton', 'johnyg', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(9, 0, 1, 0, '2016-01-05', '2016-01-05', 1452015025, 'Daniel', 'Daragan', '', '', 'Dandaragan@aol.com', '', 'Cooper City', 'enrollnow', 'ceb2441883e14291985f345dae85fb9f07072e56', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(10, 0, 1, 0, '2016-01-05', '2016-01-05', 1452015335, 'Cynthia', 'Seymour', '', '', 'cynthiakseymour@gmail.com', '', 'Coconut Grove', 'jointoday', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(11, 0, 2, 0, '2016-01-05', '2016-01-05', 1452015925, 'Sandra', 'Tickner', '', '', 'Sbwins@aol.com', '', 'Pembroke Pines', 'picky ticky', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(12, 0, 2, 0, '2016-01-05', '2016-01-05', 1452016050, 'Donald', 'Carter', '', '', 'bud3858@aol.com', '', 'Winter Garden', 'jcarter', '834ee438c33f96b64fe7661377824ac005ef0b5c', '2016-01-05', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(13, 6, 2, 1, '2016-01-05', '0000-00-00', 1452024915, 'Nelson', 'Bruce', '', '', 'n.bruce@live.com', ' ', 'Ft Lauderdale', 'nbruce', 'c58decffe89e4ae689b61a1c345db55a0ee91fa9', '2016-01-05', '', 'US', 'B', 959303, '', '', '', '', '', 'n.bruce@live.com', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(14, 7, 1, 0, '2016-01-06', '0000-00-00', 1452095490, 'Darin', 'Delia', '', '', 'darindelia@gmail.com', ' ', 'Punta Gorda', 'signupnow', 'e2d7bbb939258a6fc455dd841159c3f8ecdaefaf', '2016-01-06', '', 'US', 'B', 345981, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(15, 7, 9, 1, '2016-01-06', '0000-00-00', 1452113432, 'Adam', 'Garnett', '', '', 'garnadam@aol.com', ' ', 'Tamarac', 'getrichnow', 'fcc390ac4202457f8dbfa3b8c44fe2a04b3f443b', '2016-01-06', '', 'US', 'B', 510721, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(16, 8, 12, 0, '2016-01-06', '0000-00-00', 1452118581, 'Michael', 'Nyitray', '', '', 'worldmike@aol.com', ' ', 'Parkland', 'worldmoney', 'ff8a59c39bee18e5fc88b3f191e718473b9f1e66', '2016-01-06', '', 'US', 'B', 826558, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(17, 8, 9, 1, '2016-01-06', '0000-00-00', 1452125334, 'Eyal', 'Harel', '', 'male', 'info@gandehomes.com', '9542584087', 'Cooper City', 'gal1133', '4c0d2b951ffabd6f9a10489dc40fc356ec1d26d5', '1972-05-18', '10620 Griffin Rd.', 'United States', 'B', 117366, '', '', '', '', '', 'info@gandehomes.com', '', '', 0, '', 'Suite 108', '', 'FL', 1, '', NULL),
(18, 9, 5, 0, '2016-01-06', '0000-00-00', 1452126795, 'Barbara', 'Dagosta', '', '', 'barbara@bzbenterprises.com', ' ', 'Boca Raton', 'badausa', '9ee5eac168ddecd30de7cc551bbb410eca92e80e', '2016-01-06', '', 'US', 'B', 395085, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(19, 9, 9, 1, '2016-01-06', '0000-00-00', 1452128193, 'William', 'Daragan', '', '', 'billdaragan@yahoo.com', ' ', 'Cooper City', 'bill', 'ec1e7fb8656dba32737acabc2e5a1fb2d02a973f', '2016-01-06', '', 'US', 'B', 207413, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(20, 10, 9, 0, '2016-01-06', '0000-00-00', 1452128604, 'Melissa ', 'Daragan', '', '', 'cece9526@icloud.com', ' ', 'Cooper City', 'cece', 'ec1e7fb8656dba32737acabc2e5a1fb2d02a973f', '2016-01-06', '', 'US', 'B', 800140, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(21, 10, 5, 1, '2016-01-07', '0000-00-00', 1452190031, 'ferney dovan', 'ramirez hoyos', '', '', 'fedoraho@hotmail.com', ' ', 'cali', 'newbeginning', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-07', '', 'ZA', 'B', 296260, '', '', '', '', '', '', '', '', 0, '', '', '', 'south america', 1, '', NULL),
(22, 11, 21, 0, '2016-01-07', '0000-00-00', 1452190450, 'edinson', 'ramirez hoyos', '', '', 'polacoramirez@hotmail.com', ' ', 'cali', 'yaerahoradeplata', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-07', '', 'CO', 'B', 447401, '', '', '', '', '', '', '', '', 0, '', '', '', 'south america', 1, '', NULL),
(23, 11, 22, 1, '2016-01-07', '0000-00-00', 1452192398, 'holman', 'ramirez hoyas', '', '', 'sandraachinte@hotmail.com', ' ', 'cali', 'milagrollego', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-07', '', 'CO', 'B', 712015, '', '', '', '', '', '', '', '', 0, '', '', '', '-', 1, '', NULL),
(24, 12, 3, 0, '2016-01-07', '0000-00-00', 1452203309, 'silvia', 'delcoro', '', '', 'sdelcoro@gmail.com', ' ', 'deerfield beach', 'silvia', '66718ee827f435c00017446e78f6892aadcb84d9', '2016-01-07', '', 'US', 'B', 455617, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(25, 0, 10, 0, '2016-01-08', '2016-01-08', 1452274488, 'Nathaniel', 'Quinn-Seymour', '', '', 'nq25@cornell.edu', '', 'New York', 'touchdown', '10c51a18cb9fb57997e5078c8f51c329a0678e2c', '2016-01-08', '', 'US', 'C', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'NY', 1, '', ''),
(26, 13, 1, 0, '2016-01-08', '0000-00-00', 1452276379, 'Doug', 'Kerl', '', '', 'dougkerl@outlook.com', '5615428146', 'Boynton Beach', 'enrolltoday', '7c4a8d09ca3762af61e59520943dc26494f8941b', '1967-01-30', '802 SW 34th Ave', 'United States', 'B', 218222, '', '', '', '', '', '', '', '', 0, '', '', '', 'LA', 1, '', NULL),
(27, 0, 9, 0, '2016-01-08', '2016-01-08', 1452282129, 'mistake', 'mistake', '', '', 'm@mistake.com', ' ', 'mistake', 'mistake', 'f872caad177d67bbe18c119d0505f2d3caa02af3', '2016-01-08', '', 'US', 'F', 284642, '', '', '', '', '', '', '', '', 0, '', '', '', 'NV', 1, '', NULL),
(28, 14, 3, 0, '2016-01-09', '0000-00-00', 1452375148, 'liegh', 'summit', '', '', 'lbsummit@live.com', ' ', 'fort lauderdale', 'lsummit', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-09', '', 'US', 'B', 154915, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(29, 0, 13, 0, '2016-01-09', '2016-01-09', 1452382855, 'keith', 'jordano', '', '', 'keith@jordanogroup.com', '', 'west palm beach', 'jordano', '418a3bbe9a9a39b481f5f19985e09bc4993501e0', '2016-01-09', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(30, 15, 5, 0, '2016-01-11', '0000-00-00', 1452535480, 'orlando', 'marrero', '', '', 'landomarro@gmail.com', ' ', 'fort lauderdale', 'orlando', '8724b0d83b05bd3bb739367ff70f60d9f720beac', '2016-01-11', '', 'US', 'B', 183205, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(31, 0, 13, 0, '2016-01-11', '2016-01-11', 1452538090, 'Tara', 'Ramnarine ', '', '', 'Misstsfl@gmail.com', ' ', 'Coral Springs ', 'nbk6rm8', '7bc2479c3aa0c8ec86bb6c1a345221ac9b53a24d', '2016-01-11', '', 'US', 'F', 880898, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(32, 16, 12, 0, '2016-01-12', '0000-00-00', 1452631762, 'Cayce ', 'Carter', '', '', 'cayce4sure@gmail.com', ' ', 'Winter Garden', 'cayce4sure', '5fdd1a689a6b5de34ca82106544b7f35bc253b4b', '2016-01-12', '', 'US', 'B', 315787, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(33, 16, 14, 1, '2016-01-13', '0000-00-00', 1452700519, 'Jeremy', 'Thurman', '', '', 'jeremythurman@hotmail.com', ' ', 'Punta Gorda', 'signuptoday', '33e07162459261363c7dd19ae290b0325a8b4b55', '2016-01-13', '', 'US', 'B', 326433, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(34, 17, 7, 0, '2016-01-13', '0000-00-00', 1452707613, 'Jane', 'Castellano', '', '', 'jane426@yahoo.com', ' ', 'Boca Raton', 'jcjane426', '66ea9f3384292ea17154308734b9be6d8b3cdc2f', '2016-01-13', '', 'US', 'B', 684237, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(35, 17, 9, 1, '2016-01-13', '0000-00-00', 1452709283, 'Ted', 'Baturin', '', '', 'tedbaturin@yahoo.com', ' ', 'Coconut Creek', 'teddyb', 'ec1e7fb8656dba32737acabc2e5a1fb2d02a973f', '2016-01-13', '', 'US', 'B', 259200, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(36, 18, 1, 0, '2016-01-13', '0000-00-00', 1452709560, 'Rob', 'Pash', '', '', 'pash36@gmail.com', ' ', 'Pompano Beach', 'earncashnow', '5dfbccc210430217ed5efbcd761e9e19aaffb4ba', '2016-01-13', '', 'US', 'C', 257756, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', 'Vincent Pernice'),
(37, 0, 13, 0, '2016-01-11', '2016-01-11', 1452550174, 'Juan', 'Rodriguez', '', '', 'jaradul@gmail.com', '', 'Hollywood', 'johnrod', '3cca1823afd2c593489a37d002813fb4ceb76970', '2016-01-11', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(38, 19, 9, 0, '2016-01-13', '0000-00-00', 1452720499, 'Linda ', 'Powell', '', '', 'powelljlk@bellsouth.net', ' ', 'DAVIE', 'lindakay', 'b367b0e68a1ab7475b7fdb0de19004e7d76a6f68', '2016-01-13', '', 'US', 'B', 573903, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(39, 19, 9, 1, '2016-01-14', '0000-00-00', 1452791371, 'Bill', 'Witter', '', 'male', 'billpwitter@gmail.com', ' ', 'Dunedin', 'bwitter1', '744fe77d507565cbe16119fcfd84efa3690a2b31', '1953-09-04', '2041 Lakewood Drive', 'United States', 'B', 141287, '', '', '', '', '', 'bwitter@tampabay.rr.com', '', '', 0, '', '2041 Lakewood Drive', '', 'FL', 1, '', NULL),
(40, 20, 11, 0, '2016-01-14', '0000-00-00', 1452796787, 'Diana', 'Hahn', '', '', 'rwkemper@gmail.com', ' ', 'Pembroke Pines', 'good2go', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-14', '', 'US', 'B', 260670, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(41, 20, 34, 1, '2016-01-15', '0000-00-00', 1452898929, 'William', 'Castellano', '', '', 'wcastellano13@gmail.com', ' ', 'Miami', 'cashmoney', '8ae4a0b88c00693c3040afa8c98b9aea9876e3b8', '2016-01-15', '', 'US', 'B', 781451, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(42, 21, 3, 0, '2016-01-15', '0000-00-00', 1452899007, 'jack', 'palumbo', '', '', 'golf22@gmail.com', ' ', 'boston', 'jack22', 'b4c3c31f8af634433123600f7d32b0ef31dbdab4', '2016-01-15', '', 'US', 'B', 619319, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(43, 21, 42, 1, '2016-01-15', '0000-00-00', 1452899084, 'John', 'Haritas', '', '', 'biglakesolar@gmail.com', ' ', 'Newburyport', 'winni56', '3db8c16ba5909aedac6844c1b652859f89215fd8', '2016-01-15', '', 'US', 'B', 685480, '', '', '', '', '', '', '', '', 0, '', '', '', 'MA', 1, '', NULL),
(44, 22, 3, 0, '2016-01-15', '0000-00-00', 1452899314, 'Lori', 'Lord Kmosko', '', 'female', 'gohappyface@gmail.com', '  954-842-1552', 'pembroke pines', 'lori', '19c3c878721c9c6b9a13b63c0770951c525011d1', '1970-08-15', '20802 NW 14 Court', 'United States', 'B', 725631, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(45, 22, 5, 1, '2016-01-18', '0000-00-00', 1453134568, 'yolanda', 'velasquez', '', '', 'yola.velasquez52@gmail.com', ' ', 'boca raton', 'yola', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-18', '', 'US', 'B', 495633, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(46, 23, 24, 0, '2016-01-18', '0000-00-00', 1453134687, 'Angela', 'Faria', '', 'female', 'agentfaria@gmail.com', ' ', 'Boca Raton', 'agentfaria', '7c12f7f5bc98a0893b40ae5b580249b4c49f037f', '1965-10-31', '19521 Montana Lane', 'United States', 'B', 494083, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(47, 23, 12, 1, '2016-01-18', '0000-00-00', 1453134777, 'Tom', 'Herter', '', '', 'therter46@aim.com', ' ', 'Eustis', 'coach', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-18', '', 'US', 'B', 945297, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(48, 24, 40, 0, '2016-01-18', '0000-00-00', 1453135328, 'Cheryl', 'Elam', '', '', 'chelam@cox.net', ' ', 'Surprise', 'ucandoit2', '80b7d8bb1625ca89a19937a942c31173c014c5e6', '2016-01-18', '', 'US', 'B', 488719, '', '', '', '', '', '', '', '', 0, '', '', '', 'AZ', 1, '', NULL),
(49, 24, 3, 1, '2016-01-19', '0000-00-00', 1453255190, 'amy', 'harris', '', '', 'amy7harris@gmail.com', ' ', 'saline ', 'amy7', '31f18ad59e1fa08510b5cfba409255a9ecfc85e7', '2016-01-19', '', 'US', 'B', 130647, '', '', '', '', '', '', '', '', 0, '', '', '', 'MI', 1, '', NULL),
(50, 0, 2, 0, '2016-01-19', '2016-01-19', 1453240343, 'Juan', 'Paredes', '', 'male', 'jrpar10@gmail.com', '', 'Banning', 'xolos', 'd99714a2dc5445255c482ee565625a8fd46cb6ea', '1977-11-25', '486 w. Williams st.', 'United States', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'CA', 1, '', NULL),
(51, 25, 5, 1, '2016-01-21', '0000-00-00', 1453394885, 'Joseph', 'Fazio', '', '', 'joefaz@outlook.com', ' ', 'BOYNTON BEACH', 'joefaz', 'b1aa37944c00c8d66fb66043dd9d0b7dc7e5b22e', '2016-01-21', '', 'US', 'B', 805680, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(52, 26, 51, 0, '2016-01-21', '0000-00-00', 1453411928, 'Mike', 'Vilardi', '', '', 'prestigeitems@yahoo.com', ' ', 'Lake Worth', 'mikevilardi', '2dd642609840b0e66298ab74c8f2d6bf11e022f0', '2016-01-21', '', 'US', 'B', 559514, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(53, 0, 51, 0, '2016-01-22', '2016-01-22', 1453434211, 'Andre', ' Pacini', '', '', 'Andrepassing@gmail.com', ' ', 'West palm beach ', 'andre', '0cba830d8234aa206c169a9e8bb3850a5e605f85', '2016-01-21', '', 'US', 'F', 521708, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(54, 27, 35, 0, '2016-01-24', '0000-00-00', 1453644233, 'Matt', 'Mauriello', '', '', 'matmor50@gmail.com', ' ', 'Colts Neck', 'rhino', 'b3b81c08265e22b03f9493883fd7c2a278b3d75b', '2016-01-24', '', 'US', 'B', 358594, '', '', '', '', '', '', '', '', 0, '', '', '', 'NJ', 1, '', NULL),
(55, 0, 1, 0, '2016-01-25', '2016-01-25', 1453761451, 'Diana Carolina', 'Gallego Carvajal', '', '', 'carolinagallego0228@hotmail.com', '', 'Medellin', 'pauli', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-25', '', 'CO', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'Medellin', 1, '', NULL),
(56, 0, 1, 0, '2016-01-25', '2016-01-25', 1453763860, 'John', 'Castellano', '', '', 'john@luckyclub7.com', '', 'Fort Lauderdale', 'topdog', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-25', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(57, 28, 17, 1, '2016-01-26', '0000-00-00', 1453845605, 'Yosef', 'Solovey', '', '', 'joesolovey@gmail.com', ' ', 'Hollywood', 'justgotpaid', 'f16c391e77fdad0d7f12289d868958dff54f2bf7', '2016-01-26', '', 'US', 'B', 779351, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(58, 0, 1, 0, '2016-01-27', '2016-01-27', 1453900941, 'Fabian', 'Basabe Sr.', '', 'male', 'fbasabe@aol.com', '', 'Miami Beach, Florida', 'mycash', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-01', 'PO Box 546616', 'United States', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(59, 0, 58, 0, '2016-01-27', '2016-01-27', 1453934886, 'MaryAnn', 'Basabe', '', 'female', 'mkbasabe@aol.com', '', 'Surfside, Fl', 'kingkong', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-31', 'PO Box 546616', 'United States', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(60, 0, 58, 0, '2016-01-27', '2016-01-27', 1453935168, 'Michelina', 'Mottolese', '', '', 'mmidcorp@aol.com', '', 'Miami', 'motto', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-27', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(61, 30, 46, 1, '2016-01-27', '0000-00-00', 1453946410, 'Roberto', 'Faria', '', '', 'frobertoagent@gmail.com', ' ', 'Boca Raton', 'roberto', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2016-01-27', '', 'US', 'B', 327977, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(62, 31, 46, 0, '2016-01-27', '0000-00-00', 1453947599, 'Consuelo', 'Beckman', '', '', 'mamita26kids@yahoo.com', ' ', 'Boca Raton', 'mamita', 'df409e6387c9a39b8e2b78d44e52eaabfc865680', '2016-01-27', '', 'US', 'B', 889008, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(63, 0, 3, 0, '2016-01-28', '2016-01-28', 1453992241, 'Joe', 'Gray', '', '', 'joeharleyd77@gmail.com', '', 'boynton beach', 'jojo', '348162101fc6f7e624681b7400b085eeac6df7bd', '2016-01-28', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(64, 0, 19, 0, '2016-01-29', '2016-01-29', 1454041429, 'Tim', 'Phelps', '', '', 'Tim33185@aol.com', ' ', 'Cooper city', 'drphonefix', '52da8254fbbc9f5dc7f86bfa0f68e0d1bea2c5a2', '2016-01-28', '', 'US', 'F', 478500, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(65, 32, 19, 1, '2016-01-29', '0000-00-00', 1454074523, 'Jeffrey', 'Kushner', '', '', 'jkush123@aol.com', ' ', 'Aventura', 'kush', 'ec1e7fb8656dba32737acabc2e5a1fb2d02a973f', '2016-01-29', '', 'US', 'B', 432123, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(66, 33, 19, 0, '2016-01-29', '0000-00-00', 1454076066, 'Timothy', 'Phelps', '', '', 'tim@drphonefix.com', ' ', 'Cooper City', 'drphonefix1', 'ec1e7fb8656dba32737acabc2e5a1fb2d02a973f', '2016-01-01', '123 Elm', 'United States', 'B', 509880, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(67, 33, 34, 1, '2016-01-29', '0000-00-00', 1454079286, 'joan', 'soilleux', '', '', 'jsoilluex@gmail.com', ' ', 'jupiter', 'mamabear', '0a3c157920563b7680ef6f6d2f7736d3e5a75212', '2016-01-29', '', 'US', 'B', 533928, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(68, 34, 18, 0, '2016-01-29', '0000-00-00', 1454092506, 'sue ', 'velieri', '', '', 'suevel@gmail.com', ' ', 'boca raton', 'singersue', 'c0936ad98183a2949b901da9b540c693695389ee', '2016-01-29', '', 'US', 'B', 642987, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(69, 34, 18, 1, '2016-01-29', '0000-00-00', 1454095853, 'stuart', 'maesel', '', '', 'smaesel@gmail.com', ' ', 'boca raton', 'stuartedu', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-01-29', '', 'US', 'B', 636569, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(70, 0, 60, 0, '2016-01-29', '2016-01-29', 1454087916, 'Carolina ', 'Obregon', '', '', 'Carolinaobregon@aol.com', '', 'Miami Lakes ', 'carola74', 'cd43ba1d950ee3cc6d7a7ec05cb77ffaa68477f0', '2016-01-29', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(71, 35, 45, 1, '2016-01-30', '0000-00-00', 1454183939, 'ALVARO', 'AROSEMENA', '', '', 'alvarorios234@gmail.com', ' ', 'Boca Raton', 'alvaro', '9663a15ff807e5e49c18d6e1f5d31846e88f2fb9', '2016-01-30', '', 'US', 'B', 826656, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(72, 36, 45, 0, '2016-01-30', '0000-00-00', 1454185069, 'Vanessa ', 'Arosemena', '', '', 'vanearo14@gmail.com', ' ', 'Boca Raton', 'vane2016', 'c586d080ab3537fecebc8275300ccadd22bf19a8', '2016-01-30', '', 'US', 'B', 705765, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(73, 0, 2, 0, '2016-01-31', '2016-01-31', 1454223832, 'Libia Orietta', 'Gonzalez rolando', '', '', 'aleris2003@gmail.com', ' ', 'Miami', 'aleris', '3934c746b1677ed5caedb903e56b566d8c73240d', '2016-01-31', '', 'US', 'F', 178454, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(74, 0, 1, 0, '2016-02-01', '2016-02-01', 1454370213, 'Wendy', 'Arneson', '', '', 'arnesonw@aol.com', ' ', 'Hollywood', 'arnesonw', 'cb0e4aeec58c94b7878f190581da4303a9e298aa', '2016-02-01', '', 'US', 'F', 451084, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(75, 37, 11, 1, '2016-02-03', '0000-00-00', 1454513031, 'Michael', 'French', '', '', 'michaelefrench@gmail.com', ' ', 'clermont', 'frenchy', '0453cbd61197e8695bd2adc72c08bf5d0cca3355', '2016-02-03', '', 'US', 'B', 994734, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(76, 38, 5, 0, '2016-02-04', '0000-00-00', 1454586953, 'sandra', 'martinez', '', '', 'smartinezhernandez6@gmail.com', ' ', 'villavicenco', 'sierramike', '7161a2409087e392cf68559ddac9f1b64b07510c', '2016-02-04', '', 'CO', 'B', 104377, '', '', '', '', '', '', '', '', 0, '', '', '', 'laten america', 1, '', NULL),
(77, 38, 7, 1, '2016-02-04', '0000-00-00', 1454616952, 'Payman', 'Tabib', '', '', 'pt@primetime4loan.com', ' ', 'Weston', 'primetimemtg', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-02-04', '', 'US', 'B', 501292, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(78, 39, 12, 0, '2016-02-06', '0000-00-00', 1454800319, 'Brett', 'Anton', '', '', 'banton@gmail.com', ' ', 'Helena', 'banton', '6624c76a9e20fa28ea6a00145d7b566725c610ce', '2016-02-06', '', 'US', 'B', 291007, '', '', '', '', '', '', '', '', 0, '', '', '', 'AL', 1, '', NULL),
(79, 0, 59, 0, '2016-02-11', '2016-02-11', 1455206214, 'Daniel', 'Tropiano', '', '', 'onecallwilldoit@gmail.com', '', 'New York', 'onecall', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-02-11', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'NY', 1, '', NULL),
(80, 0, 40, 0, '2016-02-18', '2016-02-18', 1455828630, 'Carolyn', 'Mills', '', '', 'alternativemom@hotmail.com', '', 'St. Petersburg', 'cutie', '249232e54304f8076a0d0d1a4f97a6474cbaf079', '2016-02-18', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(81, 40, 18, 1, '2016-02-21', '0000-00-00', 1456100599, 'Faith', 'Mauro', '', '', 'faithsal@aol.com', ' ', 'Boca Raton', 'faithsal', '3e5213806de43a568def87b72f9f90788778e161', '2016-02-21', '', 'US', 'B', 502845, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL),
(82, 0, 40, 0, '2016-02-25', '2016-02-25', 1456435443, 'Marianne', 'Jones', '', '', 'sendit2paulj@hotmail.com', '', 'Merrit Island', 'brain dr', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2016-02-25', '', 'US', 'B', 0, '', '', '', '', '', '', '', '', 0, '', '', '', 'FL', 1, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_membership`
--

DROP TABLE IF EXISTS `user_membership`;
CREATE TABLE IF NOT EXISTS `user_membership` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `number` varchar(255) NOT NULL,
  `initial` int(4) NOT NULL,
  `current` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Truncate table before insert `user_membership`
--

TRUNCATE TABLE `user_membership`;
--
-- Dumping data for table `user_membership`
--

INSERT INTO `user_membership` (`id`, `user_id`, `payment_type`, `number`, `initial`, `current`) VALUES
(1, 1, 'Payza', '', 5, 5),
(2, 2, 'Payza', '', 5, 5),
(3, 3, 'Payza', '', 5, 5),
(4, 4, 'Bank', '', 5, 5),
(5, 5, 'Bank', '', 4, 4),
(6, 6, 'Bank', '', 5, 5),
(7, 7, 'Bank', '', 4, 4),
(8, 8, 'Bank', '', 4, 4),
(9, 9, 'Bank', '', 5, 5),
(10, 10, 'Bank', '', 4, 4),
(11, 11, 'Bank', '', 5, 5),
(12, 12, 'Bank', '', 5, 5),
(13, 13, 'E-pin', '', 5, 5),
(14, 14, 'E-pin', '', 4, 4),
(15, 15, 'E-pin', '', 5, 5),
(16, 16, 'E-pin', '', 5, 5),
(17, 17, 'E-pin', '', 3, 3),
(18, 18, 'E-pin', '', 3, 3),
(19, 19, 'E-pin', '', 3, 3),
(20, 20, 'E-pin', '', 2, 2),
(21, 21, 'E-pin', '', 4, 4),
(22, 22, 'E-pin', '', 4, 4),
(23, 23, 'E-pin', '', 4, 4),
(24, 24, 'E-pin', '', 4, 4),
(25, 25, 'Wire', '', 5, 5),
(26, 26, 'E-pin', '', 5, 5),
(27, 27, 'Free', '', 1, 1),
(28, 28, 'E-pin', '', 5, 5),
(29, 29, 'Payza', '', 5, 5),
(30, 30, 'E-pin', '', 5, 5),
(31, 31, 'Free', '', 1, 1),
(32, 32, 'E-pin', '', 5, 5),
(33, 33, 'E-pin', '', 4, 4),
(34, 34, 'E-pin', '', 3, 3),
(35, 35, 'E-pin', '', 3, 3),
(36, 36, 'E-pin', '', 5, 5),
(37, 37, 'Payza', '', 2, 2),
(38, 38, 'E-pin', '', 2, 2),
(39, 39, 'E-pin', '', 3, 3),
(40, 40, 'E-pin', '', 5, 5),
(41, 41, 'E-pin', '', 3, 3),
(42, 42, 'E-pin', '', 3, 3),
(43, 43, 'E-pin', '', 4, 4),
(44, 44, 'E-pin', '', 4, 4),
(45, 45, 'E-pin', '', 4, 4),
(46, 46, 'E-pin', '', 3, 3),
(47, 47, 'E-pin', '', 5, 5),
(48, 48, 'E-pin', '', 5, 5),
(49, 49, 'E-pin', '', 5, 5),
(50, 50, 'Payza', '', 5, 5),
(51, 51, 'E-pin', '', 5, 5),
(52, 52, 'E-pin', '', 5, 5),
(53, 53, 'Free', '', 1, 1),
(54, 54, 'E-pin', '', 3, 3),
(55, 55, 'E-pin', '', 5, 5),
(56, 56, 'E-pin', '', 5, 5),
(57, 57, 'E-pin', '', 5, 5),
(58, 58, 'E-pin', '', 4, 4),
(59, 59, 'Bank', '', 4, 4),
(60, 60, 'Bank', '', 4, 4),
(61, 61, 'E-pin', '', 3, 3),
(62, 62, 'E-pin', '', 3, 3),
(63, 63, 'Bank', '', 3, 3),
(64, 64, 'Free', '', 1, 1),
(65, 65, 'E-pin', '', 2, 2),
(66, 66, 'E-pin', '', 3, 3),
(67, 67, 'E-pin', '', 3, 3),
(68, 68, 'E-pin', '', 2, 2),
(69, 69, 'E-pin', '', 2, 2),
(70, 70, 'Bank', '', 2, 2),
(71, 71, 'E-pin', '', 3, 3),
(72, 72, 'E-pin', '', 2, 2),
(73, 73, 'Free', '', 1, 1),
(74, 74, 'Free', '', 1, 1),
(75, 75, 'E-pin', '', 5, 5),
(76, 76, 'E-pin', '', 2, 2),
(77, 77, 'E-pin', '', 3, 3),
(78, 78, 'E-pin', '', 5, 5),
(79, 79, 'Bank', '', 2, 2),
(80, 80, 'Bank', '', 5, 5),
(81, 81, 'E-pin', '', 3, 3),
(82, 82, 'Bank', '', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

DROP TABLE IF EXISTS `user_message`;
CREATE TABLE IF NOT EXISTS `user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `message_to` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `user_message`
--

TRUNCATE TABLE `user_message`;
-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

DROP TABLE IF EXISTS `wallet`;
CREATE TABLE IF NOT EXISTS `wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `travel` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Truncate table before insert `wallet`
--

TRUNCATE TABLE `wallet`;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

DROP TABLE IF EXISTS `usermeta`;
CREATE TABLE `usermeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_name` varchar(50) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `echeck_ipn`;
CREATE TABLE `echeck_ipn` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `customername` varchar(100) NOT NULL,
  `customeraddress1` varchar(100) NOT NULL,
  `customeraddress2` varchar(100) DEFAULT NULL,
  `customercity` varchar(100) NOT NULL,
  `customerstate` varchar(100) NOT NULL,
  `customerzip` int(11) NOT NULL,
  `customerphone` bigint(15) NOT NULL,
  `customeremail` varchar(50) NOT NULL,
  `checkstatus` varchar(50) NOT NULL,
  `product` varchar(20) NOT NULL,
  `statusmsg` varchar(255) NOT NULL,
  `customerid` bigint(11) NOT NULL,
  `transactionid` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` tinyint(2) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `membership_upgrade`;
CREATE TABLE `membership_upgrade` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `current_membership` tinyint(2) NOT NULL,
  `upgrade_membership` tinyint(2) NOT NULL,
  `requested_date` datetime NOT NULL,
  `upgraded_date` datetime DEFAULT '0000-00-00 00:00:00',
  `payment_method` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `income_relation`;
CREATE TABLE `income_relation` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `second_step_income_id` bigint(11) NOT NULL,
  `third_step_income_id` bigint(11) NOT NULL,
  `user_id` bigint(11) NOT NULL,
  `level` bigint(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `payments` (`id`, `user_id`, `payment_method`, `payment_type`, `amount`, `date_created`) VALUES
(1, 1, 'Payza', '', '999', '2015-12-23 01:00:00'),
(2, 2, 'Payza', '', '999', '2015-12-23 05:00:00'),
(3, 3, 'Payza', '', '999', '2015-12-23 10:00:00'),
(4, 4, 'Bank', '', '999', '2016-01-06 01:06:49'),
(5, 5, 'Bank', '', '499', '2016-01-06 01:09:46'),
(6, 6, 'Bank', '', '999', '2016-01-06 01:13:29'),
(7, 7, 'Bank', '', '499', '2016-01-06 01:17:07'),
(8, 8, 'Bank', '', '499', '2016-01-06 01:24:54'),
(9, 9, 'Bank', '', '999', '2016-01-06 01:30:25'),
(10, 10, 'Bank', '', '499', '2016-01-06 01:35:35'),
(11, 11, 'Bank', '', '999', '2016-01-06 01:45:25'),
(12, 12, 'Bank', '', '999', '2016-01-06 01:47:30'),
(13, 13, 'E-pin', '', '999', '2016-01-06 04:15:15'),
(14, 14, 'E-pin', '', '499', '2016-01-06 23:51:30'),
(15, 15, 'E-pin', '', '999', '2016-01-07 04:50:32'),
(16, 16, 'E-pin', '', '999', '2016-01-07 06:16:21'),
(17, 17, 'E-pin', '', '999', '2016-01-07 08:08:54'),
(18, 18, 'E-pin', '', '299', '2016-01-07 08:33:15'),
(19, 19, 'E-pin', '', '299', '2016-01-07 08:56:33'),
(20, 20, 'E-pin', '', '99', '2016-01-07 09:03:24'),
(21, 21, 'E-pin', '', '499', '2016-01-08 02:07:11'),
(22, 22, 'E-pin', '', '499', '2016-01-08 02:14:10'),
(23, 23, 'E-pin', '', '499', '2016-01-08 02:46:38'),
(24, 24, 'E-pin', '', '499', '2016-01-08 05:48:29'),
(25, 25, 'Wire', '', '999', '2016-01-09 01:34:48'),
(26, 26, 'E-pin', '', '999', '2016-01-09 02:06:19'),
(27, 27, 'Free', '', '0', '2016-01-09 03:42:09'),
(28, 28, 'E-pin', '', '999', '2016-01-10 05:32:28'),
(29, 29, 'Payza', '', '999', '2016-01-10 07:40:55'),
(30, 30, 'E-pin', '', '999', '2016-01-12 02:04:40'),
(31, 31, 'Free', '', '0', '2016-01-12 02:48:10'),
(32, 32, 'E-pin', '', '999', '2016-01-13 04:49:22'),
(33, 33, 'E-pin', '', '499', '2016-01-13 23:55:19'),
(34, 34, 'E-pin', '', '299', '2016-01-14 01:53:33'),
(35, 35, 'E-pin', '', '299', '2016-01-14 02:21:23'),
(36, 36, 'E-pin', '', '999', '2016-01-14 02:26:00'),
(37, 37, 'Payza', '', '99', '2016-01-12 06:09:34'),
(38, 38, 'E-pin', '', '99', '2016-01-14 05:28:19'),
(39, 39, 'E-pin', '', '999', '2016-01-15 01:09:31'),
(40, 40, 'E-pin', '', '999', '2016-01-15 02:39:47'),
(41, 41, 'E-pin', '', '299', '2016-01-16 07:02:09'),
(42, 42, 'E-pin', '', '299', '2016-01-16 07:03:27'),
(43, 43, 'E-pin', '', '499', '2016-01-16 07:04:44'),
(44, 44, 'E-pin', '', '499', '2016-01-16 07:08:34'),
(45, 45, 'E-pin', '', '499', '2016-01-19 00:29:28'),
(46, 46, 'E-pin', '', '299', '2016-01-19 00:31:27'),
(47, 47, 'E-pin', '', '999', '2016-01-19 00:32:57'),
(48, 48, 'E-pin', '', '999', '2016-01-19 00:42:08'),
(49, 49, 'E-pin', '', '999', '2016-01-20 09:59:50'),
(50, 50, 'Payza', '', '999', '2016-01-20 05:52:23'),
(51, 51, 'E-pin', '', '999', '2016-01-22 00:48:05'),
(52, 52, 'E-pin', '', '999', '2016-01-22 05:32:08'),
(53, 53, 'Free', '', '0', '2016-01-22 11:43:31'),
(54, 54, 'E-pin', '', '299', '2016-01-24 22:03:53'),
(55, 55, 'E-pin', '', '999', '2016-01-26 06:37:31'),
(56, 56, 'E-pin', '', '999', '2016-01-26 07:17:40'),
(57, 57, 'E-pin', '', '999', '2016-01-27 06:00:05'),
(58, 58, 'E-pin', '', '999', '2016-01-27 21:22:21'),
(59, 59, 'Bank', '', '499', '2016-01-28 06:48:06'),
(60, 60, 'Bank', '', '499', '2016-01-28 06:52:48'),
(61, 61, 'E-pin', '', '299', '2016-01-28 10:00:10'),
(62, 62, 'E-pin', '', '299', '2016-01-28 10:19:59'),
(63, 63, 'Bank', '', '299', '2016-01-28 22:44:01'),
(64, 64, 'Free', '', '0', '2016-01-29 12:23:49'),
(65, 65, 'E-pin', '', '99', '2016-01-29 21:35:23'),
(66, 66, 'E-pin', '', '299', '2016-01-29 22:01:06'),
(67, 67, 'E-pin', '', '299', '2016-01-29 22:54:46'),
(68, 68, 'E-pin', '', '99', '2016-01-30 02:35:06'),
(69, 69, 'E-pin', '', '99', '2016-01-30 03:30:53'),
(70, 70, 'Bank', '', '99', '2016-01-30 01:18:36'),
(71, 71, 'E-pin', '', '299', '2016-01-31 03:58:59'),
(72, 72, 'E-pin', '', '99', '2016-01-31 04:17:49'),
(73, 73, 'Free', '', '0', '2016-01-31 15:03:52'),
(74, 74, 'Free', '', '0', '2016-02-02 07:43:33'),
(75, 75, 'E-pin', '', '999', '2016-02-03 23:23:51'),
(76, 76, 'E-pin', '', '99', '2016-02-04 19:55:53'),
(77, 77, 'E-pin', '', '299', '2016-02-05 04:15:52'),
(78, 78, 'E-pin', '', '999', '2016-02-07 07:11:59'),
(79, 79, 'Bank', '', '99', '2016-02-11 23:56:54'),
(80, 80, 'Bank', '', '999', '2016-02-19 04:50:30'),
(81, 81, 'E-pin', '', '299', '2016-02-22 08:23:19'),
(82, 82, 'Bank', '', '999', '2016-02-26 05:24:03');

DROP TABLE IF EXISTS `builder`;
CREATE TABLE `builder` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;