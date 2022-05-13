CREATE TABLE Timeline (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Entity					VARCHAR(255)	NOT NULL,
	[Entity_ID]				INT				NOT NULL,
	[Action]				VARCHAR(255)	NOT NULL,
	[Description]			VARCHAR(MAX),
	Time_Stamp				DATETIME		NOT NULL
);
