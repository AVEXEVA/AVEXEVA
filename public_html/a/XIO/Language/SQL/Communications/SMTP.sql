CREATE TABLE SMTP (
  `ID`              INT NOT NULL AUTO_INCREMENT,
  `Name`            VARCHAR(256),
  `Server`          INT,
  `Authentication`  INT,
  CONSTRAINT `PK_SMTP_ID`             PRIMARY KEY (`ID`),
  CONSTRAINT `FK_SMTP_Server`         FOREIGN KEY (`Server`) REFERENCES `Server`(`ID`),
  CONSTRAINT `FK_SMTP_Authentication` FOREIGN KEY (`Authentication`) REFERENCES `SMTP_Authentication`(`ID`)
);
