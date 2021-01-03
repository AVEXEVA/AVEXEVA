CREATE TABLE Elevator (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  Location INT NOT NULL,
  CONSTRAINT PK_Elevator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Elevator_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
