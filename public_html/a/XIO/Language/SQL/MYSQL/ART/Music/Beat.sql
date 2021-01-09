CREATE TABLE Beat (
  `ID`          INT NOT NULL,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `FK_Beat_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Beat_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`),
);
