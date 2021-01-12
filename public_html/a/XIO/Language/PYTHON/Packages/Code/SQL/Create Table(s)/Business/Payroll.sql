CREATE TABLE Payroll (
  ID INT NOT NULL AUTO_INCREMENT,
  Employee INT NOT NULL,
  CONSTRAINT PK_Payroll_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Payroll_Employee FOREIGN KEY (Employee) REFERENCES Employee(ID)
);
CREATE TABLE Payroll_Item (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT NOT NULL,
  Amount FLOAT,
  Paid FLOAT,
  CONSTRAINT PK_Payroll_Item_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Payroll_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Expenses (
  ID INT NOT NULL,
  Employee INT NOT NULL,
  Amount FLOAT,
  Paid FLOAT,
  CONSTRAINT PK_Expenses_ID PRIMARY KEY (ID)
);
CREATE TABLE Expense (
  ID INT NOT NULL,
  Expenses INT NOT NULL,
  Amount FLOAT,
  Paid FLOAT,
  CONSTRAINT PK_Expense_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Expense_Expenses FOREIGN KEY (Expenses) REFERENCES Expenses(ID)
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
CREATE TABLE Burden (
  ID INT NOT NULL, 
  CONSTRAINT PK_Burden_ID PRIMARY KEY (ID)
);
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
