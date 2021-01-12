CREATE TABLE Contractor (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Contractor_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Contractor_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Consultant (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Consultant PRIMARY KEY (ID),
  CONSTRAINT FK_Consultant_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
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
CREATE TABLE Position (
  ID INT NOT NULL,
  Name VARCHAR(256),
  Description text,
  CONSTRAINT PK_Position_ID PRIMARY KEY (ID)
);
