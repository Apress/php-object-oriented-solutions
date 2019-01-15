-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 06, 2008 at 07:28 PM
-- Server version: 5.0.45
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `phpsolutions`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `article_id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `image` varchar(100) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`article_id`, `title`, `article`, `image`, `updated`, `created`) VALUES
(1, 'The blog starts here!', 'The world has been waiting with bated breath, but <strong>the wait is over...</strong> It''s time to dazzle the blogosphere with my dazzling wit. Stay tuned.', 'me.jpg', '2008-04-25 14:03:48', '2008-04-21 13:06:07'),
(2, 'Stumped for something to say', 'Well, so much for a brilliant start. My mind has gone a blank. Must be all that OOP nonsense I''ve been filling my head with...', 'glum_me.jpg', '2008-04-25 14:50:31', '2008-04-21 13:08:48'),
(3, 'Oopsa-daisy!', 'Now the truth can finally be told... The original title for my book was "Object-Oriented PHP Solutions", but OOPS didn''t sound quite right. So, someone came up with the brilliant idea of changing it to "PHP Object-Oriented Solutions". Er, hang on a minute... ', 'book_cover.jpg', '2008-04-21 13:27:56', '2008-04-21 13:15:24'),
(4, 'Only two chapters to go', 'It''s been a long haul, but the end is almost in sight. I hope everyone has enjoyed this journey through OOP as much as I have writing it.', 'end_of_tunnel.jpg', '2008-04-21 13:23:04', '2008-04-21 13:23:04');
