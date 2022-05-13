CREATE TABLE `Agent`
  (
    `ID`        INT           NOT NULL  AUTO_INCREMENT,
    `Name`      VARCHAR(32)   NOT NULL,
    `Version`   VARCHAR(64)   NOT NULL,
    `Namespace` VARCHAR(128)  NOT NULL,
    CONSTRAINT `PK_Agent_ID` PRIMARY KEY (`ID`)
  ) ENGINE = MyISAM;
