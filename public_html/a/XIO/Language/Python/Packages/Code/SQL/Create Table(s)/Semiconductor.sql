CREATE TABLE Semiconductor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*Unknown*/
	[Maximum_Amperage]			FLOAT,						/*null or greater than 0*/
	[Maximum_Voltage]			FLOAT,						/*null or greater than 0*/
	[Circuit_Configuration]		VARCHAR(255)				/*Unknown*/
);