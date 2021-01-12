CREATE TABLE Refrigerator (
  Appliance INT,
  CONSTRAINT FK_Refrigerator_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
