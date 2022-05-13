CREATE TABLE Benefit (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Deductable FLOAT,
  CONSTRAINT PK_Benefit_ID PRIMARY KEY (ID)
);
