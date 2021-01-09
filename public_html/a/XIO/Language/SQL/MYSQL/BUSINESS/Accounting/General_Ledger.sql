CREATE TABLE General_Ledger (
  ID INT NOT NULL,
  Code INT,
  Account VARCHAR(256),
  Balance FLOAT,
  CONSTRAINT PK_General_Ledger_ID PRIMARY KEY (ID)
);
