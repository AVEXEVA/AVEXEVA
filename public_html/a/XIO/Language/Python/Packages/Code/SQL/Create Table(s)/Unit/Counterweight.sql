CREATE TABLE Counterweight (
	ID						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Item]					INT				NOT NULL,
	[Counterweight_Frame]	INT,						/*Foreign Key*/
	[Sheave]				INT,						/*Foreign Key*/
	[Guide]					VARCHAR(255),				/*null or Guide_Shoe or Roller_Guide*/
	[Buffer]				INT,
	[Compensation_Chain]	INT
);
CREATE TABLE Counterweight_has_Guide (
	Counterweight			INT				NOT NULL,
	Guide					INT				NOT NULL
);
CREATE TABLE Counterweight_Frame (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE [Weight] (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);
CREATE TABLE Compensation_Chain (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL	/*Foreign Key*/
);