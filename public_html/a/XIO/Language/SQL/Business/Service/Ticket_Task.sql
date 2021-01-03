CREATE TABLE Ticket_Task (
  Ticket INT,
  Task INT,
  Time_Lapse INT,
  CONSTRAINT PK_Ticket_Task_Ticket FOREIGN KEY (Ticket) REFERENCES Ticket(ID),
  CONSTRAINT FK_Ticket_Task_Task FOREIGN KEY (Task) REFERENCES Task(ID),
  CONSTRAINT FK_Ticket_Task_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
