CREATE TABLE Scene (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `PK_Scene_ID` PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Scene_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
