CREATE TABLE Sale_Order_Item (
  Sale_Order INT NOT NULL,
  Item INT NOT NULL,
  CONSTRAINT FK_Sale_Order_Item_Sale_Order FOREIGN KEY (Sale_Order) REFERENCES Sale_Order(ID),
  CONSTRAINT FK_Sale_Order_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
