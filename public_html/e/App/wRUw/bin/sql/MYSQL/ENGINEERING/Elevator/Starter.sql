CREATE TABLE Starter (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item						INT				NOT NULL,	/*Foreign Key*/
	[Soft_Starter]				BIT,						/*null or false or true*/
	[Over_Voltage_Protection]	BIT,						/*null or false or true*/
	[Under_Voltage_Protection]	BIT,						/*null or false or true*/
	[Horsepower]				FLOAT						/*null or greater than or equal to 0*/
);