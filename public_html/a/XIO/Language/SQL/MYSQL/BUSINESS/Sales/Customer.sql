CREATE TABLE `Customer` (
	`ID`             INT NOT NULL identity(1, 1) PRIMARY KEY,
	`Name`           VARCHAR(256),
	`Description`    VARCHAR(MAX),
	`Street`         VARCHAR(256),
	`City`           VARCHAR(256),
	`State`					 VARCHAR(256),
	`Zip_Code` 			 VARCHAR(256),
	`Status` 				 VARCHAR(256),
	`Website` 			 VARCHAR(256),
  CONSTRAINT `PK_Customer_ID` PRIMARY KEY (`ID`)
);
