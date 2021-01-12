CREATE TABLE `Database` (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Address VARCHAR(256),
  CONSTRAINT PK_Database_ID PRIMARY KEY (ID)
);
INSERT INTO [Database](Server, Name) VALUES(1, 'Accounting');
INSERT INTO [Database](Server, Name) VALUES(1, 'Human_Resources');
INSERT INTO [Database](Server, Name) VALUES(1, 'Information');
INSERT INTO [Database](Server, Name) VALUES(1, 'Financials');
INSERT INTO [Database](Server, Name) VALUES(1, 'Inventory');
INSERT INTO [Database](Server, Name) VALUES(1, 'Marketing');
INSERT INTO [Database](Server, Name) VALUES(1, 'Office');
INSERT INTO [Database](Server, Name) VALUES(1, 'Ownership');
INSERT INTO [Database](Server, Name) VALUES(1, 'Payroll');
INSERT INTO [Database](Server, Name) VALUES(1, 'Procurement');
INSERT INTO [Database](Server, Name) VALUES(1, 'Project');
INSERT INTO [Database](Server, Name) VALUES(1, 'Sales');
INSERT INTO [Database](Server, Name) VALUES(1, 'Service');
INSERT INTO [Database](Server, Name) VALUES(1, 'Store');
CREATE TABLE `Table` (
  ID          INT NOT NULL AUTO_INCREMENT,
  Name        VARCHAR(256) NOT NULL,
  Description TEXT,
  CONSTRAINT PK_Table_ID PRIMARY KEY (ID)
);
INSERT INTO [Table](Database, Name) VALUES(1, 'Transaction');
INSERT INTO [Table](Database, Name) VALUES(1, 'General_Ledger');
INSERT INTO [Table](Database, Name) VALUES(1, 'Transaction_Item');
INSERT INTO [Table](Database, Name) VALUES(1, 'Bank');
INSERT INTO [Table](Database, Name) VALUES(1, 'Bank_Account');
INSERT INTO [Table](Database, Name) VALUES(1, 'Bank_Account_Type');
INSERT INTO [Table](Database, Name) VALUES(1, 'General_Ledger');
INSERT INTO [Table](Database, Name) VALUES(1, 'General_Ledger');
INSERT INTO [Table]()
CREATE TABLE Datatype (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  CONSTRAINT PK_Datatype_ID PRIMARY KEY (ID)
);
CREATE TABLE `Column` (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Datatype INT NOT NULL,
  `Default` VARCHAR(MAX),
  CONSTRAINT PK_Column_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Column_Datatype FOREIGN KEY (Datatype) REFERENCES Datatype(ID)
);
CREATE TABLE Table_Column (
  `Table` INT NOT NULL,
  `Column` INT NOT NULL,
  CONSTRAINT FK_Table_Column_Table FOREIGN KEY (`Table`) REFERENCES `Table`(ID),
  CONSTRAINT FK_Table_Column_Column FOREIGN KEY (`Column`) REFERENCES `Column`(ID)
);
CREATE TABLE Reserved_Word (
  ID INT NOT NULL AUTO_INCREMENT,
  Word INT NOT NULL,
  CONSTRAINT PK_Reserved_Word_ID PRIMARY KEY (ID)
);
CREATE TABLE `Trigger` (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Trigger_ID PRIMARY KEY (ID)
);
CREATE TABLE `Index` (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Index_ID PRIMARY KEY (ID)
);
CREATE TABLE `Index_Column` (
  `Index` INT NOT NULL,
  `Column` INT NOT NULL,
  CONSTRAINT FK_Index_Column_Index FOREIGN KEY (`Index`) REFERENCES `Index`(ID),
  CONSTRAINT FK_Index_Column_Column FOREIGN KEY (`Column`) REFERENCES `Column`(ID)
);
