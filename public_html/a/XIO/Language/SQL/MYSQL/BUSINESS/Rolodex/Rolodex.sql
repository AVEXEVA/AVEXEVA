CREATE TABLE Rolodex (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Entity` INT NOT NULL,
  CONSTRAINT `PK_Rolodex_ID` PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Rolodex_Entity` FOREIGN KEY (`Entity`) REFERENCES `Entity`(`ID`),
  CONSTRAINT `FK_Rolodex_Home` FOREIGN KEY (`Home`) REFERENCES `Address`(`ID`),
  CONSTRAINT `FK_Roloex_Work` FOREIGN KEY (`Work`) REFERENCES `Address`(`ID`),
  CONSTRAINT `FK_Rolodex_Office` FOREIGN KEY (`Office`) REFERENCES `Phone`(`ID`),
  CONSTRAINT `FK_Rolodex_Cell` FOREIGN KEY (`Cell`) REFERENCES `Phone`(`ID`)
);
