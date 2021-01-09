CREATE TABLE Variable (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Variable_ID` PRIMARY KEY (`ID`)
);
