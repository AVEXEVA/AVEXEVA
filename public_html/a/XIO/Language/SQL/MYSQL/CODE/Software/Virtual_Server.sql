CREATE TABLE `Virtual_Server` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Server` INT,
  `Backup` INT,
  CONSTRAINT `PK_Virtual_Server_ID`     PRIMARY KEY (`ID`),
  CONSTRAINT `PK_Virtual_Server_Server` FOREIGN KEY (`Server`) REFERENCES `Server`(`ID`),
  CONSTRAINT `PK_Virtual_Server_Backup` FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
