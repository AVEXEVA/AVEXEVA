CREATE TABLE `avexeva_ROOT`.`User`
  (
    `Id` INT NOT NULL AUTO_INCREMENT,
    `Name` VARCHAR(32),
    `Password` VARCHAR(256) NOT NULL,
    `Email` VARCHAR(256) NOT NULL,
    PRIMARY KEY (`Id`)
  ) ENGINE = MyISAM;
