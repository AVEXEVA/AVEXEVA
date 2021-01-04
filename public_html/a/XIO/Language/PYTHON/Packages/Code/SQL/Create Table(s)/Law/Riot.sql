CREATE TABLE Riot (
  ID NOT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Riot_ID PRIMARY KEY (ID)
);
 CREATE TABLE Rioter (
   Riot INT NOT NULL,
   Person INT NOT NULL,
   CONSTRAINT FK_Rioter_Riot FOREIGN KEY (Riot) REFERENCES Riot(ID),
   CONSTRAINT FK_Rioter_Person FOREIGN KEY (Person) REFERENCES Person(ID)
 );