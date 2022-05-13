CREATE TABLE Administrator (
  User INT,
  System INT,
  CONSTRAINT FK_Administrator_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_Administrator_System FOREIGN KEY (System) REFERENCES System(ID)
);
