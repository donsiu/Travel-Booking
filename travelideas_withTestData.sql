-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2014 at 05:23 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `travelideas`
--

-- --------------------------------------------------------

--
-- Table structure for table `ideas`
--

CREATE TABLE IF NOT EXISTS `ideas` (
  `idea_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `author_id` int(10) unsigned NOT NULL,
  `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idea_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ideas`
--

INSERT INTO `ideas` (`idea_id`, `title`, `destination`, `start_date`, `end_date`, `author_id`, `create_datetime`, `last_update_datetime`) VALUES
(1, 'Shopping in HK', 'Hong Kong', '2014-06-01', '2014-06-14', 2, '2014-04-05 12:13:56', '2014-04-06 13:40:09'),
(2, 'Sydney offers plenty of historical and contemporary Australian flavor. The marvelous Sydney Opera House looks like a great origami sailboat, floating peacefully in a harbor.', 'Sydney', '2014-06-05', '2014-06-13', 3, '2014-04-05 12:21:53', '2014-04-09 07:16:16'),
(3, 'The festive city of San Juan is the perfect place to experience true Puerto Rican culture.', 'San Juan', '2014-06-05', '2014-06-13', 5, '2014-04-05 12:22:18', '2014-04-09 07:16:29'),
(4, 'The barrier island of Miami Beach is like Miamis classier twin sister equally beautiful and personable, but a little bit calmer, too. The 8th Street Designer District is a haven of high-end shopping.', 'Miami', '2014-06-07', '2014-06-14', 3, '2014-04-05 12:22:56', '2014-04-11 14:27:32'),
(5, 'Let''s go Las Vegas', 'Las Vegas', '2014-06-07', '2014-06-28', 1, '2014-04-05 12:24:14', '2014-04-11 14:38:37'),
(6, 'Playa del Carmen is one of the top dive destinations in the world, thanks to vibrant sea life and dazzling underwater caverns. On dry land, Playa is a hipper and more modern version of the fishing village it once was.', 'Playa del Carmen', '2014-06-07', '2014-06-14', 4, '2014-04-05 14:30:23', '2014-04-09 07:16:57'),
(7, 'From the Magic Kingdom to magical spa treatments, Orlando sparkles with the promise of adventure.', 'Orlando', '2014-06-07', '2014-06-14', 1, '2014-04-05 14:31:54', '2014-04-09 07:17:06'),
(8, 'Conquering New York in one visit is impossible. Instead, hit the must sees the Empire State Building, the Statue of Liberty, Central Park, the Metropolitan Museum of Art.', 'New York City', '2014-06-07', '2014-06-14', 1, '2014-04-05 14:33:54', '2014-04-11 14:28:21'),
(9, 'Rome cant be toured in a day, either. The city feels like the exhibit halls of a giant outdoor museum, a real-life collage of piazzas, open-air markets, and mind-boggling historic sites.', 'Rome', '2014-06-07', '2014-06-14', 2, '2014-04-05 17:22:56', '2014-04-11 14:28:33'),
(11, 'Lingering over pain au chocolat in a sidewalk cafe, relaxing after a day of strolling along the Seine and marveling at icons like the Eiffel Tower and the Arc de Triomphe… the perfect Paris experience.', 'Paris', '2014-06-19', '2014-06-27', 4, '2014-04-05 17:32:49', '2014-04-11 15:23:15'),
(13, 'Thailands largest island is an international magnet for beach lovers and serious divers, who enthusiastically submerge themselves in the Andaman Sea. Blue lagoons and salmon sunsets make for a dream-like atmosphere.', 'Phuket', '2014-07-04', '2014-07-11', 2, '2014-04-06 07:18:49', '2014-04-11 14:28:59'),
(10, 'The mosques, bazaars, and Turkish baths of Istanbul could keep you happily occupied for your entire trip: an eyeful of breathtaking architecture here, a good-natured haggle over a carpet there. ', 'Istanbul', '2014-11-01', '2014-11-13', 5, '2014-04-06 13:34:31', '2014-04-06 13:48:59'),
(12, 'When most people think of Zermatt, they think of one thing: The Matterhorn. This ultimate Swiss icon looms over Zermatt, first drawing visitors here in the 1860s. The village of Zermatt itself is lovely and car-free.', 'Zermatt', '2014-09-12', '2014-10-09', 3, '2014-04-06 13:39:22', '2014-04-09 07:17:44');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_login` varchar(20) NOT NULL,
  `member_password` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_login` (`member_login`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `member_login`, `member_password`, `first_name`, `last_name`, `email`, `create_datetime`, `last_update_datetime`) VALUES
(1, 'Candy', 'qqqqq', 'Candy', 'Chan', 'candychan@gmail.com', '2014-04-05 12:13:27', '2014-04-06 13:09:55'),
(2, 'Simon', 'qqqqq', 'Simon', 'Chan', 'simonchan@gmail.com', '2014-04-05 17:22:36', '2014-04-06 13:10:11'),
(3, 'Terry', 'qqqqq', 'Terry', 'Chan', 'terrychan@gmail.com', '2014-04-06 13:10:35', '2014-04-06 13:10:35'),
(4, 'Gigi', 'qqqqq', 'Gigi ', 'Leung', 'gigileung@gmail.com', '2014-04-06 13:11:10', '2014-04-06 13:11:10'),
(5, 'John', 'qqqqq', 'John', 'Wan', 'johnwan@gmail.com', '2014-04-06 13:12:05', '2014-04-06 13:12:05'),
(6, 'Testing', 'qqqqq', 'Test', 'Test', 'test@test.com', '2014-04-09 07:55:50', '2014-04-09 07:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `idea_id` int(10) unsigned NOT NULL,
  `create_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag` (`tag`,`idea_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag`, `idea_id`, `create_datetime`, `last_update_datetime`) VALUES
(1, 'Shopping', 1, '2014-04-05 12:13:56', '2014-04-05 12:13:56'),
(2, 'Famous', 2, '2014-04-05 12:21:53', '2014-04-05 12:21:53'),
(3, 'Adventure', 3, '2014-04-05 12:22:18', '2014-04-06 13:14:41'),
(4, 'Beach', 4, '2014-04-05 12:22:56', '2014-04-06 13:15:07'),
(20, ' Famous', 5, '2014-04-11 14:38:37', '2014-04-11 14:38:37'),
(6, 'Sun', 6, '2014-04-05 14:30:23', '2014-04-06 13:15:16'),
(7, 'Family', 7, '2014-04-05 14:31:54', '2014-04-06 13:15:40'),
(8, 'Beautiful', 8, '2014-04-05 14:33:54', '2014-04-05 14:33:54'),
(9, 'History', 9, '2014-04-05 17:22:56', '2014-04-06 13:15:52'),
(10, 'Culture', 10, '2014-04-05 17:23:21', '2014-04-06 13:16:02'),
(11, 'Romance', 11, '2014-04-05 17:32:49', '2014-04-06 13:16:09'),
(12, 'Skiing', 12, '2014-04-06 07:18:49', '2014-04-06 13:16:19'),
(13, 'Spa', 13, '2014-04-06 13:16:31', '2014-04-06 13:17:27'),
(14, 'Famous', 1, '2014-04-06 14:29:43', '2014-04-06 14:29:43'),
(19, 'Casino', 5, '2014-04-11 14:38:37', '2014-04-11 14:38:37');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
