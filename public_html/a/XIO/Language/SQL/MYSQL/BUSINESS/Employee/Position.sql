CREATE TABLE Position (
  ID INT NOT NULL AUTO_INCREMENT,
  Employee INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  Supervisor INT,
  CONSTRAINT PK_Position_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Position_Employee FOREIGN KEY (Employee) REFERENCES Employee(ID),
  CONSTRAINT FK_Position_Supervisor FOREIGN KEY (Supervisor) REFERENCES Position(ID)
);
