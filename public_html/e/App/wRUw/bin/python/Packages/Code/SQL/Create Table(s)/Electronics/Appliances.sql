CREATE TABLE Appliance (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Appliance_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Appliance_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Dishwasher (
  Appliance INT,
  CONSTRAINT FK_Dishwasher_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
CREATE TABLE Toaster (
  Appliance INT,
  CONSTRAINT FK_Toaster_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
CREATE TABLE Refrigerator (
  Appliance INT,
  CONSTRAINT FK_Refrigerator_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
CREATE TABLE Microwave (
  Appliance INT,
  CONSTRAINT FK_Microwave_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
