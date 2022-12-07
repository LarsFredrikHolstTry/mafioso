-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 07. Des, 2022 22:07 PM
-- Tjener-versjon: 10.4.22-MariaDB
-- PHP Version: 8.1.2

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
  `ACC_register_date` int(15) NOT NULL,
  `ACC_ip_register` varchar(255) NOT NULL,
  `ACC_type` int(255) NOT NULL,
  `ACC_last_active` int(15) NOT NULL,
  `ACC_ip_latest` varchar(255) NOT NULL,
  `ACC_status` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `accounts`
--

INSERT INTO `accounts` (`ACC_id`, `ACC_username`, `ACC_password`, `ACC_mail`, `ACC_register_date`, `ACC_ip_register`, `ACC_type`, `ACC_last_active`, `ACC_ip_latest`, `ACC_status`) VALUES
(1, 'Test', '$2y$10$OPqIGVmhQgzxYFuxQR4Vb.jFGEdRzBzEaJeJJ35MyTA/Qab8dbuOC', 'Test@test.test', 1670446860, '::1', 3, 1670447065, '::1', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `accounts_stat`
--

CREATE TABLE `accounts_stat` (
  `AS_id` int(255) NOT NULL,
  `AS_money` varchar(255) NOT NULL,
  `AS_bankmoney` varchar(255) NOT NULL DEFAULT '0',
  `AS_points` int(255) NOT NULL,
  `AS_exp` varchar(255) NOT NULL DEFAULT '0',
  `AS_rank` int(25) NOT NULL,
  `AS_city` int(5) NOT NULL,
  `AS_health` int(255) NOT NULL,
  `AS_avatar` varchar(255) NOT NULL DEFAULT '././img/avatar/standard_avatar.png',
  `AS_bio` text NOT NULL,
  `AS_bio_bg_image` varchar(255) DEFAULT NULL,
  `AS_bio_bg_color` varchar(7) DEFAULT NULL,
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
  `AS_mission_count` bigint(55) NOT NULL,
  `AS_eiendom` int(255) NOT NULL,
  `AS_bio_bg_active` enum('color','url') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `accounts_stat`
--

INSERT INTO `accounts_stat` (`AS_id`, `AS_money`, `AS_bankmoney`, `AS_points`, `AS_exp`, `AS_rank`, `AS_city`, `AS_health`, `AS_avatar`, `AS_bio`, `AS_bio_bg_image`, `AS_bio_bg_color`, `AS_bullets`, `AS_weapon`, `AS_weapon_progress`, `AS_def`, `AS_theme`, `AS_weed`, `AS_daily_exp`, `AS_protection`, `AS_lyddemper`, `AS_boost`, `AS_mission`, `AS_mission_count`, `AS_eiendom`, `AS_bio_bg_active`) VALUES
(1, '0', '1000', 0, '0', 0, 1, 100, '././img/avatar/standard_avatar.png', '', NULL, NULL, 0, 0, 0, '100000', 0, '0', 0, 0, 0, 0, 0, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `bank_transfer`
--

CREATE TABLE `bank_transfer` (
  `BT_id` int(255) NOT NULL,
  `BT_from` varchar(255) NOT NULL,
  `BT_to` varchar(255) NOT NULL,
  `BT_date` varchar(255) NOT NULL,
  `BT_money` varchar(255) NOT NULL,
  `BT_text` text NOT NULL
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

--
-- Dataark for tabell `bedrift_daily`
--

INSERT INTO `bedrift_daily` (`BEDA_id`, `BEDA_type`, `BEDA_city`, `BEDA_amount`, `BEDA_date`) VALUES
(1, 0, 0, '0', 1670446987),
(2, 0, 1, '0', 1670446987),
(3, 0, 2, '0', 1670446987),
(4, 0, 3, '0', 1670446987),
(5, 0, 4, '0', 1670446987),
(6, 0, 5, '0', 1670446987),
(7, 1, 0, '0', 1670446987),
(8, 1, 1, '0', 1670446987),
(9, 1, 2, '0', 1670446987),
(10, 1, 3, '0', 1670446987),
(11, 1, 4, '0', 1670446987),
(12, 1, 5, '0', 1670446987),
(13, 2, 0, '0', 1670446987),
(14, 2, 1, '0', 1670446987),
(15, 2, 2, '0', 1670446987),
(16, 2, 3, '0', 1670446987),
(17, 2, 4, '0', 1670446987),
(18, 2, 5, '0', 1670446987),
(19, 3, 0, '0', 1670446987),
(20, 3, 1, '0', 1670446987),
(21, 3, 2, '0', 1670446987),
(22, 3, 3, '0', 1670446987),
(23, 3, 4, '0', 1670446987),
(24, 3, 5, '0', 1670446987),
(25, 4, 0, '0', 1670446987),
(26, 4, 1, '0', 1670446987),
(27, 4, 2, '0', 1670446987),
(28, 4, 3, '0', 1670446987),
(29, 4, 4, '0', 1670446987),
(30, 4, 5, '0', 1670446987),
(31, 5, 0, '0', 1670446987),
(32, 5, 1, '0', 1670446987),
(33, 5, 2, '0', 1670446987),
(34, 5, 3, '0', 1670446987),
(35, 5, 4, '0', 1670446987),
(36, 5, 5, '0', 1670446987);

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
-- Tabellstruktur for tabell `christmas`
--

CREATE TABLE `christmas` (
  `CHR_id` int(15) NOT NULL,
  `CHR_day` int(2) NOT NULL,
  `CHR_acc_id` int(5) NOT NULL,
  `CHR_counter` int(5) NOT NULL,
  `CHR_outcome` text NOT NULL
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
(1, 0, 60),
(2, 1, 0),
(3, 2, 55),
(4, 3, 40),
(5, 4, 75);

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
  `CD_heist` int(15) NOT NULL,
  `CD_event` int(15) NOT NULL,
  `CD_safe` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `cooldown`
--

INSERT INTO `cooldown` (`CD_id`, `CD_acc_id`, `CD_crime`, `CD_airport`, `CD_gta`, `CD_brekk`, `CD_territorium`, `CD_steal`, `CD_weapon`, `CD_rc`, `CD_race`, `CD_kill`, `CD_heist`, `CD_event`, `CD_safe`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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
-- Tabellstruktur for tabell `dagens_utfordring`
--

CREATE TABLE `dagens_utfordring` (
  `DAUT_id` int(255) NOT NULL,
  `DAUT_date` int(25) NOT NULL,
  `DAUT_json` varchar(255) NOT NULL,
  `DAUT_payout_char` int(255) NOT NULL,
  `DAUT_payout_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `dagens_utfordring`
--

INSERT INTO `dagens_utfordring` (`DAUT_id`, `DAUT_date`, `DAUT_json`, `DAUT_payout_char`, `DAUT_payout_id`) VALUES
(1, 1670446985, '[27,14,10,20]', 3, 1),
(2, 1670446987, '[29,19,17,12]', 3, 1);

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
-- Tabellstruktur for tabell `dirkesett`
--

CREATE TABLE `dirkesett` (
  `DIRK_id` int(255) NOT NULL,
  `DIRK_acc_id` int(15) NOT NULL,
  `DIRK_timeout` int(15) NOT NULL
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
-- Tabellstruktur for tabell `drapsfri`
--

CREATE TABLE `drapsfri` (
  `DRAPFRI_id` int(10) NOT NULL,
  `DRAPFRI_start` int(10) NOT NULL,
  `DRAPFRI_end` int(10) NOT NULL
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
-- Tabellstruktur for tabell `firma`
--

CREATE TABLE `firma` (
  `FIRM_id` int(15) NOT NULL,
  `FIRM_acc_id` int(15) NOT NULL,
  `FIRM_type` int(15) NOT NULL,
  `FIRM_collected` int(1) NOT NULL
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
-- Tabellstruktur for tabell `forgot_password`
--

CREATE TABLE `forgot_password` (
  `FOPA_id` int(255) NOT NULL,
  `FOPA_unique_key` varchar(25) NOT NULL,
  `FOPA_acc_id` varchar(15) NOT NULL,
  `FOPA_date` varchar(15) NOT NULL,
  `FOPA_mail` text NOT NULL
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
-- Tabellstruktur for tabell `heist_chat`
--

CREATE TABLE `heist_chat` (
  `HEICHAT_msg_id` int(11) NOT NULL,
  `HEICHAT_msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `HEICHAT_acc_id` int(11) NOT NULL,
  `HEICHAT_heist_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dataark for tabell `hurtig_oppdrag`
--

INSERT INTO `hurtig_oppdrag` (`HO_id`, `HO_car_id`, `HO_car_amount`, `HO_thing_id`, `HO_thing_amount`, `HO_date`, `HO_status`) VALUES
(1, 1, 2, 1, 2, 1670446982, 0);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `julekalender`
--

CREATE TABLE `julekalender` (
  `JUL_id` int(255) NOT NULL,
  `JUL_status` int(255) NOT NULL,
  `JUL_crimes` int(255) NOT NULL,
  `JUL_acc_id` int(255) NOT NULL
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
-- Tabellstruktur for tabell `notificationsettings`
--

CREATE TABLE `notificationsettings` (
  `NOSE_id` int(25) NOT NULL,
  `NOSE_acc_id` int(25) NOT NULL,
  `NOSE_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`NOSE_json`))
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
-- Tabellstruktur for tabell `peders_ugjerninger`
--

CREATE TABLE `peders_ugjerninger` (
  `handling` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sonetid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `peng_group`
--

CREATE TABLE `peng_group` (
  `PENG_id` int(15) NOT NULL,
  `PENG_leader` int(5) NOT NULL,
  `PENG_money` varchar(255) NOT NULL,
  `PENG_equipment` int(5) NOT NULL,
  `PENG_members` text NOT NULL,
  `PENG_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `peng_logg`
--

CREATE TABLE `peng_logg` (
  `PENGLOG_id` int(255) NOT NULL,
  `PENGLOG_pengID` int(15) NOT NULL,
  `PENGLOG_desc` text NOT NULL,
  `PENGLOG_money` varchar(255) NOT NULL,
  `PENGLOG_leader` int(15) NOT NULL,
  `PENGLOG_date` int(15) NOT NULL
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

--
-- Dataark for tabell `race_club`
--

INSERT INTO `race_club` (`RC_id`, `RC_acc_id`, `RC_drift`, `RC_drag`, `RC_race`) VALUES
(1, 1, 0, 0, 0);

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
-- Tabellstruktur for tabell `safe_attempts`
--

CREATE TABLE `safe_attempts` (
  `SAT_id` int(15) NOT NULL,
  `SAT_acc_id` int(15) NOT NULL,
  `SAT_number` int(4) NOT NULL,
  `SAT_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `safe_number`
--

CREATE TABLE `safe_number` (
  `SAFE_id` int(15) NOT NULL,
  `SAFE_number` int(4) NOT NULL,
  `SAFE_winner` int(15) NOT NULL,
  `SAFE_prize` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `safe_number`
--

INSERT INTO `safe_number` (`SAFE_id`, `SAFE_number`, `SAFE_winner`, `SAFE_prize`) VALUES
(1, 1337, 0, '');

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `shout`
--

CREATE TABLE `shout` (
  `SH_id` int(25) NOT NULL,
  `SH_acc_id` int(25) NOT NULL,
  `SH_message` varchar(255) NOT NULL,
  `SH_date` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `shout_queue`
--

CREATE TABLE `shout_queue` (
  `SHTQUE_id` int(25) NOT NULL,
  `SHTQUE_acc_id` int(25) NOT NULL,
  `SHTQUE_message` varchar(255) NOT NULL,
  `SHTQUE_date` int(15) NOT NULL
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

--
-- Dataark for tabell `stock_list`
--

INSERT INTO `stock_list` (`SLI_id`, `SLI_type`, `SLI_price`, `SLI_date`) VALUES
(1, 0, 15, 1670446983),
(2, 1, 22, 1670446983),
(3, 2, 2, 1670446983),
(4, 3, 2, 1670446983),
(5, 4, 20, 1670446983),
(6, 0, 12, 1670446994),
(7, 1, 24, 1670446994),
(8, 2, 1, 1670446994),
(9, 3, 17, 1670446994),
(10, 4, 16, 1670446994),
(11, 0, 14, 1670446994),
(12, 1, 19, 1670446994),
(13, 2, 1, 1670446994),
(14, 3, 12, 1670446994),
(15, 4, 16, 1670446994),
(16, 0, 9, 1670446994),
(17, 1, 25, 1670446994),
(18, 2, 18, 1670446994),
(19, 3, 20, 1670446994),
(20, 4, 21, 1670446994);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `super_helg`
--

CREATE TABLE `super_helg` (
  `SHELG_id` int(255) NOT NULL,
  `SHELG_start` int(15) NOT NULL,
  `SHELG_title` text NOT NULL,
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
  `UL_handling` text NOT NULL,
  `UL_date` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `user_log`
--

INSERT INTO `user_log` (`UL_id`, `UL_acc_id`, `UL_money_hand`, `UL_money_bank`, `UL_exp`, `UL_city`, `UL_page`, `UL_handling`, `UL_date`) VALUES
(1, 1, '1000', '0', '0', 1, 'banken', 'Setter inn alle penger', 1670447023),
(2, 1, '0', '1000', '0', 1, 'banken', 'Setter inn: 1 000 kr', 1670447023);

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

--
-- Dataark for tabell `user_statistics`
--

INSERT INTO `user_statistics` (`US_id`, `US_acc_id`, `US_krim_v`, `US_krim_m`, `US_gta_v`, `US_gta_m`, `US_brekk_v`, `US_brekk_m`, `US_stjel_v`, `US_stjel_m`, `US_rc_v`, `US_rc_m`, `US_gambling`, `US_money_sent`, `US_money_received`, `US_max_cars`, `US_max_things`, `US_kills`, `US_pageview`, `US_jail`, `US_hurtig_oppdrag`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', 20, 20, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `verification`
--

CREATE TABLE `verification` (
  `VER_acc_id` int(255) NOT NULL,
  `VER_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dataark for tabell `verification`
--

INSERT INTO `verification` (`VER_acc_id`, `VER_hash`) VALUES
(1, '5IDg3EUbkavD1HX0LILWgVEDB');

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

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `world_cup_bet`
--

CREATE TABLE `world_cup_bet` (
  `WC_id` int(15) NOT NULL,
  `WC_acc_id` int(15) NOT NULL,
  `WC_team` int(15) NOT NULL,
  `WC_odds` varchar(25) NOT NULL,
  `WC_bet` varchar(255) NOT NULL,
  `WC_date` int(15) NOT NULL,
  `WC_match_id` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellstruktur for tabell `world_cup_match`
--

CREATE TABLE `world_cup_match` (
  `WCM_id` int(15) NOT NULL,
  `WCM_home` text NOT NULL,
  `WCM_away` text NOT NULL,
  `WCM_home_odds` int(4) NOT NULL,
  `WCM_away_odds` int(4) NOT NULL,
  `WCM_draw_odds` int(4) NOT NULL,
  `WCM_start_date` int(15) NOT NULL,
  `WCM_outcome` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Indexes for table `christmas`
--
ALTER TABLE `christmas`
  ADD PRIMARY KEY (`CHR_id`);

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
-- Indexes for table `dirkesett`
--
ALTER TABLE `dirkesett`
  ADD PRIMARY KEY (`DIRK_id`);

--
-- Indexes for table `double_xp`
--
ALTER TABLE `double_xp`
  ADD PRIMARY KEY (`DX_id`);

--
-- Indexes for table `drapsfri`
--
ALTER TABLE `drapsfri`
  ADD PRIMARY KEY (`DRAPFRI_id`);

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
-- Indexes for table `firma`
--
ALTER TABLE `firma`
  ADD PRIMARY KEY (`FIRM_id`);

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
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`FOPA_id`);

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
-- Indexes for table `heist_chat`
--
ALTER TABLE `heist_chat`
  ADD PRIMARY KEY (`HEICHAT_msg_id`);

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
-- Indexes for table `julekalender`
--
ALTER TABLE `julekalender`
  ADD PRIMARY KEY (`JUL_id`);

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
-- Indexes for table `notificationsettings`
--
ALTER TABLE `notificationsettings`
  ADD PRIMARY KEY (`NOSE_id`);

--
-- Indexes for table `nyheter`
--
ALTER TABLE `nyheter`
  ADD PRIMARY KEY (`NYH_id`);

--
-- Indexes for table `peders_ugjerninger`
--
ALTER TABLE `peders_ugjerninger`
  ADD PRIMARY KEY (`handling`);

--
-- Indexes for table `peng_group`
--
ALTER TABLE `peng_group`
  ADD PRIMARY KEY (`PENG_id`);

--
-- Indexes for table `peng_logg`
--
ALTER TABLE `peng_logg`
  ADD PRIMARY KEY (`PENGLOG_id`);

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
-- Indexes for table `safe_attempts`
--
ALTER TABLE `safe_attempts`
  ADD PRIMARY KEY (`SAT_id`);

--
-- Indexes for table `safe_number`
--
ALTER TABLE `safe_number`
  ADD PRIMARY KEY (`SAFE_id`);

--
-- Indexes for table `shout`
--
ALTER TABLE `shout`
  ADD PRIMARY KEY (`SH_id`);

--
-- Indexes for table `shout_queue`
--
ALTER TABLE `shout_queue`
  ADD PRIMARY KEY (`SHTQUE_id`);

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
-- Indexes for table `world_cup_bet`
--
ALTER TABLE `world_cup_bet`
  ADD PRIMARY KEY (`WC_id`);

--
-- Indexes for table `world_cup_match`
--
ALTER TABLE `world_cup_match`
  ADD PRIMARY KEY (`WCM_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ACC_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `accounts_stat`
--
ALTER TABLE `accounts_stat`
  MODIFY `AS_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `BEDA_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
-- AUTO_INCREMENT for table `christmas`
--
ALTER TABLE `christmas`
  MODIFY `CHR_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `city_tax`
--
ALTER TABLE `city_tax`
  MODIFY `CTAX_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cooldown`
--
ALTER TABLE `cooldown`
  MODIFY `CD_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `crime_chance`
--
ALTER TABLE `crime_chance`
  MODIFY `CCH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dagens_utfordring`
--
ALTER TABLE `dagens_utfordring`
  MODIFY `DAUT_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `dirkesett`
--
ALTER TABLE `dirkesett`
  MODIFY `DIRK_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `double_xp`
--
ALTER TABLE `double_xp`
  MODIFY `DX_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drapsfri`
--
ALTER TABLE `drapsfri`
  MODIFY `DRAPFRI_id` int(10) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `firma`
--
ALTER TABLE `firma`
  MODIFY `FIRM_id` int(15) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `FOPA_id` int(255) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `heist_chat`
--
ALTER TABLE `heist_chat`
  MODIFY `HEICHAT_msg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `heist_members`
--
ALTER TABLE `heist_members`
  MODIFY `HEIME_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hurtig_oppdrag`
--
ALTER TABLE `hurtig_oppdrag`
  MODIFY `HO_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `julekalender`
--
ALTER TABLE `julekalender`
  MODIFY `JUL_id` int(255) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `notificationsettings`
--
ALTER TABLE `notificationsettings`
  MODIFY `NOSE_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nyheter`
--
ALTER TABLE `nyheter`
  MODIFY `NYH_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peng_group`
--
ALTER TABLE `peng_group`
  MODIFY `PENG_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peng_logg`
--
ALTER TABLE `peng_logg`
  MODIFY `PENGLOG_id` int(255) NOT NULL AUTO_INCREMENT;

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
  MODIFY `PRO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poeng_products_test`
--
ALTER TABLE `poeng_products_test`
  MODIFY `PRO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poeng_sold`
--
ALTER TABLE `poeng_sold`
  MODIFY `PSO_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `race_club`
--
ALTER TABLE `race_club`
  MODIFY `RC_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `safe_attempts`
--
ALTER TABLE `safe_attempts`
  MODIFY `SAT_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safe_number`
--
ALTER TABLE `safe_number`
  MODIFY `SAFE_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shout`
--
ALTER TABLE `shout`
  MODIFY `SH_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shout_queue`
--
ALTER TABLE `shout_queue`
  MODIFY `SHTQUE_id` int(25) NOT NULL AUTO_INCREMENT;

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
  MODIFY `SLI_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `UL_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_statistics`
--
ALTER TABLE `user_statistics`
  MODIFY `US_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `verv`
--
ALTER TABLE `verv`
  MODIFY `VERV_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wiki`
--
ALTER TABLE `wiki`
  MODIFY `WIKI_ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `world_cup_bet`
--
ALTER TABLE `world_cup_bet`
  MODIFY `WC_id` int(15) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `world_cup_match`
--
ALTER TABLE `world_cup_match`
  MODIFY `WCM_id` int(15) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
