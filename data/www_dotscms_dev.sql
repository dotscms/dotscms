-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 05, 2012 at 02:21 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.9-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `www_dotscms_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `section` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `class` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=48 ;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `page_id`, `section`, `type`, `position`, `entry_date`, `class`) VALUES
(1, 2, 'content', 'html_content', 2, '2012-04-18 14:03:29', NULL),
(2, 2, 'content', 'html_content', 3, '2012-04-18 14:03:29', NULL),
(4, 2, 'right', 'html_content', 3, '2012-04-20 09:28:13', NULL),
(7, 1, 'content', 'html_content', 1, '2012-04-23 16:13:10', NULL),
(18, 1, 'right', 'html_content', 1, '2012-04-25 09:23:54', NULL),
(21, 2, 'header', 'image_content', 1, '2012-04-25 15:57:11', NULL),
(22, 2, 'right', 'image_content', 1, '2012-04-26 15:09:24', 'cucuBau231'),
(23, 1, 'header', 'image_content', 1, '2012-04-26 15:10:38', NULL),
(27, 2, 'content', 'html_content', 1, '2012-04-26 18:04:38', NULL),
(35, 2, 'right', 'links_content', 2, '2012-05-07 17:27:16', NULL),
(37, 2, 'navigation', 'navigation', 1, '2012-05-14 17:59:28', NULL),
(38, 3, 'navigation', 'image_content', 1, '2012-05-18 17:41:06', ''),
(40, 3, 'right', 'links_content', 2, '2012-09-21 01:05:57', ''),
(41, 3, 'right', 'html_content', 1, '2012-10-02 10:00:43', 'cucu-bau3'),
(42, 3, 'header', 'html_content', 2, '2012-10-02 23:40:47', ''),
(43, 3, 'content', 'html_content', 1, '2012-10-02 23:41:30', ''),
(44, 3, 'content', 'html_content', 2, '2012-10-03 10:23:40', ''),
(45, 3, 'header', 'html_content', 1, '2012-10-03 10:23:52', ''),
(46, 3, 'header', 'html_content', 4, '2012-10-03 10:24:28', ''),
(47, 3, 'header', 'html_content', 3, '2012-10-04 12:25:57', '');

-- --------------------------------------------------------

--
-- Table structure for table `block_html`
--

CREATE TABLE IF NOT EXISTS `block_html` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `block_html`
--

INSERT INTO `block_html` (`id`, `block_id`, `content`) VALUES
(1, 1, '<h1>asdas dasd asd asd asd asd</h1>\n<p>asddsf asdfa sdfasdfasd fsdfasdf asdfasdf asdf asdf asdfasd fasdfasdfasdf <br /> <a href="auth/">Login</a></p>'),
(2, 2, '<p style="text-align: left;"><a title="My New Dots Page" href="my-new-dots-page">Lorem ipsum</a> dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n<p style="text-align: left;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'),
(4, 4, '<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'),
(7, 7, '<p>Lorem ipsum <a title="First Page" href="first-page">dolor sit</a> amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'),
(8, 18, '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>'),
(12, 27, '<p>asdfasdf asdf asdf asdfaasdas sadasd aasdasd asd asd asd as das aad1</p>'),
(13, 41, '<p>hello mate</p>'),
(14, 42, '<p>buga luga luga</p>'),
(15, 43, '<p>adasda</p>'),
(16, 44, '<p>asfdasfasdf dasdfa sd</p>'),
(17, 45, '<p>fasgfsfdgsdf</p>'),
(18, 46, '<p>asdfa sdfasd fasdf asdf asd11111</p>'),
(19, 47, '<p>asdasdasdasd sfdvdsf daaa1111</p>');

-- --------------------------------------------------------

--
-- Table structure for table `block_image`
--

CREATE TABLE IF NOT EXISTS `block_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT NULL,
  `original_src` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `src` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alt` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `display_width` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_height` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crop_x1` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crop_y1` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crop_x2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crop_y2` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `block_image`
--

INSERT INTO `block_image` (`id`, `block_id`, `original_src`, `src`, `alt`, `width`, `height`, `display_width`, `display_height`, `crop_x1`, `crop_y1`, `crop_x2`, `crop_y2`) VALUES
(10, 21, '/data/uploads/77334f994c3ebe98d_Desert.jpg', '/data/uploads/edited/68194faccac31a297_77334f994c3ebe98d_Desert.jpg', 'asdasdasd', 1024, 768, '100%', '', '0', '22.75132275132275', '100', '73.54497354497354'),
(11, 22, '/data/uploads/277554fa27c077a5c1_Tulips.jpg', '/data/uploads/edited/142944fb2260d117f4_277554fa27c077a5c1_Tulips.jpg', '', 1024, 768, '100%', '', '0', '6.294706723891273', '100', '61.37339055793991'),
(12, 23, '/data/uploads/132134f993b3e6089f_Lighthouse.jpg', '/data/uploads/edited/197014facdff711718_132134f993b3e6089f_Lighthouse.jpg', '', 1024, 768, '100%', '', '0', '9.72818311874106', '100', '63.09012875536481'),
(13, 38, '/data/uploads/61764fb65f8261833_Penguins.jpg', '/data/uploads/edited/2147506a0036d52b9_61764fb65f8261833_Penguins.jpg', '', 1024, 768, '100%', '', '0', '6.723891273247497', '100', '33.333333333333336');

-- --------------------------------------------------------

--
-- Table structure for table `block_links`
--

CREATE TABLE IF NOT EXISTS `block_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` enum('file','link','page') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'page',
  `entity_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `block_links`
