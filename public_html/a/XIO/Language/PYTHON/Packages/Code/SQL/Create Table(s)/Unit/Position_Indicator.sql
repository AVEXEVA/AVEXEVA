CREATE TABLE Position_Indicator (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Type]					VARCHAR(255)				/*Unknown*/
	Composition				VARCHAR(255),				/*Unknown*/
	Color					VARCHAR(255)				/*Unknown*/
);