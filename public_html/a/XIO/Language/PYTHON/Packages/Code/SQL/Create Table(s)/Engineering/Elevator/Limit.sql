CREATE TABLE [Limit] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*Zone, Normal or Final*/
	Adjustable					BIT,						/*null or false or true*/
	Contacts					INT							/*null or positive whole number*/
);
CREATE TABLE Limit_has_Contact (
	Limit						INT				NOT NULL,	/*Foreign Key*/
	Contact						INT				NOT NULL	/*Foreign Key*/
);