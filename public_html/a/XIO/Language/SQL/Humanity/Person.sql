CREATE TABLE `Person` (
  `ID`          INT NOT NULL,
  `Entity`      INT NOT NULL,
  `First_Name`  VARCHAR(255),
  `Middle_Name` VARCHAR(255),
  `Last_Name`   VARCHAR(255),
  `Email`       INT,
  `Home`        INT,
  `Work`        INT,
  `Cell`        INT,
  `Office`      INT,
  PRIMARY KEY (ID),
  CONSTRAINT `FK_Person_Entity` FOREIGN KEY (`Entity`)  REFERENCES `Entity`(`ID`)
  CONSTRAINT `FK_Person_Email`  FOREIGN KEY (`Email`)   REFERENCES `Email`(`ID`),
  CONSTRAINT `FK_Person_Home`   FOREIGN KEY (`Home`)    REFERENCES `Address`(`ID`),
  CONSTRAINT `FK_Person_Work`   FOREIGN KEY (`Work`)    REFERENCES `Address`(`ID`),
  CONSTRAINT `FK_Person_Cell`   FOREIGN KEY (`Cell`)    REFERENCES `Phone`(`ID`),
  CONSTRAINT `FK_Person_Office` FOREIGN KEY (`Office`)  REFERENCES `Phone`(`ID`)
);
``
