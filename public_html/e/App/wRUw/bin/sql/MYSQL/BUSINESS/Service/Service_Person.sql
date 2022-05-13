CREATE TABLE Service_Person (
  Service INT NOT NULL,
  Person INT NOT NULL,
  CONSTRAINT FK_Service_Person_Service FOREIGN KEY (Service) REFERENCES Service(ID),
  CONSTRAINT FK_Service_Person_Person FOREIGN KEY (Person) REFERENCES Person(ID)
);
