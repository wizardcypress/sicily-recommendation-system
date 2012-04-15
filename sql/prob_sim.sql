DROP TABLE IF EXISTS `prob_sim`;
CREATE TABLE IF NOT EXISTS `prob_sim` (
  `pid1` int(11) NOT NULL,
  `pid2` int(11) NOT NULL,
  `sim` float,
  PRIMARY KEY  (`pid1`,`pid2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


