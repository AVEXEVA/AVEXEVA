CREATE TABLE Building (
  `ID`      INT NOT NULL AUTO_INCREMENT,
  `Land`    INT,
  `Address` INT,
  CONSTRAINT `PK_Building_ID`       PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Building_Land`     FOREIGN KEY (`Land`)    REFERENCES `Land`(`ID`),
  CONSTRAINT `FK_Building_Address`  FOREIGN KEY (`Address`) REFERENCES `Address`(`ID`)
);
