CREATE TABLE Company (
  `ID` INT              NOT NULL AUTO_INCREMENT,
  `Entity`              INT NOT NULL,
  `Name`                VARCHAR(256),
  `Parent`              INT NOT NULL,
  `Founded`             DATETIME,
  `Created`             DATETIME,
  `Headquarters`        INT,
  `Accounts_Payable`    INT,
  `Accounts_Recievable` INT,
  `Human_Resources`     INT,
  `Engineering`         INT,
  `Legal`               INT,
  CONSTRAINT `PK_Company_ID`                  PRIMARY KEY (`ID`)
  CONSTRAINT `PK_Entity_ID`                   FOREIGN KEY (`Entity`)              REFERENCES `Entity`(`ID`),
  CONSTRAINT `FK_Company_Headquarters`        FOREIGN KEY (`Headquarters`)        REFERENCES `Department`(`ID`),
  CONSTRAINT `FK_Company_Accounts_Payable`    FOREIGN KEY (`Accounts_Payable`)    REFERENCES `Department`(`ID`),
  CONSTRAINT `FK_Company_Accounts_Recievable` FOREIGN KEY (`Accounts_Recievable`) REFERENCES `Department`(`ID`),
  CONSTRAINT `FK_Company_Human_Resources`     FOREIGN KEY (`Human_Resources`)     REFERENCES `Department`(`ID`),
  CONSTRAINT `FK_Company_Engineering`         FOREIGN KEY (`Engineering`)         REFERENCES `Department`(`ID`),
  CONSTRAINT `FK_Company_Legal`               FOREIGN KEY (`Legal`)               REFERENCES `Department`(`ID`)
);
