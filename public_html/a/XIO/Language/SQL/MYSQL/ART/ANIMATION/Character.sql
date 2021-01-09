CREATE TABLE Character (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `File`        INT NOT NULL,
  CONSTRAINT `PK_Character_ID`    PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Character_File`  FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
CREATE TABLE Character_Piece (
  `Character` INT NOT NULL,
  `Piece`     INT NOT NULL,
  `File`      INT,
  CONSTRAINT `FK_Character_Piece_Character` FOREIGN KEY (`Character`) REFERENCES `Character`(`ID`),
  CONSTRAINT `FK_Character_Piece_Piece`     FOREIGN KEY (`Piece`)     REFERENCES `Piece`(`ID`),
  CONSTRAINT `FK_Character_Piece_File`      FOREIGN KEY (`File`)      REFERENCES `File`(`ID`)
);
CREATE TABLE Piece (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  CONSTRAINT `PK_Piece_ID` PRIMARY KEY (`ID`)
);
