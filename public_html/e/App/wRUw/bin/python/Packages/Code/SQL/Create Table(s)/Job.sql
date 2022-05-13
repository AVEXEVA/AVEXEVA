CREATE TABLE [Job] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Parent]					INT,
	[Customer]					INT,
	[Location]					INT,
	[Supervisor]				INT,
	[Status]					VARCHAR(255),
	[Description]				VARCHAR(MAX),
	[Start]						DATETIME,
	[End]						DATETIME
);
