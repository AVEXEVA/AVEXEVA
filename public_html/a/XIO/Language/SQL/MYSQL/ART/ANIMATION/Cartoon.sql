CREATE TABLE Cartoon (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Cartoon_ID` PRIMARY KEY (`ID`)
);
CREATE TABLE Cartoon_Character (
  `Cartoon`   INT NOT NULL,
  `Character` INT NOT NULL,
  CONSTRAINT `FK_Cartoon_Character_Cartoon` FOREIGN KEY (`Cartoon`) REFERENCES `Cartoon`(`ID`),
  CONSTRAINT `FK_Cartoon_Character_Character` FOREIGN KEY (`Character`) REFERENCES `Character`(`ID`)
);
