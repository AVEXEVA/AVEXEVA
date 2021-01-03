CREATE TABLE Microwave (
  Appliance INT,
  CONSTRAINT FK_Microwave_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
