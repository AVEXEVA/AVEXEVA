CREATE TABLE Compensating_Cable (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,
	Jacket					BIT,						/*null or False or True*/
	Chain					BIT,						/*null or False or True*/
	Inner_Insulation		BIT							/*null or False or True*/
);