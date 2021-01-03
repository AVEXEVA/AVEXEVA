CREATE TABLE `Task` (
	`ID`           INT NOT NULL identity(1, 1) PRIMARY KEY,
	`Name`         VARCHAR(255)	NOT NULL,
	`Template`     INT,
	`Parent`       INT,
	`Precedent`    INT,
	`Description`  VARCHAR(MAX),
	`Duration`     FLOAT,
	`Frequency`    VARCHAR(255),
  CONSTRAINT `PK_Task_ID`         PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Task_Parent`     FOREIGN KEY (`Parent`)    REFERENCES `Task`(`ID`),
  CONSTRAINT `FK_Task_Precedent`  FOREIGN KEY (`Precedent`) REFERENCES `Task`(`ID`)
);
