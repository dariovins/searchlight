-- ALTER TABLE `parliament_club` DROP FOREIGN KEY parliament_id;
-- ALTER TABLE `parliament_committee` DROP FOREIGN KEY parliament_id,
-- ALTER TABLE `mandate` DROP FOREIGN KEY parliament_id,
-- ALTER TABLE `mandate_member` DROP FOREIGN KEY mandate_id,
-- ALTER TABLE `mandate_member` DROP FOREIGN KEY member_id,
-- ALTER TABLE `mandate_member_club` DROP FOREIGN KEY mandate_member_id,
-- ALTER TABLE `mandate_member_club` DROP FOREIGN KEY parliament_club_id,
-- ALTER TABLE `mandate_member_committee` DROP FOREIGN KEY mandate_member_id,
-- ALTER TABLE `mandate_member_committee` DROP FOREIGN KEY parliament_committee_id,
-- ALTER TABLE `mandate_session` DROP FOREIGN KEY mandate_id,
-- ALTER TABLE `mandate_session_minutes` DROP FOREIGN KEY mandate_session_id,
-- ALTER TABLE `mandate_session_minutes_blob` DROP FOREIGN KEY session_minutes_id,
-- ALTER TABLE `mandate_session_minutes_agenda` DROP FOREIGN KEY mandate_session_id,
-- ALTER TABLE `mandate_session_minutes_agenda_debate` DROP FOREIGN KEY mandate_session_minutes_agenda_id,
-- ALTER TABLE `mandate_session_minutes_agenda_debate` DROP FOREIGN KEY mandate_member_id,


-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=1;

--
-- Table structure 
--
-- DROP TABLE IF EXISTS ``;
-- CREATE TABLE IF NOT EXISTS `` (
--   `id` mediumint(10) NOT NULL AUTO_INCREMENT,
--   PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


-- 
-- MODULE POMOCNI PODACI
-- 

-- entitet - unique name per level
DROP TABLE IF EXISTS `entity`;
CREATE TABLE `entity` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `entity_name` VARCHAR(50) NULL DEFAULT NULL,
  `entity_level` ENUM('MUNICIPAL','CANTON','ENTITY') NULL DEFAULT NULL,  -- u posebnu tabelu
-- other info
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL, -- foreign key users.id
  PRIMARY KEY (`id`),
  UNIQUE KEY (`entity_name`,`entity_level`)
);

-- izborna jedinica - u BiH electorate / entity
DROP TABLE IF EXISTS `electorate`;
CREATE TABLE `electorate` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `electorate` VARCHAR(50) NULL DEFAULT NULL,
  `entity_id` INTEGER(9) NULL DEFAULT NULL,  -- u posebnu tabelu
-- other info
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL, -- foreign key users.id
  PRIMARY KEY (`id`),
  UNIQUE KEY (`electorate`,`entity_id`)
);


-- stranke
DROP TABLE IF EXISTS `party`;
CREATE TABLE `party` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `party_name` VARCHAR(50) NULL DEFAULT NULL,
  `short_name` VARCHAR(50) NULL DEFAULT NULL,
-- other info
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL, -- foreign key users.id
  PRIMARY KEY (`id`),
  UNIQUE KEY (`party_name`)
);




--
-- PARLIAMENT MODULE
--

-- PARLIAMENT
DROP TABLE IF EXISTS `parliament`;
CREATE TABLE `parliament` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `parliament_name` VARCHAR(50) NULL DEFAULT NULL,
  `parliament_level` ENUM('MUNICIPAL','CANTON','ENTITY','STATE') NULL DEFAULT NULL,  -- u posebnu tabelu
-- other info
  `poz` INTEGER(6) NOT NULL,
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL, -- foreign key users.id
  PRIMARY KEY (`id`),
  UNIQUE KEY (`parliament_name`)
);



