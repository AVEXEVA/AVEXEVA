CREATE TABLE Escalator (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  CONSTRAINT PK_Escalator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Escalator_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
