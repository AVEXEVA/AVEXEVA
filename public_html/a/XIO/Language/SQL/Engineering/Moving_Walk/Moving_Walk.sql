CREATE TABLE Moving_Walk (
  ID INT NOT NULL AUTO_INCREMENT,
  Fixed_Transportation INT NOT NULL,
  CONSTRAINT PK_Moving_Walk_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Moving_Walk_Fixed_Transportation FOREIGN KEY (Fixed_Transportation) REFERENCES Fixed_Transportation(ID)
);
