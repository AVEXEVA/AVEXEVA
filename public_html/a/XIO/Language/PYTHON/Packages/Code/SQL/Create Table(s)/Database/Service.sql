CREATE TABLE Service (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255)	NOT NULL,
	[Type]					VARCHAR(255)	NOT NULL,
	[Canonical]				VARCHAR(255)	NOT NULL,
	[Description]			VARCHAR(255),
	[Username]				VARCHAR(255),
	[Password]				VARCHAR(255),
	[Client]				VARCHAR(255),
	[Secret]				VARCHAR(MAX),
	[Address]				VARCHAR(255),
	[Database]				VARCHAR(255)
);
CREATE TABLE [Table] (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Service]				INT 			NOT NULL,
	[Name]					VARCHAR(255)	NOT NULL,
	[Description]			VARCHAR(MAX)
);
CREATE TABLE [Column] (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Table]					VARCHAR(255)	NOT NULL,
	[Name]					VARCHAR(255),
	[Datatype]				VARCHAR(255),
	[Default]				VARCHAR(255),
	[Position]				INT,
	[Nullable]				BIT
);
CREATE TABLE [Identity] (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Out_Service]			INT 			NOT NULL,
	[Out_Table]				INT 			NOT NULL,
	[Out_Column]			INT,
	[Out_Value] 			VARCHAR(MAX),
	[In_Service]			INT 			NOT NULL,
	[In_Table]				INT 			NOT NULL,
	[In_Column]				INT,
	[In_Value] 				VARCHAR(MAX),
	[Priority]				INT
);
CREATE TABLE [Sync] (
	[ID] 					INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Out_Service]			INT 			NOT NULL,
	[Out_Table]				INT 			NOT NULL,
	[Out_Column]			INT,
	[Out_Value] 			VARCHAR(MAX),
	[In_Service]			INT 			NOT NULL,
	[In_Table]				INT 			NOT NULL,
	[In_Column]				INT,
	[In_Value] 				VARCHAR(MAX),
	[Type]					VARCHAR(255)
);