SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `Simian` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `Simian`;

-- -----------------------------------------------------
-- Table `Simian`.`AssetData`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`AssetData` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`AssetData` (
  `ID` CHAR(36) NOT NULL ,
  `Data` MEDIUMBLOB NOT NULL ,
  `ContentType` VARCHAR(50) NOT NULL DEFAULT 'application/octet-stream' ,
  `CreatorID` CHAR(36) NOT NULL ,
  `SHA256` CHAR(64) NOT NULL ,
  `CreationDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `LastAccessed` TIMESTAMP NULL ,
  `Public` TINYINT(1) NOT NULL DEFAULT TRUE ,
  `Temporary` TINYINT(1) NOT NULL DEFAULT FALSE ,
  PRIMARY KEY (`ID`) ,
  INDEX `last_accessed` (`LastAccessed` ASC) ,
  INDEX `temporary` (`Temporary` ASC) ,
  INDEX `sha256` (`SHA256` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Simian`.`Capabilities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`Capabilities` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`Capabilities` (
  `ID` CHAR(36) NOT NULL ,
  `OwnerID` CHAR(36) NOT NULL ,
  `Resource` VARCHAR(255) NOT NULL ,
  `ExpirationDate` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`ID`) ,
  UNIQUE INDEX `user_resource` (`OwnerID` ASC, `Resource` ASC) ,
  INDEX `user_id` (`OwnerID` ASC) ,
  INDEX `expiration_date` (`ExpirationDate` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Simian`.`Users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`Users` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`Users` (
  `ID` CHAR(36) NOT NULL ,
  `Name` VARCHAR(60) NOT NULL ,
  `Email` VARCHAR(60) NOT NULL ,
  PRIMARY KEY (`ID`) ,
  UNIQUE INDEX `name` (`Name` ASC) ,
  UNIQUE INDEX `email` (`Email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Simian`.`Identities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`Identities` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`Identities` (
  `Identifier` VARCHAR(255) NOT NULL ,
  `Type` VARCHAR(50) NOT NULL ,
  `Credential` VARCHAR(255) NULL ,
  `UserID` CHAR(36) NOT NULL ,
  `Enabled` TINYINT(1) NOT NULL DEFAULT TRUE ,
  UNIQUE INDEX `identifier_type` (`Identifier` ASC, `Type` ASC) ,
  INDEX `fk_id` (`UserID` ASC) ,
  CONSTRAINT `fk_id`
    FOREIGN KEY (`UserID` )
    REFERENCES `Simian`.`Users` (`ID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Simian`.`Inventory`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`Inventory` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`Inventory` (
  `ID` CHAR(36) NOT NULL ,
  `Name` VARCHAR(255) NOT NULL ,
  `ParentID` CHAR(36) NOT NULL ,
  `OwnerID` CHAR(36) NOT NULL ,
  `CreatorID` CHAR(36) NOT NULL ,
  `AssetID` CHAR(36) NULL ,
  `Description` VARCHAR(255) NULL ,
  `ContentType` VARCHAR(50) NULL ,
  `Version` INT UNSIGNED NOT NULL DEFAULT 1 ,
  `CreationDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `Type` ENUM('Folder','Item') NOT NULL ,
  `LeftNode` INT NOT NULL ,
  `RightNode` INT NOT NULL ,
  `ExtraData` TEXT NULL ,
  PRIMARY KEY (`ID`) ,
  INDEX `parent` (`ParentID` ASC) ,
  INDEX `owner` (`OwnerID` ASC) ,
  INDEX `asset` (`AssetID` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Simian`.`Scenes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`Scenes` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`Scenes` (
  `ID` CHAR(36) NOT NULL ,
  `Name` VARCHAR(50) NOT NULL ,
  `MinX` DOUBLE NOT NULL ,
  `MinY` DOUBLE NOT NULL ,
  `MinZ` DOUBLE NOT NULL ,
  `MaxX` DOUBLE NOT NULL ,
  `MaxY` DOUBLE NOT NULL ,
  `MaxZ` DOUBLE NOT NULL ,
  `Address` VARCHAR(255) NOT NULL ,
  `Enabled` TINYINT(1) NOT NULL ,
  `XYPlane` POLYGON NOT NULL ,
  `ExtraData` TEXT NULL ,
  PRIMARY KEY (`ID`) ,
  UNIQUE INDEX `name` (`Name` ASC) ,
  SPATIAL INDEX `xyplane` (`XYPlane` ASC) )
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `Simian`.`Sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`Sessions` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`Sessions` (
  `SessionID` CHAR(36) NOT NULL ,
  `UserID` CHAR(36) NOT NULL ,
  `SecureSessionID` CHAR(36) NOT NULL ,
  `SceneID` CHAR(36) NOT NULL ,
  `ScenePosition` VARCHAR(45) NULL ,
  `SceneLookAt` VARCHAR(45) NULL ,
  `ExtraData` TEXT NULL ,
  INDEX `scene` (`SceneID` ASC) ,
  INDEX `fk_user` (`UserID` ASC) ,
  PRIMARY KEY (`SessionID`) ,
  UNIQUE INDEX `user` (`UserID` ASC) ,
  CONSTRAINT `fk_user`
    FOREIGN KEY (`UserID` )
    REFERENCES `Simian`.`Users` (`ID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Simian`.`UserData`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Simian`.`UserData` ;

CREATE  TABLE IF NOT EXISTS `Simian`.`UserData` (
  `ID` CHAR(36) NOT NULL ,
  `Key` VARCHAR(50) NOT NULL ,
  `Value` MEDIUMTEXT NOT NULL ,
  UNIQUE INDEX `id_key` (`ID` ASC, `Key` ASC) ,
  INDEX `id` (`ID` ASC) ,
  CONSTRAINT `id`
    FOREIGN KEY (`ID` )
    REFERENCES `Simian`.`Users` (`ID` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
