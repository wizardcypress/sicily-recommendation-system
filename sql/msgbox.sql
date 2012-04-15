DROP TABLE IF EXISTS `msgbox`;
CREATE TABLE IF NOT EXISTS `msgbox` (
  `mid` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `status` set('readed','unread') character set utf8 collate utf8_unicode_ci NOT NULL default 'unread',
  `htmlcontent` text NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


