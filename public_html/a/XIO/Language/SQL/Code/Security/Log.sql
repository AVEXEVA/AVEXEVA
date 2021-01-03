CREATE TABLE Log (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `User`        INT NOT NULL,
  `Connection`  INT NOT NULL,
  `Script`      VARCHAR(MAX)
  CONSTRAINT `PK_Log_ID`          PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Log_User`        FOREIGN KEY (`User`)        REFERENCES `User`(`ID`),
  CONSTRAINT `FK_Log_Connection`  FOREIGN KEY (`Connection`)  REFERENCES `Connection`(`ID`)
);
