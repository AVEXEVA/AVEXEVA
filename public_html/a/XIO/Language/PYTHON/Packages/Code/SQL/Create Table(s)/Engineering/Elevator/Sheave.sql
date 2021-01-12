CREATE TABLE Sheave (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Parent					INT,						/*Foreign Key*/
	Deflector				BIT,						/*null or false or true*/
	Diameter				INT,						/*greater than 0*/
	Grooves					INT,						/*greater or equal to 1*/
	Groove_Diameter			INT,						/*greater than 0*/
	Groove_Type				VARCHAR(255)				/*U-Shape, V-Shape*/
);
CREATE TABLE Sheave_has_Cable (
	Sheave					INT				NOT NULL,	/*Foreign Key*/
	Cable					INT				NOT NULL
);