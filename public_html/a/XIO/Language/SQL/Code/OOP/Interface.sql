CREATE TABLE Interface (
  `ID`    INT NOT NULL AUTO_INCREMENT,
  `Name`  VARCHAR(256),
  `File`  INT,
  CONSTRAINT `PK_Interface_ID`    PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Interface_File`  FOREIGN KEY (`File`) REFERENCES `File`(`ID`)
);