-- KLUB - master tables svih klubova u svim parlamentima
-- iz mandata do mandata mozes dodavati nove klubove
DROP TABLE IF EXISTS `parliament_club`;
CREATE TABLE IF NOT EXISTS `parliament_club` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `parliament_id` INTEGER(9) NULL DEFAULT NULL,
  `club_name` VARCHAR(30) NULL DEFAULT NULL, -- ovo je screen name kluba
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`club_name`),
  PRIMARY KEY (`id`)
);


-- KOMISIJE - master tabela svih komisija u svim parlamentima
-- iz mandata do mandata mozes dodavati nove komisije ako se kreiraju
DROP TABLE IF EXISTS `parliament_committee`;
CREATE TABLE IF NOT EXISTS `parliament_committee` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `parliament_id` INTEGER(9) NULL DEFAULT NULL,
  `committee_name` VARCHAR(30) NULL DEFAULT NULL, -- ovo je screen name komisije
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`committee_name`)
);


-- parliament_group
-- GRUPE - master tabela svih grupa u svim parlamentima
DROP TABLE IF EXISTS `parliament_group`;
CREATE TABLE IF NOT EXISTS `parliament_group` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `parliament_id` INTEGER(9) NULL DEFAULT NULL,
  `group_name` VARCHAR(30) NULL DEFAULT NULL, -- ovo je screen name komisije
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`group_name`)
);

-- parliament_delegation
DROP TABLE IF EXISTS `parliament_delegation`;
CREATE TABLE IF NOT EXISTS `parliament_delegation` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `parliament_id` INTEGER(9) NULL DEFAULT NULL,
  `delegation_name` VARCHAR(30) NULL DEFAULT NULL, -- ovo je screen name komisije
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`delegation_name`)
);







--
-- MODULE members of parliament
--

-- MEMBERS OF PARLIAMENT IN MANDATE
-- master tabella znaci jednom reg osobu MP
-- jedinstveni registar osoba - bez obzira u kojem je parlamentu
DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `screen_name` VARCHAR(30) NULL DEFAULT NULL, -- ovo je PUNO IME u parlamentu u ZAPISNIKU, key za nalazenje imena u diskusijama i glasanjima
  `first_name` VARCHAR(50) NULL DEFAULT NULL,
  `last_name` VARCHAR(50) NULL DEFAULT NULL,
`gender` ENUM('M','F') NOT NULL DEFAULT 'M',
-- dob,
-- mjesto rodjenja
-- zanimanje
-- strucna sprema
-- srednja skola
-- fax
-- ostalo/specijalizacija
-- radno iskustvo
-- strani jezici
-- email
  `is_user` ENUM('N','Y') NOT NULL DEFAULT 'N',   -- is reg user of the system
  `is_author` ENUM('N','Y') NOT NULL DEFAULT 'N', -- is author reg in the system
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`screen_name`)
);

-- add imovinski cenzus za mp po mandatu
-- stranacka pripadnost po mandatu, copy na novi ako ostaje isti







-- 
-- MANDATE MODULE
-- 

-- MANDATE 
DROP TABLE IF EXISTS `mandate`;
CREATE TABLE IF NOT EXISTS `mandate` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `parliament_id` INTEGER(9) NULL DEFAULT NULL,
  `mandate_name` VARCHAR(30) NULL DEFAULT NULL, -- ovo je oznaka mandata e.g. 2006-2010

`date_start` DATE NULL DEFAULT NULL,
`date_end` DATE NULL DEFAULT NULL,

  `president_member_id` INTEGER(9) NULL DEFAULT NULL,
  `deputy1_member_id` INTEGER(9) NULL DEFAULT NULL,
  `deputy2_member_id` INTEGER(9) NULL DEFAULT NULL,

-- additional info

  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`mandate_name`,`parliament_id`)
);



