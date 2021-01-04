CREATE TABLE Safety_Jaw (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Rated_Car_Speed			INT,						/*null or greater than 0*/
	Rated_Load				FLOAT,						/*null or greater than 0*/
	Maximum_Tripping_Speed	INT							/*null or (greater than 0 and less than 5000)*/
);