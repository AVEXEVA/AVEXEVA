CREATE TABLE [Governor] (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Type]					VARCHAR(255),				/*Centrifugal or Instantanous*/
	Cable_Size				VARCHAR(255),				/*Unknown*/
	Cable_Condition			VARCHAR(255),				/*Unknown*/
	Cable_Length			VARCHAR(255),				/*Unknown*/
	Rated_Speed				INT,						/*null or greater than 0*/
	Trip_Speed				INT,						/*null or (greater than 0 and less than 10000)*/
	WaterTight				BIT,						/*null or false or true*/
	DustTight				BIT,						/*null or false or true*/
	Guard					BIT,						/*null or false or true*/
	Pull_Through_Minimum	VARCHAR(255),				/*Unknown*/
	Pull_Through_Maximum	VARCHAR(255),				/*Unknown*/
	[Hand]					VARCHAR(255),				/*Unknown*/
	Tripper					VARCHAR(255),				/*Unknown*/
	Link					VARCHAR(255),				/*Unknown*/
	Spring					VARCHAR(255),				/*Unknown*/
	Adjuster				VARCHAR(255)				/*Unknown*/
);
CREATE TABLE Governor_has_Tripper ();
CREATE TABLE Governor_has_Link ();
CREATE TABLE Governor_has_Spring ();
CREATE TABLE Governor_has_Adjuster ();