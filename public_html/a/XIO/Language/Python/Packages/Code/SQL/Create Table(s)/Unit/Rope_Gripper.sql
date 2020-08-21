CREATE TABLE [Rope_Gripper] (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	Rated_Speed				INT,						/*Any Number*/
	Tripping_Speed			INT,						/*Any Number*/
	Rated_Load				FLOAT,						/*Any Number*/
	System_Load				FLOAT,						/*Any Number*/
	Contact_Ratings			VARCHAR(255),				/*Unknown*/
	Door_Zone				VARCHAR(255),				/*Unknown*/
	Roping					VARCHAR(255)				/*1:1, 2:1, 4:1*/

);