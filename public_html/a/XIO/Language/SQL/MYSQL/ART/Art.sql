CREATE TABLE Art (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `Owner`       INT,
  CONSTRAINT `PK_Art_ID`    PRIMARY KEY (`ID`)
  CONSTRAINT `FK_Art_Owner` FOREIGN KEY (`Owner`) REFERENCES `User`(`ID`)
);
CREATE TABLE Art_Reference (
  `Art`       INT NOT NULL,
  `Reference` INT NOT NULL,
  CONSTRAINT  `FK_Art_Reference_Art`        FOREIGN KEY (`Art`)       REFERENCES `Art`(`ID`),
  CONSTRAINT  `FK_Art_Reference_Reference`  FOREIGN KEY (`Reference`) REFERENCES `Art`(`ID`)
);
