CREATE TABLE Package_Item (
  Package INT NOT NULL,
  Item INT NOT NULL,
  CONSTRAINT FK_Package_Item_Package FOREIGN KEY (Package) REFERENCES Package(ID),
  CONSTRAINT FK_Package_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
