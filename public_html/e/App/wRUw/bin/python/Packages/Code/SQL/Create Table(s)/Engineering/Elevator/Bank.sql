CREATE TABLE Bank (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Location]					INT				NOT NULL,	/*Foreign Key*/
	[Name]						VARCHAR(255),
	[Description]				TEXT
);
CREATE TABLE [Floor] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Location]					INT				NOT NULL,	/*Foreign Key*/
	[Name]						VARCHAR(255),
	[Height]					FLOAT,						/*greater than 0*/
	[Index]						INT,						/*index of floors*/
	[Description]				TEXT,
	[Lobby]						BIT,						/*null or false or true*/
	[Security_Desk]				BIT,						/*null or false or true*/
	[Directory]					BIT,						/*null or false or true*/
	[Intercom_System]			BIT							/*null or false or true*/
);
CREATE TABLE Access_Key_Switch (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Floor]						INT,						/*Foreign Key*/
	[Direction]					VARCHAR(255)				/*null or lower or upper*/
);
/*Relationships*/
CREATE TABLE Floor_has_Tenant (
	[Floor]						INT				NOT NULL,	/*Foreign Key*/
	Tenant						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Floor_Bank (
	[Floor]						INT,						/*Foreign Key*/
	[Bank]						INT,						/*Foreign Key*/
	[Lobby]						BIT
);
CREATE TABLE Hall_Station (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*Unknown*/
	[Composition]				VARCHAR(255),				/*Aluminum, Steel, etc.*/
	[Finish]					VARCHAR(255),				/*Unknown*/
	[Floor]						INT,						/*Foreign Key*/
	[Bank]						INT,						/*Foreign Key*/
	[Up]						BIT,						/*Null or False or True*/
	[Down]						BIT,						/*Null or False or True*/
	[LED]						BIT,						/*Null or False or True*/
	[Position_Height]			FLOAT						/*null or greater than 0*/
);
CREATE TABLE Fire_Service_Panel	(
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
	[Recall]					BIT,						/*null or false or true*/
	[Emergency_Route]			BIT							/*null or false or true*/
);
CREATE TABLE Destination_Dispatch (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Software					VARCHAR(255),
	Composition					VARCHAR(255),
	[Floor]						INT,						/*Foreign Key*/
	[Bank]						INT							/*Foreign Key*/
);
CREATE TABLE Car_Target_Indicator (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Destination_Dispatch		INT,						/*Foreign Key*/
	[Floor]						INT,						/*Foreign Key*/
	[Bank]						INT							/*Foreign Key*/
);
CREATE TABLE Hall_Lantern (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Floor]						INT,						/*Foreign Key*/
	[Type]						VARCHAR(255)				/*Discrete, Serial*/
);
CREATE TABLE Hall_Position_Indicator (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Floor]						INT,						/*Foreign Key*/
	[Bank]						INT,						/*Foreign Key*/
	[Digital]					BIT							/*Null or False or True*/
);
CREATE TABLE Hall_Door (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Floor]						INT,						/*Foreign Key*/
	[Composition]				VARCHAR(255),				/*Steel, Glass, etc.*/
	[Position]					VARCHAR(255)				/*Left, Right*/
);
CREATE TABLE Hall_Door_Hanger (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Hall_Door]					INT							/*Foreign Key*/
);
CREATE TABLE Buck (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,
	[Floor]						INT,						/*Foreign Key*/
	[Position]					VARCHAR(255),				/*Left, Right*/
	Opening_Width				INT,						/*null or greater than 0*/
	Opening_Length				INT,						/*null or greater than 0*/
	Opening_Height				INT,						/*null or greater than 0*/
	Jamb_Depth					INT							/*null or greater than 0*/
);
CREATE TABLE Header (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Floor]						INT							/*Foreign Key*/
);