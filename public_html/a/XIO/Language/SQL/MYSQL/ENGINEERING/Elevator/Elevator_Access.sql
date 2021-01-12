CREATE TABLE  Elevator_Access (
  `Elevator`      INT NOT NULL AUTO_INCREMENT,
  `Location`      INT NOT NULL,
  `Floor`         INT NOT NULL,
  `Bank`          INT NOT NULL,
  `Accessible`    BIT DEFAULT NULL,
  `Configured`    BIT DEFAULT NULL,
  `Obstructed`    BIT DEFAULT NULL,
  CONSTRAINT `FK_Elevator_Location_Elevator`  FOREIGN KEY (`Elevator`)  REFERENCES `Elevator`(`ID`),
  CONSTRAINT `FK_Elevator_Location_Location`  FOREIGN KEY (`Location`)  REFERENCES `Location`(`ID`),
  CONSTRAINT `FK_Elevator_Location_Bank`      FOREIGN KEY (`Bank`)      REFERENCES `Bank`(`ID`),
  CONSTRAINT `FK_Elevator_Location_Floor`     FOREIGN KEY (`Floor`)     REFERENCES `Floor`(`ID`)
);
