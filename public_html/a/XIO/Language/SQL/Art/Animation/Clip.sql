CREATE TABLE Clip (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `PK_Clip_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Clip_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
