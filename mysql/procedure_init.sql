drop procedure IF EXISTS initGeoIp ;
delimiter //
CREATE PROCEDURE initGeoIp()
BEGIN
declare Nb	int(11);
declare champ 	int(11);
declare x	int(2);
-- create splitted table -- 
drop table if exists Recap;
CREATE TABLE `Recap` ( `start` int(10) unsigned, `end` int(10) unsigned, `tb` varchar(2) CHARACTER SET utf8 NOT NULL DEFAULT '');

select count(*) from Block into Nb;
select Nb/5 into champ;
set x = 0;
WHILE x  < 5 DO
set @s=concat("drop table if exists t",x,";");
PREPARE stmt FROM @s; EXECUTE stmt;
set @s=concat("create table t",x," as select * from Block limit ",champ*x,",",champ,";");
PREPARE stmt FROM @s; EXECUTE stmt;
set @s=concat("insert into Recap select min(start),max(end),'t",x,"' as tb from t",x,";");
PREPARE stmt FROM @s; EXECUTE stmt;
select 'create table t',x;
SET  x = x + 1; 
END WHILE;
END//

drop procedure if exists stats;
CREATE PROCEDURE stats()
BEGIN
select count(*) from ASnum;
select count(*) from Block;
select count(*) from Location;
END//

delimiter ;

call initGeoIp();

drop procedure IF EXISTS GetLoc ;
delimiter //
CREATE PROCEDURE GetLoc(IN ip CHAR(15))
BEGIN
declare param char(3);
declare asn	char(128);
select tb from Recap where INET_ATON(ip) between start and end into param;
select Asname from geoip.ASnum where INET_ATON(ip) between start and end into asn;
SET @s = CONCAT('SELECT *,"',asn,'" as ASnum from ',param,' block left join Location as Location  on block.CityId=Location.locID  where INET_ATON(\'',ip,'\') between start and end;');
PREPARE stmt FROM @s; EXECUTE stmt;
END//

delimiter ;

call stats();
call GetLoc('8.8.8.8');
call GetLoc('217.19.48.80');
