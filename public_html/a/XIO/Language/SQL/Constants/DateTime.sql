CREATE TABLE Time_Lapse (
  ID INT NOT NULL AUTO_INCREMENT,
  Start DATETIME,
  End DATETIME,
  CONSTRAINT PK_Time_Lapse_ID PRIMARY KEY (ID)
);
CREATE TABLE Event (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Time_Lapse INT,
  CONSTRAINT PK_Event_ID PRIMARY KEY (ID)
);
CREATE TABLE Date_Frequency (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Regex INT,
  CONSTRAINT PK_Date_Frequency_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Date_Frequency_Regex FOREIGN KEY (Regex) REFERENCES Regex(ID)
);
CREATE TABLE Time_Frequency (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Regex INT,
  CONSTRAINT PK_Time_Frequency_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Time_Frequency_Regex FOREIGN KEY (Regex) REFERENCES Regex(ID)
);