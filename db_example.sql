-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 04. Feb, 2022 23:01 PM
-- Tjener-versjon: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mafiosov2`
--

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `accounts`
--

CREATE TABLE `accounts` (
  `ACC_id` int(255) NOT NULL,
  `ACC_username` varchar(255) NOT NULL,
  `ACC_password` varchar(255) NOT NULL,
  `ACC_mail` varchar(255) NOT NULL,
  `ACC_register_date` varchar(255) NOT NULL,
  `ACC_ip_register` varchar(255) NOT NULL,
  `ACC_type` int(255) NOT NULL,
  `ACC_last_active` varchar(255) NOT NULL,
  `ACC_ip_latest` varchar(255) NOT NULL,
  `ACC_status` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `accounts_stat`
--

CREATE TABLE `accounts_stat` (
  `AS_id` int(255) NOT NULL,
  `AS_money` varchar(255) NOT NULL,
  `AS_bankmoney` varchar(255) NOT NULL DEFAULT '0',
  `AS_points` int(255) NOT NULL,
  `AS_exp` varchar(255) NOT NULL,
  `AS_rank` int(25) NOT NULL,
  `AS_city` int(5) NOT NULL,
  `AS_health` int(255) NOT NULL,
  `AS_avatar` varchar(255) NOT NULL DEFAULT '././img/avatar/standard_avatar.png',
  `AS_bio` text NOT NULL,
  `AS_bullets` int(255) NOT NULL,
  `AS_weapon` int(25) NOT NULL,
  `AS_weapon_progress` int(15) NOT NULL,
  `AS_def` varchar(255) NOT NULL DEFAULT '100000',
  `AS_theme` int(255) NOT NULL,
  `AS_weed` varchar(255) NOT NULL,
  `AS_daily_exp` int(255) NOT NULL,
  `AS_protection` int(1) NOT NULL,
  `AS_lyddemper` int(1) NOT NULL,
  `AS_boost` int(1) NOT NULL,
  `AS_mission` int(255) NOT NULL,
  `AS_mission_count` varchar(255) NOT NULL,
  `AS_eiendom` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bank_transfer`
--

