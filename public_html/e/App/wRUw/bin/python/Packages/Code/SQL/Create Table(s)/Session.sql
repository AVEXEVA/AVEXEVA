CREATE TABLE [Session] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[User]						VARCHAR(255)	NOT NULL,
	[Hash]						VARCHAR(255)	NOT NULL,
	[Time_Stamp]				VARCHAR(255)	NOT NULL
);
