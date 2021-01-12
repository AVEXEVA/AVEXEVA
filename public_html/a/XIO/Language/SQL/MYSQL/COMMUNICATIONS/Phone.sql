CREATE TABLE Phone (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Number`      VARCHAR(256),
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Phone_ID` PRIMARY KEY (`ID`) REFERENCES `Phone`(`ID`)
);
