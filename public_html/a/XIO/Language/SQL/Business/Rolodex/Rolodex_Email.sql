CREATE TABLE Rolodex_Email (
  `Rolodex` INT NOT NULL,
  `Email`   INT NOT NULL,
  `Name`    VARCHAR(256),
  CONSTRAINT `FK_Rolodex_Email_Rolodex` FOREIGN KEY (`Rolodex`) REFERENCES `Rolodex`(`ID`),
  CONSTRAINT `FK_Rolodex_Email_Email`   FOREIGN KEY (`Email`)   REFERENCES `Email`(`ID`)
);
