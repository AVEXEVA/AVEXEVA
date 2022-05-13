CREATE TABLE Cartoon (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Cartoon_ID PRIMARY KEY (ID)
);
CREATE TABLE Character (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Character_ID PRIMARY KEY (ID),
);
CREATE TABLE Character_File (
  Character INT NOT NULL,
  File INT NOT NULL,
  CONSTRAINT FK_Character_File_Character FOREIGN KEY (Character) REFERENCES Character(ID),
  CONSTRAINT FK_Character_File_File FOREIGN KEY (File) REFERENCES File(ID)
);
CREATE TABLE Cartoon_Character (
  Cartoon INT NOT NULL,
  Character INT NOT NULL,
  CONSTRAINT FK_Cartoon_Character_Cartoon FOREIGN KEY (Cartoon) REFERENCES Cartoon(ID),
  CONSTRAINT FK_Cartoon_Character_Character FOREIGN KEY (Character) REFERENCES Character(ID)
);
CREATE TABLE Scene (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  File INT,
  CONSTRAINT PK_Scene_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Scene_File FOREIGN KEY (File) REFERENCES File(ID)
);
