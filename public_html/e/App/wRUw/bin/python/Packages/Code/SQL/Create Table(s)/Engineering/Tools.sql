CREATE TABLE Tool (
  ID INT NOT NULL AUTO_INCREMENT,
  Item INT,
  CONSTRAINT PK_Tool_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Tool_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Hammer (
  Tool INT,
  CONSTRAINT FK_Hammer_Tool FOREIGN KEY (Tool) REFERENCES Tool(Tool)
);
CREATE TABLE Screwdriver (
  Tool INT,
  Type INT,
  Size FLOAT,
  CONSTRAINT FK_Screwdriver_Tool FOREIGN KEY (Tool) REFERENCES Tool(ID),
  CONSTRAINT FK_Screwdriver_Type FOREIGN KEY (Type) REFERENCES Screwdriver_Type(ID)
);
CREATE TABLE Screwdriver_Type (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Screwdriver_Type_ID PRIMARY KEY (ID)
);
CREATE TABLE Wrench (
  Tool INT,
  CONSTRAINT PK_Wrench_Tool FOREIGN KEY (Tool) REFERENCES Tool(ID)
);
CREATE TABLE Socket (
  Tool INT,
  Size INT,
  CONSTRAINT PK_Socket_Tool FOREIGN KEY (Tool) REFERENCES Tool(ID),
  CONSTRAINT FK_Socket_Size FOREIGN KEY (Socket_Size) REFERENCES Socket_Size(ID)
);
CREATE TABLE Socket_Size (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Socket_Size_ID PRIMARY KEY (ID)
);
CREATE TABLE Ladder (
  Tool INT,
  Height INT,
  Width INT,
  CONSTRAINT FK_Ladder_Tool FOREIGN KEY (Tool) REFERENCES Tool(ID),
  CONSTRAINT FK_Ladder_Height FOREIGN KEY (Height) REFERENCES Size(ID),
  CONSTRAINT FK_Ladder_Width FOREIGN KEY (Width) REFERENCES Size(ID)
);
