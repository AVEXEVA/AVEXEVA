CREATE TABLE Rolodex_Address (
  `Rolodex` INT NOT NULL,
  `Address` INT NOT NULL,
  `Name`    VARCHAR(256),
  CONSTRAINT `FK_Rolodex_Address_Rolodex` FOREIGN KEY (`Rolodex`) REFERENCES `Rolodex`(`ID`),
  CONSTRAINT `FK_Rolodex_Address_Address` FOREIGN KEY (`Address`) REFERENCES `Address`(`ID`)
);
