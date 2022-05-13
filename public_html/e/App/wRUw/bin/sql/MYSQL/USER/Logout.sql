CREATE TABLE `Logout` (
  `ID`          INT NOT NULL,
  `User`        INT NOT NULL,
  `IP`          VARCHAR(64),
  `Agent`  INT,
  Timestamp DATETIME CURRNET_TIMESTAMP,
  CONSTRAINT `PK_Logout_ID`    PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Logout_User`  FOREIGN KEY (`User`)   REFERENCES `User`(`ID`)
  CONSTRAINT `FK_Logout_Agent` FOREIGN KEY (`Agent`)  REFERENCES `Agent`(`ID`)
) ENGINE = MyISAM;
