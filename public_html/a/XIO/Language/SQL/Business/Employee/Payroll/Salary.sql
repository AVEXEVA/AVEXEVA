CREATE TABLE Salary (
  ID INT NOT NULL,
  Wage INT NOT NULL,
  Time_Lapse INT NOT NULL,
  Time_Frequency INT NOT NULL,
  Currency INT NOT NULL,
  Amount FLOAT,
  CONSTRAINT PK_Salary_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Salary_Wage FOREIGN KEY (Wage) REFERENCES Wage(ID),
  CONSTRAINT FK_Salary_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID),
  CONSTRAINT FK_Salary_Time_Frequency FOREIGN KEY (Time_Frequency) REFERNCES Time_Frequency(ID)
);
