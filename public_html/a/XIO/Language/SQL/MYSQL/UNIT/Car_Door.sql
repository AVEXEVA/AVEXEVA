CREATE TABLE Car_Door (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Type]						VARCHAR(255),				/*Single, Bi-parting, Double, Triple or Peel-e*/
	[Operation]					VARCHAR(255)				/*Manual or Automatic*/
	[Hand]						VARCHAR(255)				/*Unknown*/
);