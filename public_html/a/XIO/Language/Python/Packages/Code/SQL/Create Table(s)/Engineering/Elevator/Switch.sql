CREATE TABLE [Switch] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255)				/*Unknown*/
	[Key]						INT,						/*Foreign Key*/
	ASME_Complaint				BIT							/*null or false or true*/
);