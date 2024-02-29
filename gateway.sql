-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 29, 2024 at 03:04 AM
-- Server version: 8.0.36-0ubuntu0.20.04.1
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gateway`
--

-- --------------------------------------------------------

--
-- Table structure for table `accumulated_fee`
--

CREATE TABLE `accumulated_fee` (
  `network` varchar(100) NOT NULL,
  `amount` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accumulated_fee`
--

INSERT INTO `accumulated_fee` (`network`, `amount`) VALUES
('BNB_Gas', '0'),
('BSC', '0'),
('ETHER', '0'),
('ETH_Gas', '0'),
('TRON', '0'),
('TRX_Gas', '0');

-- --------------------------------------------------------

--
-- Table structure for table `address_list`
--

CREATE TABLE `address_list` (
  `address` varchar(100) NOT NULL,
  `private_key` varchar(100) NOT NULL,
  `public` longtext NOT NULL,
  `type` enum('TRON','ETHER','BSC') NOT NULL,
  `balance` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `approved` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address_list`
--

INSERT INTO `address_list` (`address`, `private_key`, `public`, `type`, `balance`, `currency`, `status`, `approved`) VALUES
('08905e98220e577af7daf87c5e9ad2dbfec1a21f', 'b553dda368a97f87ae070d0bee5c18678dd3fa850056639878f70fb241dc93f', '71648e12a45c5fc379ef17d7c758e8c5cb130a20177826e564e7d7b66e0c1142656067b73734b387f762c2d310e6281ef448e6317dd244b3c056c31b1b84076a', 'BSC', '0.000000000000000000', 'LEGO', 'active', 1),
('2eb575df67f8f32fd1fd480e571a30411a426535', 'd1e16cf65da9c91ae64ae7f755062495eb7d78f36bc595a54bd7dfd1bdd0b65', '67beba7b09e0555264d9721312bf6c250aec153dda889c067a124b07c6b7721eddf1ca1f064b034745c07b8f1f66ab3043c5fe38890f4235b52a1330dd637dd9', 'ETHER', '0', 'USDT', 'active', 1),
('TC4kvHgfYsvunHbWj6Ha5vjbTHcppdhZtq', 'd510081467d01249df7cd054e21a71ee82d7cd51d00fe2e3cd5394e71a437e3', '04a36d992c2274a3cc04af325a39f23796297dfc196a2e05f3e63b9cee0021b14f94cb63acaaa573aee6d30df2feb75ab1f232daec02f5a4bb2f55046e688151a8', 'TRON', '0', 'USDT', 'active', 0);

-- --------------------------------------------------------

--
-- Table structure for table `app`
--

CREATE TABLE `app` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `secret` varchar(1000) NOT NULL,
  `balance` varchar(100) NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_balance`
--

CREATE TABLE `app_balance` (
  `id` int NOT NULL,
  `app_id` int NOT NULL,
  `network` varchar(111) NOT NULL,
  `currency` varchar(111) NOT NULL,
  `balance` varchar(111) NOT NULL,
  `date_created` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `app_config`
--

CREATE TABLE `app_config` (
  `config_id` int NOT NULL,
  `app_id` int NOT NULL,
  `enabled` varchar(11) NOT NULL,
  `withdraw_enable` int NOT NULL,
  `withdraw_limit` int NOT NULL,
  `withdraw_daily` int NOT NULL,
  `callback_url` varchar(1000) NOT NULL,
  `registered_ip` varchar(100) NOT NULL,
  `update_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `energy_address`
--

CREATE TABLE `energy_address` (
  `id` int NOT NULL,
  `address` varchar(1000) NOT NULL,
  `private_key` varchar(1000) NOT NULL,
  `balance` varchar(1000) NOT NULL,
  `network` enum('TRON','ETHER','BSC') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `energy_address`
--

INSERT INTO `energy_address` (`id`, `address`, `private_key`, `balance`, `network`) VALUES
(1, 'TYZVvF8tYBz56p6f6HusfepMaU2SVihHqY', 'b81977358fe26ea5bf81dd1cf8acdbaaea5b10edd38e67a8ec57a42e4a9a913', '0', 'TRON'),
(2, '0x0a11b643927b5b843edb3c2a068f73b446a9d934', '25e37ad2696407b70e1bd464e16589e36e0edd17f434260d55a9cfa2286e8c7', '0', 'ETHER'),
(3, '0x18f5F26CA0eCeA15018Bc5a351CF012c28083d7A', '6fddffa5bd3b9993b059fb4d6d07726026d504a7674c12b7e220c83474eba06', '0', 'BSC');

-- --------------------------------------------------------

--
-- Table structure for table `master_address`
--

CREATE TABLE `master_address` (
  `id` int NOT NULL,
  `address` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `priv_key` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `public` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `balance` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `network` enum('TRON','ETHER','BSC') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `master_address`
--

INSERT INTO `master_address` (`id`, `address`, `priv_key`, `public`, `balance`, `network`, `status`, `created_at`) VALUES
(1, '74143a8dd9d3cc3d8f4ad3051d18728e3c44ef95', '5ce8b17c8e3812e46d9288edfc2eeac08b0e144d88b7af76826c558ff487897', 'a0a822f487c16300617570502a1ffae25982e0dba2e5beeae75e48e28a07359c2ee77b70b080487a383de4a0c8a2ac59e2acc281078c56eba9b79840fbf552d5', '0', 'ETHER', 'active', '1654630902'),
(2, 'TFV5fDXAMYuVtKjjAZ861dUpMYTX8No1Fd', '624271b115415256308c26b2d4d7d6111cf496facddd059b3e8b12aa4149b5c', '048ec298e99c7ebd8da0976b6048de5b0da11eed2ad65a9edea898e48e30b7ebc3f359b3463c2411c7b50550d2b76bdee93752fd5872d66ca50242382ca574ffb0', '0', 'TRON', 'active', '1655333163'),
(4, '36795d8b2dd1295917634d7e063872c9d5dbc730', '9e0f3a94323bfd3354139282d1293478701a637597c2ddff395796d0f93db0c\r\n', 'bf38d41fe156858afca097473c25d4d5003a32c5fd3b1ea4d8604874b5283821753215cce3571da4029677bc49254e45d31e7d54c05a5ee39100d204d2f5b613', '0', 'BSC', 'active', '1656353887');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int NOT NULL,
  `path` text NOT NULL,
  `is_auth` tinyint(1) NOT NULL DEFAULT '0',
  `is_guest` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('control','view','all') NOT NULL DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `path`, `is_auth`, `is_guest`, `type`) VALUES
(1, 'login', 0, 1, 'all'),
(2, 'register', 0, 1, 'all'),
(9, 'account', 1, 0, 'all'),
(12, 'reset_pass', 1, 0, 'all'),
(13, 'password_reset', 1, 0, 'all'),
(26, 'deposit', 1, 0, 'all'),
(27, 'transfer_profit', 1, 0, 'all'),
(28, 'change_pp', 1, 0, 'all'),
(29, 'trading_view', 1, 0, 'all'),
(30, 'finalization', 1, 0, 'all'),
(31, 'check_usdt', 1, 0, 'control'),
(32, 'withdraw', 1, 0, 'all'),
(33, 'tx_history', 1, 0, 'all'),
(34, 'mt4_create', 1, 0, 'all'),
(35, 'mt4_reset_pass', 1, 0, 'all'),
(36, 'mt4_tf', 1, 0, 'all'),
(37, 'support', 1, 0, 'all'),
(38, 'create_message', 1, 0, 'control'),
(39, 'message', 1, 0, 'all'),
(40, 'msg', 1, 0, 'all'),
(41, 'send_message', 1, 0, 'control'),
(42, 'partnership', 1, 0, 'all'),
(43, 'create_mt4_acc', 1, 0, 'all'),
(44, 'meta_getAccount', 1, 0, 'control'),
(45, 'invoice', 1, 0, 'all'),
(46, 'check_transaction', 1, 0, 'control'),
(47, 'tf_mt4', 1, 0, 'control'),
(48, 'change_pw_meta', 1, 0, 'control'),
(49, 'purchase_ea', 1, 0, 'all'),
(50, 'profile', 1, 0, 'control'),
(51, 'sendOTP', 1, 0, 'control'),
(52, 'kyc', 1, 0, 'all'),
(53, 'kyc_back', 1, 0, 'control'),
(54, 'create_merchant', 1, 0, 'all'),
(55, 'transactions', 1, 0, 'all'),
(56, 'transaction_detail', 1, 0, 'all'),
(57, 'app_list', 1, 0, 'all'),
(58, 'app_config', 1, 0, 'all'),
(59, 'config_process', 1, 0, 'all'),
(60, 'cashout_detail', 1, 0, 'all'),
(61, 'setting', 1, 0, 'all'),
(62, 'tx_list', 1, 0, 'all'),
(63, 'update_account', 1, 0, 'all'),
(64, 'inject', 1, 0, 'all'),
(65, 'inject_detail', 1, 0, 'all');

-- --------------------------------------------------------

--
-- Table structure for table `supported_currency`
--

CREATE TABLE `supported_currency` (
  `id` int NOT NULL,
  `currency_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `currency_network` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `currency_contract` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `supported_currency`
--

INSERT INTO `supported_currency` (`id`, `currency_name`, `currency_network`, `currency_contract`) VALUES
(1554, 'USDT', 'TRON', 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t'),
(1555, 'USDT', 'ETHER', '0xdac17f958d2ee523a2206206994597c13d831ec7'),
(3884, 'LEGO', 'BSC', '0x1F98BD9CB8Db314Ced46Dc43C0a91a1F9aDAD4F5');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `trx_id` int NOT NULL,
  `app_id` varchar(111) NOT NULL,
  `address` varchar(100) NOT NULL,
  `amount` varchar(111) NOT NULL,
  `real_amount` varchar(100) NOT NULL,
  `usd_amount` varchar(100) NOT NULL,
  `currency` varchar(111) NOT NULL,
  `type` enum('TRON','ETHER','BSC') NOT NULL,
  `status` enum('Waiting','Complete','Expired') NOT NULL,
  `tx_hash` varchar(1000) NOT NULL,
  `expired_at` varchar(111) NOT NULL,
  `created_at` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_hash`
--

CREATE TABLE `transaction_hash` (
  `id` int NOT NULL,
  `hash` longtext NOT NULL,
  `tx_id` int NOT NULL,
  `address` text NOT NULL,
  `value` varchar(100) NOT NULL,
  `date_created` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `status` enum('unverified','verified','suspended') NOT NULL,
  `created_at` varchar(111) NOT NULL,
  `updated_at` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `company`, `status`, `created_at`, `updated_at`) VALUES
(1, 'demo@demo.com', 'demo', 'demo', 'demo', 'verified', '111111111', '111111111');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw`
--

CREATE TABLE `withdraw` (
  `id` int NOT NULL,
  `app_id` int NOT NULL,
  `to_address` longtext NOT NULL,
  `amount` varchar(100) NOT NULL,
  `from_api` varchar(100) NOT NULL,
  `status` enum('Complete','Pending','Cancelled') NOT NULL,
  `tx_hash` longtext NOT NULL,
  `created_at` varchar(100) NOT NULL,
  `network` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accumulated_fee`
--
ALTER TABLE `accumulated_fee`
  ADD PRIMARY KEY (`network`);

--
-- Indexes for table `address_list`
--
ALTER TABLE `address_list`
  ADD PRIMARY KEY (`address`);

--
-- Indexes for table `app`
--
ALTER TABLE `app`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_balance`
--
ALTER TABLE `app_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_config`
--
ALTER TABLE `app_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `energy_address`
--
ALTER TABLE `energy_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_address`
--
ALTER TABLE `master_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supported_currency`
--
ALTER TABLE `supported_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`trx_id`);

--
-- Indexes for table `transaction_hash`
--
ALTER TABLE `transaction_hash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw`
--
ALTER TABLE `withdraw`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app`
--
ALTER TABLE `app`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `app_balance`
--
ALTER TABLE `app_balance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `app_config`
--
ALTER TABLE `app_config`
  MODIFY `config_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `energy_address`
--
ALTER TABLE `energy_address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_address`
--
ALTER TABLE `master_address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `supported_currency`
--
ALTER TABLE `supported_currency`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3886;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `trx_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_hash`
--
ALTER TABLE `transaction_hash`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `withdraw`
--
ALTER TABLE `withdraw`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
