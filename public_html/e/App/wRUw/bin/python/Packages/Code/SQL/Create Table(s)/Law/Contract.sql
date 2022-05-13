CREATE TABLE Contract (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Text TEXT
);
CREATE TABLE Contract_Entity (
  Contract INT,
  Entity INT,
  CONSTRAINT FK_Contract_Entity_Contract FOREIGN KEY (Contract) REFERENCES Contract(ID),
  CONSTRAINT FK_Contract_Entity_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Contract_Service (
  Contract INT,
  Service INT,
  Provider INT,
  Reciever INT,
  Frequency INT,
  CONSTRAINT FK_Contract_Entity_Service_Contract FOREIGN KEY (Contract) REFERENCES Contract(ID),
  CONSTRAINT FK_Contract_Entity_Service_Service FOREIGN KEY (Service) REFERENCES Service(ID)
  CONSTRAINT FK_Contract_Entity_Service_Provider FOREIGN KEY (Provider) REFERENCES Entity(ID),
  CONSTRAINT FK_Contract_Entity_Service_Reciever FOREIGN KEY (Reciever) REFERENCES Entity(ID),
  CONSTRAINT FK_Contract_Entity_Service_Frequency FOREIGN KEY (Frequency) REFERENCES Frequency(ID)
);
CREATE TABLE Contract_Item (
  Contract INT,
  Item INT,
  Provider INT,
  Reciever INT,
  CONSTRAINT FK_Contract_Item_Contract FOREIGN KEY (Contract) REFERENCES Contract(ID),
  CONSTRAINT FK_Contract_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID),
  CONSTRAINT FK_Contract_Item_Provider FOREIGN KEY (Provider) REFERENCES Entity(ID),
  CONSTRAINT FK_Contract_Item_Reciever FOREIGN KEY (Reciever) REFERENCES Entity(ID)
);
CREATE TABLE Contract_Currency (
  Contract INT,
  Currency INT,
  Amount FLOAT,
  Frequency INT,
  Provider INT,
  Reciever INT,
  CONSTRAINT FK_Contract_Currency_Contract FOREIGN KEY (Contract) REFERENCES Contract(ID),
  CONSTRAINT FK_Contract_Currency_Currency FOREIGN KEY (Currency) REFERENCES Currency(ID),
  CONSTRAINT FK_Contract_Currency_Frequency FOREIGN KEY (Frequency) REFERENCES Frequency(ID),
  CONSTRAINT FK_Contract_Currency_Provider FOREIGN KEY (Provider) REFERENCES Entity(ID),
  CONSTRAINT FK_Contract_Currency_Reciever FOREIGN KEY (Reciever) REFERENCES Entity(ID)
);
