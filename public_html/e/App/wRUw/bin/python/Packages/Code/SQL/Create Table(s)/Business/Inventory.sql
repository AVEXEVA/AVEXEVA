CREATE TABLE Manufacturer (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Manufacturer_ID PRIMARY KEY (ID)
);
CREATE TABLE Product (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  SKU VARCHAR(256),
  Category INT NOT NULL,
  Price FLOAT,
  CONSTRAINT PK_Product_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Product_Category FOREIGN KEY (Category) REFERENCES Product_Category(ID);
);
CREATE TABLE Package (
  ID INT NOT NULL AUTO_INCREMENT,
  Product INT,
  Parent INT,
  CONSTRAINT PK_Package_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Package_Parent FOREIGN KEY (Parent) REFERENCES Package(ID)
);
CREATE TABLE Package_Item (
  Package INT NOT NULL,
  Item INT NOT NULL,
  CONSTRAINT FK_Package_Item_Package FOREIGN KEY (Package) REFERENCES Package(ID),
  CONSTRAINT FK_Package_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
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
CREATE TABLE Item_Condition (
  ID INT NOT NULL,
  Name VARCHAR(256),
  CONSTRAINT PK_Item_Condition_ID PRIMARY KEY (ID)
);
CREATE TABLE Product_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Product_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Product_Category_Parent FOREIGN KEY (Parent) REFERENCES Product_Category(ID)
);
CREATE TABLE Item_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Item_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Item_Category_Parent FOREIGN KEY (Parent) REFERENCES Item_Category(ID)
); 