-- MP in each mandate
DROP TABLE IF EXISTS `mandate_member`;    -- cross section members in different mandates;
CREATE TABLE IF NOT EXISTS `mandate_member` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_id` INTEGER(9) NULL DEFAULT NULL,
  `member_id` INTEGER(9) NULL DEFAULT NULL,
`date_start` DATE NULL DEFAULT NULL, -- datum pocetka mandata
`date_end` DATE NULL DEFAULT NULL, -- datum pocetka mandata
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`mandate_id`, `member_id`)
);
-- this list includes Executive Branch - MPs who took office.

-- members of clubs in mandate
DROP TABLE IF EXISTS `mandate_member_club`; -- cross section members in different mandates;
CREATE TABLE IF NOT EXISTS `mandate_member_club` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_member_id` INTEGER(9) NULL DEFAULT NULL,
  `parliament_club_id` INTEGER(9) NULL DEFAULT NULL,
  `active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
`date_start` DATE NULL DEFAULT NULL, -- datum pocetka mandata
`date_end` DATE NULL DEFAULT NULL, -- datum pocetka mandata
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_member_id`, `parliament_club_id`,`active`),
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'mandate_member_committee'
-- 
-- ---

DROP TABLE IF EXISTS `mandate_member_committee`;
CREATE TABLE IF NOT EXISTS `mandate_member_committee` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `mandate_member_id` INTEGER NULL DEFAULT NULL,
  `parliament_committee_id` INTEGER NULL DEFAULT NULL,
  `active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
`date_start` DATE NULL DEFAULT NULL, -- datum pocetka mandata
`date_end` DATE NULL DEFAULT NULL, -- datum pocetka mandata
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_member_id`, `parliament_committee_id`,`active`),
  PRIMARY KEY (`id`)
);


-- mandate_member_stranka
DROP TABLE IF EXISTS `mandate_member_party`;
CREATE TABLE IF NOT EXISTS `mandate_member_party` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `mandate_member_id` INTEGER NULL DEFAULT NULL,
  `party_id` INTEGER NULL DEFAULT NULL,
  `active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
`date_start` DATE NULL DEFAULT NULL, -- datum ulaska u partiju
`date_end` DATE NULL DEFAULT NULL, -- datum izlaska iz partije
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_member_id`, `party_id`,`active`),
  PRIMARY KEY (`id`)
);

-- mandate_member_entitet
DROP TABLE IF EXISTS `mandate_member_entity`;
CREATE TABLE IF NOT EXISTS `mandate_member_entity` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `mandate_member_id` INTEGER NULL DEFAULT NULL,
  `entity_id` INTEGER NULL DEFAULT NULL,
  `active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
`date_start` DATE NULL DEFAULT NULL, -- datum pocetka boravista
`date_end` DATE NULL DEFAULT NULL, -- datum odlaska/promjene boravista
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_member_id`, `entity_id`, `active`),
  PRIMARY KEY (`id`)
);

-- mandate_member_izborna_jedinica
DROP TABLE IF EXISTS `mandate_member_electorate`;
CREATE TABLE IF NOT EXISTS `mandate_member_electorate` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `mandate_member_id` INTEGER NULL DEFAULT NULL,
  `electorate_id` INTEGER NULL DEFAULT NULL,
  `active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
`date_start` DATE NULL DEFAULT NULL, -- datum pocetka/dolaska u izb jedinicu
`date_end` DATE NULL DEFAULT NULL, -- datum odlaska/promjene 
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_member_id`, `electorate_id`, `active`),
  PRIMARY KEY (`id`)
);

-- mandate_member_activities - po clanu ???
DROP TABLE IF EXISTS `mandate_member_activities`;
CREATE TABLE IF NOT EXISTS `mandate_member_activities` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `mandate_member_id` INTEGER NULL DEFAULT NULL,
  `active` ENUM('Y','N') NOT NULL DEFAULT 'Y',
