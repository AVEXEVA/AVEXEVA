CREATE TABLE Project (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(256),
  `Desription` TEXT,
  `Parent` INT,
  CONSTRAINT `PK_Project_ID`      PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Project_Parent`  FOREIGN KEY (`Parent`) REFERENCES `Parent`(`ID`)
);
