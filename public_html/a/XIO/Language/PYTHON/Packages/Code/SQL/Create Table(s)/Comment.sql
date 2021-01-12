CREATE TABLE [Comment] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Entity]					VARCHAR(255)	NOT NULL,
	[Entity_ID]					INT				NOT NULL,
	[User]						INT				NOT NULL,
	[Text]						VARCHAR(MAX)	NOT NULL,
	[Time_Stamp]				DATETIME		NOT NULL
);
