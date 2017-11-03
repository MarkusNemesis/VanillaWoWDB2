SET NAMES 'utf8';
SET SQL_MODE = '';

CREATE TABLE IF NOT EXISTS `aowow_news` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `text_loc0` varchar(255) NOT NULL default '',
  `text_loc2` varchar(255) NOT NULL default '',
  `text_loc3` varchar(255) NOT NULL default '',
  `text_loc8` varchar(255) NOT NULL default '',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='News';

INSERT IGNORE INTO `aowow_news` (`id`,`text_loc0`, `text_loc2`, `text_loc3`, `text_loc8`,`time`) VALUES
(1,'Welcome to <b><span class=\"q5\">AoWoW</span></b>!','','','Добро пожаловать на <b><span class=\"q5\">AoWoW</span></b>!','2008-09-05 07:00:00');