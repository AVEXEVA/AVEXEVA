CREATE TABLE [Edge] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Beams						INT,						/*null or INT*/
	Beam_Seperation				FLOAT,						/*null or greater than 0*/
	LED_Indicators				INT,						/*positive whole numbers*/
	Nudgable					BIT,						/*null or false or true*/
	Nudging						BIT,						/*null or flase or true*/
	Infared_Pulse_Ratio			VARCHAR(255)				/*Unknown*/
);