CREATE TABLE Wage (
  ID INT NOT NULL,
  CONSTRAINT PK_Wage_ID PRIMARY KEY (ID)
);
CREATE TABLE Wage_Hourly (
  ID INT NOT NULL,
  Wage INT NOT NULL,
  Currency INT NOT NULL,
  Amount FLOAT,
  CONSTRAINT PK_Wage_Hourly_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Wage_Hourly_Wage FOREIGN KEY (Wage) REFERENCES Wage(ID),
  CONSTRAINT PK_Wage_Hourly_Currency FOREIGN KEY (Currency) REFERENCES Currency(ID)
);
CREATE TABLE Employee_Position_Wage (
  Employee INT NOT NULL,
  Wage INT NOT NULL,
  Position INT NOT NULL,
  Burden INT NOT NULL,
  CONSTRAINT FK_Employee_Position_Wage_Employee FOREIGN KEY (Employee) REFERENCES Employee(ID),
  CONSTRAINT FK_Employee_Position_Wage_Wage FOREIGN KEY (Wage) REFERENCES Wage(ID),
  CONSTRAINT FK_Employee_Position_Wage_Position FOREIGN KEY (Position) REFERENCES Position(ID),
  CONSTRAINT FK_Employee_Position_Wage_Burden FOREIGN KEY (Burden) REFERENCES Burden(ID)
);
