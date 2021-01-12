CREATE TABLE Clothing (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT NOT NULL,
  CONSTRAINT PK_Clothing_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Clothing_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
