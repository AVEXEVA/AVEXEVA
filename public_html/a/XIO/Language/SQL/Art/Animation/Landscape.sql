CREATE TABLE Landscape (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `PK_Landscape_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Landscape_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
