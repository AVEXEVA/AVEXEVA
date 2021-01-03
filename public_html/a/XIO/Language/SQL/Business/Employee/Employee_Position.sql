CREATE TABLE Employee_Position (
  `Employee` INT NOT NULL,
  `Position` INT NOT NULL,
  CONSTRAINT `FK_Employee_Position_Employee` FOREIGN KEY (`Employee`) REFERENCES `Employee`(`ID`),
  CONSTRAINT `FK_Employee_Position_Position` FOREIGN KEY (`Position`) REFERENCES `Position`(`ID`)
);
