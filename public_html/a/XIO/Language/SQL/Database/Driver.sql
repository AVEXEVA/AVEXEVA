CREATE TABLE Driver (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Driver_ID PRIMARY KEY (ID)
);
CREATE TABLE Driver_Function (
  Driver INT NOT NULL,
  Function INT NOT NULL,
  CONSTRAINT FK_Driver_Function_Driver FOREIGN KEY (Driver) REFERENCES Driver(ID),
  CONSTRAINT FK_Driver_function_Function FOREIGN KEY (Function) REFERENCES Function(ID)
);
