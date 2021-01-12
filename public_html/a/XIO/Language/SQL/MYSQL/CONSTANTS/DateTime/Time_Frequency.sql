CREATE TABLE Time_Frequency (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Regex INT,
  CONSTRAINT PK_Time_Frequency_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Time_Frequency_Regex FOREIGN KEY (Regex) REFERENCES Regex(ID)
);
