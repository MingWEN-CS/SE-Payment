-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 06 月 13 日 06:51
-- 服务器版本: 5.5.25
-- PHP 版本: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
--
-- 数据库: `payment`
--

--
-- 转存表中的数据 `se_user`
--

INSERT IGNORE INTO `se_user` (`UID`, `USERNAME`, `PASSWD`, `EMAIL`, `TYPE`, `BALANCE`, `PHONE`) VALUES
(1, '123', '202cb962ac59075b964b07152d234b70', 'a@a.com', 1, 0, NULL),
(2, '1234', '202cb962ac59075b964b07152d234b70', 'a@b.com', 0, 0, NULL);

--
-- 转存表中的数据 `se_buyer`
--

INSERT IGNORE INTO `se_buyer` (`UID`, `PASSWDPAYMENT`, `CREDIT`, `VIP`, `AUTHENTICATED`) VALUES
(2, '202cb962ac59075b964b07152d234b70', 0, 0, 0);

--
-- 转存表中的数据 `se_seller`
--

INSERT IGNORE INTO `se_seller` (`UID`, `PASSWDCONSIGN`) VALUES
(1, 'a');

--
-- 转存表中的数据 `se_goods`
--

INSERT IGNORE INTO `se_goods` (`id`, `type`) VALUES
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
-- 转存表中的数据 `se_airplane_ticket`
--

INSERT IGNORE INTO `se_airplane_ticket` (`id`, `name`, `seller_id`, `bought_count`, `score`, `score_count`, `image_uri`, `stock`, `description`, `price`, `departure_date_time`, `arrival_date_time`, `departure_place`, `arrival_place`, `non_stop`, `carbin_type`) VALUES
(3, '阿溴的私人飞机[@.@]efghi', 1, 0, 4.5000000000, 1, 'www.baidu.com', 20, '很好', 100.00, 1370208720000, 1370208900000, '浙江', '江苏', 1, 'First'),
(6, '阿溴的公共飞机[@.@]efpoi', 1, 0, 4.5000000000, 1, 'www.baidu.com', 20, '很好', 90.00, 1370289540000, 1370289540000, '香港', '上海', 1, 'Bussiness'),
(9, '阿溴Da飞机[@.@]efpoi', 1, 0, 4.5000000000, 1, 'www.baidu.com', 20, '很好', 95.00, 1370289660000, 1370289660000, '江苏', '上海', 1, 'Economy');

--
-- 转存表中的数据 `se_general_goods`
--

INSERT IGNORE INTO `se_general_goods` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`) VALUES
(1, 'abcdefg溴溴溴', 0.50, 1, 0, 5.0000000000, 1, '江苏', 'www.baidu.com', 20, '很好'),
(4, 'abcdefg溴溴', 4.50, 1, 0, 5.0000000000, 1, '浙江', 'www.baidu.com', 20, '很好'),
(7, 'abcdefg溴', 2.50, 1, 0, 5.0000000000, 1, '浙江', 'www.baidu.com', 20, '很好');

--
-- 转存表中的数据 `se_hotel_room`
--

INSERT IGNORE INTO `se_hotel_room` (`id`, `name`, `price`, `seller_id`, `bought_count`, `score`, `score_count`, `place`, `image_uri`, `stock`, `description`, `date_time`, `suit_type`) VALUES
(2, '阿溴的房间[>.<]efghi', 0.30, 1, 0, 4.0000000000, 1, '浙江', 'www.baidu.com', 20, '很好', 1370208720000, 'luxury'),
(5, '阿溴的后宫[>.<]efghi', 5.30, 1, 0, 4.0000000000, 1, '浙江', 'www.baidu.com', 20, '很好', 1370208720000, 'Bussiness'),
(8, '阿溴的小房间[>.<]efghi', 3.30, 1, 0, 4.0000000000, 1, '上海', 'www.baidu.com', 20, '很好', 1370208720000, 'Single');


insert into se_shopping_cart values(1,1,1,1);
insert into se_shopping_cart values(2,1,2,2)