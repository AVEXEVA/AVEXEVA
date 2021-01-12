CREATE TABLE Job (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT,
  Type INT,
  CONSTRAINT PK_Job_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Job_Parent FOREIGN KEY (Parent) REFERENCES Job(ID),
  CONSTRAINT FK_Job_type FOREIGN KEY (Type) REFERENCES Job_Type(ID)
);
CREATE TABLE Job_Type (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Job_Type_ID PRIMARY KEY (ID)
);
CREATE TABLE Company (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  Name VARCHAR(256),
  Parent INT NOT NULL,
  Founded DATETIME,
  Created DATETIME,
  Headquarters INT,
  Accounts_Payable INT,
  Accounts_Recievable INT,
  Human_Resources INT,
  Engineering INT,
  Legal INT,
  CONSTRAINT PK_Company_ID                  PRIMARY KEY (ID)
  CONSTRAINT PK_Entity_ID                   FOREIGN KEY (Entity)              REFERENCES Entity(ID),
  CONSTRAINT FK_Company_Headquarters        FOREIGN KEY (Headquarters)        REFERENCES Department(ID),
  CONSTRAINT FK_Company_Accounts_Payable    FOREIGN KEY (Accounts_Payable)    REFERENCES Department(ID),
  CONSTRAINT FK_Company_Accounts_Recievable FOREIGN KEY (Accounts_Recievable) REFERENCES Department(ID),
  CONSTRAINT FK_Company_Human_Resources     FOREIGN KEY (Human_Resources)     REFERENCES Department(ID),
  CONSTRAINT FK_Company_Engineering         FOREIGN KEY (Engineering)         REFERENCES Department(ID),
  CONSTRAINT FK_Company_Legal               FOREIGN KEY (Legal)               REFERENCES Department(ID)
);
CREATE TABLE Consultant (
  ID      INT NOT NULL AUTO_INCREMENT,
  Company INT NOT NULL,
  CONSTRAINT PK_Consultant_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Consultant_Company FOREIGN KEY (Company) REFERENCES Company(ID)
);
CREATE TABLE Department (
  ID          INT NOT NULL AUTO_INCREMENT,
  Company     INT NOT NULL,
  Name        VARCHAR(256),
  Supervisor  INT,
  Rolodex     INT NOT NULL,
  CONSTRAINT PK_Department_ID         PRIMARY KEY (ID),
  CONSTRAINT FK_Department_Company    FOREIGN KEY (Company) REFERENCES Company(ID),
  CONSTRAINT FK_Department_Supervisor FOREIGN KEY (Supervisor) REFERENCES Person(ID),
  CONSTRAINT FK_Department_Rolodex    FOREIGN KEY (Rolodex) REFERENCES Rolodex(ID)
);
CREATE TABLE Position (
  ID INT NOT NULL AUTO_INCREMENT,
  Employee INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  Supervisor INT,
  CONSTRAINT PK_Position_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Position_Employee FOREIGN KEY (Employee) REFERENCES Employee(ID),
  CONSTRAINT FK_Position_Supervisor FOREIGN KEY (Supervisor) REFERENCES Position(ID)
);
CREATE TABLE Employee (
  ID                      INT NOT NULL AUTO_INCREMENT,
  Person                  INT NOT NULL,
  Social_Security_Number  VARCHAR(15),
  Hired                   DATETIME,
  Reviewed                DATETIME,
  Fired                   DATETIME,
  CONSTRAINT PK_Employee_ID     PRIMARY KEY (ID),
  CONSTRAINT FK_Employee_Person REFERENCES Person(ID)
);
CREATE TABLE Business_System (
  ID INT NOT NULL AUTO_INCREMENT,
  Enterprise_Resource_Planning_System INT,
  Customer_Relationship_Management_System INT,
  Payroll_System INT,
  Inventory_Tracking_System INT,
  CONSTRAINT PK_Business_System_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Business_System_Enterprise_Resource_Planning FOREIGN KEY (Enterprise_Resource_Planning) REFERENCES Enterprise_Resource_Planning(ID),
  CONSTRAINT FK_Business_System_Customer_Relationship_Management FOREIGN KEY (Customer_Relationship_Management) REFERENCES Customer_Relationship_Management(ID),
  CONSTRAINT FK_Business_System_Payroll_System FOREIGN KEY (Payroll_System) REFERENCES Payroll_System(ID),
  CONSTRAINT FK_Business_System_Inventory_Tracking FOREIGN KEY (Inventory_Tracking) REFERENCES Inventory_Tracking(ID)
);
CREATE TABLE Enterprise_Resource_Planning_System (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Enterprise_Resource_Planning_System_ID PRIMARY KEY (ID)
);
CREATE TABLE Customer_Relationship_Management_System (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Customer_Relationship_System_Management PRIMARY KEY (ID)
);
CREATE TABLE Payroll_System (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Payroll_System_ID PRIMARY KEY (ID)
);
CREATE TABLE Inventory_Tracking_System (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Inventory_Tracking_System_ID PRIMARY KEY (ID)
);
