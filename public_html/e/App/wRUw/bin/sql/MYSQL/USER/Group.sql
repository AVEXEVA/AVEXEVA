CREATE TABLE `Group` (
    `ID`          INT NOT NULL AUTO_INCREMENT,
    `Creator`     INT NOT NULL,
    `Name`        VARCHAR(256) NOT NULL,
    `Description` TEXT,
    `Created`     DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `PK_Group_ID`      PRIMARY KEY (`ID`),
    CONSTRAINT `FK_Group_Creator` FOREIGN KEY (`Creator`)   REFERENCES `User`(`ID`)
) ENGINE = MyISAM;
