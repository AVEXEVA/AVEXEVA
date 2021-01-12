CREATE TABLE Controller (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255)				/*Unknown*/
);
CREATE TABLE [Computer] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Operating_System			INT,						/*Foreign Key*/
	Software					INT,
	[Username]					VARCHAR(255),				/*string length is greater than 0*/
	[Password]					VARCHAR(255)				/*any string*/
);
CREATE TABLE Computer_controls_Controller (
	Computer					INT				NOT NULL,
	Controller					INT				NOT NULL
);
CREATE TABLE Drive (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*any string*/	
	Input_Voltage				FLOAT,						/*null or greater than or equal to 0*/
	Input_Amperage				FLOAT,						/*null or greater than or equal to 0*/
	Output_Voltage				FLOAT,						/*null or greater than or equal to 0*/
	Output_Amperage				FLOAT,						/*null or greater than or equal to 0*/
	Horsepower					FLOAT						/*null or greater than or equal to 0*/
);
CREATE TABLE Drive_Type (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				TEXT
);
INSERT INTO Drive_Type(Name) VALUES('Motor Generator (MG)');
INSERT INTO Drive_Type(Name) VALUES('Silicon Controller Rectifiers (SCR)');
INSERT INTO Drive_Type(Name) VALUES('Pulse Width Modulation (PWM)');
INSERT INTO Drive_Type(Name) VALUES('Variable Voltage Variable Frequency (VVVF)');
INSERT INTO Drive_Type(Name) VALUES('Regenrative Drives (Regen)');
CREATE TABLE Board (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Parent						INT,						/*Foreign Key of Board's Parent Item*/
	[Type]						INT,						/*Foreign Key*/
	Digital						BIT							/*Null or False or True*/
);
CREATE TABLE Board_Type (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				TEXT
);
INSERT INTO Board_Type(Name) VALUES('CPU Board');
INSERT INTO Board_Type(Name) VALUES('Digital Board');
INSERT INTO Board_Type(Name) VALUES('Supply Board');
INSERT INTO Board_Type(Name) VALUES('Direct Flight Board');
INSERT INTO Board_Type(Name) VALUES('Load Weigh Board');
INSERT INTO Board_Type(Name) VALUES('Read Door Board');
INSERT INTO Board_Type(Name) VALUES('HVIB');
INSERT INTO Board_Type(Name) VALUES('Relay Board');
INSERT INTO Board_Type(Name) VALUES('Display Board');
INSERT INTO Board_Type(Name) VALUES('Message Board');
INSERT INTO Board_Type(Name) VALUES('Power Board');
INSERT INTO Board_Type(Name) VALUES('Main Board');
INSERT INTO Board_Type(Name) VALUES('Remote Board');
INSERT INTO Board_Type(Name) VALUES('PCB');
INSERT INTO Board_Type(Name) VALUES('PCL');
INSERT INTO Board_Type(Name) VALUES('Control Board');
INSERT INTO Board_Type(Name) VALUES('Inverter PCB');
CREATE TABLE Controller_has_Board (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Board						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Semiconductor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*Unknown*/
	[Maximum_Amperage]			FLOAT,						/*null or greater than 0*/
	[Maximum_Voltage]			FLOAT,						/*null or greater than 0*/
	[Circuit_Configuration]		VARCHAR(255)				/*Unknown*/
);
CREATE TABLE Controller_has_Semiconductor (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Semiconductor				INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Contact (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Contact (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Contact						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Rectifier (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Rectifier (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Rectifier					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Contactor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Contactor (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Contactor					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Transformer (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Transformer (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Transformer					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Capacitor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Case]						VARCHAR(255),				/*Unknown*/
	[Voltage]					VARCHAR(255),				/*null or greater than or equal to 0*/
	[Voltage_Type]				VARCHAR(255),				/*Unknown*/
	[Microfarad]				VARCHAR(255)				/*Unknown*/
);
CREATE TABLE Controller_has_Capicator (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Capicator					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Resistor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Resistor (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Resistor					INT				NOT NULL,	/*Foreign Key*/
);
CREATE TABLE Coil (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Coil (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Coil						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Terminal (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_Terminal (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Terminal					INT				NOT NULL,	/*Foreign Key*/
);
CREATE TABLE SCR (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Controller_has_SCR (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	SCR							INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Operating_System (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				TEXT
);
INSERT INTO Operating_System(Name) VALUES('Windows');
INSERT INTO Operating_System(Name) VALUES('Windows DOS');
INSERT INTO Operating_System(Name) VALUES('Windows 95');
INSERT INTO Operating_System(Name) VALUES('Windows 97');
INSERT INTO Operating_System(Name) VALUES('Windows 2000');
INSERT INTO Operating_System(Name) VALUES('Windows XP');
INSERT INTO Operating_System(Name) VALUES('Windows ME');
INSERT INTO Operating_System(Name) VALUES('Windows 7');
INSERT INTO Operating_System(Name) VALUES('Windows 10');
INSERT INTO Operating_System(Name) VALUES('Linux');
INSERT INTO Operating_System(Name) VALUES('Apple');
INSERT INTO Operating_System(Name) VALUES('Other');
CREATE TABLE Software (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[Description]				TEXT
);
CREATE TABLE Fault (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Code						VARCHAR(255),				/*Unknown*/
	[Name]						VARCHAR(255),
	[Description]				TEXT
);
CREATE TABLE Controller_has_Fault (
	Controller					INT				NOT NULL,	/*Foreign Key*/
	Fault						INT				NOT NULL	/*Foreign Key*/
);