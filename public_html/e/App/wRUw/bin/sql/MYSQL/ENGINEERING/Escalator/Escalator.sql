CREATE TABLE Escalator (
  ID INT NOT NULL AUTO_INCREMENT,
  Fixed_Transportation INT NOT NULL,
  CONSTRAINT PK_Escalator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Escalator_Fixed_Transportation FOREIGN KEY (Fixed_Transportation) REFERENCES Fixed_Transportation(ID)
);
