CREATE TABLE Session (
  `ID`          INT NOT NULL,
  `User`        INT NOT NULL,
  `Token`       INT NOT NULL,
  `Time_Lapse`  INT NOT NULL,
  `Agent`       INT NOT NULL,
  `Refreshed`   DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `PK_Session_ID`          PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Session_User`        FOREIGN KEY (`User`)        REFERENCES `User`(`ID`),
  CONSTRAINT `FK_Session_Token`       FOREIGN KEY (`Token`)       REFERENCES `Token`(`ID`),
  CONSTRAINT `FK_Session_Time_Lapse`  FOREIGN KEY (`Time_Lapse`)  REFERENCES `Time_Lapse`(`ID`),
  CONSTRAINT `FK_Session_Agent`       FOREIGN KEY (`Agent`)       REFERENCES `Agent`(`ID`)
);
