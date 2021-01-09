CREATE TABLE Elevator (
  ID INT NOT NULL AUTO_INCREMENT,
  Fixed_Transportation INT NOT NULL,
  Location INT NOT NULL,
  CONSTRAINT PK_Elevator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Elevator_Fixed_Transportation FOREIGN KEY (Fixed_Transportation) REFERENCES Fixed_Transportation(ID)
);
CREATE TABLE Elevator_Type (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Elevator_Type_ID PRIMARY KEY (ID)
);
INSERT INTO Elevator_Type(Name) VALUES('Passenger'), ('Freight');
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
