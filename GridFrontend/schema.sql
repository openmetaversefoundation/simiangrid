CREATE TABLE IF NOT EXISTS `sgf_sessions` (
  `session_id` varchar(40) collate utf8_bin NOT NULL default '0',
  `ip_address` varchar(16) collate utf8_bin NOT NULL default '0',
  `user_agent` varchar(150) collate utf8_bin NOT NULL default '',
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text collate utf8_bin NOT NULL default '',
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `sgf_user_session` (
	`session_id` VARCHAR(36) NOT NULL,
	`user_id` VARCHAR(36) NOT NULL,
	PRIMARY KEY ( `session_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `sgf_user_settings` (
	`user_id` VARCHAR(36) NOT NULL,
	`style` VARCHAR(16) NOT NULL,
	PRIMARY KEY ( `user_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
