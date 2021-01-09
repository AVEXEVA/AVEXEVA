CREATE TABLE Power_Supply (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*
	Buzzer						BIT,						/*null or false or true*/
	Enclosure					VARCHAR(255),				/*Unknown*/
	Power_Type					VARCHAR(255)				/*Unknown*/
);