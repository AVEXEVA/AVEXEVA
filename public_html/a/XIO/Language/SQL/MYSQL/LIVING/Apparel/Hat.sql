CREATE TABLE Hat (
  Clothing INT NOT NULL,
  CONSTRAINT FK_Hat_Clothing FOREIGN KEY (Clothing) REFERENCES Clothing(ID)
);
