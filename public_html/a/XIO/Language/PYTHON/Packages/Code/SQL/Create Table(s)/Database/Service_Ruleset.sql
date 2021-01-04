CREATE TABLE Database_Service_Ruleset (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255)
);
CREATE TABLE Database_Service_Rules (
	[Ruleset] 				INT 			NOT NULL,
	[Rule]					INT 			NOT NULL
);
CREATE TABLE Database_Service_Rule (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Logic]					VARCHAR(MAX)	NOT NULL
);