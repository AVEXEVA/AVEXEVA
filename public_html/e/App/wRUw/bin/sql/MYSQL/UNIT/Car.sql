CREATE TABLE Car (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
);
CREATE TABLE Car_Station (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Car]					INT,						/*Foreign Key*/
	No_Smoking				BIT,						/*Null or False or True*/
	Gong					BIT,						/*Null or False or True*/
	Handicap_Chimes			BIT,						/*Null or False or True*/
	Electrical_Outlet		BIT,						/*Null or False or True*/
	Service_Cabinet			BIT,						/*Null or False or True*/
	Intercom				BIT,						/*Null or False or True*/
	Composition				VARCHAR(255)				/*Steel, Aluminum, Glass, etc.*/
);
CREATE TABLE Car_Top (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
);
CREATE TABLE Car_Station_has_Button (
	Car_Station				INT				NOT NULL,	/*Foreign Key*/
	Button					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Button (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Label						VARCHAR(255),
	Functionality				TEXT,
	Braille						BIT,						/*null or false or true*/
	[Length]					FLOAT,						/*null or greater than 0*/
	[Width]						FLOAT,						/*null or greater than 0*/
	[Height]					FLOAT						/*null or greater than 0*/
);
CREATE TABLE Sling (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE [Platform] (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Sling]					INT,						/*Foreign Key*/
	Fireproofing			BIT,						/*null or False or True*/
	Bolster					VARCHAR(255)				/*No idea what this is*/
);
CREATE TABLE Crosshead (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Sling]					INT							/*Foreign Key*/
);
CREATE TABLE Post (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Position]				VARCHAR(255)				/*Bottom Left, Left, Top Left, Top, Top Right, Right, Bottom Right, Bottom*/
);
CREATE TABLE Safety_Jaw (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Platform]				INT,						/*Foreign Key*/
	Rated_Car_Speed			INT,						/*null or greater than 0*/
	Rated_Load				FLOAT,						/*null or greater than 0*/
	Maximum_Tripping_Speed	INT							/*null or (greater than 0 and less than 5000)*/
);
CREATE TABLE Roller_Guide (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,
	Rail_Size					FLOAT,			/*greater than 0*/
	Blade_Width					FLOAT,			/*greater than 0*/
	[Hand]						VARCHAR(255)	/*Left, Right*/
);
/*Relationships*/
CREATE TABLE Roller_Guide_has_Roller (
	Roller_Guide				INT,			/*Foreign Key*/
	Roller						INT				/*Foreign Key*/
);
CREATE TABLE Roller (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,
	Diameter					INT,			/*greater than 0*/
	Face						INT,			/*greater than 0*/
	Bearings					INT				/*???*/
);
CREATE TABLE Guide_Shoe (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,
	[Hand]						VARCHAR(255),	/*Left, Right*/
	Maximum_Car_Speed			INT,			/*greater than 0*/
	Rail_Size					INT				/*greater than 0*/
);