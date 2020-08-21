CREATE TABLE Transaction (
  ID INT NOT NULL,
  From INT NOT NULL,
  To INT NOT NULL,
  Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT PK_Transaction_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Transaction_From FOREIGN KEY (Entity) REFERENCES Entity(ID),
  CONSTRAINT FK_Transaction_To FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE General_Ledger (
  ID INT NOT NULL,
  Code INT,
  Account VARCHAR(256),
  Balance FLOAT,
  CONSTRAINT PK_General_Ledger_ID PRIMARY KEY (ID)
);
CREATE TABLE Transaction_Item (
  ID INT NOT NULL AUTO_INCREMENT,
  Transaction INT NOT NULL,
  Item INT NOT NULL,
  CONSTRAINT PK_Transaction_Item_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Transaction_Item_Transaction FOREIGN KEY (Transaction) REFERENCES Transaction(ID),
  CONSTRAINT FK_Transaction_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Transaction_Currency (
  Transaction INT NOT NULL,
  Currency INT NOT NULL,
  Amount FLOAT DEFAULT 0.00,
  CONSTRAINT FK_Transaction_Currency_Transaction FOREIGN KEY (Transaction) REFERENCES Transaction(ID),
  CONSTRAINT FK_Transaction_Currency_Currency FOREIGN KEY (Currency) REFERENCES Currency(ID),
);
CREATE TABLE Bank (
  ID INT NOT NULL AUTO_INCREMENT,
  Company INT NOT NULL,
  CONSTRAINT PK_Bank_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Bank_Company FOREIGN KEY (Company) REFERENCES Company(ID)
);
CREATE TABLE Bank_Account (
  ID INT NOT NULL AUTO_INCREMENT,
  Number INT,
  Route INT
  CONSTRAINT PK_Bank_Account_ID PRIMARY KEY (ID)
);
CREATE TABLE Bank_Account_Type (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Account_Type_ID PRIMARY KEY (ID)
);
