CREATE TABLE Connection (
  `ID`        INT NOT NULL AUTO_INCREMENT,
  `User`      INT NOT NULL,
  `Token`     VARCHAR(256) NOT NULL,
  `Created`   DATETIME,
  `Refreshed` DATETIME,
  CONSTRAINT `PK_Connection_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Connection_User` PRIMARY KEY (`User`)
);
