SET NAMES utf8;
SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `answer`;
CREATE TABLE `answer` (
	`id_answer` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_team` int(25) unsigned NOT NULL COMMENT 'tym, ktery hada kod',
	`id_task` int(25) unsigned NOT NULL COMMENT 'ukol, jehoz kod se hada',
	`code` varchar(250) COLLATE utf8_czech_ci NOT NULL COMMENT 'kod ukolu, na ktery tym odpovida',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_answer`),
	UNIQUE KEY `id_team` (`id_team`,`id_task`,`code`),
	KEY `id_task` (`id_task`),
	CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`id_task`) REFERENCES `task` (`id_task`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='pokusy uhadnout kod ukolu';


DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
	`id_chat` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_team` int(25) unsigned NOT NULL COMMENT 'tym, ktery prispevek vlozil',
	`id_parent` int(25) unsigned NULL COMMENT 'rodicovsky prispevek',
	`content` text COLLATE utf8_czech_ci NOT NULL COMMENT 'text prispevku',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_chat`),
	KEY `id_team` (`id_team`),
	CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`id_parent`) REFERENCES `chat` (`id_chat`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='diskusni prispevku na chatu';


DROP TABLE IF EXISTS `competitor`;
CREATE TABLE `competitor` (
	`id_competitor` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_team` int(25) unsigned NOT NULL COMMENT 'tym, do ktereho ucastnik patri',
	`id_school` int(25) unsigned DEFAULT NULL COMMENT 'skola, kam ucastnik chodi',
	`name` varchar(250) COLLATE utf8_czech_ci NOT NULL COMMENT 'jmeno',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_competitor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='informace o soutezicich';


DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
	`id_log` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_team` int(25) unsigned NULL COMMENT 'tym, ktereho se zaznam tyka',
	`type` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'typ zaznamu',
	`text` text COLLATE utf8_czech_ci NOT NULL COMMENT 'text zaznamu',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	PRIMARY KEY (`id_log`),
	KEY `id_team` (`id_team`),
	CONSTRAINT `log_ibfk_1` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='logovani akci tymu';


DROP TABLE IF EXISTS `school`;
CREATE TABLE `school` (
	`id_school` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`name` varchar(150) COLLATE utf8_czech_ci NOT NULL COMMENT 'nazev skoly',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_school`),
	UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Skoly, ze kterych pochazi soutezici';


DROP TABLE IF EXISTS `serie`;
CREATE TABLE `serie` (
	`id_serie` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_year` int(25) unsigned NOT NULL,
	`to_show` datetime NOT NULL COMMENT 'cas, kdy ma byt serie zverejnena',
	`text` text COLLATE utf8_czech_ci NOT NULL COMMENT 'komentar k serii, ktery muze napr. obsahovat odkaz ke stazeni pdf apod.',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_serie`),
	KEY `id_year` (`id_year`),
	CONSTRAINT `serie_ibfk_1` FOREIGN KEY (`id_year`) REFERENCES `year` (`id_year`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='serie ukolu';


DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
	`id_task` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_serie` int(25) unsigned DEFAULT NULL COMMENT 'serie, do ktere ukol patri',
	`number` int(2) unsigned NOT NULL COMMENT 'cislo ukolu v ramci serie',
	`type` enum('logical','programming','idea') COLLATE utf8_czech_ci NOT NULL COMMENT 'typ ukolu',
	`name` varchar(250) COLLATE utf8_czech_ci NOT NULL COMMENT 'nazev ukolu',
	`code` varchar(250) COLLATE utf8_czech_ci NOT NULL COMMENT 'kod ukolu, ktery maji ucastnicke tymy resit',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_task`),
	KEY `id_serie` (`id_serie`),
	CONSTRAINT `task_ibfk_1` FOREIGN KEY (`id_serie`) REFERENCES `serie` (`id_serie`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='ukoly';


DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
	`id_team` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`id_year` int(25) unsigned NOT NULL,
	`name` varchar(150) COLLATE utf8_czech_ci NOT NULL COMMENT 'prihlasovaci jmeno',
	`password` varchar(160) COLLATE utf8_czech_ci NOT NULL COMMENT 'zahashovane heslo',
	`category` enum('high_school','college','other') COLLATE utf8_czech_ci NOT NULL COMMENT 'soutezni kategorie',
	`email` varchar(150) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'e-mailova adresa',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	`reset_code` varchar(160) COLLATE utf8_czech_ci NULL COMMENT 'password reset code',
	`source` enum('history', 'friends', 'facebook', 'instagram', 'paper', 'email') COLLATE utf8_czech_ci NULL COMMENT 'odkud se o soutezi dozvedeli',
	PRIMARY KEY (`id_team`),
	UNIQUE KEY `id_year` (`id_year`,`name`),
	UNIQUE KEY `id_year_2` (`id_year`,`email`),
	CONSTRAINT `team_ibfk_1` FOREIGN KEY (`id_year`) REFERENCES `year` (`id_year`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Soutezni tymy';

DROP TABLE IF EXISTS `year`;
CREATE TABLE `year` (
	`id_year` int(25) unsigned NOT NULL AUTO_INCREMENT COMMENT 'identifikator',
	`name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
	`registration_start` datetime NOT NULL COMMENT 'cas, kdy zacina registrace do tohoto rocniku',
	`registration_end` datetime NOT NULL COMMENT 'cas, kdy konci registrace do tohoto rocniku',
	`game_start` datetime NOT NULL COMMENT 'cas, kdy zacina hra',
	`game_end` datetime NOT NULL COMMENT 'cas, kdy konci hra',
	`inserted` datetime NOT NULL COMMENT 'cas, kdy byla polozka vlozena do systemu',
	`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'cas, kdy byla polozka naposledy zmenena',
	PRIMARY KEY (`id_year`),
	UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci COMMENT='Rocniky';
