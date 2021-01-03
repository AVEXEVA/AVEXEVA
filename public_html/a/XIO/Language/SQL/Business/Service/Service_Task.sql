CREATE TABLE Service_Task (
  Service INT,
  Task INT,
  Time_Lapse INT,
  CONSTRAINT FK_Service_Task_Service FOREIGN KEY (Service) REFERENCES Service(ID),
  CONSTRAINT FK_Service_Task_Task FOREIGN KEY (Task) REFERENCES Task(ID),
  CONSTRAINT FK_Service_Task_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
