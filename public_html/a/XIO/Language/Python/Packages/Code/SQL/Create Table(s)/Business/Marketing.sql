CREATE TABLE Newsletter (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Newsletter_ID PRIMARY KEY (ID)
);
CREATE TABLE Campaign (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Campaign_ID PRIMARY KEY (ID)
);
CREATE TABLE Promotion (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Promotion_ID PRIMARY KEY (ID)
);
CREATE TABLE Campaign_Promotion (
  Campaign INT NOT NULL,
  Promotion INT NOT NULL,
  CONSTRAINT FK_Campaign_Promotion_Campaign FOREIGN KEY (Campaign) REFERENCES Campaign(ID),
  CONSTRAINT FK_Campaign_Promotion_Promotion FOREIGN KEY (Promotion) REFERENCES Promotion(ID)
);
CREATE TABLE Advertisement (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Advertisement_ID PRIMARY KEY (ID)
);
CREATE TABLE Campaign_Advertisement (
  Campaign INT NOT NULL,
  Advertisement INT NOT NULL,
  CONSTRAINT FK_Campaign_Advertisement_Campaign FOREIGN KEY (Campaign) REFERENCES Campaign(ID),
  CONSTRAINT FK_Campaign_Advertisement_Advertisement FOREIGN KEY (Advertisement) REFERENCES Advertisement(ID)
);
