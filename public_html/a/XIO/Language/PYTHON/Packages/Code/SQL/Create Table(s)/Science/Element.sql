CREATE TABLE Element (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Abbreviation VARCHAR(2),
  Description TEXT,
  Valence_Electrons INT,
  CONSTRAINT PK_Molecule_ID PRIMARY KEY (ID)
);
