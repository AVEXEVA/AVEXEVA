CREATE TABLE Project_Entity (
  `Project` INT NOT NULL,
  `Entity` INT NOT NULL,
  CONSTRAINT `FK_Project_Entity_Project`  FOREIGN KEY (`Project`) REFERENCES `Project`(`ID`),
  CONSTRAINT `FK_Project_Entity_Entity`   FOREIGN KEY (`Entity`)  REFERENCES `Entity`(`ID`)
);
