CREATE TABLE Support_Instance (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Creator INT,
  Time_Lapse INT,
  CONSTRAINT PK_Support_Instance_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Support_Instance_Creator FOREIGN KEY (Creator) REFERENCES User(ID),
  CONSTRAINT FK_Support_Instance_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
