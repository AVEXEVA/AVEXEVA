CREATE TABLE Asset (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Product INT,
  Category INT NOT NULL,
  Value FLOAT,
  CONSTRAINT PK_Asset_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Asset_Category FOREIGN KEY (Category) REFERENCES Asset_Category(ID)
);
CREATE TABLE Asset_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Desription TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Asset_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Asset_Category_Parent FOREIGN KEY (Parent) REFERENCES Asset_Category(ID)
);
