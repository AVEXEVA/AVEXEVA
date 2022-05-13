CREATE TABLE Relay (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Type]					VARCHAR(255)				/*Solid State, Solid-State Contactor, Buchholz, Force-Guided Contacts, Overload Protection, Safety, Time Delay, Machine Tool, Mercury-Wetted, Latching, Reed, Polarized, Coaxial, Contactor*/
);