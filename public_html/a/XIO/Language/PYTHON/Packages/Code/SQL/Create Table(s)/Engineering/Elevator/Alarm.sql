CREATE TABLE Alarm (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Volume						FLOAT,						/*null or positive number*/
	Mount						VARCHAR(255),				/*Unknown*/
	Voltage						INT,						/*null or positive number*/
	Power_Type					VARCHAR(255)				/*Unknown*/
);