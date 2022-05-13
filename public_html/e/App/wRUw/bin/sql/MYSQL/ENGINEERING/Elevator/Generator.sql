CREATE TABLE Generator (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item					INT				NOT NULL,	/*Foreign Key*/
	[Power_Input]			VARCHAR(255),
	Input_Voltage			FLOAT,						/*Any Number*/
	Input_Amperage			FLOAT,						/*Any Number*/
	[Power_Output]			VARCHAR(255),
	Output_Voltage			FLOAT,						/*Any Number*/
	Output_Amperage			FLOAT,						/*Any Number*/
	Wattage_Rating			VARCHAR(255),				/*????*/
	RPM						FLOAT,						/*Any Number*/
	Horsepower				FLOAT,						/*Any Number*/
	Windings				VARCHAR(MAX)
);