DROP TABLE IF EXISTS `jedis`;

CREATE TABLE `jedis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `strain` varchar(255) NOT NULL DEFAULT '',
  `rank` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;