CREATE TABLE Employee (
  ID                      INT NOT NULL AUTO_INCREMENT,
  Person                  INT NOT NULL,
  Social_Security_Number  VARCHAR(15),
  Hired                   DATETIME,
  Reviewed                DATETIME,
  Fired                   DATETIME,
  CONSTRAINT PK_Employee_ID     PRIMARY KEY (ID),
  CONSTRAINT FK_Employee_Person REFERENCES Person(ID)
);
CREATE TABLE Employee_Position (
  Employee INT NOT NULL,
  Position INT NOT NULL,
  CONSTRAINT FK_Employee_Position_Employee FOREIGN KEY (Employee) REFERENCES Employee(ID),
  CONSTRAINT FK_Employee_Position_Position FOREIGN KEY (Position) REFERENCES Position(ID)
);
