CREATE TABLE Fraternity_Member (
  ID INT NOT NULL AUTO_INCREMENT,
  Chapter INT NOT NULL,
  Person INT NOT NULL,
  Identification VARCHAR(256),
  CONSTRAINT PK_Fraternity_Member_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Fraternity_Member_Chapter FOREIGN KEY (Chapter) REFERENCES Fraternity_Chapter(ID),
  CONSTRAINT FK_Fraternity_Chapter_Person FOREIGN KEY (Person) REFERENCES Person(ID)
);
