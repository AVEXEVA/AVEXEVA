CREATE TABLE Service_Column (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Table]					VARCHAR(255)	NOT NULL,
	[Column]				VARCHAR(255),
	[Datatype]				VARCHAR(255),
	[Default]				VARCHAR(255),
	[Position]				INT,
	[Nullable]				BIT
);