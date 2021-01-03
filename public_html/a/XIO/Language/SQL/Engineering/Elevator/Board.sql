CREATE TABLE Board (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Parent					INT,						/*Foreign Key of Board's Parent Item*/
	[Type]					VARCHAR(255),				/*CPU Board, Digital Board, Supply Board, Direct Flight Board, Load Weigh Board, Read Door Board, HVIB, Relay Board, Display Board, Message Board, Power Board, Main Board, Remote Board, PCB, PCL, Control Board, Inverter PCB*/
	Digital					BIT							/*Null or False or True*/
);