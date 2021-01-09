CREATE TABLE Intellectual_Property (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Intellectual_Property_ID PRIMARY KEY (ID)
);
CREATE TABLE Copyright (
  ID INT NOT NULL AUTO_INCREMENT,
  Intellectual_Property INT NOT NULL,
  CONSTRAINT PK_Copyright_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Copyright_Intellectual_Property FOREIGN KEY (Intellectual_Property) REFERENCES Intellectual_Property(ID)
);
CREATE TABLE Trademark (
  ID INT NOT NULL AUTO_INCREMENT,
  Intellectual_Property INT NOT NULL,
  CONSTRAINT PK_Trademark_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Trademark_Intellectual_Property FOREIGN KEY (Intellectual_Property) REFERENCES Intellectual_Property(ID)
);
