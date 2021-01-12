CREATE TABLE Service_Table_Connection (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Internal_Table]		INT 		 	NOT NULL,
	[External_Table] 		INT 			NOT NULL
);