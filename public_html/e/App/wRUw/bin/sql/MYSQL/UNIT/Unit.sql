CREATE TABLE [Unit] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				VARCHAR(MAX),
	[Type]						VARCHAR(255),
	[Building_ID]				VARCHAR(255),
	[City_ID]					VARCHAR(255)

);
