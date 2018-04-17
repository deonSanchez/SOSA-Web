CREATE TABLE `board` (
  `idboard` int(11) NOT NULL AUTO_INCREMENT,
  `board_name` varchar(45) NOT NULL,
  `lock_tilt` tinyint(4) NOT NULL,
  `lock_rotate` tinyint(4) NOT NULL,
  `lock_zoom` tinyint(4) NOT NULL,
  `board_color` varchar(45) NOT NULL,
  `background_color` varchar(45) NOT NULL,
  `cover_color` varchar(45) NOT NULL,
  `image` mediumblob,
  `json_object` json NOT NULL,
  PRIMARY KEY (`idboard`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8