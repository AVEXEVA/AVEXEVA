CREATE TABLE [Platform] (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Fireproofing			BIT,						/*null or False or True*/
	Bolster					VARCHAR(255)				/*No idea what this is*/
);