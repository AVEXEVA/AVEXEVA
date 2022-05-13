CREATE TABLE Insurance_Company (
  ID INT NOT NULL AUTO_INCREMENT,
  Company INT,
  CONSTRAINT PK_Insurance_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Insurance_Company FOREIGN KEY (Company) REFERENCES Company(ID)
);
