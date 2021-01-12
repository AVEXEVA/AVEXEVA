CREATE TABLE Rolodex_Phone (
  `Rolodex` INT NOT NULL,
  `Phone`   INT NOT NULL,
  `Name`    VARCHAR(256),
  CONSTRAINT `FK_Rolodex_Phone_Rolodex` FOREIGN KEY (`Rolodex`) REFERENCES `Rolodex`(`ID`),
  CONSTRAINT `FK_Rolodex_Phone_Phone`   FOREIGN KEY (`Phone`)   REFERENCES `Phone`(`ID`),
);
