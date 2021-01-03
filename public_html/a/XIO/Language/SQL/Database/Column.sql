CREATE TABLE `Column` (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Datatype INT NOT NULL,
  `Default` VARCHAR(MAX),
  CONSTRAINT PK_Column_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Column_Datatype FOREIGN KEY (Datatype) REFERENCES Datatype(ID)
);
