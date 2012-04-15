DROP TABLE IF EXISTS  `user_rate`;
CREATE TABLE IF NOT EXISTS `user_rate` (
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `wrong` int(11) NOT NULL,
  `right` int(11) NOT NULL,
  `rate` float default 0,
  PRIMARY KEY  (`uid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



