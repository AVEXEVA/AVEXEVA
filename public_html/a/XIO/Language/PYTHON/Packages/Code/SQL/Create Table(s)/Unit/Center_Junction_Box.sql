CREATE TABLE Center_Junction_Box (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Position				VARCHAR(255)				/*Height in Feet*/
);