-- additional info ???
`date_start` DATE NULL DEFAULT NULL, -- datum pocetka aktivnosti
`date_end` DATE NULL DEFAULT NULL, -- datum zavrsetka aktivnosti
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_member_id`, `electorate_id`, `active`),
  PRIMARY KEY (`id`)
);




--
-- MODULE SESSION
--

-- PARLIAMENTARY SESSION
DROP TABLE IF EXISTS `mandate_session`;
CREATE TABLE IF NOT EXISTS `mandate_session` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_id` INTEGER(9) NULL DEFAULT NULL,
  `session_no` INTEGER(9) NULL DEFAULT NULL,
  `session_date` DATETIME NULL DEFAULT NULL,
  `session_chair_member_id` INTEGER(9) NULL DEFAULT NULL, -- ForeignKey member.id - MP koji predsjedava - mora biti iz liste MPa za mandat
`session_start_time` DATETIME NULL DEFAULT NULL,
`session_end_time` DATETIME NULL DEFAULT NULL,
`relURI` varchar(255) NULL DEFAULT NULL, -- link za parlamentarnu stranicu za sjednicu
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_id`,`session_no`),
  PRIMARY KEY (`id`)
);


--
-- STENOGRAM MODULE
--

-- session_minutes
DROP TABLE IF EXISTS `mandate_session_minutes`;
CREATE TABLE IF NOT EXISTS `mandate_session_minutes` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_session_id` INTEGER(9) NULL DEFAULT NULL,
  `file_text` VARCHAR(50) NULL DEFAULT NULL, -- rel path to txt file - OVAJ FILE SE UCITAVA U GUI
  `file_pdf` VARCHAR(50) NULL DEFAULT NULL, -- rel path to pdf file
`relURI` varchar(255) NULL DEFAULT NULL, -- link za dokument stenograma za parlamentarnu sjednicu
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- blob dump of minutes text file
DROP TABLE IF EXISTS `mandate_session_minutes_blob`;
CREATE TABLE IF NOT EXISTS `mandate_session_minutes_blob` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_session_minutes_id` INTEGER(9) NULL DEFAULT NULL,
  `file_dump` BLOB NULL DEFAULT NULL,-- rel path to txt file - OVAJ FILE SE UCITAVA U GUI
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);


-- dnevni red
DROP TABLE IF EXISTS `mandate_session_minutes_agenda`;
CREATE TABLE IF NOT EXISTS `mandate_session_minutes_agenda` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_session_minutes_id` INTEGER(9) NULL DEFAULT NULL,
  `agenda_no` INTEGER(9) NULL DEFAULT NULL, -- broj tacke dnevnog reda
  `agenda_title` VARCHAR(255) NULL DEFAULT NULL, -- STRING za nalazenje ostalih items
`agenda_type` INTEGER(6) NULL DEFAULT NULL,  - ForeignKey to agenda_types table
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  UNIQUE KEY (`mandate_session_minutes_id`,`agenda_no`),
  PRIMARY KEY (`id`)
);

