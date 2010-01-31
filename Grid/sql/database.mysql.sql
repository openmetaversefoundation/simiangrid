SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `AssetData`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `AssetData` ;

CREATE  TABLE IF NOT EXISTS `AssetData` (
  `ID` CHAR(36) NOT NULL ,
  `Data` MEDIUMBLOB NOT NULL ,
  `ContentType` VARCHAR(50) NOT NULL DEFAULT 'application/octet-stream' ,
  `CreationDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `LastAccessed` TIMESTAMP NULL ,
  `CreatorID` CHAR(36) NOT NULL ,
  `Public` TINYINT(1) NOT NULL DEFAULT TRUE ,
  PRIMARY KEY (`ID`) ,
  INDEX `last_accessed` (`LastAccessed` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Capabilities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Capabilities` ;

CREATE  TABLE IF NOT EXISTS `Capabilities` (
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
-- Table `Identities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Identities` ;

CREATE  TABLE IF NOT EXISTS `Identities` (
  `Identifier` VARCHAR(255) NOT NULL ,
  `Type` VARCHAR(50) NOT NULL ,
  `Credential` VARCHAR(255) NULL ,
  `UserID` CHAR(36) NOT NULL ,
  `Enabled` TINYINT(1) NOT NULL DEFAULT TRUE ,
  UNIQUE INDEX `identifier_type` (`Identifier` ASC, `Type` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Inventory`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Inventory` ;

CREATE  TABLE IF NOT EXISTS `Inventory` (
  `ID` CHAR(36) NOT NULL ,
  `Name` VARCHAR(255) NOT NULL ,
  `ParentID` CHAR(36) NOT NULL ,
  `OwnerID` CHAR(36) NOT NULL ,
  `AssetID` CHAR(36) NULL ,
  `Description` VARCHAR(255) NULL ,
  `PreferredContentType` VARCHAR(50) NULL ,
  `Version` INT UNSIGNED NOT NULL DEFAULT 1 ,
  `CreationDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `Type` ENUM('Folder','Item') NOT NULL ,
  `LeftNode` INT NOT NULL ,
  `RightNode` INT NOT NULL ,
  `ExtraData` TEXT NULL ,
  PRIMARY KEY (`ID`) ,
  INDEX `parent_owner` (`ParentID` ASC, `OwnerID` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Scenes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Scenes` ;

CREATE  TABLE IF NOT EXISTS `Scenes` (
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
-- Table `Users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Users` ;

CREATE  TABLE IF NOT EXISTS `Users` (
  `ID` CHAR(36) NOT NULL ,
  `Key` VARCHAR(50) NOT NULL ,
  `Value` MEDIUMTEXT NOT NULL ,
  UNIQUE INDEX `id_key` (`ID` ASC, `Key` ASC) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
