CREATE TABLE Administrator (
  User INT,
  System INT,
  CONSTRAINT FK_Administrator_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_Administrator_System FOREIGN KEY (System) REFERENCES System(ID)
);
CREATE TABLE Administrator_System (
  Administrator INT,
  System INT,
  CONSTRAINT FK_Administrator_System_User FOREIGN KEY (Administrator) REFERENCES User(ID),
  CONSTRAINT FK_Administrator_System_System FOREIGN KEY (System) REFERENCES System(ID)
);
