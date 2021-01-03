CREATE TABLE Button (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Label						VARCHAR(255),
	Functionality				TEXT,
	Braille						BIT,						/*null or false or true*/
	[Length]					FLOAT,						/*null or greater than 0*/
	[Width]						FLOAT,						/*null or greater than 0*/
	[Height]					FLOAT						/*null or greater than 0*/
);