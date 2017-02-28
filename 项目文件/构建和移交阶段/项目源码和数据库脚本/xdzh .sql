-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2015 年 05 月 28 日 18:49
-- 服务器版本: 5.5.23
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_xdzh`
--

-- --------------------------------------------------------

--
-- 表的结构 `accesstoken`
--

CREATE TABLE IF NOT EXISTS `accesstoken` (
  `id` int(1) NOT NULL,
  `token` varchar(200) NOT NULL,
  `time` int(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `booktable`
--

CREATE TABLE IF NOT EXISTS `booktable` (
  `bookid` varchar(10) NOT NULL,
  `guestid` varchar(8) NOT NULL,
  `shopid` varchar(5) NOT NULL,
  `styleid` varchar(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `message` varchar(200) NOT NULL,
  `time` varchar(20) NOT NULL,
  `state` varchar(10) NOT NULL,
  FULLTEXT KEY `bookid` (`bookid`,`guestid`,`shopid`,`styleid`,`name`,`tel`,`message`,`time`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `evaluationtable`
--

CREATE TABLE IF NOT EXISTS `evaluationtable` (
  `evaluationid` varchar(10) NOT NULL,
  `guestid` varchar(8) NOT NULL,
  `shopid` varchar(5) NOT NULL,
  `styleid` varchar(5) NOT NULL,
  `bookid` varchar(10) NOT NULL,
  `time` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `stars` varchar(1) NOT NULL,
  `message` varchar(200) NOT NULL,
  `respon` varchar(200) NOT NULL,
  FULLTEXT KEY `evaluationid` (`evaluationid`,`guestid`,`shopid`,`styleid`,`bookid`,`time`,`name`,`stars`,`message`,`respon`),
  FULLTEXT KEY `evaluationid_2` (`evaluationid`,`guestid`,`shopid`,`styleid`,`bookid`,`time`,`name`,`stars`,`message`,`respon`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `guesttable`
--

CREATE TABLE IF NOT EXISTS `guesttable` (
  `guestid` varchar(8) NOT NULL,
  `openid` varchar(30) NOT NULL,
  `lng` float NOT NULL,
  `lat` float NOT NULL,
  FULLTEXT KEY `guestid` (`guestid`),
  FULLTEXT KEY `openid` (`openid`),
  FULLTEXT KEY `guestid_2` (`guestid`,`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `listtable`
--

CREATE TABLE IF NOT EXISTS `listtable` (
  `bookid` varchar(10) NOT NULL,
  `guestid` varchar(8) NOT NULL,
  `shopid` varchar(5) NOT NULL,
  `styleid` varchar(5) NOT NULL,
  `name` varchar(10) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(30) NOT NULL,
  `cost` int(3) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `state` varchar(200) NOT NULL,
  FULLTEXT KEY `bookid` (`bookid`,`guestid`,`shopid`,`styleid`,`name`,`date`,`time`,`tel`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `shoptable`
--

CREATE TABLE IF NOT EXISTS `shoptable` (
  `shopid` varchar(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `size` varchar(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `owner` varchar(20) NOT NULL,
  `tel` varchar(15) NOT NULL,
  `lng` float NOT NULL,
  `lat` float NOT NULL,
  `stars` float NOT NULL,
  `pic` mediumblob NOT NULL,
  FULLTEXT KEY `shopid` (`shopid`,`name`,`size`,`username`,`password`,`address`,`owner`,`tel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `styletable`
--

CREATE TABLE IF NOT EXISTS `styletable` (
  `styleid` varchar(5) NOT NULL,
  `shopid` varchar(5) NOT NULL,
  `price` varchar(5) NOT NULL,
  `time` varchar(5) NOT NULL,
  `info` varchar(200) NOT NULL,
  `pic` mediumblob NOT NULL,
  FULLTEXT KEY `styleid` (`styleid`,`shopid`,`price`,`time`,`info`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