--

INSERT INTO `block_links` (`id`, `block_id`, `parent_id`, `type`, `entity_id`, `title`, `href`, `position`) VALUES
(2, 35, NULL, 'link', NULL, 'Something for nothing', 'http://www.dotscms.dev/my-new-dots-page', 2),
(5, 35, NULL, 'page', 1, 'My new DotsCMS page', 'my-new-dots-page', 1),
(6, 35, NULL, 'page', 2, 'First Page', 'first-page', 4),
(7, 35, NULL, 'link', 1, 'Login', 'http://www.dotscms.dev/auth/', 3),
(8, 40, NULL, 'page', 2, 'testing files and links', 'first-page', 1),
(9, 40, NULL, 'link', NULL, 'testing link 2', 'http://www.zendexperts.com', 2),
(10, 40, NULL, 'page', 2, 'Cool new link ', 'first-page', 3);

-- --------------------------------------------------------

--
-- Table structure for table `block_navigation`
--

CREATE TABLE IF NOT EXISTS `block_navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('page','link','header','-') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'page',
  `entity_id` int(11) DEFAULT NULL,
  `href` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `block_navigation`
--

INSERT INTO `block_navigation` (`id`, `block_id`, `parent_id`, `title`, `type`, `entity_id`, `href`, `position`) VALUES
(2, 37, NULL, 'Login', 'link', NULL, 'http://www.dotscms.dev/auth/', 2),
(3, 37, NULL, 'Fidelachius', 'page', 2, 'first-page', 4),
(8, 37, NULL, 'Amarachius', 'page', 1, 'my-new-dots-page', 5),
(12, 37, NULL, 'By Around251', 'link', NULL, 'http://www.around25.com/', 7),
(15, 37, NULL, '', '-', NULL, NULL, 3),
(16, 37, NULL, '', '-', NULL, NULL, 7),
(17, 37, NULL, 'Home', 'page', 3, '', 1),
(18, 37, NULL, 'Cool Header', 'header', NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `template` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '1',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `alias`, `title`, `template`, `language`, `position`, `create_date`, `last_update`) VALUES
(1, 'my-new-dots-page', 'My new DotsCMS page', 'dots-pages/pages/page', 'en', 1, '2012-04-12 11:32:40', '2012-04-12 11:32:40'),
(2, 'first-page', 'first page', 'dots-pages/pages/page', 'en', 1, '2012-04-17 12:18:39', NULL),
(3, '', 'DotsCMS', 'dots-pages/pages/page', 'en', 1, '2012-04-12 11:32:40', '2012-04-12 11:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `page_metas`
--

CREATE TABLE IF NOT EXISTS `page_metas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `robots` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `copyright` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `charset` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'UTF-8',
  `expires_after` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `page_metas`
--

INSERT INTO `page_metas` (`id`, `page_id`, `title`, `keywords`, `description`, `author`, `robots`, `copyright`, `charset`, `expires_after`) VALUES
(1, 1, 'My new DotsCMS page', 'dots, page, cms', 'My first page', 'Cosmin Harangus', '', 'August 2012', 'UTF-8', NULL),
(2, 2, 'first page', 'dots, page', 'aasdasdads', 'asd', 'no-index', '2012', 'UTF-8', NULL),
(4, 3, 'DotsCMS', 'dots, page', 'DotsCMS', 'Around25', 'no-index', '2012', 'UTF-8', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password_salt` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `role` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'guest',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `password_salt`, `last_login`, `role`) VALUES
(1, 'admin', 'cosmin@around25.com', 'secret', '', NULL, 'guest');

CREATE TABLE IF NOT EXISTS `block_slideshows` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT NULL,
  `effect` varchar(50) DEFAULT NULL,
  `animSpeed` int(11) DEFAULT NULL,
  `pauseTime` int(11) DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `block_slideshow_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `block_slideshow_id` int(11) NOT NULL,
  `src` varchar(250) NOT NULL DEFAULT '',
  `caption` text,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;