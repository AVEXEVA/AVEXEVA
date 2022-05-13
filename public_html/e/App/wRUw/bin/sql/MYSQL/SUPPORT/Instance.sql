CREATE TABLE Instance (
  `ID`          INT NOT NULL AUTO_INCREMENT,
  `User`        INT,
  `Name`        VARCHAR(256),
  `Description` TEXT,
  `Start`       DATETIME DEFAULT CURRENT_TIMESTAMP,
  `End`         DATETIME,
  CONSTRAINT `PK_Instance_ID`   PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Instance_User` FOREIGN KEY (`User`)     REFERENCES `User`(`ID`)
);
