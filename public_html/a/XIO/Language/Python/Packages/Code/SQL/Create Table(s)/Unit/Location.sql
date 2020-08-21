CREATE TABLE [Location] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Customer]					INT				NOT NULL,
	[Name]						VARCHAR(255),
	[Description]				VARCHAR(MAX),
	[Street]					VARCHAR(255),
	[City]						VARCHAR(255),
	[State]						VARCHAR(255),
	[Zip_Code]					VARCHAR(255),
	[Latitude]					FLOAT,
	[Longitude]					FLOAT,
	[Route]						INT
);
