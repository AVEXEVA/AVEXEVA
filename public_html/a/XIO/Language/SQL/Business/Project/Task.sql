CREATE TABLE Project_Task (
  `Project` INT NOT NULL,
  `Task` INT NOT NULL,
  `Entity` INT NOT NULL,
  CONSTRAINT `FK_Project_Task_Project`  FOREIGN KEY (`Project`) REFERENCES `Project`(ID),
  CONSTRAINT `FK_Project_Task_Task`     FOREIGN KEY (`Task`)    REFERENCES `Task`(ID),
  CONSTRAINT `FK_Project_Task_Entity`   FOREIGN KEY (`Entity`)  REFERENCES Entity(ID)
);
