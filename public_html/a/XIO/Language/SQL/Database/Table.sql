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
