CREATE TABLE Service (
  ID INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Service_ID PRIMARY KEY (ID)
  CONSTRAINT PK_Service_Parent FOREIGN KEY (Service) REFERENCES Service(ID)
);
