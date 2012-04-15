DROP TABLE IF EXISTS `feeds`;
CREATE TABLE IF NOT EXISTS `feeds` (
  `fid` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `reply` int(11) default 0,
  `comment` int(11) default 0,
  `reply_fid` int(11) default 0,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


