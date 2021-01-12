CREATE TABLE Protest (
  ID NOT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Protest_ID PRIMARY KEY (ID)
);
 CREATE TABLE Protester (
   Protest INT NOT NULL,
   Person INT NOT NULL,
   CONSTRAINT FK_Rioter_Protest FOREIGN KEY (Protest) REFERENCES Protest(ID),
   CONSTRAINT FK_Rioter_Person FOREIGN KEY (Person) REFERENCES Person(ID)
 );
