CREATE TABLE `Login` (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `User`        INT NOT NULL,
  `IP`          VARCHAR(64),
  `Agent`  INT,
  `Timestamp`   DATETIME CURRENT_TIMESTAMP,
  CONSTRAINT `PK_Login_ID`      PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Login_User`    FOREIGN KEY (`User`)    REFERENCES `User`(`ID`),
  CONSTRAINT `FK_Login_IP`      FOREIGN KEY (`IP`)      REFERENCES `IP`(`ID`),
  CONSTRAINT `FK_Login_Agent`   FOREIGN KEY (`Agent`)   REFERENCES `Agent`(`ID`)
) ENGINE = MyISAM;
