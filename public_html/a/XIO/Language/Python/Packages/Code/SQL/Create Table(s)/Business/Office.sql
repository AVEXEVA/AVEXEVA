CREATE TABLE Office (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Office_ID PRIMARY KEY (ID)
);
CREATE TABLE Desk (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Desk_ID PRIMARY KEY (ID)
);
CREATE TABLE Desk_Computer (
  Desk INT NOT NULL,
  Computer INT NOT NULL,
  CONSTRAINT FK_Desk_Computer_Desk FOREIGN KEY (Desk) REFERENCES Desk(ID),
  CONSTRAINT FK_Desk_Computer_Computer FOREIGN KEY (Computer) REFERENCES Computer(ID)
);
