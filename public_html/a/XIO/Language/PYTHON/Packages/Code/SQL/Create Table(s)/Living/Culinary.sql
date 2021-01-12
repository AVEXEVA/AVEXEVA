CREATE TABLE Food (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Category INT NOT NULL,
  CONSTRAINT PK_Food_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Food_Category FOREIGN KEY (Category) REFERENCES Food_Category(ID)
);
CREATE TABLE Drink (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Category INT NOT NULL
);
CREATE TABLE Drink_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT,
  CONSTRAINT PK_Drink_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Drink_Category_Parent FOREIGN KEY (Parent) REFERENCES Drink_Category(ID)
);
CREATE TABLE Food_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Parent INT,
  CONSTRAINT PK_Food_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Food_Category_Parent FOREIGN KEY (Parent) REFERENCES Food_Category(ID)
);
CREATE TABLE Food_Ingredient (
  Food INT NOT NULL,
  Ingredient INT NOT NULL,
  CONSTRAINT Food_Ingredient_Food FOREIGN KEY (Food) REFERENCES Food(ID),
  CONSTRAINT Food_Ingredient_Ingredient FOREIGN KEY (Ingredient) REFERENCES Ingredient(ID)
);
CREATE TABLE Seafood (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Seafood_ID PRIMARY KEY (ID)
);
CREATE TABLE Shellfish (
  ID INT NOT NULL AUTO_INCREMENT,
  Seafood INT NOT NULL,
  CONSTRAINT PK_Shellfish_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Shellfish_Seafood FOREIGN KEY (Seafood) REFERENCES Seafood(ID)
);
