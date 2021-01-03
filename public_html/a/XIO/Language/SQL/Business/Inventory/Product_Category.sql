CREATE TABLE Product_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Product_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Product_Category_Parent FOREIGN KEY (Parent) REFERENCES Product_Category(ID)
);
