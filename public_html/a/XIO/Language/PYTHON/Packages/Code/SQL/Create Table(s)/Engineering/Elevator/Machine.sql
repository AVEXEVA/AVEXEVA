CREATE TABLE Machine (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,
	[Power_Type]			VARCHAR(255),
	Input_Voltage			FLOAT,
	Input_Amperage			FLOAT,
	RPM						FLOAT,
	Horsepower				FLOAT,
	Roping					VARCHAR(255),				/*1:1, 2:1 or 4:1*/
	Drum_Size				FLOAT,						/*Unknown*/
	Geared					BIT,
	MRL						BIT,
	Mounting_Pad			BIT,
	Cable_Size				VARCHAR(255),
	Cable_Length			VARCHAR(255),
	Cable_Quantity			INT,
	Cable_Condition			VARCHAR(255)
);

INSERT INTO Device.dbo.Machine(Item) VALUES(12);

CREATE TABLE Machine_has_Brake (
	Machine					INT				NOT NULL,	/*Foreign Key*/
	Brake					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Brake (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*Drum, Disc, Spring, Magnet, Pin, Multiple Disk, Magnetic Particle*/
	Voltage						INT,						/*0 to infinity*/
	Amperage					INT,						/*0 to infinity*/
	Wattage						INT,						/*0 to infinity*/
	Contacts					INT,						/*0 to infinity*/
	Coil						INT,						/*0 to infinity*/
	Plates						INT,						/*0 to infinity*/
	Arms						INT							/*0 to infinity*/
);
/*Relationships*/
CREATE TABLE Brake_has_Contact (
	Brake						INT				NOT NULL,	/*Foreign Key*/
	Contact						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Brake_has_Coil (
	Brake						INT				NOT NULL,	/*Foreign Key*/
	Coil						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Brake_has_Plate (
	Brake						INT				NOT NULL,	/*Foreign Key*/
	Plate						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Brake_has_Arm (
	Brake						INT				NOT NULL,	/*Foreign Key*/
	Arm							INT				NOT NULL	/*Foreign Key*/
);