-- TABLE mandate_session_minutes_agenda_types
DROP TABLE IF EXISTS `mandate_session_minutes_agenda_types`;
CREATE TABLE IF NOT EXISTS `mandate_session_minutes_agenda_types` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `agenda_type_name` MEDIUMTEXT NULL DEFAULT NULL,
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);
-- agenda KEYWORDS to parse text
DROP TABLE IF EXISTS `mandate_session_minutes_agenda_types_keywords`;
CREATE TABLE IF NOT EXISTS `mandate_session_minutes_agenda_types_keywords` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `agenda_type_id` INTEGER(9) NULL DEFAULT NULL, -- FKEY
  `keyword` MEDIUMTEXT NULL DEFAULT NULL,
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- session replika
DROP TABLE IF EXISTS `mandate_session_minutes_agenda_debate`;
CREATE TABLE IF NOT EXISTS `mandate_session_minutes_agenda_debate` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `mandate_session_minutes_agenda_id` INTEGER(9) NULL DEFAULT NULL,
  `mandate_member_id` INTEGER(9) NULL DEFAULT NULL,
  `debate_text` MEDIUMTEXT NULL DEFAULT NULL, -- text chunk - OVAJ text SE prikazuje za svakog MP u debati
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` INTEGER(9) NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- VIEW replika
DROP VIEW IF EXISTS mandate_session_minutes_agenda_debate_view;
CREATE VIEW mandate_session_minutes_agenda_debate_view AS
SELECT 
msmad.id as id,
msmad.mandate_session_minutes_agenda_id,
msma.mandate_session_minutes_id,
msm.mandate_session_id,
ms.mandate_id,
m.parliament_id,
mmp.member_id,
msmad.mandate_member_id,
p.parliament_name,
p.parliament_level,
m.mandate_name,
ms.session_no,
ms.session_date,
ms.session_chair_member_id,
msm.file_pdf,
msm.file_text,
msma.agenda_no,
msma.agenda_title,
msmad.debate_text
FROM
mandate_session_minutes_agenda_debate as msmad LEFT JOIN mandate_member as mmp ON (msmad.mandate_member_id=mmp.id) LEFT JOIN member as mp ON (mmp.member_id = mp.id),
mandate_session_minutes_agenda as msma, mandate_session_minutes as msm,
mandate_session as ms, mandate as m, parliament as p
WHERE
    msmad.mandate_session_minutes_agenda_id = msma.id 
AND msma.mandate_session_minutes_id = msm.id
AND msm.mandate_session_id = ms.id
AND ms.mandate_id = m.id
AND m.parliament_id = p.id







-- TO DO - RIJESITI OSTALE OSOBE I OSTALE TACNE DN REDA







--
-- MODULE GLASANJE 
--





--
-- MODULE NACRTI ZAKONA SA PARLAMENTA
--




--
-- MODULE SL. LIST
--





--
-- USER MODULE
--

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `type` INTEGER(3) NOT NULL DEFAULT 0,
  `grupa` INTEGER(10) NULL DEFAULT NULL,
  `grupa_common` INTEGER(4) NULL DEFAULT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(40) NOT NULL,
  `ime` VARCHAR(20) NULL DEFAULT NULL,
  `prezime` VARCHAR(20) NULL DEFAULT NULL,
  `adresa` VARCHAR(100) NULL DEFAULT NULL,
  `grad` VARCHAR(100) NULL DEFAULT NULL,
  `drzava` VARCHAR(100) NULL DEFAULT NULL,
  `tel` VARCHAR(100) NULL DEFAULT NULL,
  `fax` VARCHAR(100) NULL DEFAULT NULL,
  `mob` VARCHAR(100) NULL DEFAULT NULL,
  `mail` VARCHAR(50) NOT NULL,
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `deleted` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_access` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);


