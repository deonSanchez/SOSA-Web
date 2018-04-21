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

-- -----------------------------------------------------
-- Table `sosa`.`board`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`board` (
  `idboard` INT(11) NOT NULL AUTO_INCREMENT,
  `board_name` VARCHAR(45) NOT NULL,
  `lock_tilt` TINYINT(4) NOT NULL,
  `lock_rotate` TINYINT(4) NOT NULL,
  `lock_zoom` TINYINT(4) NOT NULL,
  `cover_board` TINYINT(4) NOT NULL,
  `board_color` VARCHAR(45) NOT NULL,
  `background_color` VARCHAR(45) NOT NULL,
  `cover_color` VARCHAR(45) NOT NULL,
  `image` LONGTEXT NULL DEFAULT NULL,
  `camerax` DECIMAL(45,15) NULL DEFAULT NULL,
  `cameray` DECIMAL(45,15) NULL DEFAULT NULL,
  `cameraz` DECIMAL(45,15) NULL DEFAULT NULL,
  PRIMARY KEY (`idboard`))
ENGINE = InnoDB
AUTO_INCREMENT = 52
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sosa`.`stimulus_set`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`stimulus_set` (
  `version` VARCHAR(45) NULL DEFAULT NULL,
  `stimset_id` INT(11) NOT NULL AUTO_INCREMENT,
  `relative_size` INT(11) NULL DEFAULT NULL,
  `window_size` VARCHAR(45) NULL DEFAULT NULL,
  `title` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`stimset_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`experiment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`experiment` (
  `experiment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `stimset_id` INT(11) NULL DEFAULT NULL,
  `idboard` INT(11) NULL DEFAULT NULL,
  `title` VARCHAR(50) NOT NULL,
  `show_background` INT(11) NOT NULL DEFAULT '1',
  `show_labels` INT(11) NOT NULL DEFAULT '1',
  `preview_img` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`experiment_id`),
  INDEX `set_idx` (`stimset_id` ASC),
  INDEX `board_idx` (`idboard` ASC),
  CONSTRAINT `board`
    FOREIGN KEY (`idboard`)
    REFERENCES `sosa`.`board` (`idboard`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `set`
    FOREIGN KEY (`stimset_id`)
    REFERENCES `sosa`.`stimulus_set` (`stimset_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`users` (
  `username` VARCHAR(45) NULL DEFAULT NULL,
  `password` VARCHAR(45) NULL DEFAULT NULL,
  `userid` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`userid`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`results`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`results` (
  `result_id` INT(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` INT(11) NOT NULL,
  `admin_id` INT(11) NOT NULL,
  PRIMARY KEY (`result_id`),
  INDEX `id_idx` (`experiment_id` ASC),
  INDEX `admin_id_idx` (`admin_id` ASC),
  CONSTRAINT `admin_id`
    FOREIGN KEY (`admin_id`)
    REFERENCES `sosa`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `experiment`
    FOREIGN KEY (`experiment_id`)
    REFERENCES `sosa`.`experiment` (`experiment_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`sessions` (
  `sid` VARCHAR(45) NOT NULL,
  `userid` INT(11) NOT NULL,
  `timestamp` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`sid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`stimulus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`stimulus` (
  `stimulus_id` INT(11) NOT NULL AUTO_INCREMENT,
  `stimset_id` INT(11) NOT NULL,
  `label` VARCHAR(45) NOT NULL,
  `label_r` INT(11) NULL DEFAULT NULL,
  `label_g` INT(11) NULL DEFAULT NULL,
  `label_b` INT(11) NULL DEFAULT NULL,
  `peg_r` INT(11) NULL DEFAULT NULL,
  `peg_g` INT(11) NULL DEFAULT NULL,
  `peg_b` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`stimulus_id`),
  INDEX `id_idx` (`stimset_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 54
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
