CREATE TABLE Building (
  ID INT NOT NULL AUTO_INCREMENT,
  Address INT,
  CONSTRAINT PK_Building_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Building_Address FOREIGN KEY (Address) REFERENCES Address(ID)
);
CREATE TABLE Apartment (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Building INT NOT NULL,
  CONSTRAINT PK_Apartment_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Apartment_Building FOREIGN KEY (Building) REFERENCES Building(ID)
);
CREATE TABLE Tenant (
  ID INT NOT NULL AUTO_INCREMENT,
  Floor   INT NOT NULL,
  Entity INT NOT NULL,
  CONSTRAINT PK_Tenant_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Tenant_Floor FOREIGN KEY (Floor) REFERENCES Floor(ID),
  CONSTRAINT FK_Tenant_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Floor (
  ID INT NOT NULL AUTO_INCREMENT,
  Location INT NOT NULL,
  Name VARCHAR(256),
  CONSTRAINT FK_Floor_ID       PRIMARY KEY (ID),
  CONSTRAINT FK_Floor_Location FOREIGN KEY (Location) REFERENCES Location(ID)
);
CREATE TABLE Bank (
  ID INT NOT NULL AUTO_INCREMENT,
  Location INT NOT NULL,
  Name VARCHAR(256),
  PRIMARY KEY (ID),
  CONSTRAINT FK_Bank_Location FOREIGN KEY (Location) REFERENCES Location(ID)
);
CREATE TABLE Furniture (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT NOT NULL,
  CONSTRAINT PK_Furniture_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Furniture_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
