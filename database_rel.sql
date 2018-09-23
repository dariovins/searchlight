



-- ---
-- ---
-- Test Data
-- ---
INSERT INTO `parliament` (`id`,`parliament_name`,`parliament_level`,`poz`,`userID`) VALUES
(NULL,'predstavnicki dom BiH','STATE','1','1'),
(NULL,'dom naroda BiH','STATE','2','1');
(NULL,'test parliament','STATE','2','1'); -- 3
INSERT INTO `parliament_committee` (`id`,`parliament_id`,`committee_name`,`userID`) VALUES
(NULL,'1','vanjska politika','1'),
(NULL,'1','finansije','1'),
(NULL,'2','narodi kom 1','1'),
(NULL,'2','narodi kom 2','1');
(NULL,'3','test committee','1'); -- 9
INSERT INTO `parliament_club` (`id`,`parliament_id`,`club_name`,`userID`) VALUES
(NULL,'1','HDZ','1'),
(NULL,'1','SDA','1'),
(NULL,'2','Bosnjaci','1'),
(NULL,'2','Portugalci','1');
(NULL,'3','test club','1'); -- 9


INSERT INTO `mandate` (`id`,`parliament_id`,`mandate_name`,`year_start`,`year_end`,`president_user_id`,`deputy1_user_id`,`deputy2_user_id`,`userID`) VALUES
(NULL,'1','2006-2010','2006','2010','1','3','4','1'),
(NULL,'1','2010-2014','2010','2014','2','4','3','1'),
(NULL,'2','2006-2010','2006','2010','5','7','8','1'),
(NULL,'2','2010-2014','2010','2014','6','8','7','1');
(NULL,'3','2010-2014','2010','2014','1','2','3','1'); -- 9

INSERT INTO `mandate_session` (`id`,`mandate_id`,`session_no`,`session_date`,`session_chair_user_id`,`userID`) VALUES
(NULL,5,1,now(),'1','1'),
(NULL,6,1,now(),'2','1'),
(NULL,7,1,now(),'8','1'),
(NULL,8,1,now(),'8','1');
(NULL,9,1,now(),'9','1'); -- 9 dario chair session 1
(NULL,9,2,now(),'10','1'); -- 10 dep 1 chair session 2
INSERT INTO `mandate_session_minutes` (`id`,`mandate_session_id`,`file_text`,`file_pdf`,`userID`) VALUES
(NULL,'5','','','1'),
(NULL,'6','','','1'),
(NULL,'7','','','1'),
(NULL,'8','','','1');
(NULL,'9','','','1'), -- 5
(NULL,'10','','','1'); -- 6
INSERT INTO `mandate_session_minutes_blob` (`id`,`mandate_session_minutes_id`,`file_dump`,`userID`) VALUES
(NULL,1,'','1'),
(NULL,2,'','1'),
(NULL,3,'','1'),
(NULL,4,'','1');
(NULL,5,'','1'),
(NULL,6,'','1');
INSERT INTO `mandate_session_minutes_agenda` (`id`,`mandate_session_minutes_id`,`agenda_no`,`agenda_title`,`userID`) VALUES
(NULL,1,'1','tacka 1','1'),
(NULL,1,'2','tacka 2','1'),
(NULL,2,'1','tacka 1','1'),
(NULL,3,'1','tacka 1','1'),
(NULL,4,'1','tacka 1','1');
(NULL,5,'1','test tacka 1','1'), -- 10
(NULL,5,'2','test tacka 2','1'), -- 11
(NULL,6,'1','test tacka 1','1'), -- 12
(NULL,6,'2','test tacka 2','1'); -- 13
INSERT INTO `mandate_session_minutes_agenda_debate` (`id`,`mandate_session_minutes_agenda_id`,`mandate_member_id`,`debate_text`,`userID`) VALUES
(NULL,'1','1','dar reko','1'),
(NULL,'1','2','gor reko','1'),
(NULL,'1','1','dar reko opet','1'),
(NULL,'2','2','dar reko 2','1'),
(NULL,'2','1','gor reko 2','1'),
(NULL,'3','1','dar reko 3','1'),
(NULL,'3','2','gor reko 3','1'),
(NULL,'4','5','narod gor reko','1'),
(NULL,'4','6','narod dar reko','1'),
(NULL,'5','6','narod dar reko 2','1'),
(NULL,'5','5','narod gor reko 2','1'); -- 11

(NULL,'10','9','test dario reko 1','1'), -- 12
(NULL,'10','10','test dep1 reko 1','1'), -- 13
(NULL,'10','9','test dario reko opet 1','1'), -- 14
(NULL,'11','9','test dario reko 2','1'), -- 15
(NULL,'11','11','test dep2 reko 2','1'); -- 16


INSERT INTO `member` (`id`,`screen_name`,`first_name`,`last_name`,`is_user`,`is_author`,`userID`) VALUES
(NULL,'dario','D','V','N','N','1'), -- 1
(NULL,'goran','G','T','N','N','1'), -- 2
(NULL,'dep 1','D','1','N','N','1'), -- 3
(NULL,'dep 2','D','2','N','N','1'), -- 4
(NULL,'narod dario','D','V','N','N','1'),
(NULL,'narod goran','G','T','N','N','1'),
(NULL,'narod dep 1','D','1','N','N','1'),
(NULL,'narod dep 2','D','2','N','N','1');
INSERT INTO `mandate_member` (`id`,`mandate_id`,`member_id`,`userID`) VALUES
(NULL,'5','1','1'),
(NULL,'5','2','1'),
(NULL,'6','1','1'),
(NULL,'6','2','1'),
(NULL,'7','5','1'),
(NULL,'7','6','1'),
(NULL,'8','5','1'),
(NULL,'8','6','1');
(NULL,'9','1','1'); -- 9 dario
(NULL,'9','3','1'), -- 10 dep1
(NULL,'9','4','1'); -- 11 dep2
INSERT INTO `mandate_member_club` (`id`,`mandate_member_id`,`parliament_club_id`,`active`,`userID`) VALUES
(NULL,'1','5','N','1'),
(NULL,'1','6','Y','1'),
(NULL,'2','5','Y','1'),
(NULL,'3','6','Y','1'),
(NULL,'4','6','Y','1'),
(NULL,'5','5','Y','1'),
(NULL,'6','6','Y','1'),
(NULL,'7','5','Y','1'),
(NULL,'8','5','Y','1');
(NULL,'9','9','Y','1'); -- 19
INSERT INTO `mandate_member_committee` (`id`,`mandate_member_id`,`parliament_committee_id`,`active`,`userID`) VALUES
(NULL,'1','5','N','1'),
(NULL,'1','6','Y','1'),
(NULL,'2','5','Y','1'),
(NULL,'3','6','Y','1'),
(NULL,'4','6','Y','1'),
(NULL,'5','5','Y','1'),
(NULL,'6','6','Y','1'),
(NULL,'7','5','Y','1'),
(NULL,'8','5','Y','1');
(NULL,'9','9','Y','1'); -- 19






INSERT INTO `mandate_session_minutes_agenda_debate_view` (`id`,`mandate_session_minutes_agenda_id`,`mandate_session_minutes_id`,`mandate_session_id`,`mandate_id`,`parliament_id`,`member_id`,`mandate_member_id`,`parliament_name`,`parliament_level`,`mandate_name`,`session_no`,`session_date`,`session_chair_user_id`,`file_pdf`,`file_text`,`agenda_no`,`agenda_title`,`debate_text`) VALUES
('','','','','','','','','','','','','','','','','','','');

