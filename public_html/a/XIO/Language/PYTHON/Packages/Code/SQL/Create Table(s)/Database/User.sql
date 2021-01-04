CREATE TABLE [User] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255)	NOT NULL,
	[Password]					VARCHAR(255)	NOT NULL,
	[Email]						VARCHAR(255)	NOT NULL,
	[First_Name]				VARCHAR(255),
	[Middle_Name]				VARCHAR(255),
	[Last_Name]					VARCHAR(255)
);
