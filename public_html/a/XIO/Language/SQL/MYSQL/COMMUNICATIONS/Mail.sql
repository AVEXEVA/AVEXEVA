CREATE TABLE Mail (
  `ID`      INT NOT NULL AUTO_INCREMENT,
  `Sender`  INT NOT NULL,
  `Subject` VARCHAR(256),
  `Text`    TEXT,
  CONSTRAINT `PK_Email_ID`      PRIMARY KEY (`ID`)
  CONSTRAINT `FK_Email_Sender`  FOREIGN KEY (`Sender`) REFERENCES `User`(`ID`)
);
