CREATE TABLE Entity (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Entity_ID` PRIMARY KEY (`ID`)
);
