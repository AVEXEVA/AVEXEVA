CREATE TABLE Service_Column_Connection (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Internal_Column]		INT 		 	NOT NULL,
	[External_Column]		INT 			NOT NULL,
	[Inbound_Ruleset]		INT,
	[Outbound_Ruleset]		INT
);