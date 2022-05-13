CREATE TABLE Buffer (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,
	[Type]					VARCHAR(255)	DEFAULT(NULL),
	Stroke_Range			VARCHAR(255)	DEFAULT(NULL),
	Minimum_Load			VARCHAR(255)	DEFAULT(NULL),
	Maximum_Load			VARCHAR(255)	DEFAULT(NULL),
	Maximum_Car_Speed		VARCHAR(255)	DEFAULT(NULL)
);