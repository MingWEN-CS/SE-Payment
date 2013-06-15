/* if you are using sqlite the following are not necessary */
DROP DATABASE IF EXISTS payment;
CREATE DATABASE payment;
/* if failed on following CREATE USER sql due to exits laolao already just delete it and redo >.<*/
-- CREATE USER 'laolao'@'localhost' IDENTIFIED BY 'laolao';
GRANT ALL PRIVILEGES ON payment.* TO 'laolao'@'localhost';
USE payment;
/* if you are using sqlite please start here */
/* key tables has referenced foreign keys*/
-- --------------------------------------------------------
--
-- Table structure for table `se_user`
--

DROP TABLE IF EXISTS `se_user`;
CREATE TABLE IF NOT EXISTS `se_user` (
  `UID` int(10) NOT NULL AUTO_INCREMENT,
  `USERNAME` char(20) CHARACTER SET utf8 NOT NULL,
  `PASSWD` char(32) CHARACTER SET utf8 NOT NULL,
  `EMAIL` char(30) CHARACTER SET utf8 NOT NULL,
  `TYPE` tinyint(1) NOT NULL,
  `BALANCE` int(11) DEFAULT '0',
  `PHONE` char(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`UID`),
  UNIQUE KEY `ID` (`UID`),
  UNIQUE KEY `USERNAME` (`USERNAME`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

DROP TABLE IF EXISTS se_goods;
CREATE TABLE se_goods(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	type INTEGER NOT NULL
	/*	1: general goods
		2: hotel room
		3: airplane ticket
	It is better to use get funcions in GeneralGoodsModel and other models*/
);
-- --------------------------------------------------------

--
-- Table structure for table `se_receiveaddress`
--

CREATE TABLE IF NOT EXISTS `se_receiveaddress` (
  `ADDRESSID` char(50) NOT NULL DEFAULT '',
  `UID` int(11) NOT NULL,
  `RECEIVERNAME` char(50) DEFAULT NULL,
  `RECEIVERPHONE` char(20) DEFAULT NULL,
  `PROVINCE` char(50) DEFAULT NULL,
  `CITY` char(50) DEFAULT NULL,
  `STRICT` char(50) DEFAULT NULL,
  `STREET` char(100) DEFAULT NULL,
  PRIMARY KEY (`ADDRESSID`),
  KEY `UID` (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS se_orders;
CREATE TABLE se_orders(
	ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	BUYER INT(10) NOT NULL,
	SELLER INT(10) NOT NULL,
	TOTALPRICE numeric(15,2),
    	ADDRESSID char(50) CHARACTER SET utf8 NOT NULL,
	ISDELETE varchar(5) CHARACTER SET utf8 NOT NULL DEFAULT 'NO',
	STATE char(20) CHARACTER SET utf8 NOT NULL,
   	 ISAUDIT varchar(5) CHARACTER SET utf8 NOT NULL DEFAULT 'NO',
	foreign key (BUYER) references `se_user`(`UID`) on delete cascade,
	foreign key (SELLER) references `se_user`(`UID`) on delete cascade,
   	 foreign key (ADDRESSID) references `se_receiveaddress`(`ADDRESSID`) on delete cascade
);



/* group 1 */
--
--
-- --------------------------------------------------------

--
-- Table structure for table `se_buyer`
--

CREATE TABLE IF NOT EXISTS `se_buyer` (
  `UID` int(11) NOT NULL,
  `PASSWDPAYMENT` char(32) CHARACTER SET utf8 NOT NULL,
  `CREDIT` int(11) NOT NULL DEFAULT '0',
  `VIP` tinyint(1) NOT NULL DEFAULT '0',
  `AUTHENTICATED` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table 'se_seller'
--

CREATE TABLE IF NOT EXISTS `se_seller` (
  `UID` int(11) NOT NULL,
  `PASSWDCONSIGN` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `se_usercard`
--

CREATE TABLE IF NOT EXISTS `se_usercard` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERID` int(11) DEFAULT NULL,
  `CARDID` char(50) NOT NULL,
   PRIMARY KEY (`ID`),
   KEY `se_user_id` (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `se_buyer`
--
ALTER TABLE `se_buyer`
  ADD CONSTRAINT `se_buyer_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `se_user` (`UID`);

--
-- Constraints for table `se_receiveaddress`
--
ALTER TABLE `se_receiveaddress`
  ADD CONSTRAINT `se_receiveaddress_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `se_user` (`UID`);

--
-- Constraints for table `se_seller`
--
ALTER TABLE `se_seller`
  ADD CONSTRAINT `se_seller_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `se_user` (`UID`);

--
-- Constraints for table `se_usercard`
--

ALTER TABLE `se_usercard`
  ADD CONSTRAINT `se_usercard_ibfk_1` FOREIGN KEY (`USERID`) REFERENCES `se_user` (`UID`);

/* group 2 */


DROP TABLE IF EXISTS se_order_goods;
CREATE TABLE se_order_goods(
	OID INTEGER NOT NULL,
	GID INTEGER NOT NULL,
	PRICE numeric(15,2) NOT NULL,
	AMOUNT INTEGER NOT NULL,
	NAME VARCHAR(256) CHARACTER SET utf8 NOT NULL,
	PRIMARY KEY(oid,gid),
	foreign key (OID) references se_orders(ID) on delete cascade,
	foreign key (GID) references se_goods(ID) on delete cascade
);
DROP TABLE IF EXISTS se_order_operation;
CREATE TABLE se_order_operation(
    	`OID` INTEGER NOT NULL,
	`OPERATION` char(20) CHARACTER SET utf8 NOT NULL,
	`TIME` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`OPERATOR` char(20) CHARACTER SET utf8 NOT NULL DEFAULT 'system',
	primary key(`OID`,`TIME`),
	foreign key (`OID`) references `se_orders`(`ID`) on delete cascade
);

/* group 3 */


DROP TABLE IF EXISTS se_general_goods;
CREATE TABLE se_general_goods(
	id INTEGER NOT NULL PRIMARY KEY,
	name VARCHAR(256),
	price numeric(15,2),
	seller_id INTEGER,
	bought_count INTEGER,
	score numeric(11,10),
	score_count INTEGER,
	place VARCHAR(64),
	image_uri VARCHAR(256),
	stock INTEGER,
	description VARCHAR(1024),
	foreign key (id) references se_goods(id) on delete cascade,
	foreign key (seller_id) references se_user(UID) on delete cascade
);

DROP TABLE IF EXISTS se_hotel_room;
CREATE TABLE se_hotel_room(
	id INTEGER NOT NULL PRIMARY KEY,
	name VARCHAR(256),
	price numeric(15,2),
	seller_id INTEGER,
	bought_count INTEGER,
	score numeric(11,10),
	score_count INTEGER,
	place VARCHAR(64),
	image_uri VARCHAR(256),
	stock INTEGER,
	description VARCHAR(1024),
	date_time BIGINT,
	suit_type VARCHAR(32),
	foreign key (id) references se_goods(id) on delete cascade,
	foreign key (seller_id) references se_user(UID) on delete cascade
);

DROP TABLE IF EXISTS se_airplane_ticket;
CREATE TABLE se_airplane_ticket(
	id INTEGER NOT NULL PRIMARY KEY,
	name VARCHAR(256),
	seller_id INTEGER,
	bought_count INTEGER,
	score numeric(11,10),
	score_count INTEGER,
	image_uri VARCHAR(256),
	stock INTEGER,
	description VARCHAR(1024),
	price numeric(15,2),
	departure_date_time BIGINT,
	arrival_date_time BIGINT,
	departure_place VARCHAR(64),
	arrival_place VARCHAR(64),
	non_stop BOOLEAN,
	carbin_type VARCHAR(32),
	foreign key (id) references se_goods(id) on delete cascade,
	foreign key (seller_id) references se_user(UID) on delete cascade
);

DROP TABLE IF EXISTS se_browse_history;
CREATE TABLE se_browse_history(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	good_id INTEGER,
	se_user_id INTEGER,
	date_time BIGINT,
	foreign key (good_id) references se_user(UID) on delete cascade,
	foreign key (se_user_id) references se_user(UID) on delete cascade
);

DROP TABLE IF EXISTS se_search_history;
CREATE TABLE se_search_history(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	search_key VARCHAR(256),
	se_user_id INTEGER,
	date_time BIGINT,
	foreign key (se_user_id) references se_user(UID) on delete cascade
);

DROP TABLE IF EXISTS se_feedback;
CREATE TABLE se_feedback(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	se_user_id INTEGER,
	transaction_id INTEGER,
	score INTEGER,
	comment VARCHAR(1024),
	date_time BIGINT,
	foreign key (se_user_id) references se_user(UID) on delete cascade,	
	foreign key (transaction_id) references se_orders(id) on delete cascade
	/* if you are using sqlite please use following instead */
	/* foreign key (transaction_id) references transactions(id) on delete cascade */
);

DROP TABLE IF EXISTS se_shopping_cart;
CREATE TABLE se_shopping_cart(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	se_user_id INTEGER,
	good_id INTEGER,
	good_count INTEGER,
	foreign key (good_id) references se_goods(id) on delete cascade,
	foreign key (se_user_id) references se_user(UID) on delete cascade
);

/* group 4 */

/* group 5 */
