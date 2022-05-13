CREATE TABLE Object (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `PK_Object_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Object_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
