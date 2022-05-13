CREATE TABLE Ticket_Grouping (
  ID INT NOT NULL AUTO_INCREMENT,
  Parent INT,
  CONSTRAINT PK_Ticket_Grouping_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Ticket_Grouping_Parent FOREIGN KEY (Parent) REFERENCES Ticket_Grouping(ID)
);
