CREATE TABLE Cable (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Diameter				FLOAT,						/*null or greater than 0*/
	[Type]					VARCHAR(255)				/*Steel, etc.*/
);