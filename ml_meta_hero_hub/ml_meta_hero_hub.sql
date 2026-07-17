-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 04:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ml_meta_hero_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `builds`
--

CREATE TABLE `builds` (
  `build_id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `build_name` varchar(100) NOT NULL,
  `build_type` varchar(20) DEFAULT 'Core',
  `items` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `builds`
--

INSERT INTO `builds` (`build_id`, `hero_id`, `build_name`, `build_type`, `items`, `description`) VALUES
(1, 1, 'Frontline Initiator', 'Core', 'Warrior Boots, Dominance Ice, Blade Armor, Athena\'s Shield, Antique Cuirass, Immortality', 'Prioritizes crowd control uptime and damage reduction for reliable engages.'),
(2, 2, 'Hook Lockdown', 'Core', 'Tough Boots, Dominance Ice, Cursed Helmet, Athena\'s Shield, Brute Force Breastplate, Immortality', 'Focuses on cooldown reduction so hooks come off cooldown faster.'),
(3, 3, 'Bruiser Chou', 'Core', 'Warrior Boots, Bloodlust Axe, Blade of Despair, Endless Battle, Brute Force Breastplate, Immortality', 'Balances burst damage with enough survivability to dive backlines.'),
(4, 5, 'Full Burst Ling', 'Core', 'Swift Boots, Raptor Machete, Malefic Roar, Blade of Despair, Endless Battle, Immortality', 'Maximizes burst damage for one-shot potential on squishy targets.'),
(5, 5, 'Sustain Ling', 'Situational', 'Swift Boots, Bloodlust Axe, Malefic Roar, Endless Battle, Wind of Nature, Immortality', 'Trades some burst for lifesteal against poke-heavy lineups.'),
(6, 9, 'Hybrid Beatrix', 'Core', 'Swift Boots, Berserker\'s Fury, Scarlet Phantom, Windtalker, Malefic Roar, Golden Staff', 'Balances physical and magic penetration for her dual weapon kit.'),
(7, 7, 'Burst Mage', 'Core', 'Arcane Boots, Clock of Destiny, Lightning Truncheon, Genius Wand, Divine Glaive, Holy Crystal', 'Stacks magic power and penetration for maximum umbrella burst.'),
(8, 11, 'Late Game Carry', 'Core', 'Swift Boots, Berserker\'s Fury, Wind of Nature, Windtalker, Scarlet Phantom, Malefic Roar', 'Standard attack-speed scaling build for consistent DPS output.'),
(9, 12, 'Global Sustain', 'Core', 'Arcane Boots, Enchanted Talisman, Genius Wand, Oracle, Holy Crystal, Fleeting Time', 'Reduces ultimate cooldown to keep the healing aura active more often.'),
(10, 14, 'Shield Support', 'Core', 'Tough Boots, Enchanted Talisman, Dominance Ice, Oracle, Athena\'s Shield, Ice Queen Wand', 'Provides durability while keeping her attached ally topped up.');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `user_id`, `hero_id`, `created_at`) VALUES
(1, 2, 5, '2026-07-17 14:48:02'),
(2, 2, 9, '2026-07-17 14:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `heroes`
--

CREATE TABLE `heroes` (
  `hero_id` int(11) NOT NULL,
  `hero_name` varchar(100) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `difficulty` varchar(20) DEFAULT 'Normal',
  `overview` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `heroes`
--

INSERT INTO `heroes` (`hero_id`, `hero_name`, `role_id`, `image_url`, `difficulty`, `overview`, `status`) VALUES
(1, 'Tigreal', 1, 'assets/img/heroes/tigreal.png', 'Easy', 'A frontline initiator built around multi-hit crowd control combos that lock down the enemy backline.', 'Available'),
(2, 'Franco', 1, 'assets/img/heroes/franco.png', 'Normal', 'A hook-based tank who fishes for high-value picks from fog of war and drags priority targets out of position.', 'Available'),
(3, 'Chou', 2, 'assets/img/heroes/chou.png', 'Hard', 'A mobile skill-shot fighter known for punish combos and a game-changing ultimate that can steal kills across the map.', 'Available'),
(4, 'Paquito', 2, 'assets/img/heroes/paquito.png', 'Normal', 'A combo-driven fighter that chains dashes and knock-ups for sustained burst damage in extended fights.', 'Available'),
(5, 'Ling', 3, 'assets/img/heroes/ling.png', 'Hard', 'A mobility-heavy assassin who scales walls for repositioning and resets cooldowns on takedowns.', 'Available'),
(6, 'Gusion', 3, 'assets/img/heroes/gusion.png', 'Hard', 'A burst assassin with a short-cooldown flurry combo designed to erase squishy targets in seconds.', 'Available'),
(7, 'Kagura', 4, 'assets/img/heroes/kagura.png', 'Hard', 'An umbrella-controlling mage whose skills interact with each other for extended zone control and burst.', 'Available'),
(8, 'Lylia', 4, 'assets/img/heroes/lylia.png', 'Normal', 'A portal-based mage that dishes out area damage from a distance while staying mobile.', 'Available'),
(9, 'Beatrix', 5, 'assets/img/heroes/beatrix.png', 'Hard', 'A weapon-swapping marksman who adapts her kit to different engagement ranges mid-fight.', 'Available'),
(10, 'Claude', 5, 'assets/img/heroes/claude.png', 'Normal', 'A dual-pistol marksman who steals enemy buffs and chains dash resets during team fights.', 'Available'),
(11, 'Miya', 5, 'assets/img/heroes/miya.png', 'Easy', 'A classic attack-speed marksman that scales safely into the late game with consistent damage.', 'Available'),
(12, 'Estes', 6, 'assets/img/heroes/estes.png', 'Normal', 'A sustain-focused support who heals allies across the map through a linked aura ultimate.', 'Available'),
(13, 'Mathilda', 6, 'assets/img/heroes/mathilda.png', 'Normal', 'A hybrid support-assassin known for early-game roaming pressure and repositioning utility.', 'Available'),
(14, 'Angela', 6, 'assets/img/heroes/angela.png', 'Normal', 'A guardian-spirit support who attaches to allies to shield and empower their damage output.', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `hero_counters`
--

CREATE TABLE `hero_counters` (
  `counter_id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `related_hero_id` int(11) DEFAULT NULL,
  `counter_type` varchar(20) DEFAULT 'Strong Against',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_counters`
--

INSERT INTO `hero_counters` (`counter_id`, `hero_id`, `related_hero_id`, `counter_type`, `notes`) VALUES
(1, 5, 6, 'Strong Against', 'Ling can dodge Gusion\'s combo with wall-climb mobility and punish the cooldown window.'),
(2, 5, 3, 'Weak Against', 'Chou\'s knock-up chains can interrupt Ling before she reaches her target.'),
(3, 6, 8, 'Strong Against', 'Gusion\'s burst combo drops Lylia before her portal skills come off cooldown.'),
(4, 6, 2, 'Weak Against', 'Franco\'s hook punishes Gusion\'s lack of hard crowd-control escape.'),
(5, 3, 9, 'Strong Against', 'Chou\'s mobility lets him close the gap on Beatrix before she can kite.'),
(6, 3, 1, 'Weak Against', 'Tigreal\'s multi-hit stun combo can lock Chou down before he can escape.'),
(7, 9, 11, 'Strong Against', 'Beatrix\'s weapon-swap burst out-trades Miya in extended fights.'),
(8, 9, 5, 'Weak Against', 'Ling\'s mobility closes the distance Beatrix relies on to kite.'),
(9, 1, 7, 'Strong Against', 'Tigreal\'s engage range limits Kagura\'s ability to safely zone from afar.'),
(10, 1, 4, 'Weak Against', 'Paquito\'s combo chains can bypass a single Tigreal stun window.');

-- --------------------------------------------------------

--
-- Table structure for table `hero_stats`
--

CREATE TABLE `hero_stats` (
  `stat_id` int(11) NOT NULL,
  `hero_id` int(11) DEFAULT NULL,
  `patch_id` int(11) DEFAULT NULL,
  `rank_tier` varchar(20) DEFAULT 'Mythic',
  `win_rate` decimal(5,2) DEFAULT NULL,
  `pick_rate` decimal(5,2) DEFAULT NULL,
  `ban_rate` decimal(5,2) DEFAULT NULL,
  `tier_grade` varchar(5) DEFAULT 'B'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_stats`
--

INSERT INTO `hero_stats` (`stat_id`, `hero_id`, `patch_id`, `rank_tier`, `win_rate`, `pick_rate`, `ban_rate`, `tier_grade`) VALUES
(1, 1, 2, 'Mythic', 52.30, 18.40, 12.10, 'A'),
(2, 2, 2, 'Mythic', 50.80, 15.20, 22.60, 'A'),
(3, 3, 2, 'Mythic', 49.60, 12.70, 28.90, 'B'),
(4, 4, 2, 'Mythic', 53.10, 14.30, 9.40, 'A'),
(5, 5, 2, 'Mythic', 51.90, 10.80, 31.50, 'S'),
(6, 6, 2, 'Mythic', 48.70, 16.90, 24.30, 'B'),
(7, 7, 2, 'Mythic', 54.20, 9.60, 18.70, 'S'),
(8, 8, 2, 'Mythic', 50.10, 11.40, 7.80, 'B'),
(9, 9, 2, 'Mythic', 55.40, 13.10, 26.20, 'S'),
(10, 10, 2, 'Mythic', 49.90, 17.60, 15.30, 'B'),
(11, 11, 2, 'Mythic', 51.20, 20.10, 6.50, 'A'),
(12, 12, 2, 'Mythic', 52.60, 8.90, 4.10, 'B'),
(13, 13, 2, 'Mythic', 53.80, 19.30, 11.20, 'A'),
(14, 14, 2, 'Mythic', 50.40, 12.20, 5.60, 'B'),
(15, 1, 2, 'Legend', 51.10, 16.80, 9.20, 'B'),
(16, 3, 2, 'Legend', 50.30, 14.10, 21.40, 'A'),
(17, 5, 2, 'Legend', 52.70, 9.40, 25.80, 'S'),
(18, 9, 2, 'Legend', 53.90, 10.60, 19.90, 'A'),
(19, 11, 2, 'Legend', 50.60, 22.30, 5.10, 'A'),
(20, 1, 1, 'Mythic', 49.80, 17.10, 10.60, 'B'),
(21, 3, 1, 'Mythic', 51.40, 13.90, 24.20, 'A'),
(22, 5, 1, 'Mythic', 50.20, 11.60, 27.30, 'A'),
(23, 9, 1, 'Mythic', 52.90, 12.40, 20.10, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `patches`
--

CREATE TABLE `patches` (
  `patch_id` int(11) NOT NULL,
  `patch_version` varchar(20) NOT NULL,
  `release_date` date DEFAULT NULL,
  `patch_notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Archived'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patches`
--

INSERT INTO `patches` (`patch_id`, `patch_version`, `release_date`, `patch_notes`, `status`) VALUES
(1, '1.8.88', '2026-05-20', 'Early-game jungle experience adjusted; several tank heroes received minor durability nerfs.', 'Archived'),
(2, '1.8.90', '2026-06-24', 'Marksman core items rebalanced; assassin burst combos toned down in the early game; support roaming gold gain increased.', 'Current');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(30) NOT NULL,
  `role_icon` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_icon`) VALUES
(1, 'Tank', '🛡️'),
(2, 'Fighter', '⚔️'),
(3, 'Assassin', '🗡️'),
(4, 'Mage', '🔮'),
(5, 'Marksman', '🏹'),
(6, 'Support', '💚');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'Player',
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'admin123', 'System Administrator', 'Admin', 'Active', '2026-07-17 14:48:02'),
(2, 'shadowstrike', 'player123', 'Shadow Strike', 'Player', 'Active', '2026-07-17 14:48:02'),
(3, 'junglequeen', 'player123', 'Jungle Queen', 'Player', 'Active', '2026-07-17 14:48:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `builds`
--
ALTER TABLE `builds`
  ADD PRIMARY KEY (`build_id`),
  ADD KEY `hero_id` (`hero_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_hero` (`user_id`,`hero_id`),
  ADD KEY `hero_id` (`hero_id`);

--
-- Indexes for table `heroes`
--
ALTER TABLE `heroes`
  ADD PRIMARY KEY (`hero_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `hero_counters`
--
ALTER TABLE `hero_counters`
  ADD PRIMARY KEY (`counter_id`),
  ADD KEY `hero_id` (`hero_id`),
  ADD KEY `related_hero_id` (`related_hero_id`);

--
-- Indexes for table `hero_stats`
--
ALTER TABLE `hero_stats`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `hero_id` (`hero_id`),
  ADD KEY `patch_id` (`patch_id`);

--
-- Indexes for table `patches`
--
ALTER TABLE `patches`
  ADD PRIMARY KEY (`patch_id`),
  ADD UNIQUE KEY `patch_version` (`patch_version`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `builds`
--
ALTER TABLE `builds`
  MODIFY `build_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `heroes`
--
ALTER TABLE `heroes`
  MODIFY `hero_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hero_counters`
--
ALTER TABLE `hero_counters`
  MODIFY `counter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hero_stats`
--
ALTER TABLE `hero_stats`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `patches`
--
ALTER TABLE `patches`
  MODIFY `patch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `builds`
--
ALTER TABLE `builds`
  ADD CONSTRAINT `builds_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`hero_id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`hero_id`);

--
-- Constraints for table `heroes`
--
ALTER TABLE `heroes`
  ADD CONSTRAINT `heroes_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `hero_counters`
--
ALTER TABLE `hero_counters`
  ADD CONSTRAINT `hero_counters_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`hero_id`),
  ADD CONSTRAINT `hero_counters_ibfk_2` FOREIGN KEY (`related_hero_id`) REFERENCES `heroes` (`hero_id`);

--
-- Constraints for table `hero_stats`
--
ALTER TABLE `hero_stats`
  ADD CONSTRAINT `hero_stats_ibfk_1` FOREIGN KEY (`hero_id`) REFERENCES `heroes` (`hero_id`),
  ADD CONSTRAINT `hero_stats_ibfk_2` FOREIGN KEY (`patch_id`) REFERENCES `patches` (`patch_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
