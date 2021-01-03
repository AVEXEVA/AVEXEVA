CREATE TABLE `SMTP_Authentication` (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_SMTP_Authentication_ID` PRIMARY KEY (`ID`)
);
