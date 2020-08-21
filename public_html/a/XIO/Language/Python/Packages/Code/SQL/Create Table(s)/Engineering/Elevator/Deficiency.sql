CREATE TABLE Deficiency (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Job							INT				NOT NULL,
	Elevator_Part				INT				NOT NULL,
	Remedy						INT				NOT NULL,
	Violation					INT				NOT NULL
);