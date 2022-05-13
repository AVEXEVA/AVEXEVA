CREATE TABLE `SMTP_Authentication` (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_SMTP_Authentication_ID` PRIMARY KEY (`ID`)
);
CREATE TABLE SMTP (
  `ID`              INT NOT NULL AUTO_INCREMENT,
  `Name`            VARCHAR(256),
  `Server`          INT,
  `Authentication`  INT,
  CONSTRAINT `PK_SMTP_ID`             PRIMARY KEY (`ID`),
  CONSTRAINT `FK_SMTP_Server`         FOREIGN KEY (`Server`) REFERENCES `Server`(`ID`),
  CONSTRAINT `FK_SMTP_Authentication` FOREIGN KEY (`Authentication`) REFERENCES `SMTP_Authentication`(`ID`)
);

CREATE TABLE Mail (
  `ID`      INT NOT NULL AUTO_INCREMENT,
  `Sender`  INT NOT NULL,
  `Subject` VARCHAR(256),
  `Text`    TEXT,
  CONSTRAINT `PK_Email_ID`      PRIMARY KEY (`ID`)
  CONSTRAINT `FK_Email_Sender`  FOREIGN KEY (`Sender`) REFERENCES `User`(`ID`)
);
