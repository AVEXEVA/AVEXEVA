CREATE TABLE Server (
  ID INT NOT NULL AUTO_INCREMENT,
  Computer INT,
  Name VARCHAR(256),
  Address VARCHAR(256),
  CONSTRAINT PK_Server_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Server_Computer FOREIGN KEY (Computer) REFERENCES Computer(ID)
);
CREATE TABLE Server_Port (
  Server INT NOT NULL,
  Port INT NOT NULL,
  CONSTRAINT FK_Server_Port_Server FOREIGN KEY (Server) REFERENCES Server(ID),
  CONSTRAINT FK_Server_Port_Port FOREIGN KEY (Port) REFERENCES Port(ID)
);
