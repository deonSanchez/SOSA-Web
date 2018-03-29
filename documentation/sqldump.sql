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
-- Table `sosa`.`experiment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`experiment` (
  `experiment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `board_tintrgb` VARCHAR(50) NULL DEFAULT NULL,
  `background_tintrgb` VARCHAR(50) NULL DEFAULT NULL,
  `grid_size` INT(11) NULL DEFAULT NULL,
  `show_background` INT(11) NOT NULL DEFAULT '1',
  `show_labels` INT(11) NOT NULL DEFAULT '1',
  `label_pos` INT(11) NULL DEFAULT NULL,
  `label_shade` INT(11) NULL DEFAULT NULL,
  `label_size` INT(11) NULL DEFAULT NULL,
  `preview_img` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`experiment_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`user` (
  `username` INT(11) NULL DEFAULT NULL,
  `password` VARCHAR(45) NULL DEFAULT NULL,
  `uid` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`uid`))
ENGINE = InnoDB
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
    REFERENCES `sosa`.`user` (`uid`)
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
-- Table `sosa`.`stimulus_set`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`stimulus_set` (
  `name` VARCHAR(45) NULL DEFAULT NULL,
  `version` VARCHAR(45) NULL DEFAULT NULL,
  `stimset_id` INT(11) NOT NULL AUTO_INCREMENT,
  `relative_size` INT(11) NULL DEFAULT NULL,
  `window_size` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`stimset_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `sosa`.`stimulus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sosa`.`stimulus` (
  `stimulus_id` INT(11) NOT NULL AUTO_INCREMENT,
  `stimset_id` INT(11) NOT NULL,
  `label` VARCHAR(45) NULL DEFAULT NULL,
  `label_color` VARCHAR(45) NULL DEFAULT NULL,
  `peg_color` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`stimulus_id`),
  INDEX `id_idx` (`stimset_id` ASC),
  CONSTRAINT `id`
    FOREIGN KEY (`stimset_id`)
    REFERENCES `sosa`.`stimulus_set` (`stimset_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
