CREATE TABLE Class (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(256),
  `Description` TEXT,
  `File` INT,
  CONSTRAINT `PK_Class_ID`    PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Class_File`  FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
