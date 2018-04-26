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
AUTO_INCREMENT = 83
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `sosa`.`experiment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`experiment` (
  `experiment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `access_key` VARCHAR(45) NOT NULL,
  `admin` VARCHAR(255) NULL DEFAULT NULL,
  `stimset_id` INT(11) NULL DEFAULT NULL,
  `idboard` INT(11) NULL DEFAULT NULL,
  `title` VARCHAR(50) NOT NULL,
  `show_background` INT(11) NOT NULL DEFAULT '1',
  `show_labels` INT(11) NOT NULL DEFAULT '1',
  `preview_img` VARCHAR(45) NULL DEFAULT NULL,
  `grid` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`experiment_id`),
  INDEX `set_idx` (`stimset_id` ASC),
  INDEX `board_idx` (`idboard` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 100
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`results`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`results` (
  `result_id` INT(11) NOT NULL AUTO_INCREMENT,
  `experiment_id` INT(11) NOT NULL,
  `identifier` VARCHAR(255) NULL DEFAULT 'N/A',
  `admin` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`result_id`),
  INDEX `id_idx` (`experiment_id` ASC),
  CONSTRAINT `parent_experiment`
    FOREIGN KEY (`experiment_id`)
    REFERENCES `sosa`.`experiment` (`experiment_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 54
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`result_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`result_log` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `results_id` INT(11) NOT NULL,
  `stimulus_id` INT(11) NOT NULL,
  `stimulus_name` VARCHAR(25) NOT NULL,
  `timestamp` DOUBLE NOT NULL,
  `action` VARCHAR(25) NOT NULL,
  `from_x` DOUBLE NULL DEFAULT NULL,
  `from_y` DOUBLE NULL DEFAULT NULL,
  `to_x` DOUBLE NOT NULL,
  `to_y` DOUBLE NOT NULL,
  PRIMARY KEY (`log_id`),
  INDEX `parent_result_idx` (`results_id` ASC),
  INDEX `parent_stimulus_idx` (`stimulus_id` ASC),
  CONSTRAINT `parent_result`
    FOREIGN KEY (`results_id`)
    REFERENCES `sosa`.`results` (`result_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 147
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
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`sessions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`sessions` (
  `sid` VARCHAR(45) NOT NULL,
  `userid` INT(11) NOT NULL,
  `timestamp` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`sid`),
  INDEX `parent_user_idx` (`userid` ASC),
  CONSTRAINT `parent_user`
    FOREIGN KEY (`userid`)
    REFERENCES `sosa`.`users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`stimulus_set`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`stimulus_set` (
  `version` VARCHAR(45) NULL DEFAULT NULL,
  `stimset_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`stimset_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 40
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
  INDEX `id_idx` (`stimset_id` ASC),
  CONSTRAINT `parent_set`
    FOREIGN KEY (`stimset_id`)
    REFERENCES `sosa`.`stimulus_set` (`stimset_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 86
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
