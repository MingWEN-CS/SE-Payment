DROP DATABASE IF EXISTS payment;
CREATE DATABASE payment;
CREATE USER 'laolao'@'localhost' IDENTIFIED BY 'laolao';
GRANT ALL PRIVILEGES ON payment.* TO 'laolao'@'localhost';
USE payment;
DROP TABLE IF EXISTS User;
CREATE TABLE User(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY
);

DROP TABLE IF EXISTS Goods;
CREATE TABLE Goods(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY
);

DROP TABLE IF EXISTS GeneralGoods;
CREATE TABLE GeneralGoods(
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
	description VARCHAR(1024)
);

DROP TABLE IF EXISTS HotelRoom;
CREATE TABLE HotelRoom(
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
	suit_type VARCHAR(32)
);

DROP TABLE IF EXISTS AirplaneTicket;
CREATE TABLE AirplaneTicket(
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
	departue_date_time BIGINT,
	arrival_date_time BIGINT,
	departue_place VARCHAR(64),
	arrival_place VARCHAR(64),
	non_stop BOOLEAN,
	carbin_type VARCHAR(32)
);

DROP TABLE IF EXISTS BrowseHistory;
CREATE TABLE BrowseHistory(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	good_id INTEGER,
	date_time BIGINT
);

DROP TABLE IF EXISTS SearchHistory;
CREATE TABLE SearchHistory(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	search_key VARCHAR(256),
	date_time BIGINT
);

DROP TABLE IF EXISTS Feedback;
CREATE TABLE Feedback(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INTEGER,
	score INTEGER,
	comment VARCHAR(1024),
	date_time BIGINT
);

DROP TABLE IF EXISTS ShoppingCart;
CREATE TABLE ShoppingCart(
	id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INTEGER,
	good_id INTEGER,
	good_count INTEGER
);