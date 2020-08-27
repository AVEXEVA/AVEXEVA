CREATE TABLE `avexeva_ROOT`.`Agent`
  (
    `Id` INT NOT NULL AUTO_INCREMENT,
    `Name` VARCHAR(32) NOT NULL,
    `Version` VARCHAR(64) NOT NULL,
    `Namespace` VARCHAR(128) NOT NULL,
    PRIMARY KEY (`Id`)
  ) ENGINE = MyISAM;
