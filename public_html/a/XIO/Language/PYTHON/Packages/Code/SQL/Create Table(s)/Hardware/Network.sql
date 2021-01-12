CREATE TABLE Router (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  User INT,
  CONSTRAINT PK_Router_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Router_Item FOREIGN KEY (Item) REFERENCES Item(ID),
  CONSTRAINT FK_Router_User FOREIGN KEY (User) REFERENCES User(ID)
);
CREATE TABLE Modem (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Modem_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Modem_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
