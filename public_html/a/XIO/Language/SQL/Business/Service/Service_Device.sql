CREATE TABLE Service_Device (
  Service INT NOT NULL,
  Device INT NOT NULL,
  CONSTRAINT FK_Service_Device_Service FOREIGN KEY (Service) REFERENCES Service(ID),
  CONSTRAINT FK_Service_Device_Device FOREIGN KEY (Device) REFERENCES Device(ID)
);
