CREATE TABLE Voice_Box (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Volume						INT							/*Less than 10000*/
);