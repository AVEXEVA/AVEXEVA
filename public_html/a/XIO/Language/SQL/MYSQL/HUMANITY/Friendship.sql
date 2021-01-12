CREATE TABLE Friendship (
  Friendor INT NOT NULL,
  Friendee INT NOT NULL,
  CONSTRAINT FK_Friend_Friendor FOREIGN KEY (Friendor) REFERENCES Person(ID),
  CONSTRAINT FK_Friend_Friendee FOREIGN KEY (Friendee) REFERENCES Person(ID)
);
