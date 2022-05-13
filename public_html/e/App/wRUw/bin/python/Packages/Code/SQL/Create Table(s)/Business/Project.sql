CREATE TABLE Project (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Desription TEXT,
  Parent INT,
  CONSTRAINT PK_Project_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Project_Parent FOREIGN KEY (Parent) REFERENCES Parent(ID)
);
CREATE TABLE Project_Entity (
  Project INT NOT NULL,
  Entity INT NOT NULL,
  CONSTRAINT FK_Project_Entity_Project FOREIGN KEY (Project) REFERENCES Project(ID),
  CONSTRAINT FK_Project_Entity_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Project_Task (
  Project INT NOT NULL,
  Task INT NOT NULL,
  Entity INT NOT NULL,
  CONSTRAINT FK_Project_Task_Project FOREIGN KEY (Project) REFERENCES Project(ID),
  CONSTRAINT FK_Project_Task_Task FOREIGN KEY (Task) REFERENCES Task(ID),
  CONSTRAINT FK_Project_Task_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
