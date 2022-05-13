CREATE TABLE Item_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Item_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Item_Category_Parent FOREIGN KEY (Parent) REFERENCES Item_Category(ID)
); 
