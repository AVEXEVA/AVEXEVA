CREATE TABLE Contention }(
  Person1 INT NOT NULL,
  Person2 INT NOT NULL,
  CONSTRAINT FK_Contention_Person1 FOREIGN KEY (Person1) REFERENCES Person(ID),
  CONSTRAINT FK_Contention_Person2 FOREIGN KEY (Person2) REFERENCES Person(ID)
);
