CREATE TABLE Dumbwaiter (
  ID INT NOT NULL AUTO_INCREMENT,
  Fixed_Transportation INT NOT NULL,
  CONSTRAINT PK_Dumbwaiter_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Dumbwaiter_Fixed_Transportation FOREIGN KEY (Fixed_Transportation) REFERENCES Fixed_Transportation(ID)
);
