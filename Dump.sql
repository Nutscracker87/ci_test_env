CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(1024) DEFAULT NULL,
  `short_description` varchar(2048) DEFAULT NULL,
  `text` text,
  `img` varchar(1024) DEFAULT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `news` (`id`, `header`, `short_description`, `text`, `img`, `tags`, `status`, `time_created`, `time_updated`)
VALUES
	(1,'News #1','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore \' +\n            \'et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\' +\n            \' ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu \' +\n            \'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt \' +\n            \'mollit anim id est laborum.','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore \' +\n            \'et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip\' +\n            \' ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu \' +\n            \'fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt \' +\n            \'mollit anim id est laborum.','/assets/images/news/cover-news-20180808.png','кек,чебурек','open','2018-08-30 16:31:14','2018-10-11 04:37:16'),
	(3,'Эх, чужд1 кайф, сплющь1','<p>Широкая электрификация южных губерний даст мощный толчок подъёму сельского хозяйства.<br></p>','<<<<<<<p>Эй, жлоб! Где туз? Прячь юных <u><b>съёмщиц</b></u> в шкаф. Съешь [же] ещё этих мягких <span style=\"background-color: rgb(255, 255, 0);\">французских</span> булок да выпей чаю. В чащах юга жил бы цитрус? Да, но фальшивый экземпляр! Эх, чужак! Общий съём <a href=\"#\" target=\"_blank\">цен</a> шляп (юфть) — вдрызг!<br></p>','/assets/images/news/3.jpg',NULL,'open','2018-10-11 04:33:27','2018-11-13 04:17:04'),
  (4,'Эх, чужд2 кайф, сплющь2','<p>Широкая электрификация северных губерний даст мощный толчок подъёму сельского хозяйства.<br></p>','<<<<<<<p>Эй, жлоб! Где туз? Прячь юных <u><b>съёмщиц</b></u> в шкаф. Съешь [же] ещё этих мягких <span style=\"background-color: rgb(255, 255, 0);\">французских</span> булок да выпей чаю. В чащах юга жил бы цитрус? Да, но фальшивый экземпляр! Эх, чужак! Общий съём <a href=\"#\" target=\"_blank\">цен</a> шляп (юфть) — вдрызг!<br></p>','/assets/images/news/3.jpg',NULL,'open','2018-10-11 04:33:27','2018-11-13 04:17:04'),
  (5,'Эх, чужд3 кайф, сплющь3','<p>Широкая электрификация восточных губерний даст мощный толчок подъёму сельского хозяйства.<br></p>','<<<<<<<p>Эй, жлоб! Где туз? Прячь юных <u><b>съёмщиц</b></u> в шкаф. Съешь [же] ещё этих мягких <span style=\"background-color: rgb(255, 255, 0);\">французских</span> булок да выпей чаю. В чащах юга жил бы цитрус? Да, но фальшивый экземпляр! Эх, чужак! Общий съём <a href=\"#\" target=\"_blank\">цен</a> шляп (юфть) — вдрызг!<br></p>','/assets/images/news/3.jpg',NULL,'open','2018-10-11 04:33:27','2018-11-13 04:17:04');


CREATE TABLE `comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
  `news_id` INT(11) NOT NULL ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `level` INT(3) NOT NULL DEFAULT '1',
  `parent_id` INT(11) NOT NULL ,
  `time_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `time_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

ALTER TABLE `comments` ADD FOREIGN KEY (`news_id`) REFERENCES `news`(`id`) ON DELETE CASCADE;

INSERT INTO `comments` (`text`, `news_id`, `user_id`, `level`, `parent_id`)
VALUES
	('comment 1', 1, 1, 1, 0),
  ('comment 2', 1, 2, 1, 1),
  ('comment 3', 1, 2, 1, 0),
  ('comment 4', 3, 2, 1, 0),
  ('comment 5', 3, 1, 1, 4),
	('comment 6', 3, 1, 1, 4);

CREATE TABLE `likes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,

  `entity_id` INT(11) NOT NULL ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `entity` enum('news','comment') DEFAULT 'news',
  `time_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `time_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `likes` (`entity_id`, `user_id`, `entity`)
VALUES
	(1, 1, 'news'),
  (1, 2, 'news'),
  (3, 2, 'news'),
  (3, 1, 'news'),
  (6, 1, 'comment'),
  (7, 2, 'comment'),
  (7, 1, 'comment');