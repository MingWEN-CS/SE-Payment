-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 06 月 11 日 18:03
-- 服务器版本: 5.5.25
-- PHP 版本: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- 数据库: `payment`
--

--
-- 转存表中的数据 `goods`
--

INSERT IGNORE INTO `goods` (`id`, `type`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 1),
(5, 2),
(6, 3),
(7, 1),
(8, 2),
(9, 3);

--
-- 转存表中的数据 `airplane_ticket`
--

INSERT IGNORE INTO `airplane_ticket` (`id`, `name`, `seller_id`, `bought_count`, `score`, `score_count`, `image_uri`, `stock`, `description`, `price`, `departue_date_time`, `arrival_date_time`, `departue_place`, `arrival_place`, `non_stop`, `carbin_type`) VALUES
(3, '阿溴的私人飞机[@.@]efghi', 1, 0, 4.5000000000, 1, 'www.baidu.com', 20, '很好', 100.00, 1370208720000, 1370208900000, 'Hangzhou', 'Hangzhou', 1, 'First');
INSERT IGNORE INTO `airplane_ticket` (`id`, `name`, `seller_id`, `bought_count`, `score`, `score_count`, `image_uri`, `stock`, `description`, `price`, `departue_date_time`, `arrival_date_time`, `departue_place`, `arrival_place`, `non_stop`, `carbin_type`) VALUES
(6, '阿溴的公共飞机[@.@]efpoi', 1, 0, 4.5000000000, 1, 'www.baidu.com', 20, '很好', 90.00, 1370289540000, 1370289540000, 'Hangzhou', 'Hangzhou', 1, 'Bussiness');
INSERT IGNORE INTO `airplane_ticket` (`id`, `name`, `seller_id`, `bought_count`, `score`, `score_count`, `image_uri`, `stock`, `description`, `price`, `departue_date_time`, `arrival_date_time`, `departue_place`, `arrival_place`, `non_stop`, `carbin_type`) VALUES
(9, '阿溴Da飞机[@.@]efpoi', 1, 0, 4.5000000000, 1, 'www.baidu.com', 20, '很好', 95.00, 1370289660000, 1370289660000, 'Hangzhou', 'Hangzhou', 1, 'Economy');
--
-- 转存表中的数据 `general_goods`
--

INSERT IGNORE INTO `general_goods` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`) VALUES
(1, 'abcdefg溴溴溴', 0.50, 1, 0, 5.0000000000, 1, '杭州', 'www.baidu.com', 20, '很好');
INSERT IGNORE INTO `general_goods` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`) VALUES
(4, 'abcdefg溴溴', 4.50, 1, 0, 5.0000000000, 1, '杭州', 'www.baidu.com', 20, '很好');
INSERT IGNORE INTO `general_goods` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`) VALUES
(7, 'abcdefg溴', 2.50, 1, 0, 5.0000000000, 1, '杭州', 'www.baidu.com', 20, '很好');

--
-- 转存表中的数据 `hotel_room`
--

INSERT IGNORE INTO `hotel_room` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`, `date_time`, `suit_type`) VALUES
(2, '阿溴的房间[>.<]efghi', 0.30, 1, 0, 4.0000000000, 1, 'Hangzhou', 'www.baidu.com', 20, '很好', 1370208720000, 'Luxury');
INSERT IGNORE INTO `hotel_room` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`, `date_time`, `suit_type`) VALUES
(5, '阿溴的后宫[>.<]efghi', 5.30, 1, 0, 4.0000000000, 1, 'Hangzhou', 'www.baidu.com', 20, '很好', 1370208720000, 'Bussiness');
INSERT IGNORE INTO `hotel_room` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`, `date_time`, `suit_type`) VALUES
(8, '阿溴的小房间[>.<]efghi', 3.30, 1, 0, 4.0000000000, 1, 'Hangzhou', 'www.baidu.com', 20, '很好', 1370208720000, 'Single');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
