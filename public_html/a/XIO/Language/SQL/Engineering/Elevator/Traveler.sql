CREATE TABLE Traveler (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,
	Braid					BIT,						/*null or False or True*/
	Jacket					BIT							/*null or False or True*/
);
/* Relationships */
CREATE TABLE Traveler_has_Center_Core (
	Traveler				INT,						/*Foreign Key*/
	Center_Core 			INT							/*Foreign Key*/
);
CREATE TABLE Center_Core (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Type]					VARCHAR(255)				/*Steel, Jute*/
);
CREATE TABLE Traveler_has_Insulated_Conductors (
	Traveler				INT,						/*Foreign Key*/
	Insulated_Conductor		INT							/*Foreign Key*/
);
CREATE TABLE Insulated_Conductor (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Traveler_has_Shielded_Pairs (
	Traveler				INT,						/*Foreign Key*/
	Shielded_Pair			INT							/*Foreign Key*/
);
CREATE TABLE Shielded_Pair (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Traveler_has_Coaxial_Cable (
	Traveler				INT,						/*Foreign Key*/
	Coaxial_Cable			INT							/*Foreign Key*/
);
CREATE TABLE Coaxial_Cable (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Traveler_has_Filler (
	Traveler				INT,						/*Foreign Key*/
	Filler					INT							/*Foreign Key*/
);
CREATE TABLE Filler (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);