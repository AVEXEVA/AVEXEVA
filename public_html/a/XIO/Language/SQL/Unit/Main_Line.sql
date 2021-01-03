CREATE TABLE Main_Line (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Voltage					FLOAT,						/*null or greater than or equal to 0*/
	Amperage				FLOAT,						/*null or greater than or equal to 0*/
	Maintained_By			VARCHAR(255),				/*Main Line Maintained By*/
	Locked_Out				BIT,
	Tagged_Out				BIT
);