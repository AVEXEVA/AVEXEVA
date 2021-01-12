CREATE TABLE Database_Service_Table (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Database_Service]				INT 			NOT NULL,
	[Table]					VARCHAR(255)	NOT NULL,
);

CREATE TABLE Database_Service_Synchronization (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Out]					INT 			NOT NULL,
	[In]					INT 			NOT NULL,
);
CREATE TABLE Database_Service_Translations (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255)
);
CREATE TABLE Database_Service_Translation (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Set]					INT 			NOT NULL,
	[Keyword]				VARCHAR(MAX),
	[Alias]					VARCHAR(MAX)
);
CREATE TABLE Database_Service_Constants (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255),
	[Regex]					VARCHAR(MAX),
	[Value]					VARCHAR(MAX)	
);
CREATE TABLE Database_Service_Table_Identifies_Column (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Out]					INT 			NOT NULL,
	[In]					INT 			NOT NULL
);
CREATE TABLE Database_Service_Ruleset (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255)
);
CREATE TABLE Database_Service_Rule (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Ruleset]				INT 			NOT NULL,
	[Equals]				VARCHAR(MAX)
);
CREATE TABLE Database_Service_Identity (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Out_Table]				INT 			NOT NULL,
	[Out_Column]			INT,
	[Out_Ruleset]			INT,
	[Out_Value] 			VARCHAR(MAX),
	[In_Table]				INT 			NOT NULL,
	[In_Column]				INT,
	[In_Ruleset]			INT,
	[In_Value] 				VARCHAR(MAX),
	[Priority]				INT
);
CREATE TABLE Database_Service_Identitication (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Out]					INT,
	[Outbound]				VARCHAR(MAX),
	[In]					INT,
	[Inbound]				VARCHAR(MAX)
);