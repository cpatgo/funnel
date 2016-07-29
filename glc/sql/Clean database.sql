-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 22, 2016 at 11:14 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_user`, `username`, `password`) VALUES
(0, 'Learncash', '693a8f1aa352fc0215223a45b465055953aa586b');

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL,
  `parent_menu` varchar(255) NOT NULL,
  `menu_file` varchar(255) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=119 ;

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
(121, 'Pending Member Upgrade', 'accounting', 'pending_upgrade',  1);

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `ads_category`
--

CREATE TABLE IF NOT EXISTS `ads_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catg_name` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `advertisement`
--

CREATE TABLE IF NOT EXISTS `advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `advertisement`
--

INSERT INTO `advertisement` (`id`, `title`, `content`, `img`) VALUES
(1, 'phpMyAdmin 3.1.3.1 -\n    192.168.1.101', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `app_members`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `authorize_ipn`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `board`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `board`
--

INSERT INTO `board` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 0, 0, 0, 0, 0, '2015-06-10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_break`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `board_break`
--

INSERT INTO `board_break` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_fifth`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `board_break_fifth`
--

INSERT INTO `board_break_fifth` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_fourth`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `board_break_fourth`
--

INSERT INTO `board_break_fourth` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_second`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `board_break_second`
--

INSERT INTO `board_break_second` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600);

-- --------------------------------------------------------

--
-- Table structure for table `board_break_sixth`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `board_break_third`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `board_break_third`
--

INSERT INTO `board_break_third` (`id`, `user_id`, `board_b_id`, `real_parent`, `qualified_id`, `level`, `date`, `time`) VALUES
(1, 1, 1, 0, 0, 0, '2013-06-11', 1433952000),
(2, 2, 1, 1, 0, 0, '2015-06-20', 1434729600),
(3, 3, 1, 1, 0, 0, '2015-06-30', 1434729600);

-- --------------------------------------------------------

--
-- Table structure for table `board_fifth`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `board_fifth`
--

INSERT INTO `board_fifth` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 0, 0, 0, 0, 0, '2015-06-10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_fourth`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `board_fourth`
--

INSERT INTO `board_fourth` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 0, 0, 0, 0, 0, '2015-06-10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_second`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `board_second`
--

INSERT INTO `board_second` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 0, 0, 0, 0, 0, '2015-06-10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `board_seven`
--

CREATE TABLE IF NOT EXISTS `board_seven` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `board_sixth`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `board_third`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `board_third`
--

INSERT INTO `board_third` (`board_id`, `parent_id`, `real_parent`, `pos1`, `pos2`, `pos3`, `pos4`, `pos5`, `pos6`, `pos7`, `level`, `date`, `time`, `mode`) VALUES
(1, 0, 0, 1, 2, 3, 0, 0, 0, 0, 0, '2015-06-10', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `Country` varchar(100) NOT NULL,
  `State` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `classified_info`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `deduct_amount`
--

CREATE TABLE IF NOT EXISTS `deduct_amount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` text,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `no_of_user` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dwolla`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `edata_ipn`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `e_voucher`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `e_voucher_transfer`
--

CREATE TABLE IF NOT EXISTS `e_voucher_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_id` int(11) NOT NULL,
  `e_voucher` int(11) NOT NULL,
  `date` date NOT NULL,
  `note` text NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `form_data`
--

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
-- Dumping data for table `form_data`
--

INSERT INTO `form_data` (`id`, `user_id`, `data1`, `data2`, `data3`, `data4`, `data5`, `date`) VALUES
(1, 1, '', '', '', '', '', '2013-09-22');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `income_process`
--

CREATE TABLE IF NOT EXISTS `income_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `income_process`
--

INSERT INTO `income_process` (`id`, `mode`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `income_reserve`
--

CREATE TABLE IF NOT EXISTS `income_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `income_id` int(11) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `reserve` decimal(10,2) NOT NULL,
  `reserve_percentage` int(10) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `level_board_income`
--

CREATE TABLE IF NOT EXISTS `level_board_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NOT NULL,
  `income` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE IF NOT EXISTS `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership` varchar(255) NOT NULL,
  `amount` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

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

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL,
  `parent_menu` varchar(255) NOT NULL,
  `menu_file` varchar(255) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

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

CREATE TABLE IF NOT EXISTS `merchants` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `merchant` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `img_url` varchar(200) DEFAULT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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

CREATE TABLE IF NOT EXISTS `merchant_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `money_transfer`
--

CREATE TABLE IF NOT EXISTS `money_transfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL,
  `from_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(100) NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

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
(75, 'e_data_cutoff', ''),
(76, 'authorize_net_cutoff', ''),
(77, 'authorize_net_2_cutoff', ''),
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

-- --------------------------------------------------------

--
-- Table structure for table `payment_info`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `payment_information`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_mode` varchar(50) NOT NULL,
  `logo_filename` varchar(100) DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `plan_setting`
--

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

CREATE TABLE IF NOT EXISTS `point_wallet` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_point` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

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

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `products_name` varchar(255) NOT NULL,
  `prod_amount` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `products_name`, `prod_amount`) VALUES
(1, 'Training centre', 500);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE IF NOT EXISTS `purchase_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `date_approved` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_vouchers`
--

CREATE TABLE IF NOT EXISTS `purchase_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reg_voucher`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `request` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `security_password`
--

CREATE TABLE IF NOT EXISTS `security_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `security_password` int(11) NOT NULL,
  `date` date NOT NULL,
  `mode` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

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
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `welcome_message`, `forget_password_message`, `payout_generate_message`, `email_welcome_message`, `direct_member_message`, `payment_request_message`, `payment_transfer_message`, `member_to_member_message`, `epin_generate_message`, `user_pin_generate_message`, `parent_limit`, `registration_fees`, `min_transfer`, `min_withdrawal`, `upgrade_membership_fees`, `direct_member_income`, `pin_cost`, `admin_tax`, `withdrawal_tax`, `first_board_name`, `first_board_income_1`, `first_board_income_2`, `first_board_point`, `first_board_join`, `second_board_name`, `second_board_income_1`, `second_board_income_2`, `second_board_point`, `second_board_join`, `third_board_name`, `third_board_income_1`, `third_board_income_2`, `third_board_point`, `third_board_join`, `fourth_board_name`, `fourth_board_income_1`, `fourth_board_income_2`, `fourth_board_point`, `fourth_board_join`, `five_board_name`, `five_board_income_1`, `five_board_income_2`, `five_board_point`, `five_board_join`, `six_board_name`, `six_board_income_1`, `six_board_income_2`, `six_board_point`, `min_q_referrals`, `min_free_referrals`, `q_time`) VALUES
(1, '<p>WELCOME TO <strong><strong>Global Learning Center®™</strong></strong> TEAM.</p>\r\n\r\n<p>Congratulations upon your decision to join our team.</p>\r\n\r\n<p>Our goal is to provide public ads with outstanding ad appreciation. You were accepted in with other applicants because we feel that your qualifications and personality will contribute more to our goal of representing outstanding excellence. Equally important, we think that you will be an enthusiastic, friendly and energetic Associate who will help us bring distinction to <strong>Global Learning Center®™</strong> team.</p>\r\n\r\n<p>Each of us—your Co-Associates, General Management and Owners—want you to succeed in your new ads. We extend to you a pledge of 100% cooperation in gaining your trust, loyalty and friendship. Just meet us halfway. That adds up to a total of 150%. With that much input from both of us, there is no way we can fail.</p>\r\n\r\n<p>We want your introduction to <strong>Global Learning Center®™</strong> to be a very personal one. You will be taken on a tour of the back-office, enjoy a talk with your Sponsor to review the rules of your particular the introduced to your Associates, including your General Management. Throughout this introductory period, feel free to ask questions on any point that is not clear to you.</p>\r\n\r\n<p><strong>Global Learning Center®™</strong> has given rise to many new corporations ads, one of which is yours. Although you may not be employed directly by <strong>Global Learning Center®™</strong>, you adhere to the policies specified on this site.</p>\r\n\r\n<p>You will be meeting and working with a team of skilled and talented individuals who all have a common interest – helping care for the customer’s needs. In the eyes of the customer you represent the ads. Your appearance, actions and personality reflect the commitment we share in taking care of the needs of our customers and associates. Together we can make things happen because we care about you and your customers.</p>\r\n\r\n<p>On <strong>Global Learning Center®™</strong> website is the basic information you will need to review. Read it carefully and refer to it often. However, <strong>Global Learning Center®™</strong> Rules Tab is the source of up-to-date policy. It takes precedence over shared information. <strong>Global Learning Center®™</strong> will advise you of policy changes affecting site. If you have any unanswered questions, please do not hesitate to make them known to us.</p>\r\n\r\n<p>Updated June 2014<br />\r\nSincerely, <br />\r\n<strong>Global Learning Center®™</strong> 1001 RTE. 636, Harvey NB E6K3G5, <br />\r\nSkype Shirleyadams60, <br />\r\nE-mail info@Kenect.org</p>', 'Welcome User #username , Your Password Is: #password , thanks. ', 'Hello user #pay_generate_username , Your payment of amount #amount has generated.', 'Welcome #username !\r\nYour Pin is : #user_pin .', 'Hello Username #real_parent_username , You have added new user #new_username as Direct Member.', 'Hello Username #pay_request_username , You have request for amount #request_amount to Global Learning Center.', 'Hello Username #pay_request_username , Your payment of amount $ #request_amount USD has been transferred by Global Learning Center.', 'Hello User #requested_user , You have received amount of $ #request_amount USD from the User #payee_username !', 'Hello Username #payee_epin_username  , You have received e-Voucher of amount #epin_amount from #epin_generate_username and your e-Voucher is #epin .', 'Hello User #requested_user , You have received amount of $ #request_amount USD from the User #payee_username !', 5, 20, 10, 0, 50, 0, 0, 0, 0, 'Level 1', 0, 125, 0, 99, 'Level 2', 0, 200, 0, 299, 'Level 3', 0, 400, 0, 499, 'Level 4', 0, 1000, 0, 999, 'Level 5', 2500, 2500, 0, 2499, '', 0, 0, 0, 2, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `system_date`
--

CREATE TABLE IF NOT EXISTS `system_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `system_date`
--

INSERT INTO `system_date` (`id`, `sys_date`) VALUES
(1, '2015-06-10');

-- --------------------------------------------------------

--
-- Table structure for table `temp_stp`
--

CREATE TABLE IF NOT EXISTS `temp_stp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_users`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usermeta`
--

CREATE TABLE IF NOT EXISTS `usermeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_name` varchar(50) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `real_parent` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `date` date NOT NULL,
  `activate_date` date DEFAULT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `parent_id`, `real_parent`, `position`, `date`, `activate_date`, `time`, `f_name`, `l_name`, `user_img`, `gender`, `email`, `phone_no`, `city`, `username`, `password`, `dob`, `address`, `country`, `type`, `user_pin`, `beneficiery_name`, `ac_no`, `bank`, `branch`, `bank_code`, `payza_account`, `tax_id`, `pan_no`, `pin_code`, `father_name`, `district`, `state`, `provience`, `optin_affiliate`, `dwolla_id`, `description`) VALUES
(1, 0, 0, 0, '2015-12-23', '0000-00-00', 1450803600, 'Kage Enterprises', 'Inc.', '13458848761', 'male', 'LuckyClub7@gmail.com', '9548028826', 'Covington', 'joinnow', '0456d7eb01e9236d47898b09bc9c8f5ea3df8054', '1970-05-01', '519 E 19th Avenue Covington, LA 70433', 'United States', 'B', 286027, '', '2147483647', '', '', '', 'christopher.cowart@gmail.com', '', '', 175001, '', '', 'California', '', 0, '', NULL),
(2, 1, 1, 0, '2015-12-23', '0000-00-00', 1450818000, 'Robert', 'Kemper', '', 'male', 'rwkemper@gmail.com', '3522557717', 'MANDI', 'bkemper', '50eca6dc968d31bd0b2cb9d49cf2f91072c3f8da', '1975-06-06', '1385 Lake Avenue Clermont, FL. 34711', 'US', 'B', 933023, '', '2147483648', '', '', '', '', '', '', 175002, '', '', 'California', '', 0, '', NULL),
(3, 2, 2, 0, '2015-12-23', '0000-00-00', 1450836000, 'max', 'well', '', 'male', 'BCMKTAMER@aol.com', '5613066999', 'boca raton', 'Boston', '20a54b10d3333ded6355fdecd0c0160058873848', '0000-00-00', '3100 South Dixie Highway G76\r\nBoca Raton, FL.33432', 'United States', 'B', 933023, '', '2147483649', '', '', '', '', '', '', 175003, '', '3100 s dixie highway', 'California', '', 0, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_membership`
--

CREATE TABLE IF NOT EXISTS `user_membership` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `number` varchar(255) NOT NULL,
  `initial` int(4) NOT NULL,
  `current` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_membership`
--

INSERT INTO `user_membership` (`id`, `user_id`, `payment_type`, `number`, `initial`, `current`) VALUES
(1, 1, 'Payza', '', 5, 5),
(2, 2, 'Payza', '', 5, 5),
(3, 3, 'Payza', '', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

CREATE TABLE IF NOT EXISTS `user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_by` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `message_to` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE IF NOT EXISTS `wallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `travel` double NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

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

DROP TABLE IF EXISTS `builder`;
CREATE TABLE `builder` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;