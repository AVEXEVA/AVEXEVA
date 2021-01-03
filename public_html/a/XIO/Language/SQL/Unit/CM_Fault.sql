CREATE TABLE CM_Fault (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Location]				VARCHAR(255),
	[Unit]					VARCHAR(255),
	[Date]					DATETIME,
	[Fault]					VARCHAR(MAX),
	[Status]				VARCHAR(MAX),
	Num1					INT,
	Num2					INT,
	Num3					INT
);
CREATE TABLE CM_Unit (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Elev_ID]				INT,
	[Location]				VARCHAR(255),
	[Unit]					VARCHAR(255)
);