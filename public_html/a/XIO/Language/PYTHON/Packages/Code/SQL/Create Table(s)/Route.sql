CREATE TABLE [Route] (
	ID							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				VARCHAR(MAX),
	[User]						INT
);
