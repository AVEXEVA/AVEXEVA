CREATE TABLE Selector (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,
	[Type]					VARCHAR(255)				/*Tape, Magnetic, Machine, Digital*/
);