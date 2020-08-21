CREATE TABLE Permission (
  ID INT NOT NULL AUTO_INCREMENT,
  Read BIT DEFAULT 0,
  Write BIT DEFAULT 0,
  Execute BIT DEFAULT 0,
  CONSTRAINT PK_Permission_ID   PRIMARY KEY (ID),
  CONSTRAINT FK_Permission_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_Permission_File FOREIGN KEY (File) REFERENCES File(ID)
);
CREATE TABLE User_File_Permission (
  User        INT NOT NULL AUTO_INCREMENT,
  File        INT NOT NULL,
  Permission  INT NOT NULL,
  CONSTRAINT FK_User_File_Permission_User       FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_User_File_Permission_File       FOREIGN KEY (File) REFERENCES File(ID),
  CONSTRAINT FK_User_File_Permission_Permission FOREIGN KEY (Permission) REFERENCES Permission(ID)
);

CREATE TABLE User_Table_Permission (
  User        INT NOT NULL AUTO_INCREMENT,
  Table       INT NOT NULL,
  Permission  INT NOT NULL
  CONSTRAINT FK_User_Table_Permission_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_User_Table_Permission_Table FOREIGN KEY (Table) REFERENCES Table(ID),
  CONSTRAINT FK_User_Table_Permission_Permission FOREIGN KEY (Permission) REFERENCES Permission(ID)
);
