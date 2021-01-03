CREATE TABLE Fraternity (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Fraternity_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Fraternity_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
