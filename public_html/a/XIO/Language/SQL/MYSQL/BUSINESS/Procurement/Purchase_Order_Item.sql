CREATE TABLE Purchase_Order_Item (
  ID INT NOT NULL AUTO_INCREMENT,
  Purchase_Order INT NOT NULL,
  CONSTRAINT PK_Purchase_Order_Item PRIMARY KEY (ID),
  CONSTRAINT FK_Purchase_Order_Item_Purchase_Order FOREIGN KEY (Purchase_Order) REFERENCES Purchase_Order(ID)
);
