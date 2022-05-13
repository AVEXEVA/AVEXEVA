CREATE TABLE Cab (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Composition					VARCHAR(255),				/*Steel, Glass, etc*/
	Finish						VARCHAR(255),				/*Steel, Glass, etc.*/
	Smoke_Detector				BIT,						/*null or false or true*/
	Hatch						BIT,						/*null or false or true*/
	Emergency_Lighting			BIT,						/*null or false or true*/
	Safety_Mirror				BIT,						/*null or false or true*/
	Security_Camera				BIT,						/*null or false or true*/
	Fan							BIT							/*null or false or true*/
);
CREATE TABLE Handrail (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Cab]						INT,						/*Foreign Key*/
	[Position]					VARCHAR(255)				/*Left, Back or Right*/
);
CREATE TABLE Fan (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Cab]						INT,						/*Foreign Key*/
	Voltage						INT,						/*null or greater than or equal to 0*/
	Powered_By					VARCHAR(255),				/*Unknown*/
	Speeds						VARCHAR(255)				/*Unknown*/
);
CREATE TABLE Safety_Mirror (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Cab]						INT							/*Foreign Key*/
);
CREATE TABLE Security_Camera (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Cab]						INT							/*Foreign Key*/
);
CREATE TABLE Visual_Display (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Cab]						INT,						/*Foreign Key*/
	Touchable					BIT							/*null or false or true*/
);
CREATE TABLE Speaker (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Cab]						INT,						/*Foreign Key*/
);