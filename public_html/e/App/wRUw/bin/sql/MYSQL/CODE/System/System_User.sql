CREATE TABLE System_User (
  `System`  INT NOT NULL,
  `User`    INT NOT NULL,
  CONSTRAINT `FK_System_User_System`  FOREIGN KEY (`System`)  REFERENCES `System`(`ID`),
  CONSTRAINT `FK_System_User_User`    FOREIGN KEY (`User`)    REFERENCES `User`(`ID`)
);
