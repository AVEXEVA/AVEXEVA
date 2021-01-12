CREATE TABLE Patch (
  `ID`      INT NOT NULL AUTO_INCREMENT,
  `Version` INT,
  CONSTRAINT `PK_Patch_ID`      PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Patch_Version` FOREIGN KEY `Version`(`ID`)
);
