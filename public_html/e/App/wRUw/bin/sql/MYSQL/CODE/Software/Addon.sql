CREATE TABLE Addon (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Addon_ID` PRIMARY KEY (`ID`)
);
