CREATE TABLE Capacitor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Case]						VARCHAR(255),				/*Unknown*/
	[Voltage]					VARCHAR(255),				/*null or greater than or equal to 0*/
	[Voltage_Type]				VARCHAR(255),				/*Unknown*/
	[Microfarad]				VARCHAR(255)				/*Unknown*/
);