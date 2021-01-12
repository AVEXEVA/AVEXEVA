CREATE TABLE Store (
  ID INT NOT NULL AUTO_INCREMENT,
  Owner INT NOT NULL,
  CONSTRAINT PK_Store_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Store_Owner FOREIGN KEY (Owner) REFERENCES Entity(ID)
);