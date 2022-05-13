CREATE TABLE Hall_Button (
  ID INT NOT NULL AUTO_INCREMENT,
  Button INT,
  Bank INT,
  Floor INT,
  CONSTRAINT PK_Hall_Button_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Hall_Button_Button FOREIGN KEY (Button) REFERENCES Button(ID),
  CONSTRAINT FK_Hall_Button_Bank FOREIGN KEY (Bank) REFERENCES Bank(ID),
  CONSTRAINT FK_Hall_Button_Floor FOREIGN KEY (Floor) REFERENCES Floor(ID)
);
CREATE TABLE Destination_Dispatch (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Destination_Dispatch PRIMARY KEY (ID),
  CONSTRAINT FK_Destination_Dispatch_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Hall_Lantern (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Hall_Lantern_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Hall_Lantern_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Car_Target_Indicator (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  Elevator INT,
  Floor INT,
  CONSTRAINT PK_Car_Target_Indicator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Car_Target_Indicator_Item FOREIGN KEY (Item) REFERENCES Item(ID)
  CONSTRAINT FK_Car_Target_Indicator_Elevator FOREIGN KEY (Elevator) REFERENCES Elevator(ID),
  CONSTRAINT FK_Car_Target_Indicator_Floor FOREIGN KEY (Floor) REFERENCES Floor(ID)
);
CREATE TABLE Position_Indicator (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Position_Indicator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Position_Indicator_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
