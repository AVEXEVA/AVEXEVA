CREATE TABLE Hour (
  ID INT NOT NULL AUTO_INCREMENT,
  Number INT,
  CONSTRIANT PK_Hour_ID PRIMARY KEY (ID)
);
CREATE TABLE Minute (
  ID INT NOT NULL AUTO_INCREMENT,
  Number INT,
  CONSTRAINT PK_Minute_ID PRIMARY KEY (ID)
);
CREATE TABLE Second (
  ID INT NOT NULL AUTO_INCREMENT,
  Number FLOAt,
  CONSTRAINT PK_Second_ID PRIMARY KEY (ID)
);