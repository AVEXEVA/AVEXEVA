CREATE TABLE Toe_Guard (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Retractable				BIT							/*Null or False or True*/
);