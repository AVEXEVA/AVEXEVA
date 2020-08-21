CREATE TABLE GPS (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Phone						VARCHAR(255),
	Latitude					FLOAT,
	Longitude					FLOAT,
	Altitude					FLOAT,
	[TimeStamp]					DATETIME
);
