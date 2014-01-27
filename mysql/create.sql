DROP TABLE if EXISTS ASnum ;
CREATE TABLE `ASnum` (
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  `Asname` varchar(128) NOT NULL
);



DROP TABLE IF EXISTS Block;
CREATE TABLE `Block` (
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  `CityId` int(11) DEFAULT NULL
);


-- CREATE TABLE `GeoIPCountryWhois` (
--   `start_ip` char(15) NOT NULL,
--   `end_ip` char(15) NOT NULL,
--   `start` int(10) unsigned NOT NULL,
--   `end` int(10) unsigned NOT NULL,
--   `cc` char(2) NOT NULL,
--   `cn` varchar(50) NOT NULL
-- );
-- 


DROP TABLE IF EXISTS Location;
CREATE TABLE `Location` (
  `locId` int(11) NOT NULL,
  `country` char(2) DEFAULT NULL,
  `region` char(2) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postalCode` varchar(6) DEFAULT NULL,
  `latitude` decimal(10,4) DEFAULT NULL,
  `longitude` decimal(10,4) DEFAULT NULL,
  `metroCode` int(10) unsigned DEFAULT NULL,
  `areaCode` char(1) DEFAULT NULL,
  PRIMARY KEY (`locId`)
);


---- adapt to PATH
load data infile '/tmp/GeoIPASNum2.csv' into table ASnum fields terminated by ',' optionally enclosed by '"'  lines terminated by '\n' ignore 2 lines;
load data infile '/tmp/GeoLiteCity-Blocks.csv' into table Block fields terminated by ',' optionally enclosed by '"'  lines terminated by '\n' ignore 2 lines;
load data infile '/tmp/GeoLiteCity-Location.csv' into table Location fields terminated by ',' optionally enclosed by '"'  lines terminated by '\n' ignore 2 lines;

