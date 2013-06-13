/* if you are using sqlite the following are not necessary */
DROP DATABASE IF EXISTS payment;
CREATE DATABASE payment;
/* if failed on following CREATE USER sql due to exits laolao already just delete it and redo >.<*/
CREATE USER 'laolao'@'localhost' IDENTIFIED BY 'laolao';
GRANT ALL PRIVILEGES ON payment.* TO 'laolao'@'localhost';
USE payment;
/* if you are using sqlite please start here */
DROP TABLE IF EXISTS user;
/* group 1 */
CREATE TABLE user(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
);
=======

--

--
CREATE TABLE IF NOT EXISTS `Buyer` (
  `UID` int(11) NOT NULL,
  `PASSWDPAYMENT` char(32) CHARACTER SET utf8 NOT NULL,
  `CREDIT` int(11) NOT NULL DEFAULT '0',
  `VIP` tinyint(1) NOT NULL DEFAULT '0',
  `AUTHENTICATED` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Receiveaddress`
--

CREATE TABLE IF NOT EXISTS `Receiveaddress` (
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

-- --------------------------------------------------------

--
-- Table structure for table `Seller`
--

CREATE TABLE IF NOT EXISTS `Seller` (
  `UID` int(11) NOT NULL,
  `PASSWORDCONSIGN` char(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `UID` int(10) NOT NULL AUTO_INCREMENT,
  `USERNAME` char(20) CHARACTER SET utf8 NOT NULL,
  `PASSWORD` char(32) CHARACTER SET utf8 NOT NULL,
  `EMAIL` char(30) CHARACTER SET utf8 NOT NULL,
  `TYPE` tinyint(1) NOT NULL,
  `BALANCE` int(11) DEFAULT '0',
  `PHONE` char(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`UID`),
  UNIQUE KEY `ID` (`UID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `User`
--


-- --------------------------------------------------------

--
-- Table structure for table `Usercard`
--

CREATE TABLE IF NOT EXISTS `Usercard` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERID` int(11) DEFAULT NULL,
  `CARDID` char(50) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_id` (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Buyer`
--
ALTER TABLE `Buyer`
  ADD CONSTRAINT `buyer_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`);

--
-- Constraints for table `Receiveaddress`
--
ALTER TABLE `Receiveaddress`
  ADD CONSTRAINT `receiveaddress_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `User` (`UID`);

--
-- Constraints for table `Usercard`
--
ALTER TABLE `Usercard`
  ADD CONSTRAINT `usercard_ibfk_1` FOREIGN KEY (`USERID`) REFERENCES `USER` (`UID`);

/* group 2 */

DROP TABLE IF EXISTS orders;
CREATE TABLE orders(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	buyer char(50) NOT NULL,
	seller char(50) NOT NULL,
	totalprice numeric(15,2),
	isdelete bit(1) NOT NULL,
	state char(20) NOT NULL,
	foreign key (buyer) references buyer(USERNAME) on delete cascade,
	foreign key (seller) references seller(USERNAME) on delete cascade
);
DROP TABLE IF EXISTS order_goods;
CREATE TABLE order_goods(
	oid INTEGER NOT NULL,
	gid INTEGER NOT NULL,
	price numeric(15,2) NOT NULL,
	number INTEGER NOT NULL,
	name VARCHAR(256),
	PRIMARY KEY(oid,gid),
	foreign key (oid) references orders(id) on delete cascade,
	foreign key (gid) references goods(id) on delete cascade
);
DROP TABLE IF EXISTS order_operation;
CRAETE TABLE order_operation(
	oid INTEGER NOT NULL,
	operation char(20) NOT NULL,
	time date NOT NULL,
	operator char(20),
	primary key(oid,time),
	foreign key (oid) references orders(id) on delete cascade
);
/* if you are using sqlite please use following instead */
/* 
CREATE TABLE transactions(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT
);
 */

/* group 3 */
DROP TABLE IF EXISTS goods;
CREATE TABLE goods(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	type INTEGER NOT NULL
);

DROP TABLE IF EXISTS general_goods;
CREATE TABLE general_goods(
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
	foreign key (id) references goods(id) on delete cascade,
	foreign key (seller_id) references user(id) on delete cascade
);

DROP TABLE IF EXISTS hotel_room;
CREATE TABLE hotel_room(
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
	foreign key (id) references goods(id) on delete cascade,
	foreign key (seller_id) references user(id) on delete cascade
);

DROP TABLE IF EXISTS airplane_ticket;
CREATE TABLE airplane_ticket(
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
	foreign key (id) references goods(id) on delete cascade,
	foreign key (seller_id) references user(id) on delete cascade
);

DROP TABLE IF EXISTS browse_history;
CREATE TABLE browse_history(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	good_id INTEGER,
	user_id INTEGER,
	date_time BIGINT,
	foreign key (good_id) references goods(id) on delete cascade,
	foreign key (user_id) references user(id) on delete cascade
);

DROP TABLE IF EXISTS search_history;
CREATE TABLE search_history(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	search_key VARCHAR(256),
	user_id INTEGER,
	date_time BIGINT,
	foreign key (user_id) references user(id) on delete cascade
);

DROP TABLE IF EXISTS feedback;
CREATE TABLE feedback(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	user_id INTEGER,
	transaction_id INTEGER,
	score INTEGER,
	comment VARCHAR(1024),
	date_time BIGINT,
	foreign key (user_id) references user(id) on delete cascade,	
	foreign key (transaction_id) references transaction(id) on delete cascade
	/* if you are using sqlite please use following instead */
	/* foreign key (transaction_id) references transactions(id) on delete cascade */
);

DROP TABLE IF EXISTS shopping_cart;
CREATE TABLE shopping_cart(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	/* if you are using sqlite please use following instead */
	/* id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT */
	user_id INTEGER,
	good_id INTEGER,
	good_count INTEGER,
	foreign key (good_id) references goods(id) on delete cascade,
	foreign key (user_id) references user(id) on delete cascade
);

DROP TABLE IF EXISTS orders;
CREATE TABLE orders(
	id INTEGER NOT NULL PRIMARY KEY
);

/* group 4 */

/* group 5 */
