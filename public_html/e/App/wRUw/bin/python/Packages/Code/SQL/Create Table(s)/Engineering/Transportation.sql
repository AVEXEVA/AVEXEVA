CREATE TABLE Transportation (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Transportation_ID PRIMARY KEY (ID)
);
CREATE TABLE Elevator (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  Location INT NOT NULL,
  CONSTRAINT PK_Elevator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Elevator_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
CREATE TABLE Elevator_Type (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Elevator_Type_ID PRIMARY KEY (ID)
);
INSERT INTO Elevator_Type(Name) VALUES('Passenger'), ('Freight');
CREATE TABLE Dumbwaiter (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  CONSTRAINT PK_Dumbwaiter_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Dumbwaiter_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
CREATE TABLE Escalator (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  CONSTRAINT PK_Escalator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Escalator_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
CREATE TABLE Moving_Walk (
  ID INT NOT NULL AUTO_INCREMENT,
  Transportation INT NOT NULL,
  CONSTRAINT PK_Moving_Walk_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Moving_Walk_Transportation FOREIGN KEY (Transportation) REFERENCES Transportation(ID)
);
CREATE TABLE  Elevator_Access (
  Elevator      INT NOT NULL AUTO_INCREMENT,
  Location      INT NOT NULL,
  Floor         INT NOT NULL,
  Bank          INT NOT NULL,
  `Accessible`  BIT DEFAULT NULL,
  Configured    BIT DEFAULT NULL,
  Obstructed    BIT DEFAULT NULL,
  CONSTRAINT FK_Elevator_Location_Elevator      FOREIGN KEY (Elevator)      REFERENCES Elevator(ID),
  CONSTRAINT FK_Elevator_Location_Location  FOREIGN KEY (Location)  REFERENCES Location(ID),
  CONSTRAINT FK_Elevator_Location_Bank      FOREIGN KEY (Bank)      REFERENCES Bank(ID),
  CONSTRAINT FK_Elevator_Location_Floor     FOREIGN KEY (Floor)     REFERENCES Floor(ID)
);
