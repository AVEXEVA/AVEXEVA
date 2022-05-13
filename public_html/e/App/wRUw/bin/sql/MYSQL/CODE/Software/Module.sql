CREATE TABLE Module (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256) NOT NULL,
  `Description` TEXT,
  CONSTRAINT `PK_Module_ID` PRIMARY KEY (`ID`)
);
