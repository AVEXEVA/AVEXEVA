CREATE TABLE Seller (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Seller_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Seller_Entity FOREIGN KEY (Entity) REFERNECES Entity(ID),
);
