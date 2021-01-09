CREATE TABLE Office_Computer (
  Desk INT NOT NULL,
  Computer INT NOT NULL,
  CONSTRAINT FK_Desk_Computer_Desk FOREIGN KEY (Desk) REFERENCES Desk(ID),
  CONSTRAINT FK_Desk_Computer_Computer FOREIGN KEY (Computer) REFERENCES Computer(ID)
);
