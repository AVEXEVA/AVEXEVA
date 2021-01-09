CREATE TABLE Software (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `Product`     INT,
  CONSTRAINT `PK_Software_ID`       PRIMARY KEY (`ID`)
  CONSTRAINT `FK_Software_Product`  FOREIGN KEY (`Product`) REFERENCES `Product`(`ID`)
);
