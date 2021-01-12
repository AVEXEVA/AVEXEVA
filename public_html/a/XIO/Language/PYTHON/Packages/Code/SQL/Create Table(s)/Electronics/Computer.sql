CREATE TABLE Computer (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Computer_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Computer_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Monitor (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Monitor_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Monitor_Item FOREIGN KEY (Monitor)
);
CREATE TABLE Keyboard (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Keyboard_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Keyboard_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Mouse (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Mouse_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Mouse_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
