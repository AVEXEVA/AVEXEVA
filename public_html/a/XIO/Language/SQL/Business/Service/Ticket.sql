CREATE TABLE Ticket (
  ID INT NOT NULL,
  Grouping INT NOT NULL,
  Time_Lapse INT,
  CONSTRAINT PK_Ticket_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Ticket_Grouping FOREIGN KEY (Grouping) REFERENCES Ticket_Grouping(ID),
  CONSTRAINT FK_Ticket_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
