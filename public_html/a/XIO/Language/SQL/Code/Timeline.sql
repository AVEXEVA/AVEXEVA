CREATE TABLE Timeline (
	`ID` 						INT NOT NULL identity(1, 1) PRIMARY KEY,
	`Entity` 				INT NOT NULL,
	`Action`				VARCHAR(256) NOT NULL,
	`Description` 	VARCHAR(MAX),
	`Time_Stamp` 		DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT `PK_Timeline_ID`	 		PRIMARY KEY (`ID`),
	CONSTRAINT `PK_Timeline_Entity` REFERENCES `Entity`(`ID`)
);
