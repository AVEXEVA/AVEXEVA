CREATE TABLE Group (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Group_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Group_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
