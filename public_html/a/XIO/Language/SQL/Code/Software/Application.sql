CREATE TABLE Application (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Application_ID` PRIMARY KEY (`ID`)
);
