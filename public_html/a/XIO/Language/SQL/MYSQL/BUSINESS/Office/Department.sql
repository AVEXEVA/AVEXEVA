CREATE TABLE Department (
  ID          INT NOT NULL AUTO_INCREMENT,
  Company     INT NOT NULL,
  Name        VARCHAR(256),
  Supervisor  INT,
  Rolodex     INT NOT NULL,
  CONSTRAINT PK_Department_ID         PRIMARY KEY (ID),
  CONSTRAINT FK_Department_Company    FOREIGN KEY (Company) REFERENCES Company(ID),
  CONSTRAINT FK_Department_Supervisor FOREIGN KEY (Supervisor) REFERENCES Person(ID),
  CONSTRAINT FK_Department_Rolodex    FOREIGN KEY (Rolodex) REFERENCES Rolodex(ID)
);
