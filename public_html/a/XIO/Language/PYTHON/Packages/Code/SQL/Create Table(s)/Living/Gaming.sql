CREATE TABLE Video_Game (
  ID INT NOT NULL,
  Game INT NOT NULL,
  Software INT NOT NULL,
  CONSTRAINT PK_Video_Game_ID PRIMARY KEY (Video_Game)
  CONSTRAINT FK_Video_Game_Game FOREIGN KEY (Game) REFERENCES Game(ID),
  CONSTRAINT FK_Video_Game_Software FOREIGN KEY (Software) REFERENCES Software(ID)
);
