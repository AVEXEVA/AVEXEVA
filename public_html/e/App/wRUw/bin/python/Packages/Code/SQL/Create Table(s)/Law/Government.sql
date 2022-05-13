CREATE TABLE Government (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Address INT,
  Parent INT,
  CONSTRAINT PK_Government_ID PRIMARY KEY (ID)
  CONSTRAINT FK_Government_Address FOREIGN KEY (Address) REFERENCES Address(ID)
  CONSTRAINT FK_Government_Parent FOREIGN KEY (Parent) REFERENCES Government(ID)
);
CREATE TABLE Government_Official (
  ID INT NOT NULL AUTO_INCREMENT,
  Person INT NOT NULL,
  Position INT NOT NULL,
  CONSTRAINT PK_Government_Official_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Government_Offical_Person FOREIGN KEY (Person) REFERENCES Person(ID),
  CONSTRAINT FK_Government_Official_Position FOREIGN KEY (Position) REFERENCES Position(ID)
);
CREATE TABLE Law (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Government INT,
  CONSTRAINT PK_Law_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Law_Government FOREIGN KEY (Government) REFERENCES Government(ID)
);
