CREATE TABLE [Permission] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[User]						VARCHAR(255)	NOT NULL,
	[Entity]					VARCHAR(255)	NOT NULL,
	[Access]					INT				DEFAULT(0)
);
