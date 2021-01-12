CREATE TABLE User_Person (
  `User` INT NOT NULL,
  `Person` INT NOT NULL,
  CONSTRAINT `FK_User_Person_User`    FOREIGN KEY (`User`)    REFERENCES `User`(`ID`),
  CONSTRAINT `FK_User_Person_Person`  FOREIGN KEY (`Person`)  REFERENCES `Person`(`ID`)
);
