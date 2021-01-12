CREATE TABLE Door_Hanger (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Hand]						VARCHAR(255)				/*Left or Right*/
);