CREATE TABLE `License` (
  `ID`      INT NOT NULL,
  `System`  INT NOT NULL,
  `Name`    VARCHAR(255),
  `Price`   FLOAT,
  CONSTRAINT `PK_License_ID`      PRIMARY KEY (`ID`),
  CONSTRAINT `FK_License_System`  FOREIGN KEY (`System`) REFERENCES `System`(`ID`)
) ENGINE = MyISAM;
CREATE TABLE `User_License` (
  `User`    INT NOT NULL,
  `License` INT NOT NULL,
  `Price`   FLOAT,
  `Paid`    FLOAT,
  `Start`   DATETIME,
  `End`     DATETIME,
  CONSTRAINT FK_User_License_User     FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_User_License_License  FOREIGN KEY (License) REFERENCES License(ID)
) ENGINE = MyISAM;
