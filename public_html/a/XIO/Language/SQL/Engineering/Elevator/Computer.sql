CREATE TABLE Computer (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,
	Operating_System		VARCHAR(255),
	[Username]				VARCHAR(255),
	[Password]				VARCHAR(255)
);