CREATE TABLE `bank_transfer` (
  `BT_id` int(255) NOT NULL,
  `BT_from` varchar(255) NOT NULL,
  `BT_to` varchar(255) NOT NULL,
  `BT_date` varchar(255) NOT NULL,
  `BT_money` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `banned`
--

CREATE TABLE `banned` (
  `BAN_id` int(255) NOT NULL,
  `BAN_acc_id` varchar(255) NOT NULL,
  `BAN_reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bedrift_daily`
--

CREATE TABLE `bedrift_daily` (
  `BEDA_id` int(25) NOT NULL,
  `BEDA_type` int(5) NOT NULL,
  `BEDA_city` int(1) NOT NULL,
  `BEDA_amount` varchar(255) NOT NULL,
  `BEDA_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bedrift_inntekt`
--

CREATE TABLE `bedrift_inntekt` (
  `BEIN_id` int(255) NOT NULL,
  `BEIN_acc_id` int(25) NOT NULL,
  `BEIN_bedrift_type` int(2) NOT NULL,
  `BEIN_city` int(1) NOT NULL,
  `BEIN_money` varchar(255) NOT NULL,
  `BEIN_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bedrift_inntekt_history`
--

CREATE TABLE `bedrift_inntekt_history` (
  `BEIN_id` int(255) NOT NULL,
  `BEIN_acc_id` int(25) NOT NULL,
  `BEIN_bedrift_type` int(2) NOT NULL,
  `BEIN_city` int(1) NOT NULL,
  `BEIN_money` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BEIN_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `beskyttelse`
--

CREATE TABLE `beskyttelse` (
  `BESK_id` int(255) NOT NULL,
  `BESK_acc_id` int(255) NOT NULL,
  `BESK_garasje` int(255) NOT NULL,
  `BESK_ting` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `blackjack_active`
--

CREATE TABLE `blackjack_active` (
  `BJ_id` int(255) NOT NULL,
  `BJ_acc_id` int(255) NOT NULL,
  `BJ_status` int(255) NOT NULL,
  `BJ_bet` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `blackjack_owner`
--

CREATE TABLE `blackjack_owner` (
  `BJO_id` int(255) NOT NULL,
  `BJO_city` int(255) NOT NULL,
  `BJO_owner` int(255) NOT NULL,
  `BJO_bank` varchar(255) NOT NULL,
  `BJO_maksbet` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `block`
--

CREATE TABLE `block` (
  `BL_id` int(255) NOT NULL,
  `BL_acc_id` varchar(255) NOT NULL,
  `BL_acc_id_blocked` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `boost_account`
--

CREATE TABLE `boost_account` (
  `BOACC_id` int(255) NOT NULL,
  `BOACC_acc_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bundles`
--

CREATE TABLE `bundles` (
  `BUNDLE_id` int(255) NOT NULL,
  `BUNDLE_title` varchar(255) NOT NULL,
  `BUNDLE_date` int(15) NOT NULL,
  `BUNDLE_img` varchar(255) NOT NULL,
  `BUNDLE_price` varchar(255) NOT NULL,
  `BUNDLE_info` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bunker_active`
--

CREATE TABLE `bunker_active` (
  `BUNACT_id` int(255) NOT NULL,
  `BUNACT_acc_id` int(15) NOT NULL,
  `BUNACT_status` int(15) NOT NULL,
  `BUNACT_cooldown` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bunker_chosen`
--

CREATE TABLE `bunker_chosen` (
  `BUNCHO_id` int(255) NOT NULL,
  `BUNCHO_acc_id` int(255) NOT NULL,
  `BUNCHO_bunkerID` int(255) NOT NULL,
  `BUNCHO_city` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bunker_log`
--

CREATE TABLE `bunker_log` (
  `BUNK_id` int(255) NOT NULL,
  `BUNK_acc_id` int(15) NOT NULL,
  `BUNK_in` int(15) NOT NULL,
  `BUNK_city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bunker_owner`
--

CREATE TABLE `bunker_owner` (
  `BUNOWN_id` int(255) NOT NULL,
  `BUNOWN_acc_id` int(15) NOT NULL,
  `BUNOWN_price` int(255) NOT NULL,
  `BUNOWN_city` int(5) NOT NULL,
  `BUNOWN_bank` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `cashback`
--

CREATE TABLE `cashback` (
  `CB_id` int(255) NOT NULL,
  `CB_acc_id` int(255) NOT NULL,
  `CB_saldo` varchar(255) NOT NULL,
  `CB_vip` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `changelog`
--

CREATE TABLE `changelog` (
  `CL_id` int(255) NOT NULL,
  `CL_acc_id` int(11) NOT NULL,
  `CL_edit_column` varchar(255) NOT NULL,
  `CL_value_before` varchar(255) NOT NULL,
  `CL_value_after` varchar(255) NOT NULL,
  `CL_changed_by` int(11) NOT NULL,
  `CL_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `charm`
--

CREATE TABLE `charm` (
  `CH_id` int(255) NOT NULL,
  `CH_acc_id` int(255) NOT NULL,
  `CH_charm` int(255) NOT NULL,
  `CH_use` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `city_tax`
--

CREATE TABLE `city_tax` (
  `CTAX_id` int(255) NOT NULL,
  `CTAX_city` int(255) NOT NULL,
  `CTAX_tax` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `city_tax`
--

INSERT INTO `city_tax` (`CTAX_id`, `CTAX_city`, `CTAX_tax`) VALUES
(1, 0, 35),
(2, 1, 15),
(3, 2, 75),
(4, 3, 60),
(5, 4, 65);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `cooldown`
--

CREATE TABLE `cooldown` (
  `CD_id` int(255) NOT NULL,
  `CD_acc_id` int(255) NOT NULL,
  `CD_crime` int(15) NOT NULL,
  `CD_airport` int(15) NOT NULL,
  `CD_gta` int(15) NOT NULL,
  `CD_brekk` int(15) NOT NULL,
  `CD_territorium` int(15) NOT NULL,
  `CD_steal` int(15) NOT NULL,
  `CD_weapon` int(15) NOT NULL,
  `CD_rc` int(15) NOT NULL,
  `CD_race` int(15) NOT NULL,
  `CD_kill` int(15) NOT NULL,
  `CD_heist` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `crime_chance`
--

CREATE TABLE `crime_chance` (
  `CCH_id` int(255) NOT NULL,
  `CCH_acc_id` int(25) NOT NULL,
  `CCH_city` int(2) NOT NULL,
  `CCH_alternative` int(2) NOT NULL,
  `CCH_chance` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `crypto`
--

CREATE TABLE `crypto` (
  `CRYPTO_id` int(255) NOT NULL,
  `CRYPTO_acc_id` int(255) NOT NULL,
  `CRYPTO_motherboard` varchar(255) NOT NULL,
  `CRYPTO_gpu` varchar(255) NOT NULL,
  `CRYPTO_psu` varchar(255) NOT NULL,
  `CRYPTO_fans` varchar(255) NOT NULL,
  `CRYPTO_eth_amount` varchar(255) NOT NULL,
  `CRYPTO_guide` int(1) NOT NULL,
  `CRYPTO_double` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `crypto`
--

INSERT INTO `crypto` (`CRYPTO_id`, `CRYPTO_acc_id`, `CRYPTO_motherboard`, `CRYPTO_gpu`, `CRYPTO_psu`, `CRYPTO_fans`, `CRYPTO_eth_amount`, `CRYPTO_guide`, `CRYPTO_double`) VALUES
(6, 24, '42', '10', '40', '120', '0', 6, 0),
(7, 10, '50', '240', '50', '526', '41463.86639999802', 6, 0),
(8, 6, '2000', '10000', '2000', '20000', '2428523.2400000393', 6, 1),
(9, 9, '700', '700', '700', '2000', '88930.45699999767', 6, 0),
(10, 3, '2000', '2870', '2000', '14350', '359740.8445400044', 6, 0),
(11, 4, '2000', '10000', '2000', '20000', '35271.72000000001', 6, 1),
(12, 21, '4', '11', '7', '34', '2105.525729999922', 5, 0),
(13, 29, '2', '5', '2', '13', '957.2352900000252', 6, 0),
(14, 28, '6', '5', '2', '12', '', 3, 0),
(15, 5, '2000', '10000', '2000', '20000', '284786.4799999984', 6, 1),
(16, 8, '2000', '10000', '2000', '20000', '1071868.3800000339', 6, 0),
(17, 25, '7', '34', '82', '500', '2849.301796000027', 6, 0),
(18, 22, '2000', '10000', '2000', '20000', '7184.9800000000005', 6, 0),
(19, 23, '1', '5', '1', '10', '956.9087000000252', 0, 0),
(20, 31, '1', '5', '1', '10', '798.1859600000203', 6, 0),
(21, 1, '2000', '10000', '2000', '20000', '280867.39999999845', 6, 1),
(22, 19, '30', '117', '30', '400', '21459.314448000998', 6, 0),
(23, 12, '250', '1250', '250', '2500', '280377.51500000723', 5, 1),
(24, 7, '5', '7', '5', '15', '1330.9848859999784', 6, 0),
(25, 13, '1', '5', '1', '10', '951.030080000025', 6, 0),
(26, 33, '2000', '10000', '2000', '20000', '2695414.7834700122', 6, 1),
(27, 34, '1', '5', '1', '10', '949.723720000025', 1, 0),
(28, 18, '2000', '10000', '2000', '20000', '322670.919999998', 6, 1),
(29, 32, '41', '15', '21', '70', '0', 6, 0),
(30, 35, '1', '5', '1', '10', '942.5387400000247', 3, 0),
(31, 36, '1', '5', '1', '10', '942.5387400000247', 5, 0),
(32, 37, '2000', '10000', '2000', '20000', '2043147.0400000769', 6, 1),
(33, 40, '1', '5', '1', '10', '909.5531500000237', 0, 0),
(34, 42, '2000', '10000', '2000', '20000', '1097995.5800000313', 6, 0),
(35, 46, '1', '5', '1', '10', '775.6512500000196', 0, 0),
(36, 47, '1', '5', '1', '10', '535.6076000000121', 0, 0),
(37, 38, '1', '5', '1', '10', '778.5905600000197', 0, 0),
(38, 26, '2000', '10000', '2000', '20000', '653.18', 6, 0),
(39, 51, '2000', '10000', '2000', '20000', '1335753.1000000075', 6, 0),
(40, 52, '2000', '10000', '2000', '20000', '17635.860000000004', 6, 0),
(41, 56, '11', '55', '11', '110', '6567.071719999997', 6, 0),
(42, 53, '2000', '10000', '2000', '20000', '199873.07999999932', 6, 1),
(43, 58, '2000', '10000', '2000', '20000', '1194666.2200000216', 9, 0),
(44, 60, '2000', '10000', '2000', '20000', '1099955.120000031', 6, 0),
(45, 61, '500', '2500', '500', '5000', '8654.635000000002', 6, 0),
(46, 64, '1', '5', '1', '10', '557.8157200000128', 6, 0),
(47, 68, '2000', '10000', '2000', '20000', '1306.36', 6, 1),
(48, 69, '2000', '10000', '2000', '20000', '7838.160000000001', 6, 0),
(49, 71, '2000', '10000', '2000', '20000', '154150.4799999998', 6, 1),
(50, 72, '301', '55', '301', '910', '5655.559029999998', 6, 0),
(51, 73, '0', '0', '0', '0', '0', 6, 1),
(52, 74, '1', '5', '1', '10', '503.60178000001116', 0, 0),
(53, 75, '0', '0', '0', '0', '0', 6, 0),
(54, 76, '2000', '5', '2000', '20000', '479.1075300000104', 6, 0),
(55, 77, '1', '5', '1', '10', '469.9630100000101', 0, 0),
(56, 78, '1', '5', '1', '10', '422.93405000000865', 6, 0),
(57, 81, '2000', '10000', '2000', '20000', '219468.47999999838', 6, 0),
(58, 85, '2000', '10000', '2000', '20000', '9144.52', 6, 0),
(59, 87, '2000', '10000', '2000', '20000', '16982.680000000004', 6, 0),
(60, 82, '2000', '10000', '2000', '20000', '309607.31999999814', 6, 1),
(61, 86, '2000', '10000', '2000', '20000', '156763.19999999978', 6, 1),
(62, 90, '2000', '10000', '2000', '20000', '220774.8399999991', 6, 1),
(63, 93, '2', '6', '3', '23', '377.4074040000007', 6, 0),
(64, 95, '1', '5', '1', '10', '296.2171300000047', 0, 0),
(65, 99, '1', '5', '1', '10', '232.20549000000273', 0, 0),
(66, 100, '14', '13', '14', '50', '536.9792779999984', 6, 0),
(67, 102, '80', '100', '100', '200', '3448.790400000037', 6, 0),
(68, 103, '2000', '10000', '2000', '20000', '13063.6', 6, 1),
(69, 110, '2000', '4456', '2000', '20000', '163658.6906240011', 6, 1),
(70, 111, '5', '6', '5', '30', '195.95400000000038', 6, 0),
(71, 113, '1', '5', '1', '10', '157.0897900000004', 0, 0),
(72, 114, '2000', '10000', '2000', '20000', '99283.36000000003', 6, 1),
(73, 115, '208', '1040', '208', '2080', '67.93072', 6, 0),
(74, 116, '1', '5', '1', '10', '81.64750000000005', 0, 0),
(75, 118, '1', '5', '1', '10', '80.66773000000006', 9, 0),
(76, 119, '1', '5', '1', '10', '76.42206000000012', 0, 0),
(77, 121, '1', '5', '1', '10', '58.45961000000022', 6, 0),
(78, 124, '2000', '1432', '2000', '11530', '2806.061279999999', 6, 0),
(79, 117, '2000', '5', '2000', '20000', '2.28613', 6, 0);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `crypto_rigs`
--

CREATE TABLE `crypto_rigs` (
  `CRR_id` int(255) NOT NULL,
  `CRR_acc_id` varchar(255) NOT NULL,
  `CRR_crypto` varchar(255) NOT NULL,
  `CRR_gpu` int(255) NOT NULL,
  `CRR_fan` int(255) NOT NULL,
  `CRR_motherboard` int(255) NOT NULL,
  `CRR_psu` int(255) NOT NULL,
  `CRR_pig` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `crypto_user`
--

CREATE TABLE `crypto_user` (
  `CRU_id` int(255) NOT NULL,
  `CRU_acc_id` varchar(255) NOT NULL,
  `CRU_amount` varchar(255) NOT NULL,
  `CRU_crypto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `dagens_utfordring`
--

CREATE TABLE `dagens_utfordring` (
  `DAUT_id` int(255) NOT NULL,
  `DAUT_date` int(25) NOT NULL,
  `DAUT_json` varchar(255) NOT NULL,
  `DAUT_payout_char` int(255) NOT NULL,
  `DAUT_payout_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `dagens_utfordring_user`
--

CREATE TABLE `dagens_utfordring_user` (
  `DAUTUS_id` int(255) NOT NULL,
  `DAUTUS_acc_id` int(255) NOT NULL,
  `DAUTUS_crime` int(25) NOT NULL,
  `DAUTUS_gta` int(25) NOT NULL,
  `DAUTUS_brekk` int(25) NOT NULL,
  `DAUTUS_heist` int(25) NOT NULL,
  `DAUTUS_stjel` int(25) NOT NULL,
  `DAUTUS_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `detektiv`
--

CREATE TABLE `detektiv` (
  `DETK_id` int(255) NOT NULL,
  `DETK_acc_id` int(255) NOT NULL,
  `DETK_lvl` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `detektiv_sok`
--

CREATE TABLE `detektiv_sok` (
  `DETSOK_id` int(255) NOT NULL,
  `DETSOK_acc_id` int(255) NOT NULL,
  `DETSOK_user` int(255) NOT NULL,
  `DETSOK_city` varchar(255) NOT NULL,
  `DETSOK_date` int(15) NOT NULL,
  `DETSOK_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `double_xp`
--

CREATE TABLE `double_xp` (
  `DX_id` int(25) NOT NULL,
  `DX_acc_id` int(25) NOT NULL,
  `DX_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `family`
--

CREATE TABLE `family` (
  `FAM_id` int(255) NOT NULL,
  `FAM_name` varchar(255) NOT NULL,
  `FAM_avatar` varchar(255) NOT NULL DEFAULT 'img/avatar/family_standard.png',
  `FAM_profil` text NOT NULL,
  `FAM_bedrift` int(1) NOT NULL,
  `FAM_avtalegiro` int(1) NOT NULL,
  `FAM_bank` varchar(255) NOT NULL,
  `FAM_forsvar` varchar(255) NOT NULL,
  `FAM_bullets` varchar(255) NOT NULL,
  `FAM_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `family_applicant`
--

CREATE TABLE `family_applicant` (
  `FAMAP_id` int(255) NOT NULL,
  `FAMAP_fam_id` int(255) NOT NULL,
  `FAMAP_acc_id` int(255) NOT NULL,
  `FAMAP_text` text NOT NULL,
  `FAMAP_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `family_donation`
--

CREATE TABLE `family_donation` (
  `FAMDON_id` int(255) NOT NULL,
  `FAMDON_fam_id` int(255) NOT NULL,
  `FAMDON_acc_id` int(255) NOT NULL,
  `FAMDON_action` int(1) NOT NULL,
  `FAMDON_value` varchar(255) NOT NULL,
  `FAMDON_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `family_member`
--

CREATE TABLE `family_member` (
  `FAMMEM_id` int(255) NOT NULL,
  `FAMMEM_acc_id` int(255) NOT NULL,
  `FAMMEM_fam_id` int(255) NOT NULL,
  `FAMMEM_role` int(25) NOT NULL,
  `FAMMEM_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `fengsel`
--

CREATE TABLE `fengsel` (
  `FENG_ID` int(255) NOT NULL,
  `FENG_acc_id` int(255) NOT NULL,
  `FENG_reason` varchar(255) NOT NULL,
  `FENG_countdown` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `fengsel_direktor`
--

CREATE TABLE `fengsel_direktor` (
  `FENGDI_id` int(255) NOT NULL,
  `FENGDI_acc_id` varchar(255) NOT NULL,
  `FENGDI_city` varchar(255) NOT NULL,
  `FENGDI_bank` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `flyplass`
--

CREATE TABLE `flyplass` (
  `FP_id` int(255) NOT NULL,
  `FP_city` int(2) NOT NULL,
  `FP_owner` int(255) NOT NULL,
  `FP_money` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `flyplass_pris`
--

CREATE TABLE `flyplass_pris` (
  `FLYPRIS_id` int(255) NOT NULL,
  `FLYPRIS_city` int(15) NOT NULL,
  `FLYPRIS_price` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `forum`
--

CREATE TABLE `forum` (
  `FRM_id` int(255) NOT NULL,
  `FRM_topic_id` int(255) NOT NULL,
  `FRM_cat` int(25) NOT NULL,
  `FRM_title` varchar(255) NOT NULL,
  `FRM_content` text NOT NULL,
  `FRM_date` int(25) NOT NULL,
  `FRM_acc_id` int(255) NOT NULL,
  `FRM_last_reply` int(25) NOT NULL,
  `FRM_closed` int(1) NOT NULL,
  `FRM_fam_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `forum_edited`
--

CREATE TABLE `forum_edited` (
  `FEDIT_id` int(255) NOT NULL,
  `FEDIT_forum_id` int(255) NOT NULL,
  `FEDIT_before` text NOT NULL,
  `FEDIT_after` text NOT NULL,
  `FEDIT_by` int(255) NOT NULL,
  `FEDIT_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `forum_likes`
--

CREATE TABLE `forum_likes` (
  `FLIKES_id` int(255) NOT NULL,
  `FLIKES_forum_id` int(255) NOT NULL,
  `FLIKES_type` int(1) NOT NULL,
  `FLIKES_acc_id` int(255) NOT NULL,
  `FLIKES_date` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `garage`
--

CREATE TABLE `garage` (
  `GA_id` int(255) NOT NULL,
  `GA_acc_id` int(255) NOT NULL,
  `GA_car_id` int(255) NOT NULL,
  `GA_car_city` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `gta_chance`
--

CREATE TABLE `gta_chance` (
  `GCH_id` int(255) NOT NULL,
  `GCH_acc_id` int(25) NOT NULL,
  `GCH_city` int(2) NOT NULL,
  `GCH_alternative` int(2) NOT NULL,
  `GCH_chance` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `half_fp`
--

CREATE TABLE `half_fp` (
  `HALFFP_id` int(255) NOT NULL,
  `HALFFP_acc_id` int(255) NOT NULL,
  `HALFFP_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `heatmap`
--

CREATE TABLE `heatmap` (
  `HM_id` int(255) NOT NULL,
  `HM_acc_id` varchar(255) NOT NULL,
  `HM_x` int(255) NOT NULL,
  `HM_y` int(255) NOT NULL,
  `HM_page` varchar(255) NOT NULL,
  `HM_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `heist`
--

CREATE TABLE `heist` (
  `HEIST_id` int(15) NOT NULL,
  `HEIST_type` int(1) NOT NULL,
  `HEIST_countdown` int(15) NOT NULL,
  `HEIST_leader` int(15) NOT NULL,
  `HEIST_city` varchar(15) NOT NULL,
  `HEIST_status` int(255) NOT NULL,
  `HEIST_info` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `heist_members`
--

CREATE TABLE `heist_members` (
  `HEIME_id` int(255) NOT NULL,
  `HEIME_acc_id` varchar(255) NOT NULL,
  `HEIME_heist_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `hurtig_oppdrag`
--

CREATE TABLE `hurtig_oppdrag` (
  `HO_id` int(255) NOT NULL,
  `HO_car_id` int(255) NOT NULL,
  `HO_car_amount` int(255) NOT NULL,
  `HO_thing_id` int(255) NOT NULL,
  `HO_thing_amount` int(255) NOT NULL,
  `HO_date` int(15) NOT NULL,
  `HO_status` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `kast_mynt`
--

CREATE TABLE `kast_mynt` (
  `KM_id` int(255) NOT NULL,
  `KM_player_1` int(25) NOT NULL,
  `KM_kron_mynt` int(1) NOT NULL,
  `KM_bet` varchar(255) NOT NULL,
  `KM_countdown` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `keep_me_login`
--

CREATE TABLE `keep_me_login` (
  `KMLI_id` int(255) NOT NULL,
  `KMLI_hash` varchar(255) NOT NULL,
  `KMLI_acc_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `kf_owner`
--

CREATE TABLE `kf_owner` (
  `KFOW_id` int(255) NOT NULL,
  `KFOW_city` int(15) NOT NULL,
  `KFOW_acc_id` int(255) NOT NULL,
  `KFOW_money` varchar(255) NOT NULL,
  `KFOW_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `konk`
--

CREATE TABLE `konk` (
  `KONK_id` int(255) NOT NULL,
  `KONK_site` varchar(255) NOT NULL,
  `KONK_from` int(255) NOT NULL,
  `KONK_to` int(255) NOT NULL,
  `KONK_title` text NOT NULL,
  `KONK_description` text NOT NULL,
  `KONK_prize` varchar(255) NOT NULL,
  `KONK_status` int(10) NOT NULL,
  `KONK_winners` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `konk_user`
--

CREATE TABLE `konk_user` (
  `KU_id` int(255) NOT NULL,
  `KU_acc_id` int(255) NOT NULL,
  `KU_count` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `last_events`
--

CREATE TABLE `last_events` (
  `LAEV_id` int(255) NOT NULL,
  `LAEV_user` int(255) NOT NULL,
  `LAEV_text` text NOT NULL,
  `LAEV_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `last_race`
--

CREATE TABLE `last_race` (
  `LR_id` int(255) NOT NULL,
  `LR_acc_id` int(255) NOT NULL,
  `LR_contestant` int(255) NOT NULL,
  `LR_winner` int(255) NOT NULL,
  `LR_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `last_stjel`
--

CREATE TABLE `last_stjel` (
  `LASTJ_id` int(255) NOT NULL,
  `LASTJ_acc_id` int(255) NOT NULL,
  `LASTJ_username` varchar(255) NOT NULL,
  `LASTJ_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `lotto`
--

CREATE TABLE `lotto` (
  `LOTTO_id` int(255) NOT NULL,
  `LOTTO_acc_id` int(255) NOT NULL,
  `LOTTO_amount` varchar(255) NOT NULL,
  `LOTTO_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `lotto_winner`
--

CREATE TABLE `lotto_winner` (
  `LOTTOW_id` int(255) NOT NULL,
  `LOTTOW_acc_id` int(255) NOT NULL,
  `LOTTOW_sum` varchar(255) NOT NULL,
  `LOTTOW_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `marked`
--

CREATE TABLE `marked` (
  `MARKED_id` int(255) NOT NULL,
  `MARKED_seller` int(255) NOT NULL,
  `MARKED_cat` int(255) NOT NULL,
  `MARKED_private` varchar(255) NOT NULL,
  `MARKED_info` varchar(255) NOT NULL,
  `MARKED_price` varchar(255) NOT NULL,
  `MARKED_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `notification`
--

CREATE TABLE `notification` (
  `NO_id` int(255) NOT NULL,
  `NO_text` varchar(255) NOT NULL,
  `NO_date` int(25) NOT NULL,
  `NO_acc_id` int(255) NOT NULL,
  `NO_new` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `nyheter`
--

CREATE TABLE `nyheter` (
  `NYH_id` int(255) NOT NULL,
  `NYH_title` varchar(255) NOT NULL,
  `NYH_text` text NOT NULL,
  `NYH_writer` int(255) NOT NULL,
  `NYH_badge` int(2) NOT NULL,
  `NYH_header` varchar(255) NOT NULL,
  `NYH_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `pm`
--

CREATE TABLE `pm` (
  `PM_id` int(255) NOT NULL,
  `PM_pmid` varchar(25) NOT NULL,
  `PM_acc_id_from` int(25) NOT NULL,
  `PM_acc_id_to` int(25) NOT NULL,
  `PM_title` varchar(255) NOT NULL,
  `PM_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `pm_new`
--

CREATE TABLE `pm_new` (
  `PMN_id` int(255) NOT NULL,
  `PMN_pmid` varchar(255) NOT NULL,
  `PMN_acc_id1` int(25) NOT NULL,
  `PMN_acc_id1_new` int(2) NOT NULL,
  `PMN_acc_id2` int(25) NOT NULL,
  `PMN_acc_id2_new` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `pm_text`
--

CREATE TABLE `pm_text` (
  `PMT_id` int(255) NOT NULL,
  `PMT_pmid` varchar(255) NOT NULL,
  `PMT_acc_id` int(255) NOT NULL,
  `PMT_text` text NOT NULL,
  `PMT_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `poeng_betingelse`
--

CREATE TABLE `poeng_betingelse` (
  `PBET_id` int(255) NOT NULL,
  `PBET_acc_id` int(255) NOT NULL,
  `PBET_date` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `poeng_payments`
--

CREATE TABLE `poeng_payments` (
  `payment_id` int(11) NOT NULL,
  `stripe_reference` varchar(255) NOT NULL,
  `buyer` int(255) NOT NULL,
  `amount` decimal(15,0) NOT NULL,
  `payment_info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `poeng_products`
--

CREATE TABLE `poeng_products` (
  `PRO_id` int(255) NOT NULL,
  `PRO_name` int(255) NOT NULL,
  `PRO_price` int(255) NOT NULL,
  `PRO_stripe_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `poeng_products`
--

INSERT INTO `poeng_products` (`PRO_id`, `PRO_name`, `PRO_price`, `PRO_stripe_id`) VALUES
(1, 25, 30, 'price_1H60D7Ap8W0uvp6nTZnnQkyU'),
(2, 60, 50, 'price_1H60AFAp8W0uvp6nHPYU2zIH'),
(3, 120, 80, 'price_1H60ASAp8W0uvp6nKjrNhjyD'),
(4, 250, 150, 'price_1H60AdAp8W0uvp6naChpXt6K'),
(5, 500, 280, 'price_1H60AmAp8W0uvp6n85DHJVl9'),
(6, 1000, 499, 'price_1H60AvAp8W0uvp6ncIHF24Zg');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `poeng_products_test`
--

CREATE TABLE `poeng_products_test` (
  `PRO_id` int(255) NOT NULL,
  `PRO_name` int(255) NOT NULL,
  `PRO_price` int(255) NOT NULL,
  `PRO_stripe_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `poeng_products_test`
--

INSERT INTO `poeng_products_test` (`PRO_id`, `PRO_name`, `PRO_price`, `PRO_stripe_id`) VALUES
(1, 25, 30, 'price_1H2zyNAp8W0uvp6nTboBIR8x');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `poeng_sold`
--

CREATE TABLE `poeng_sold` (
  `PSO_id` int(255) NOT NULL,
  `PSO_antall` varchar(255) NOT NULL,
  `PSO_total_pris` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `race_club`
--

CREATE TABLE `race_club` (
  `RC_id` int(255) NOT NULL,
  `RC_acc_id` int(255) NOT NULL,
  `RC_drift` int(255) NOT NULL,
  `RC_drag` int(255) NOT NULL,
  `RC_race` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `rc_owner`
--

CREATE TABLE `rc_owner` (
  `RCOWN_id` int(255) NOT NULL,
  `RCOWN_acc_id` int(255) NOT NULL,
  `RCOWN_city` int(1) NOT NULL,
  `RCOWN_bank` varchar(255) NOT NULL,
  `RCOWN_price` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `rob_chance`
--

CREATE TABLE `rob_chance` (
  `RCH_id` int(255) NOT NULL,
  `RCH_acc_id` int(25) NOT NULL,
  `RCH_city` int(2) NOT NULL,
  `RCH_alternative` int(2) NOT NULL,
  `RCH_chance` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `smugling`
--

CREATE TABLE `smugling` (
  `SMUG_id` int(255) NOT NULL,
  `SMUG_acc_id` int(255) NOT NULL,
  `SMUG_type` int(15) NOT NULL,
  `SMUG_lvl` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `smugling_daily`
--

CREATE TABLE `smugling_daily` (
  `SMDAI_id` int(255) NOT NULL,
  `SMDAI_acc_id` int(255) NOT NULL,
  `SMDAI_days` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `steal_chance`
--

CREATE TABLE `steal_chance` (
  `STCH_id` int(255) NOT NULL,
  `STCH_acc_id` int(255) NOT NULL,
  `STCH_type` int(1) NOT NULL,
  `STCH_chance` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `stocks`
--

CREATE TABLE `stocks` (
  `ST_id` int(255) NOT NULL,
  `ST_type` int(11) NOT NULL,
  `ST_price` int(11) NOT NULL,
  `ST_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `stocks_logg`
--

CREATE TABLE `stocks_logg` (
  `STLG_id` int(255) NOT NULL,
  `STLG_acc_id` int(25) NOT NULL,
  `STLG_type` int(11) NOT NULL,
  `STLG_date` int(11) NOT NULL,
  `STLG_action` int(1) NOT NULL,
  `STLG_amount` varchar(255) NOT NULL,
  `STLG_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `stock_behold`
--

CREATE TABLE `stock_behold` (
  `STB_id` int(255) NOT NULL,
  `STB_acc_id` int(255) NOT NULL,
  `STB_0` varchar(255) NOT NULL,
  `STB_1` varchar(255) NOT NULL,
  `STB_2` varchar(255) NOT NULL,
  `STB_3` varchar(255) NOT NULL,
  `STB_4` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `stock_list`
--

CREATE TABLE `stock_list` (
  `SLI_id` int(255) NOT NULL,
  `SLI_type` int(255) NOT NULL,
  `SLI_price` int(255) NOT NULL,
  `SLI_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `super_helg`
--

CREATE TABLE `super_helg` (
  `SHELG_id` int(255) NOT NULL,
  `SHELG_start` int(15) NOT NULL,
  `SHELG_end` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `support`
--

CREATE TABLE `support` (
  `SP_id` int(25) NOT NULL,
  `SP_status` int(1) NOT NULL,
  `SP_text` text NOT NULL,
  `SP_from` int(25) NOT NULL,
  `SP_title` varchar(50) NOT NULL,
  `SP_date` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `supported_crypto`
--

CREATE TABLE `supported_crypto` (
  `SUC_id` int(255) NOT NULL,
  `SUC_ticker` varchar(15) NOT NULL,
  `SUC_name` varchar(255) NOT NULL,
  `SUC_date` int(15) NOT NULL,
  `SUC_price` varchar(255) NOT NULL,
  `SUC_price_pr_gpu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `swiss`
--

CREATE TABLE `swiss` (
  `SWISS_id` int(255) NOT NULL,
  `SWISS_account` varchar(255) NOT NULL,
  `SWISS_password` varchar(255) NOT NULL,
  `SWISS_saldo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `swiss_account`
--

CREATE TABLE `swiss_account` (
  `SWIACC_id` int(255) NOT NULL,
  `SWIACC_acc` varchar(255) NOT NULL,
  `SWIACC_acc_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `terninger`
--

CREATE TABLE `terninger` (
  `TERN_id` int(255) NOT NULL,
  `TERN_player` int(255) NOT NULL,
  `TERN_amount` varchar(255) NOT NULL,
  `TERN_dices` varchar(255) NOT NULL,
  `TERN_bet` varchar(255) NOT NULL,
  `TERN_date` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `territorium`
--

CREATE TABLE `territorium` (
  `TE_id` int(255) NOT NULL,
  `TE_family_id` int(255) NOT NULL,
  `TE_city` int(255) NOT NULL,
  `TE_money` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `things`
--

CREATE TABLE `things` (
  `TH_id` int(255) NOT NULL,
  `TH_acc_id` int(255) NOT NULL,
  `TH_type` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `uploaded_avatar`
--

CREATE TABLE `uploaded_avatar` (
  `UOA_id` int(255) NOT NULL,
  `UOA_acc_id` varchar(255) NOT NULL,
  `UOA_avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_log`
--

CREATE TABLE `user_log` (
  `UL_id` int(255) NOT NULL,
  `UL_acc_id` int(255) NOT NULL,
  `UL_money_hand` varchar(255) NOT NULL,
  `UL_money_bank` varchar(255) NOT NULL,
  `UL_exp` varchar(255) NOT NULL,
  `UL_city` int(5) NOT NULL,
  `UL_page` varchar(255) NOT NULL,
  `UL_handling` varchar(255) NOT NULL,
  `UL_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `user_statistics`
--

CREATE TABLE `user_statistics` (
  `US_id` int(255) NOT NULL,
  `US_acc_id` int(255) NOT NULL,
  `US_krim_v` int(255) NOT NULL,
  `US_krim_m` int(255) NOT NULL,
  `US_gta_v` int(255) NOT NULL,
  `US_gta_m` int(255) NOT NULL,
  `US_brekk_v` int(255) NOT NULL,
  `US_brekk_m` int(255) NOT NULL,
  `US_stjel_v` int(255) NOT NULL,
  `US_stjel_m` int(255) NOT NULL,
  `US_rc_v` int(255) NOT NULL,
  `US_rc_m` int(255) NOT NULL,
  `US_gambling` varchar(255) NOT NULL,
  `US_money_sent` varchar(255) NOT NULL,
  `US_money_received` varchar(255) NOT NULL,
  `US_max_cars` int(255) NOT NULL DEFAULT 20,
  `US_max_things` int(255) NOT NULL DEFAULT 20,
  `US_kills` int(15) NOT NULL,
  `US_pageview` int(255) NOT NULL,
  `US_jail` int(255) NOT NULL,
  `US_hurtig_oppdrag` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `verification`
--

CREATE TABLE `verification` (
  `VER_acc_id` int(255) NOT NULL,
  `VER_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `verv`
--

CREATE TABLE `verv` (
  `VERV_id` int(255) NOT NULL,
  `VERV_acc_id` int(255) NOT NULL,
  `VERV_by` int(255) NOT NULL,
  `VERV_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `wiki`
--

CREATE TABLE `wiki` (
  `WIKI_ID` int(255) NOT NULL,
  `WIKI_title` varchar(255) NOT NULL,
  `WIKI_desc` text NOT NULL,
  `WIKI_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `wiki`
--

INSERT INTO `wiki` (`WIKI_ID`, `WIKI_title`, `WIKI_desc`, `WIKI_date`) VALUES
(5, 'Pengerank', '<table>\r\n    <tbody><tr>\r\n        <th>Pengerank</th>\r\n        <th>Penger fra</th>\r\n        <th>Penger til</th>\r\n    </tr>\r\n        <tr>\r\n        <td>Uteligger</td>\r\n        <td>0</td>\r\n        <td>5 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Fattig</td>\r\n        <td>5 000</td>\r\n        <td>100 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Arbeider</td>\r\n        <td>100 000</td>\r\n        <td>1 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Langer</td>\r\n        <td>1 000 000</td>\r\n        <td>5 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Middel klasse</td>\r\n        <td>5 000 000</td>\r\n        <td>100 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Ã¸vrige klasse</td>\r\n        <td>100 000 000</td>\r\n        <td>250 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Rik</td>\r\n        <td>250 000 000</td>\r\n        <td>500 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Middels rik</td>\r\n        <td>500 000 000</td>\r\n        <td>1 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>MilliardÃ¦r</td>\r\n        <td>1 000 000 000</td>\r\n        <td>2 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>MultimilliardÃ¦r</td>\r\n        <td>2 000 000 000</td>\r\n        <td>5 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>MangemilliardÃ¦r</td>\r\n        <td>5 000 000 000</td>\r\n        <td>10 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Berryktet milliardÃ¦r</td>\r\n        <td>10 000 000 000</td>\r\n        <td>25 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Aksjemegler</td>\r\n        <td>25 000 000 000</td>\r\n        <td>50 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>BÃ¸rssjef</td>\r\n        <td>50 000 000 000</td>\r\n        <td>100 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Hotell-investor</td>\r\n        <td>100 000 000 000</td>\r\n        <td>250 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Oljesjeik</td>\r\n        <td>250 000 000 000</td>\r\n        <td>500 000 000 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>Forretningsmagnat</td>\r\n        <td>500 000 000 000</td>\r\n        <td>inf</td>\r\n    </tr>\r\n    </tbody></table>', 1633015757),
(7, 'Banken', 'Rentene kommer an pÃ¥ hvor mye du har pÃ¥ din konto. Satsene vil du se i din bank. Du fÃ¥r ikke renter pÃ¥ mer enn 500 000 000 kr.', 1633015639),
(9, 'Familie', 'For Ã¥ opprette familie mÃ¥ du ha ranken Konsulent eller hÃ¸yere. Prisen for Ã¥ opprette familie er 5 000 000. Det er kun plass til fem individuelle familier.\r\n<br><br>\r\nEn fordel med Ã¥ vÃ¦re medlem i en familie er ekstra forsvar. Man vil altsÃ¥ fÃ¥ en prosentandel av det familien har i forsvar.<br>\r\nForsvaret fra familien blir fordelt slik:<br>\r\n50% til sjef<br>\r\n25% til direktÃ¸r<br>\r\n15% til Ã¸konom<br>\r\nResterende 10% blir fordelt pÃ¥ resten av medlemmene ', 1633171028),
(10, 'BB-koder', 'BB-koder kan brukes pÃ¥ profil og forum for Ã¥ style teksten.\r\n\r\n<table>\r\n<tr><th>BB-kode</th><th>Beskrivelse</th><th>Eksempel</th></tr>\r\n<tr><td>[center][/center]</td><td>Midtstilt tekst</td><td><center>Eksempel</center></tr>\r\n<tr><td>[b][/b]</td><td>Fet tekst</td><td><b>Eksempel</b></tr>\r\n<tr><td>[i][/i]</td><td>Italic tekst</td><td><i>Eksempel</i></tr>\r\n<tr><td>[u][/u]</td><td>Understrek tekst</td><td><u>Eksempel</u></tr>\r\n<tr><td>[tr][/tr]</td><td>Transparant tekst</td><td></tr>\r\n<tr><td>[size=15][/size]</td><td>StÃ¸rrelse pÃ¥ tekst</td><td><span style=\'font-size: 15px;\'>Eksempel</span></tr>\r\n<tr><td>[img]url[/img]</td><td>Bilde</td><td><img style=\'width: 150px; height: auto;\' src=\'img/avatar/standard_avatar.png\'></tr>\r\n<tr><td>[color=red][/color]</td><td>Farge pÃ¥ tekst</td><td><span style=\'color: red;\'>Eksempel</span></tr>\r\n<tr><td>[youtube]HShOMLxQ1Ww[/youtube]</td><td>Koden etter v=</td><td><embed width=\"130\" height=\"70\" src=\"https://www.youtube.com/v/HShOMLxQ1Ww\"></tr>\r\n<tr><td>[visitor]</td><td>Viser brukernavnet til besÃ¸kende</td><td><center></center></tr>\r\n<tr><td>[exp]</td><td>Viser hvor mye exp du har</td><td><center></center></tr>\r\n<tr><td>[money]</td><td>Viser pengene du har pÃ¥ hÃ¥nden</td><td><center></center></tr>\r\n<tr><td>[money_bank]</td><td>Viser pengene du har i banken</td><td><center></center></tr>\r\n<tr><td>[tr][/tr]</td><td>Transparant innhold</td><td><center></center></tr>\r\n</table>', 1633015656),
(11, 'Ranker', '<table>\r\n<tr><th>Navn</th><th>EXP fra</th><th>EXP til</th></tr>\r\n<tr><td>Sivilist</td><td>0</td><td>20</td></tr>\r\n<tr><td>PÃ¸bel</td><td>20</td><td>100</td></tr>\r\n<tr><td>Soldat</td><td>100</td><td>200</td></tr>\r\n<tr><td>Konsulent</td><td>250</td><td>750</td></tr>\r\n<tr><td>ViserÃ¥dmann</td><td>750</td><td>1 500</td></tr>\r\n<tr><td>RÃ¥dmann</td><td>1 500</td><td>3 000</td></tr>\r\n<tr><td>ViseguvernÃ¸r</td><td>3 000</td><td>5 000</td></tr>\r\n<tr><td>GuvernÃ¸r</td><td>5 000</td><td>10 000</td></tr>\r\n<tr><td>Visesenator</td><td>10 000</td><td>16 000</td></tr>\r\n<tr><td>Senator</td><td>16 000</td><td>25 000</td></tr>\r\n<tr><td>Visekonge</td><td>25 000</td><td>50 000</td></tr>\r\n<tr><td>Konge</td><td>50 000</td><td>100 000</td></tr>\r\n<tr><td>Visepresident</td><td>100 000</td><td>250 000</td></tr>\r\n<tr><td>President</td><td>250 000</td><td>500 000</td></tr>\r\n<tr><td>Legende</td><td>500 000</td><td>1 000 000</td></tr>\r\n<tr><td>Mafioso</td><td>1 000 000</td><td>INF</td></tr>\r\n</table>', 1633015769),
(12, 'Byskatt', 'Byskatt er noe som trekker prosent fra kriminalitet, biltyveri og brekk. Om du har 50% pÃ¥ en kriminalitet-alternativ og byskatten er pÃ¥ 30% sÃ¥ har du 20% sjanse for Ã¥ klare kriminaliteten. Byskatten endres hver time og er tilfeldig fra by til by. Den kan settes ned til 0% ved hjelp av poeng pÃ¥ poeng-siden.', 1633015683),
(13, 'Ransbeskyttelse', 'Ransbeskyttelse finner du under \"drapsorganisering\". Med en ransbeskyttelse kan du beskytte deg mot ran fra andre spillere. Du mister 1 stk ransbeskyttelse ved midnatt. Pris for beskyttelse er 500 000 kr og dekker penger pÃ¥ hÃ¥nden, garasje og lageret ditt. Du kan enten kjÃ¸pe ransbeskyttelse for poeng eller penger. Dersom du Ã¸nsker flere dager med ransbeskyttelse sÃ¥ anbefales det at du kjÃ¸per med poeng da du kun kan kjÃ¸pe for gjeldende dag med penger.', 1633015780),
(15, 'Drap', 'For Ã¥ regne ut hvor mye kuler som trengs til ett drap trenger du spilleren sin exp og forsvar. GÃ¥ pÃ¥ drap-siden og trykk pÃ¥ \"kalkulator\" for Ã¥ regne ut antall kuler som kreves for ditt vÃ¥pen. NÃ¥r en spiller blir drept sÃ¥ mister han eller hun alle sine eiendeler, er spilleren leder av en familie vil familien bli lagt ned og alle medlemmene i familien vil bli uten familie.\r\n<br><br>\r\nNÃ¥r du angriper noen sÃ¥ mister du kulene, uansett om du treffer eller bommer. NÃ¥r du angriper har du halvt forsvarspoeng i 4 timer. Etter 4 timer vil du fÃ¥ tilbake dine forsvarspoeng. \r\n<br><br>\r\nFor Ã¥ kunne bli drept eller drepe mÃ¥ du ha vÃ¦rt registrert i 3 dager eller ha registrert 200 exp.\r\n<br><br>\r\nBrukeren du skal drepe mÃ¥ vÃ¦re over ranken RÃ¥dmann for at det skal telles pÃ¥ profilen.', 1641583062),
(16, 'Smugling', 'Smugling kan utfÃ¸res hvert dÃ¸gn og det er endel penger Ã¥ hente pÃ¥ det. Desto mer utstyr du har, desto mer penger vil du tjene pr gram marijuana du har skaffet. Smugling fungerer med at du bygger opp et kartell som du supplerer med marijuana ved Ã¥ utfÃ¸re spesiell kriminell handling.\r\n<br><br>\r\nDu kjÃ¸per utstyr for summen som er beskrevet. For Ã¥ fÃ¥ utbetalt er du avhengig av Ã¥ stjele nok gram marijuana til Ã¥ dekke kravet. Du har 7 dager pÃ¥ deg Ã¥ nÃ¥ kravet, nÃ¥r du ikke kravet innen 7 dager blir alt av ditt utstyr beslaglagt og du fÃ¥r ingen verdi tilbake. Ved salg av utstyr mottar du 50% av verdien, men du fÃ¥r ingen av poengene tilbake. Det koster 10 poeng per utstyr du selger.\r\n<br><br>\r\nMan mister all marijuana ved midnatt. Dette gjelder ogsÃ¥ om du har lagt ut marijuana for salg pÃ¥ Fynn.no.', 1633015810),
(17, 'Cannabis', 'Marijuana kan stjeles fra kriminalitet. Du kan bruke marijuana til Ã¥ tjene penger pÃ¥ smugling, eller selge til privatpersoner pÃ¥ marked. Ved midnatt sÃ¥ fjernes alle annonser om marijuana fra Fynn.no og fra hÃ¥nden din.', 1633102552),
(22, 'VÃ¥pen', '<table>\r\n    <tbody><tr>\r\n        <th>VÃ¥pen nr.</th>\r\n        <th>VÃ¥pen navn</th>\r\n        <th>Pris pr trening</th>\r\n        <th>Boost</th>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Glock 19</td>\r\n        <td>25 000</td>\r\n        <td>1</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Five Seven</td>\r\n        <td>40 000</td>\r\n        <td>2</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Tec-9</td>\r\n        <td>55 000</td>\r\n        <td>4</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>4</td>\r\n        <td>Desert Eagle</td>\r\n        <td>70 000</td>\r\n        <td>7</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>5</td>\r\n        <td>MP5-SD</td>\r\n        <td>85 000</td>\r\n        <td>9</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>6</td>\r\n        <td>CZ75-Auto</td>\r\n        <td>100 000</td>\r\n        <td>11</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>7</td>\r\n        <td>MP7</td>\r\n        <td>115 000</td>\r\n        <td>13</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>8</td>\r\n        <td>MAC-10</td>\r\n        <td>130 000</td>\r\n        <td>16</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>9</td>\r\n        <td>MP5-S</td>\r\n        <td>145 000</td>\r\n        <td>20</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>10</td>\r\n        <td>UZI</td>\r\n        <td>160 000</td>\r\n        <td>24</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>11</td>\r\n        <td>MP9</td>\r\n        <td>175 000</td>\r\n        <td>27</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>12</td>\r\n        <td>M4A4</td>\r\n        <td>190 000</td>\r\n        <td>29</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>13</td>\r\n        <td>M4A1-S</td>\r\n        <td>205 000</td>\r\n        <td>31</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>14</td>\r\n        <td>AK-47</td>\r\n        <td>220 000</td>\r\n        <td>33</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>15</td>\r\n        <td>AUG</td>\r\n        <td>235 000</td>\r\n        <td>35</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>16</td>\r\n        <td>SSG 08</td>\r\n        <td>250 000</td>\r\n        <td>36</td>\r\n\r\n    </tr>\r\n        <tr>\r\n        <td>17</td>\r\n        <td>AWP</td>\r\n        <td>265 000</td>\r\n        <td>37</td>\r\n\r\n    </tr>\r\n    </tbody></table>', 1633015610),
(23, 'Marked', 'PÃ¥ marked kan du kjÃ¸pe og selge ting mellom brukere. NÃ¥r du selger vil mafiaen ta 10% avgift av salgs-belÃ¸pet.', 1633015748),
(24, 'Retningslinjer', '<h3 style=\"color:white\">Â§1 Utestengelse</h3>\r\nVi forbeholder oss retten til Ã¥ utestengelse brukere ved mistanke om juks, svindel eller andre faktorer som Ã¸delegger spillet i det korte og lange lÃ¸p<br>\r\n<br>\r\n<h3 style=\"color:white\">Â§2 Verdier</h3>\r\nVed en eventuell Â§1 Utestengelse tar vi ikke noe ansvar for tap av verdier, verken poeng eller goder som allerede er kjÃ¸pt med poeng.<br>\r\n<br>\r\n<h3 style=\"color:white\">Â§3 Informasjonslagring</h3>\r\nPÃ¥ Mafioso lagres det informasjon som kan vÃƒÂ¦re nyttig Ã¥ bruke i en eventuell sak om utestengelse. Alt av data blir lagret trygt i vÃ¥re dataservere og blir ikke delt eller sett pÃ¥ med mindre det er mistanke om juks eller svindel.<br>\r\n<br>\r\n<h3 style=\"color:white\">Â§4 Registrering av brukerkonto</h3>\r\nNÃ¥r du har registrert deg er det den brukeren du skal spille pÃ¥ til den ikke er i live lengre. Er det registrert at du har 2 brukere har du inntil 1 uke pÃ¥ deg til Ã¥ sende support om at du har flere bukere pÃ¥ IP-en du er regsitrert pÃ¥. Vi minner om at det kun er lov med 1 brukerkonto pr person. Dersom du skal ha flere brukere pÃ¥ din IP-adresse skal dette vÃ¦re dokumentert at det ikke er deg som eier begge.<br>\r\n<br>\r\n<h3 style=\"color:white\">Â§5 Grafisk innhold</h3>\r\nSpillet er ment for unge voksne og voksne, derfor kan det inneholde grafiske innhold pÃ¥ siden. Alt av pornografisk innhold er ulovlig og vil bli slettet av forum moderator / moderator.<br>\r\n<br>\r\n<h3 style=\"color:white\">Â§6 Negative omtaler</h3>\r\nDet er ikke lov Ã¥ promotere for andre mafiaspill pÃ¥ mafioso, dette fÃ¸rer til utestengelse.<br>\r\n<br>\r\n<h3 style=\"color:white\">Â§7 Tredjeparts programmer</h3>\r\nVed bruk av tredjeparts programmer som autoclicker, bot osv vil det bli gitt utestengelse uten forvarsel.<br>\r\n<br>', 1633027790),
(25, 'Hurtig oppdrag', 'Hurtig oppdrag er noe du kan utfÃ¸re hver time. Det er kun 1 hurtig oppdrag som blir lagt ut, og det er kun 1 person som kan utfÃ¸re det hurtige oppdraget. Det er derfor fÃ¸rste mann til mÃ¸lla prinsippet som gjelder.<br>\r\nDet vil komme 1 bil-krav og 1 ting-krav. Utbetalingen for det hurtige oppdraget vil variere for hvor sjeldne de bilene og tingene er.<br><br>\r\nUtbetaling:<br>\r\n\r\n<table>\r\n    <tr>\r\n        <th>Kategori</th>\r\n        <th>Penger pr bil/ting</th>\r\n        <th>EXP pr bil/ting</th>\r\n    </tr>\r\n    <tr>\r\n        <td>Low-tier</td>\r\n        <td>2 000 000</td>\r\n        <td>1</td>\r\n    </tr>\r\n    <tr>\r\n        <td>mid-tier</td>\r\n        <td>5 000 000</td>\r\n        <td>2</td>\r\n    </tr>\r\n    <tr>\r\n        <td>high-tier</td>\r\n        <td>10 000 000</td>\r\n        <td>4</td>\r\n    </tr>\r\n</table>', 1633015729),
(26, 'Biler', 'Under vil du se listen over alle bilene i spillet. <br><br>\r\n<table>\r\n    <tbody><tr>\r\n        <th>Alternativ</th>\r\n        <th>Bil</th>\r\n        <th>Pris</th>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Toyota Corolla</td>\r\n        <td>23 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Mercedes-Benz C-Klasse</td>\r\n        <td>41 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Honda Jazz</td>\r\n        <td>13 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Volvo 240</td>\r\n        <td>8 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Toyota Verso</td>\r\n        <td>63 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Audi A4</td>\r\n        <td>81 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Audi Q3</td>\r\n        <td>229 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Mazda CX-3</td>\r\n        <td>184 900</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>BMW X3</td>\r\n        <td>175 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Tesla model Y</td>\r\n        <td>298 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Mercedes-Benz E-Klasse</td>\r\n        <td>149 700</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Nissan Qashqai</td>\r\n        <td>138 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Audi e-tron 55</td>\r\n        <td>789 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>BMW X5</td>\r\n        <td>829 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Tesla Model X</td>\r\n        <td>819 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Jaguar I-Pace</td>\r\n        <td>759 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Mercedes-Benz Gelandewagen G63</td>\r\n        <td>899 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Audi Q4</td>\r\n        <td>898 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Rolls Royce Wraith</td>\r\n        <td>5 450 000</td>\r\n    </tr>\r\n    </tbody></table>', 1633015671),
(27, 'Ting', 'Under vil du se en liste over alle ting i spillet <br><br>\r\n<table>\r\n    <tbody><tr>\r\n        <th>Alternativ</th>\r\n        <th>Ting</th>\r\n        <th>Pris</th>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Laptop</td>\r\n        <td>3 500</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>StÃ¸vsuger</td>\r\n        <td>2 100</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Potteplante</td>\r\n        <td>500</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Hasjplante</td>\r\n        <td>750</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Komfyr</td>\r\n        <td>2 400</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>DÃ¸rmatte</td>\r\n        <td>120</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Ringeklokke</td>\r\n        <td>50</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Sko-stativ</td>\r\n        <td>500</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Pokal</td>\r\n        <td>250</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1</td>\r\n        <td>Motorolje</td>\r\n        <td>900</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Dobbeltseng</td>\r\n        <td>5 400</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>KjÃ¸leskap</td>\r\n        <td>5 300</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>TÃ¸rketrommel</td>\r\n        <td>4 200</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Stereoanlegg</td>\r\n        <td>2 400</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>55\" tommer TV</td>\r\n        <td>4 444</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>75\" TV</td>\r\n        <td>7 777</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>StasjonÃ¦r PC</td>\r\n        <td>8 240</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Pass</td>\r\n        <td>3 200</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Visakort</td>\r\n        <td>0</td>\r\n    </tr>\r\n        <tr>\r\n        <td>2</td>\r\n        <td>Mastercard</td>\r\n        <td>0</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Diamant ring</td>\r\n        <td>15 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Pushwagner maleri</td>\r\n        <td>35 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Persisk teppe</td>\r\n        <td>17 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Gullklokke</td>\r\n        <td>14 000</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Gull pokal</td>\r\n        <td>7 200</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Bitz bestikk</td>\r\n        <td>1 750</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Lysestake</td>\r\n        <td>690</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Damp-ovn</td>\r\n        <td>4 995</td>\r\n    </tr>\r\n        <tr>\r\n        <td>3</td>\r\n        <td>Samsung smart fridge</td>\r\n        <td>44 900</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1, 2, 3</td>\r\n        <td>Energidrikk</td>\r\n        <td>0</td>\r\n    </tr>\r\n        <tr>\r\n        <td>1, 2, 3</td>\r\n        <td>Hemmelig kiste</td>\r\n        <td>0</td>\r\n    </tr>\r\n    </tbody></table>', 1642069845),
(28, 'Aksjemarked', 'PÃ¥ aksjemarked har du mulighet for Ã¥ kjÃ¸pe opposisjoner i fiktive firmaer.<br>\r\nNÃ¥r du kjÃ¸per en aksje og firmaet du har opposisjoner i gÃ¥r konkurs vil den slÃ¥ seg konkurs og du vil miste alle dine aksjer i dette firmaet.<br>\r\nPrisene oppdateres hvert 10. minutt og er styrt av en tilfeldig generator.', 1633015629),
(30, 'Territorium', '<p>Familien har mulighet for Ã¥ ta over 1 by. Denne byen er familien sitt ansvar og familien vil bli belÃ¸nnet med 10% av penger fra kriminalitet, 10% av verdien til bilen og tingen som stjeles og 10% av heist. Pengene blir utbetalt ved midnatt, det vil si at dersom en annen familie Ã¸nsker Ã¥ ta over samme by, sÃ¥ mÃ¥ de ta den over 23:59 for Ã¥ fÃ¥ pengene som skulle blitt utbetalt til den originale familien.</p>\r\n<p>AltsÃ¥ er det 1 000 000 000kr i territoriumet sÃ¥ vil disse fortsatt ligge i banken og den nye eieren av byen vil kunne stjele dette.<br>Kulene til angrepet mÃ¥ ligge i <b><a href=\"?side=familie&p=kulelager\">familiens kulelager</a></b>.</p>\r\n<p>Forsvar mÃ¥ bygges opp av familien for Ã¥ unngÃ¥ at noen stjeler territoriumet fra dem. Prisen for Ã¥ angripe en familie er 1 kule pr 1000 forsvar.</p>\r\n<p>Dersom man eier en by, altsÃ¥ har tatt over et territorium, sÃ¥ eier man denne byen til noen andre tar over den, eller til familien blir lagt ned. Territorium kan ogsÃ¥ selges til andre familier mot en gitt pris.</p>\r\n<p>Om man tar over en by som ikke har en eier fra fÃ¸r, koster dette 500 000 000 kr.</p>\r\n<p>NÃ¥r man er eier av et territorium har man mulighet for Ã¥ se hvor mange innbyggere det er i denne byen.</p>', 1639954126),
(32, 'Krypto mining', 'Velkomen til krypto mining! Jeg skal lÃ¦re deg alt du trenger Ã¥ vite for Ã¥ komme i gang med krypto mining. FÃ¸r vi starter kan jeg kjapt fortelle at krypto mining vil gi deg penger hver time, det er opp til deg om du Ã¸nsker Ã¥ selge de eller ikke. NÃ¥r du miner krypto vil du fÃ¥ et antall ethereum (ETH) basert pÃ¥ hvor mange skjermkort du har. Prisen endres hvert minutt og det kan vÃ¦re en god ide Ã¥ selge ethereum nÃ¥r prisen er hÃ¸y.\r\n<br><br>\r\nNoe du mÃ¥ passe pÃ¥ er overoppheting. Dersom du overoppheter mining riggen vil du kunne skade komponenter og mest sannsynelig Ã¸delegge disse. Du kan se temperaturen pÃ¥ hÃ¸yre siden\r\n<br><br>\r\nFor Ã¥ sjekke hvor mye penger du fÃ¥r i timen for din mining rig ser du dette i menyen pÃ¥ hÃ¸yre side\r\n<br><br>\r\nFor Ã¥ ikke overopphete mining riggen din mÃ¥ du fÃ¸lge disse guidene: <br>Maks 5 skjermkort per hovedkort.<br>Minst 2 vifter per skjermkort<br>Maks 1 PSU per hovedkort', 1633102516),
(33, 'Bunker', '<b>KjÃ¸p av bunker</b><br>\r\nDet er kun mulig Ã¥ eie 4 eiendeler samtidig og kun 1 bunker per person.\r\nPrisen pÃ¥ bunker ligger pÃ¥ 100 000 000kr\r\n<br><br>\r\n<b>Som eier av bunker</b><br>\r\nNÃ¥r du eier en bunker kan du selv velge prisen pÃ¥ bruk av bunkere. Prisen kan ikke vÃ¦re mindre enn 0 kr eller hÃ¸yere enn 100 000kr. Dersom noen bruker din bunker vil du kunne se hvem som har brukt den pÃ¥ \"Mine bedrifter\"\r\n<br><br>\r\n<b>Ved bruk av bunker</b><br>\r\nDu kan velge Ã¥ enten bruke staten sin bunker for 500 000kr per bruk, eller en privat bunker som har pris pÃ¥ 100 000 eller mindre. NÃ¥r du er i bunker er det ikke mulig Ã¥ drepe deg og du kan ikke drepe andre. Det er heller ikke mulig Ã¥ utfÃ¸re kriminelle handlinger i en bunker.\r\n<br><br>\r\n<b>Drap</b><br>\r\nDersom man skyter noen men bommer vil man ikke fÃ¥ vite om brukeren er i bunker eller om du er i feil by.\r\nBrukeren du skyter vil fÃ¥ vite hvor nÃ¦rme du var pÃ¥ angrepet.\r\n', 1641583232),
(34, 'Boost ved dÃ¸d', 'Det er helt normalt Ã¥ dÃ¸ pÃ¥ et mafiaspill, men for at vi Ã¸nsker Ã¥ ta vare pÃ¥ spillerne vÃ¥re fÃ¥r man diverse goder ved registrering av ny bruker.\r\nDersom man dÃ¸r har man nÃ¥ mulighet for Ã¥ fÃ¥ en boost pÃ¥ ny bruker. Dette er 10% av ranken du har og noen andre goder. Dersom du lurer pÃ¥ hva du fÃ¥r kan du gÃ¥ pÃ¥ \"hovedkvarter\".\r\nFor Ã¥ fÃ¥ gode mÃ¥ du vÃ¦re over ranken RÃ¥dmann.', 1641583194);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`ACC_id`);

--
-- Indexes for table `accounts_stat`
--
ALTER TABLE `accounts_stat`
  ADD PRIMARY KEY (`AS_id`);

--
-- Indexes for table `bank_transfer`
--
ALTER TABLE `bank_transfer`
  ADD PRIMARY KEY (`BT_id`);

--
-- Indexes for table `banned`
--
ALTER TABLE `banned`
  ADD PRIMARY KEY (`BAN_id`);

--
-- Indexes for table `bedrift_daily`
--
ALTER TABLE `bedrift_daily`
  ADD PRIMARY KEY (`BEDA_id`);

--
-- Indexes for table `bedrift_inntekt`
--
ALTER TABLE `bedrift_inntekt`
  ADD PRIMARY KEY (`BEIN_id`);

--
-- Indexes for table `bedrift_inntekt_history`
--
ALTER TABLE `bedrift_inntekt_history`
  ADD PRIMARY KEY (`BEIN_id`);

--
-- Indexes for table `beskyttelse`
--
ALTER TABLE `beskyttelse`
  ADD PRIMARY KEY (`BESK_id`);

--
-- Indexes for table `blackjack_active`
--
ALTER TABLE `blackjack_active`
  ADD PRIMARY KEY (`BJ_id`);

--
-- Indexes for table `blackjack_owner`
--
ALTER TABLE `blackjack_owner`
  ADD PRIMARY KEY (`BJO_id`);

--
-- Indexes for table `block`
--
ALTER TABLE `block`
  ADD PRIMARY KEY (`BL_id`);

--
-- Indexes for table `boost_account`
--
ALTER TABLE `boost_account`
  ADD PRIMARY KEY (`BOACC_id`);

--
-- Indexes for table `bundles`
--
ALTER TABLE `bundles`
  ADD PRIMARY KEY (`BUNDLE_id`);

--
-- Indexes for table `bunker_active`
--
ALTER TABLE `bunker_active`
  ADD PRIMARY KEY (`BUNACT_id`);

--
-- Indexes for table `bunker_chosen`
--
ALTER TABLE `bunker_chosen`
  ADD PRIMARY KEY (`BUNCHO_id`);

--
-- Indexes for table `bunker_log`
--
ALTER TABLE `bunker_log`
  ADD PRIMARY KEY (`BUNK_id`);

--
-- Indexes for table `bunker_owner`
--
ALTER TABLE `bunker_owner`
  ADD PRIMARY KEY (`BUNOWN_id`);

--
-- Indexes for table `cashback`
--
ALTER TABLE `cashback`
  ADD PRIMARY KEY (`CB_id`);

--
-- Indexes for table `changelog`
--
ALTER TABLE `changelog`
  ADD PRIMARY KEY (`CL_id`);

--
-- Indexes for table `charm`
--
ALTER TABLE `charm`
  ADD PRIMARY KEY (`CH_id`);

--
-- Indexes for table `city_tax`
--
ALTER TABLE `city_tax`
  ADD PRIMARY KEY (`CTAX_id`);

--
-- Indexes for table `cooldown`
--
ALTER TABLE `cooldown`
  ADD PRIMARY KEY (`CD_id`);

--
-- Indexes for table `crime_chance`
--
ALTER TABLE `crime_chance`
  ADD PRIMARY KEY (`CCH_id`);

--
-- Indexes for table `crypto`
--
ALTER TABLE `crypto`
  ADD PRIMARY KEY (`CRYPTO_id`);

--
-- Indexes for table `crypto_rigs`
--
ALTER TABLE `crypto_rigs`
  ADD PRIMARY KEY (`CRR_id`);

--
-- Indexes for table `crypto_user`
--
ALTER TABLE `crypto_user`
  ADD PRIMARY KEY (`CRU_id`);

--
-- Indexes for table `dagens_utfordring`
--
ALTER TABLE `dagens_utfordring`
  ADD PRIMARY KEY (`DAUT_id`);

--
-- Indexes for table `dagens_utfordring_user`
--
ALTER TABLE `dagens_utfordring_user`
  ADD PRIMARY KEY (`DAUTUS_id`);

--
-- Indexes for table `detektiv`
--
ALTER TABLE `detektiv`
  ADD PRIMARY KEY (`DETK_id`);

--
-- Indexes for table `detektiv_sok`
--
ALTER TABLE `detektiv_sok`
  ADD PRIMARY KEY (`DETSOK_id`);

--
-- Indexes for table `double_xp`
--
ALTER TABLE `double_xp`
  ADD PRIMARY KEY (`DX_id`);

--
-- Indexes for table `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`FAM_id`);

--
-- Indexes for table `family_applicant`
--
ALTER TABLE `family_applicant`
  ADD PRIMARY KEY (`FAMAP_id`);

--
-- Indexes for table `family_donation`
--
ALTER TABLE `family_donation`
  ADD PRIMARY KEY (`FAMDON_id`);

--
-- Indexes for table `family_member`
--
ALTER TABLE `family_member`
  ADD PRIMARY KEY (`FAMMEM_id`);

--
-- Indexes for table `fengsel`
--
ALTER TABLE `fengsel`
  ADD PRIMARY KEY (`FENG_ID`);

--
-- Indexes for table `fengsel_direktor`
--
ALTER TABLE `fengsel_direktor`
  ADD PRIMARY KEY (`FENGDI_id`);

--
-- Indexes for table `flyplass`
--
ALTER TABLE `flyplass`
  ADD PRIMARY KEY (`FP_id`);

--
-- Indexes for table `flyplass_pris`
--
ALTER TABLE `flyplass_pris`
  ADD PRIMARY KEY (`FLYPRIS_id`);

--
-- Indexes for table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`FRM_id`);

--
-- Indexes for table `forum_edited`
--
ALTER TABLE `forum_edited`
  ADD PRIMARY KEY (`FEDIT_id`);

--
-- Indexes for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD PRIMARY KEY (`FLIKES_id`);

--
-- Indexes for table `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`GA_id`);

--
-- Indexes for table `gta_chance`
--
ALTER TABLE `gta_chance`
  ADD PRIMARY KEY (`GCH_id`);

--
-- Indexes for table `half_fp`
--
ALTER TABLE `half_fp`
  ADD PRIMARY KEY (`HALFFP_id`);

--
-- Indexes for table `heatmap`
--
ALTER TABLE `heatmap`
  ADD PRIMARY KEY (`HM_id`);

--
-- Indexes for table `heist`
--
ALTER TABLE `heist`
  ADD PRIMARY KEY (`HEIST_id`);

--
-- Indexes for table `heist_members`
--
ALTER TABLE `heist_members`
  ADD PRIMARY KEY (`HEIME_id`);

--
-- Indexes for table `hurtig_oppdrag`
--
ALTER TABLE `hurtig_oppdrag`
  ADD PRIMARY KEY (`HO_id`);

--
-- Indexes for table `kast_mynt`
--
ALTER TABLE `kast_mynt`
  ADD PRIMARY KEY (`KM_id`);

--
-- Indexes for table `keep_me_login`
--
ALTER TABLE `keep_me_login`
  ADD PRIMARY KEY (`KMLI_id`);

--
-- Indexes for table `kf_owner`
--
ALTER TABLE `kf_owner`
  ADD PRIMARY KEY (`KFOW_id`);

--
-- Indexes for table `konk`
--
ALTER TABLE `konk`
  ADD PRIMARY KEY (`KONK_id`);

--
-- Indexes for table `konk_user`
--
ALTER TABLE `konk_user`
  ADD PRIMARY KEY (`KU_id`);

--
-- Indexes for table `last_events`
--
ALTER TABLE `last_events`
  ADD PRIMARY KEY (`LAEV_id`);

--
-- Indexes for table `last_race`
--
ALTER TABLE `last_race`
  ADD PRIMARY KEY (`LR_id`);

--
-- Indexes for table `last_stjel`
--
ALTER TABLE `last_stjel`
  ADD PRIMARY KEY (`LASTJ_id`);

--
-- Indexes for table `lotto`
--
ALTER TABLE `lotto`
  ADD PRIMARY KEY (`LOTTO_id`);

--
-- Indexes for table `lotto_winner`
--
ALTER TABLE `lotto_winner`
  ADD PRIMARY KEY (`LOTTOW_id`);

--
-- Indexes for table `marked`
--
ALTER TABLE `marked`
  ADD PRIMARY KEY (`MARKED_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`NO_id`);

--
-- Indexes for table `nyheter`
--
ALTER TABLE `nyheter`
  ADD PRIMARY KEY (`NYH_id`);

--
-- Indexes for table `pm`
--
ALTER TABLE `pm`
  ADD PRIMARY KEY (`PM_id`);

--
-- Indexes for table `pm_new`
--
ALTER TABLE `pm_new`
  ADD PRIMARY KEY (`PMN_id`);

--
-- Indexes for table `pm_text`
--
ALTER TABLE `pm_text`
  ADD PRIMARY KEY (`PMT_id`);

--
-- Indexes for table `poeng_betingelse`
--
ALTER TABLE `poeng_betingelse`
  ADD PRIMARY KEY (`PBET_id`);

--
-- Indexes for table `poeng_payments`
--
ALTER TABLE `poeng_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `poeng_products`
--
ALTER TABLE `poeng_products`
  ADD PRIMARY KEY (`PRO_id`);

--
-- Indexes for table `poeng_products_test`
--
ALTER TABLE `poeng_products_test`
  ADD PRIMARY KEY (`PRO_id`);

--
-- Indexes for table `poeng_sold`
--
ALTER TABLE `poeng_sold`
  ADD PRIMARY KEY (`PSO_id`);

--
-- Indexes for table `race_club`
--
ALTER TABLE `race_club`
  ADD PRIMARY KEY (`RC_id`);

--
-- Indexes for table `rc_owner`
--
ALTER TABLE `rc_owner`
  ADD PRIMARY KEY (`RCOWN_id`);

--
-- Indexes for table `rob_chance`
--
ALTER TABLE `rob_chance`
  ADD PRIMARY KEY (`RCH_id`);

--
-- Indexes for table `smugling`
--
ALTER TABLE `smugling`
  ADD PRIMARY KEY (`SMUG_id`);

--
-- Indexes for table `smugling_daily`
--
ALTER TABLE `smugling_daily`
  ADD PRIMARY KEY (`SMDAI_id`);

--
-- Indexes for table `steal_chance`
--
ALTER TABLE `steal_chance`
  ADD PRIMARY KEY (`STCH_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`ST_id`);

--
-- Indexes for table `stocks_logg`
--
ALTER TABLE `stocks_logg`
  ADD PRIMARY KEY (`STLG_id`);

--
-- Indexes for table `stock_behold`
--
ALTER TABLE `stock_behold`
  ADD PRIMARY KEY (`STB_id`);

--
-- Indexes for table `stock_list`
--
ALTER TABLE `stock_list`
  ADD PRIMARY KEY (`SLI_id`);

--
-- Indexes for table `super_helg`
--
ALTER TABLE `super_helg`
  ADD PRIMARY KEY (`SHELG_id`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`SP_id`);

--
-- Indexes for table `supported_crypto`
--
ALTER TABLE `supported_crypto`
  ADD PRIMARY KEY (`SUC_id`);

--
-- Indexes for table `swiss`
--
ALTER TABLE `swiss`
  ADD PRIMARY KEY (`SWISS_id`);

--
-- Indexes for table `swiss_account`
--
ALTER TABLE `swiss_account`
  ADD PRIMARY KEY (`SWIACC_id`);

--
-- Indexes for table `terninger`
--
ALTER TABLE `terninger`
  ADD PRIMARY KEY (`TERN_id`);

--
-- Indexes for table `territorium`
--
ALTER TABLE `territorium`
  ADD PRIMARY KEY (`TE_id`);

--
-- Indexes for table `things`
--
ALTER TABLE `things`
  ADD PRIMARY KEY (`TH_id`);

--
-- Indexes for table `uploaded_avatar`
--
ALTER TABLE `uploaded_avatar`
  ADD PRIMARY KEY (`UOA_id`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`UL_id`);

--
-- Indexes for table `user_statistics`
--
ALTER TABLE `user_statistics`
  ADD PRIMARY KEY (`US_id`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`VER_acc_id`);

--
-- Indexes for table `verv`
--
ALTER TABLE `verv`
  ADD PRIMARY KEY (`VERV_id`);

--
-- Indexes for table `wiki`
--
ALTER TABLE `wiki`
  ADD PRIMARY KEY (`WIKI_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ACC_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts_stat`
--
ALTER TABLE `accounts_stat`
  MODIFY `AS_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_transfer`
--
ALTER TABLE `bank_transfer`
  MODIFY `BT_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banned`
--
ALTER TABLE `banned`
  MODIFY `BAN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bedrift_daily`
--
ALTER TABLE `bedrift_daily`
  MODIFY `BEDA_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bedrift_inntekt`
--
ALTER TABLE `bedrift_inntekt`
  MODIFY `BEIN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bedrift_inntekt_history`
--
ALTER TABLE `bedrift_inntekt_history`
  MODIFY `BEIN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beskyttelse`
--
ALTER TABLE `beskyttelse`
  MODIFY `BESK_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blackjack_active`
--
ALTER TABLE `blackjack_active`
  MODIFY `BJ_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blackjack_owner`
--
ALTER TABLE `blackjack_owner`
  MODIFY `BJO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `block`
--
ALTER TABLE `block`
  MODIFY `BL_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boost_account`
--
ALTER TABLE `boost_account`
  MODIFY `BOACC_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bundles`
--
ALTER TABLE `bundles`
  MODIFY `BUNDLE_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bunker_active`
--
ALTER TABLE `bunker_active`
  MODIFY `BUNACT_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bunker_chosen`
--
ALTER TABLE `bunker_chosen`
  MODIFY `BUNCHO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bunker_log`
--
ALTER TABLE `bunker_log`
  MODIFY `BUNK_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bunker_owner`
--
ALTER TABLE `bunker_owner`
  MODIFY `BUNOWN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashback`
--
ALTER TABLE `cashback`
  MODIFY `CB_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `changelog`
--
ALTER TABLE `changelog`
  MODIFY `CL_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charm`
--
ALTER TABLE `charm`
  MODIFY `CH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_tax`
--
ALTER TABLE `city_tax`
  MODIFY `CTAX_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cooldown`
--
ALTER TABLE `cooldown`
  MODIFY `CD_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crime_chance`
--
ALTER TABLE `crime_chance`
  MODIFY `CCH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crypto`
--
ALTER TABLE `crypto`
  MODIFY `CRYPTO_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `crypto_rigs`
--
ALTER TABLE `crypto_rigs`
  MODIFY `CRR_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crypto_user`
--
ALTER TABLE `crypto_user`
  MODIFY `CRU_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dagens_utfordring`
--
ALTER TABLE `dagens_utfordring`
  MODIFY `DAUT_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dagens_utfordring_user`
--
ALTER TABLE `dagens_utfordring_user`
  MODIFY `DAUTUS_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detektiv`
--
ALTER TABLE `detektiv`
  MODIFY `DETK_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detektiv_sok`
--
ALTER TABLE `detektiv_sok`
  MODIFY `DETSOK_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `double_xp`
--
ALTER TABLE `double_xp`
  MODIFY `DX_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family`
--
ALTER TABLE `family`
  MODIFY `FAM_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_applicant`
--
ALTER TABLE `family_applicant`
  MODIFY `FAMAP_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_donation`
--
ALTER TABLE `family_donation`
  MODIFY `FAMDON_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `family_member`
--
ALTER TABLE `family_member`
  MODIFY `FAMMEM_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fengsel`
--
ALTER TABLE `fengsel`
  MODIFY `FENG_ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fengsel_direktor`
--
ALTER TABLE `fengsel_direktor`
  MODIFY `FENGDI_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flyplass`
--
ALTER TABLE `flyplass`
  MODIFY `FP_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flyplass_pris`
--
ALTER TABLE `flyplass_pris`
  MODIFY `FLYPRIS_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum`
--
ALTER TABLE `forum`
  MODIFY `FRM_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_edited`
--
ALTER TABLE `forum_edited`
  MODIFY `FEDIT_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `FLIKES_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `garage`
--
ALTER TABLE `garage`
  MODIFY `GA_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gta_chance`
--
ALTER TABLE `gta_chance`
  MODIFY `GCH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `half_fp`
--
ALTER TABLE `half_fp`
  MODIFY `HALFFP_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `heatmap`
--
ALTER TABLE `heatmap`
  MODIFY `HM_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `heist`
--
ALTER TABLE `heist`
  MODIFY `HEIST_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `heist_members`
--
ALTER TABLE `heist_members`
  MODIFY `HEIME_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hurtig_oppdrag`
--
ALTER TABLE `hurtig_oppdrag`
  MODIFY `HO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kast_mynt`
--
ALTER TABLE `kast_mynt`
  MODIFY `KM_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keep_me_login`
--
ALTER TABLE `keep_me_login`
  MODIFY `KMLI_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kf_owner`
--
ALTER TABLE `kf_owner`
  MODIFY `KFOW_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konk`
--
ALTER TABLE `konk`
  MODIFY `KONK_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konk_user`
--
ALTER TABLE `konk_user`
  MODIFY `KU_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `last_events`
--
ALTER TABLE `last_events`
  MODIFY `LAEV_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `last_race`
--
ALTER TABLE `last_race`
  MODIFY `LR_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `last_stjel`
--
ALTER TABLE `last_stjel`
  MODIFY `LASTJ_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lotto`
--
ALTER TABLE `lotto`
  MODIFY `LOTTO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lotto_winner`
--
ALTER TABLE `lotto_winner`
  MODIFY `LOTTOW_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marked`
--
ALTER TABLE `marked`
  MODIFY `MARKED_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `NO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nyheter`
--
ALTER TABLE `nyheter`
  MODIFY `NYH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm`
--
ALTER TABLE `pm`
  MODIFY `PM_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_new`
--
ALTER TABLE `pm_new`
  MODIFY `PMN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pm_text`
--
ALTER TABLE `pm_text`
  MODIFY `PMT_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poeng_betingelse`
--
ALTER TABLE `poeng_betingelse`
  MODIFY `PBET_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poeng_payments`
--
ALTER TABLE `poeng_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poeng_products`
--
ALTER TABLE `poeng_products`
  MODIFY `PRO_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `poeng_products_test`
--
ALTER TABLE `poeng_products_test`
  MODIFY `PRO_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `poeng_sold`
--
ALTER TABLE `poeng_sold`
  MODIFY `PSO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `race_club`
--
ALTER TABLE `race_club`
  MODIFY `RC_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rc_owner`
--
ALTER TABLE `rc_owner`
  MODIFY `RCOWN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rob_chance`
--
ALTER TABLE `rob_chance`
  MODIFY `RCH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smugling`
--
ALTER TABLE `smugling`
  MODIFY `SMUG_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smugling_daily`
--
ALTER TABLE `smugling_daily`
  MODIFY `SMDAI_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `steal_chance`
--
ALTER TABLE `steal_chance`
  MODIFY `STCH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `ST_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks_logg`
--
ALTER TABLE `stocks_logg`
  MODIFY `STLG_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_behold`
--
ALTER TABLE `stock_behold`
  MODIFY `STB_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_list`
--
ALTER TABLE `stock_list`
  MODIFY `SLI_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `super_helg`
--
ALTER TABLE `super_helg`
  MODIFY `SHELG_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `SP_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supported_crypto`
--
ALTER TABLE `supported_crypto`
  MODIFY `SUC_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `swiss`
--
ALTER TABLE `swiss`
  MODIFY `SWISS_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `swiss_account`
--
ALTER TABLE `swiss_account`
  MODIFY `SWIACC_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terninger`
--
ALTER TABLE `terninger`
  MODIFY `TERN_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `territorium`
--
ALTER TABLE `territorium`
  MODIFY `TE_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `things`
--
ALTER TABLE `things`
  MODIFY `TH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploaded_avatar`
--
ALTER TABLE `uploaded_avatar`
  MODIFY `UOA_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `UL_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_statistics`
--
ALTER TABLE `user_statistics`
  MODIFY `US_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verv`
--
ALTER TABLE `verv`
  MODIFY `VERV_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wiki`
--
ALTER TABLE `wiki`
  MODIFY `WIKI_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
