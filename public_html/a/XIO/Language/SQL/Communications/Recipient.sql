CREATE TABLE Recipient (
  `Mail`  INT NOT NULL,
  `User`  INT NOT NULL,
  CONSTRAINT `FK_Recipient_Mail` FOREIGN KEY (`Mail`) REFERENCES `Mail`(`ID`),
  CONSTRAINT `FK_Recipient_User` FOREIGN KEY (`User`) REFERENCES `User`(`ID`)
);
