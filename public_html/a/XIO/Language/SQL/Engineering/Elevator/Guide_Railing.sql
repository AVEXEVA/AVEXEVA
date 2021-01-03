CREATE TABLE Guide_Railing (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
/*Relationships*/
CREATE TABLE Guide_Railing_has_Fish_Plate (
	Fish_Plate					INT,						/*Foreign Key*/
	[Lower_Guide_Railing]		INT,						/*Foreign Key*/
	[Upper_Guide_Railing]		INT							/*Foreign Key*/
);
CREATE TABLE Fish_Plate (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Bolts						INT							/*greater than 0*/
);