CREATE TABLE Album (
  `ID`          INT NOT NULL,
  `Art`         INT NOT NULL,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT,
  CONSTRAINT `FK_Album_ID`    PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Album_Art`   FOREIGN KEY (`Art`)   REFERENCES `Art`(`ID`),
  CONSTRAINT `FK_Album_File`  FOREIGN KEY (`File`)  REFERENCES `File`(`ID`),
);