--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `grupa`, `grupa_common`, `username`, `password`, `ime`, `prezime`, `adresa`, `grad`, `drzava`, `tel`, `fax`, `mob`, `mail`, `komentar`, `deleted`, `date_deleted`, `date_created`, `date_access`, `date_lastChange`) VALUES
(1, 1, NULL, NULL, 'dario', '8a49317e060e23bb86f9225ca581e0a9', 'admin', 'user', '', '', '', '', '', '', 'dariovins@gmail.com', '', 'N', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2011-10-20 12:12:10', '2011-10-20 12:12:10');


--
-- Table structure for table `authors`
--
DROP TABLE IF EXISTS `authors`;
CREATE TABLE IF NOT EXISTS `authors` (
  `id` INTEGER(10) NOT NULL AUTO_INCREMENT,
  `type` INTEGER(3) NOT NULL DEFAULT 0,
  `is_user` ENUM('N','Y') NOT NULL DEFAULT 'N',
  `users_id` INTEGER(10) NULL DEFAULT NULL,
  `ime` VARCHAR(20) NOT NULL,
  `prezime` VARCHAR(20) NOT NULL,
  `adresa` VARCHAR(100) NULL DEFAULT NULL,
  `grad` VARCHAR(100) NULL DEFAULT NULL,
  `drzava` VARCHAR(100) NULL DEFAULT NULL,
  `tel` VARCHAR(100) NULL DEFAULT NULL,
  `fax` VARCHAR(100) NULL DEFAULT NULL,
  `mob` VARCHAR(100) NULL DEFAULT NULL,
  `mail` VARCHAR(50) NOT NULL,
  `komentar` MEDIUMTEXT NULL DEFAULT NULL,
  `date_created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_firstAccess` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastLogin` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_deleted` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_lastChange` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);







-- ---
-- Foreign Keys 
-- ---
ALTER TABLE `parliament_club` ADD FOREIGN KEY (parliament_id) REFERENCES `parliament` (`id`);
ALTER TABLE `parliament_committee` ADD FOREIGN KEY (parliament_id) REFERENCES `parliament` (`id`);
ALTER TABLE `mandate` ADD FOREIGN KEY (parliament_id) REFERENCES `parliament` (`id`);
ALTER TABLE `mandate_member` ADD FOREIGN KEY (mandate_id) REFERENCES `mandate` (`id`);
ALTER TABLE `mandate_member` ADD FOREIGN KEY (member_id) REFERENCES `member` (`id`);
ALTER TABLE `mandate_member_club` ADD FOREIGN KEY (mandate_member_id) REFERENCES `mandate_member` (`id`);
ALTER TABLE `mandate_member_club` ADD FOREIGN KEY (parliament_club_id) REFERENCES `parliament_club` (`id`);
ALTER TABLE `mandate_member_committee` ADD FOREIGN KEY (mandate_member_id) REFERENCES `mandate_member` (`id`);
ALTER TABLE `mandate_member_committee` ADD FOREIGN KEY (parliament_committee_id) REFERENCES `parliament_committee` (`id`);
ALTER TABLE `mandate_session` ADD FOREIGN KEY (mandate_id) REFERENCES `mandate` (`id`);
ALTER TABLE `mandate_session_minutes` ADD FOREIGN KEY (mandate_session_id) REFERENCES `mandate_session` (`id`);
ALTER TABLE `mandate_session_minutes_blob` ADD FOREIGN KEY (mandate_session_minutes_id) REFERENCES `mandate_session_minutes` (`id`);
ALTER TABLE `mandate_session_minutes_agenda` ADD FOREIGN KEY (mandate_session_minutes_id) REFERENCES `mandate_session_minutes` (`id`);
ALTER TABLE `mandate_session_minutes_agenda_debate` ADD FOREIGN KEY (mandate_session_minutes_agenda_id) REFERENCES `mandate_session_minutes_agenda` (`id`);
ALTER TABLE `mandate_session_minutes_agenda_debate` ADD FOREIGN KEY (mandate_member_id) REFERENCES `mandate_member` (`id`);

ALTER TABLE `mandate_member_entity` ADD FOREIGN KEY (mandate_member_id) REFERENCES `mandate_member` (`id`);
ALTER TABLE `mandate_member_entity` ADD FOREIGN KEY (entity_id) REFERENCES `entity` (`id`);

ALTER TABLE `electorate` ADD FOREIGN KEY (entity_id) REFERENCES `entity` (`id`);



SELECT 
msmad.id as id,
msmad.mandate_session_minutes_agenda_id,
msma.mandate_session_minutes_id,
msm.mandate_session_id,
ms.mandate_id,
m.parliament_id,
mmp.member_id,
msmad.mandate_member_id,
p.parliament_name,
p.parliament_level,
m.mandate_name,
ms.session_no,
ms.session_date,
ms.session_chair_member_id,
msm.file_pdf,
msm.file_text,
msma.agenda_no,
msma.agenda_title,
msmad.debate_text
FROM
mandate_session_minutes_agenda_debate as msmad LEFT JOIN mandate_member as mmp ON (msmad.mandate_member_id=mmp.id) LEFT JOIN member as mp ON (mmp.member_id = mp.id),
mandate_session_minutes_agenda as msma, mandate_session_minutes as msm,
mandate_session as ms, mandate as m, parliament as p
WHERE
    msmad.mandate_session_minutes_agenda_id = msma.id 
AND msma.mandate_session_minutes_id = msm.id
AND msm.mandate_session_id = ms.id
AND ms.mandate_id = m.id
AND m.parliament_id = p.id



