CREATE TABLE Address (
  ID            INT NOT NULL AUTO_INCREMENT,
  Name          VARCHAR(256),
  Room          INT NOT NULL,
  Floor         INT NOT NULL,
  Street        INT NOT NULL,
  Locale        INT NOT NULL,
  City          INT NOT NULL,
  State         INT NOT NULL,
  Zip_Code      INT NOT NULL,
  Country       INT NOT NULL,
  Latitude      FLOAT,
  Longitude     FLOAT,
  CONSTRAINT PK_Address_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Address_Country FOREIGN KEY (Country) REFERENCES Country(ID),
  CONSTRAINT FK_Address_State FOREIGN KEY (State) REFERENCES State(ID),
  CONSTRAINT FK_Address_City FOREIGN KEY (City) REFERENCES City(ID),
  CONSTRAINT FK_Address_Street FOREIGN KEY (Street) REFERENCES Street(ID),
  CONSTRAINT FK_Address_Floor FOREIGN KEY (Floor) REFERENCES Floor(ID),
  CONSTRAINT FK_Address_Room FOREIGN KEY (Room) REFERENCES Room(ID)
);
CREATE TABLE Country (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_Country_ID PRIMARY KEY (ID)
);
CREATE TABLE Street (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_Street_ID PRIMARY KEY (ID)
);
CREATE TABLE City (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_City_ID PRIMARY KEY (ID)
);
CREATE TABLE State (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_State_ID PRIMARY KEY (ID)
);
CREATE TABLE Zip_Code (
  ID INT NOT NULL AUTO_INCREMENT,
  Number VARCHAR(25) NOT NULL,
  CONSTRAINT PK_Zip_Code_ID PRIMARY KEY (ID)
);
CREATE TABLE Rolodex (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Rolodex_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Rolodex_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID),
  CONSTRAINT FK_Rolodex_Home FOREIGN KEY (Home) REFERENCES Address(ID),
  CONSTRAINT FK_Roloex_Work FOREIGN KEY (Work) REFERENCES Address(ID),
  CONSTRAINT FK_Rolodex_Office FOREIGN KEY (Office) REFERENCES Phone(ID),
  CONSTRAINT FK_Rolodex_Cell FOREIGN KEY (Cell) REFERENCES Phone(ID)
);
CREATE TABLE Rolodex_Phone (
  Rolodex INT NOT NULL,
  Phone INT NOT NULL,
  Name VARCHAR(256),
  CONSTRAINT FK_Rolodex_Phone_Rolodex FOREIGN KEY (Rolodex) REFERENCES Rolodex(ID),
  CONSTRAINT FK_Rolodex_Phone_Phone FOREIGN KEY (Phone) REFERENCES Phone(ID),
);
CREATE TABLE Rolodex_Address (
  Rolodex INT NOT NULL,
  Address INT NOT NULL,
  Name VARCHAR(256),
  CONSTRAINT FK_Rolodex_Address_Rolodex FOREIGN KEY (Rolodex) REFERENCES Rolodex(ID),
  CONSTRAINT FK_Rolodex_Address_Address FOREIGN KEY (Address) REFERENCES Address(ID)
);
CREATE TABLE Rolodex_Email (
  Rolodex INT NOT NULL,
  Email INT NOT NULL,
  Name VARCHAR(256),
  CONSTRAINT FK_Rolodex_Email_Rolodex FOREIGN KEY (Rolodex) REFERENCES Rolodex(ID),
  CONSTRAINT FK_Rolodex_Email_Email FOREIGN KEY (Email) REFERENCES Email(ID)
);
