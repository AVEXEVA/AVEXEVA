CREATE TABLE Service (
  ID INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Service_ID PRIMARY KEY (ID)
  CONSTRAINT PK_Service_Parent FOREIGN KEY (Service) REFERENCES Service(ID)
);
CREATE TABLE Service_Person (
  Service INT NOT NULL,
  Person INT NOT NULL,
  CONSTRAINT FK_Service_Person_Service FOREIGN KEY (Service) REFERENCES Service(ID),
  CONSTRAINT FK_Service_Person_Person FOREIGN KEY (Person) REFERENCES Person(ID)
);
CREATE TABLE Service_Device (
  Service INT NOT NULL,
  Device INT NOT NULL,
  CONSTRAINT FK_Service_Device_Service FOREIGN KEY (Service) REFERENCES Service(ID),
  CONSTRAINT FK_Service_Device_Device FOREIGN KEY (Device) REFERENCES Device(ID)
);
CREATE TABLE Service_Task (
  Service INT,
  Task INT,
  Time_Lapse INT,
  CONSTRAINT FK_Service_Task_Service FOREIGN KEY (Service) REFERENCES Service(ID),
  CONSTRAINT FK_Service_Task_Task FOREIGN KEY (Task) REFERENCES Task(ID),
  CONSTRAINT FK_Service_Task_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
CREATE TABLE Service_Frequency (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT FK_Service_Frequency_ID PRIMARY KEY (ID)
);
CREATE TABLE Ticket_Grouping (
  ID INT NOT NULL AUTO_INCREMENT,
  Parent INT,
  CONSTRAINT PK_Ticket_Grouping_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Ticket_Grouping_Parent FOREIGN KEY (Parent) REFERENCES Ticket_Grouping(ID)
);
CREATE TABLE Ticket (
  ID INT NOT NULL,
  Grouping INT NOT NULL,
  Time_Lapse INT,
  CONSTRAINT PK_Ticket_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Ticket_Grouping FOREIGN KEY (Grouping) REFERENCES Ticket_Grouping(ID),
  CONSTRAINT FK_Ticket_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
CREATE TABLE Task (
  ID INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT,
  CONSTRAINT PK_Task_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Task_Parent FOREIGN KEY (Parent) REFERENCES Task(ID)
);
CREATE TABLE Ticket_Task (
  Ticket INT,
  Task INT,
  Time_Lapse INT,
  CONSTRAINT PK_Ticket_Task_Ticket FOREIGN KEY (Ticket) REFERENCES Ticket(ID),
  CONSTRAINT FK_Ticket_Task_Task FOREIGN KEY (Task) REFERENCES Task(ID),
  CONSTRAINT FK_Ticket_Task_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
