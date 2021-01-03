CREATE TABLE Year (
  ID INT NOT NULL,
  Number INT,
  CONSTRAINT PK_Year_ID PRIMARY KEY (ID)
);
CREATE TABLE Month (
  ID INT NOT NULL,
  Number INT,
  CONSTRAINT PK_Month_ID PRIMARY KEY (ID)
);
CREATE TABLE Day (
  ID INT NOT NULL,
  Number INT,
  CONSTRAINT PK_Day_ID PRIMARY KEY (ID)
);
