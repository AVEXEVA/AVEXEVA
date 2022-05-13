CREATE TABLE Saddle (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Composition					VARCHAR(255),				/*Steel, etc.*/
	[Speed]						VARCHAR(255)				/*Single Speed, etc.*/
);