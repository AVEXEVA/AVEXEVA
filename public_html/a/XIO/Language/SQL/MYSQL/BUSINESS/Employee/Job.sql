CREATE TABLE Job (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT,
  Type INT,
  CONSTRAINT PK_Job_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Job_Parent FOREIGN KEY (Parent) REFERENCES Job(ID),
  CONSTRAINT FK_Job_type FOREIGN KEY (Type) REFERENCES Job_Type(ID)
);
CREATE TABLE Job_Type (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Job_Type_ID PRIMARY KEY (ID)
);