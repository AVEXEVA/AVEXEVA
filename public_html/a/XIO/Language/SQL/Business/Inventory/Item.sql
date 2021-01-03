CREATE TABLE Item (
  ID INT NOT NULL,
  Name VARCHAR(256),
  Product INT,
  Condition INT,
  Category INT,
  CONSTRAINT PK_Item_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Item_Product FOREIGN KEY (Product) REFERENCES Product(ID),
  CONSTRAINT FK_Item_Condition FOREIGN KEY (Condition) REFERENCES Item_Condition(ID),
  CONSTRAINT FK_Item_Category FOREIGN KEY (Category) REFERENCES Item_Category(ID)
);
