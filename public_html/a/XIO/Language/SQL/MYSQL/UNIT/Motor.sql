CREATE TABLE Motor (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	Parent						INT,						/*Foreign Key of Motor's Parent Item ID*/
	[Power_Type]				VARCHAR(255),				/*Alternating Current or Direct Current*/
	Voltage						FLOAT,						/*null or greater than or equal to 0*/
	Amperage					FLOAT,						/*null or greater than or equal to 0*/
	Horsepower					FLOAT,						/*null or greater than or equal to 0*/
	RPM							FLOAT,						/*null or greater than or equal to 0*/
	Direct_Drive				BIT,						/*null or false or true*/
	Permanent_Magnet			BIT,						/*null or false or true*/
	Geared						BIT,						/*null or false or true*/
	Right_Angle					BIT,						/*null or false or true*/
	Induction					BIT,						/*null or false or true*/
	Helical						BIT							/*null or false or true*/
);
CREATE TABLE Worm (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Diameter				FLOAT,						/*null or greater than 0*/
	Gear_Size				FLOAT,						/*null or greater than 0**/
	Shaft_Size				FLOAT						/*null or greater than 0*/
);
CREATE TABLE Armature (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Motor					INT,						/*Foreign Key*/
	Interpoles				INT							/*null or greater than 0*/
);
CREATE TABLE Armature_Brush (	
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Commutator (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Armature				INT							/*Foreign Key*/
);
CREATE TABLE Interpole (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Armature				INT							/*Foreign Key*/
);
CREATE TABLE Magnetic_Field (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Interpole				INT							/*Foreign Key*/
);