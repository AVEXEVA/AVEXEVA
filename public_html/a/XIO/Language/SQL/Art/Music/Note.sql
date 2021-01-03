CREATE TABLE Note (
  `ID`          INT NOT NULL,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `FK_Note_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Note_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`),
);
