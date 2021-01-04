CREATE TABLE User (
  ID INT NOT NULL,
  Name VARCHAR(255),
  Password VARCHAR(255),
  Person INT,
  PRIMARY KEY (ID),
  CONSTRAINT FK_User_Person FOREIGN KEY (Person) REFERENCES Person(ID)
);
CREATE TABLE User_License (
  User INT NOT NULL,
  License INT NOT NULL,
  Price FLOAT,
  Paid FLOAT,
  Start DATETIME,
  End DATETIME,
  CONSTRAINT FK_User_License_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_User_License_License FOREIGN KEY (License) REFERENCES License(ID)
);
CREATE TABLE License (
  ID INT NOT NULL,
  Name VARCHAR(255),
  Price FLOAT, /*GET RID OF ASAP*/
);
CREATE TABLE Person (
  ID INT NOT NULL,
  First_Name VARCHAR(255),
  Middle_Name VARCHARVARCHAR(255),
  Last_Name VARCHAR(255),
  Email INT,
  Home INT,
  Work INT,
  Cell INT,
  Office INT,
  PRIMARY KEY (ID),
  CONSTRAINT FK_Person_Email  FOREIGN KEY (Email)   REFERENCES Email(ID),
  CONSTRAINT FK_Person_Home   FOREIGN KEY (Home)    REFERENCES Address(ID),
  CONSTRAINT FK_Person_Work   FOREIGN KEY (Work)    REFERENCES Address(ID),
  CONSTRAINT FK_Person_Cell   FOREIGN KEY (Cell)    REFERENCES Phone(ID),
  CONSTRAINT FK_Person_Office FOREIGN KEY (Office)  REFERENCES Phone(ID)
);
CREATE TABLE Address (
  ID INT NOT NULL,
  Street VARCHAR(255),
  City VARCHAR(255),
  State VARCHAR(255),
  Zip_Code VARCHAR(30),
  Country VARCHAR(255),
  Latitude FLOAT,
  Longitude FLOAT
);
CREATE TABLE Email (
  ID INT NOT NULL,
  Address VARCHAR(255)
);
CREATE TABLE Phone (
  ID INT NOT NULL,
  Number VARCHAR(255)
);
