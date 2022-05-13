CREATE TABLE Work_Item (
	ID							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Work_Order					INT,
	[Item]						INT,
	[Consumed]					BIT
);
