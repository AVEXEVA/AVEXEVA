CREATE TABLE [Customer] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				VARCHAR(MAX),
	[Street]					VARCHAR(255),
	[City]						VARCHAR(255),
	[State]						VARCHAR(255),
	[Zip_Code]					VARCHAR(255),
	[Status]					VARCHAR(255),
	[Website]					VARCHAR(255)
);
