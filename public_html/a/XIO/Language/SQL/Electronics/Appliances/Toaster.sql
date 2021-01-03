CREATE TABLE Toaster (
  Appliance INT,
  CONSTRAINT FK_Toaster_Appliance FOREIGN KEY (Appliance) REFERENCES Appliance(ID)
);
