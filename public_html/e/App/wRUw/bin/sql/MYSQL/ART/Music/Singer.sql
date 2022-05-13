CREATE TABLE Singer (
  `ID`          INT NOT NULL,
  `User`        INT,
  `Person`      INT,
  `Description` TEXT,
  CONSTRAINT `FK_Singer_ID`    PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Singer_Album` FOREIGN KEY (`User`)   REFERENCES `User`(`ID`),
  CONSTRAINT `FK_Singer_File`  FOREIGN KEY (`Person`) REFERENCES `Person`(`ID`),
);
