CREATE TABLE Dishwasher (
  Appliance INT,
  CONSTRAINT FK_Dishwasher_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
