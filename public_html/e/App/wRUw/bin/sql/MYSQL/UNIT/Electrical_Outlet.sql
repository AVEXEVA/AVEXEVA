CREATE TABLE Electrical_Outlet (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Parent]					INT,						/*Foreign Key of Electrical Outlet's Parent ID*/
	[Type]						VARCHAR(255),				/*Unknown*/
	[GFCI]						BIT							/*null or false or true*/
);