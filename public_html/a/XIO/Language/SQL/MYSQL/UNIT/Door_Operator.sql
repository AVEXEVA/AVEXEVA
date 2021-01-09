CREATE TABLE Door_Operator (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Speed						VARCHAR(255),				/*Foreign Key*/
	Hand						VARCHAR(255)
);
CREATE TABLE Door_Operator_Speed (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Speed						VARCHAR(255)	NOT NULL
);