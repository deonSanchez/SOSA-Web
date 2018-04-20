-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema sosa
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sosa
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sosa` DEFAULT CHARACTER SET latin1 ;
USE `sosa` ;

CREATE TABLE `board` (
  `idboard` int(11) NOT NULL AUTO_INCREMENT,
  `board_name` varchar(45) NOT NULL,
  `lock_tilt` tinyint(4) NOT NULL,
  `lock_rotate` tinyint(4) NOT NULL,
  `lock_zoom` tinyint(4) NOT NULL,
  `cover_board` tinyint(4) NOT NULL,
  `board_color` varchar(45) NOT NULL,
  `background_color` varchar(45) NOT NULL,
  `cover_color` varchar(45) NOT NULL,
  `image` longtext,
  `camerax` decimal(45,15) DEFAULT NULL,
  `cameray` decimal(45,15) DEFAULT NULL,
  `cameraz` decimal(45,15) DEFAULT NULL,
  PRIMARY KEY (`idboard`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
CREATE TABLE `experiment` (
  `experiment_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `board_tintrgb` varchar(50) DEFAULT NULL,
  `background_tintrgb` varchar(50) DEFAULT NULL,
  `grid_size` int(11) DEFAULT NULL,
  `show_background` int(11) NOT NULL DEFAULT '1',
  `show_labels` int(11) NOT NULL DEFAULT '1',
  `label_pos` int(11) DEFAULT NULL,
  `label_shade` int(11) DEFAULT NULL,
  `label_size` int(11) DEFAULT NULL,
  `preview_img` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`experiment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
CREATE TABLE `results` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`result_id`),
  KEY `id_idx` (`experiment_id`),
  KEY `admin_id_idx` (`admin_id`),
  CONSTRAINT `admin_id` FOREIGN KEY (`admin_id`) REFERENCES `users` (`userid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `experiment` FOREIGN KEY (`experiment_id`) REFERENCES `experiment` (`experiment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `sessions` (
  `sid` varchar(45) NOT NULL,
  `userid` int(11) NOT NULL,
  `timestamp` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `stimulus` (
  `stimulus_id` int(11) NOT NULL AUTO_INCREMENT,
  `stimset_id` int(11) NOT NULL,
  `label` varchar(45) NOT NULL,
  `label_r` int(11) DEFAULT NULL,
  `label_g` int(11) DEFAULT NULL,
  `label_b` int(11) DEFAULT NULL,
  `peg_r` int(11) DEFAULT NULL,
  `peg_g` int(11) DEFAULT NULL,
  `peg_b` int(11) DEFAULT NULL,
  PRIMARY KEY (`stimulus_id`),
  KEY `id_idx` (`stimset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
CREATE TABLE `stimulus_set` (
  `version` varchar(45) DEFAULT NULL,
  `stimset_id` int(11) NOT NULL AUTO_INCREMENT,
  `relative_size` int(11) DEFAULT NULL,
  `window_size` varchar(45) DEFAULT NULL,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`stimset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;
CREATE TABLE `users` (
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
