CREATE TABLE Song (
  `ID`          INT NOT NULL,
  `Album`       INT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `FK_Song_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Song_Album` FOREIGN KEY (`Album`) REFERENCES `Album`(`ID`),
  CONSTRAINT `FK_Song_File` FOREIGN KEY (`File`) REFERENCES `File`(`ID`),
);
