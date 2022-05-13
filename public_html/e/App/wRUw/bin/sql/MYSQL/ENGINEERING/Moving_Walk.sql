CREATE TABLE Moving_Walk (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  CONSTRAINT PK_Elevator_ID PRIMARY KEY (ID)
  CONSTRAINT FK_Moving_Walk_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
