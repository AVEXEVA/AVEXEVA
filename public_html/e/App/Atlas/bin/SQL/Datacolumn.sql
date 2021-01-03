CREATE TABLE Datacolumn (
	[ID] int IDENTITY(1,1) NOT FOR REPLICATION NOT NULL,
	[Table] int NOT NULL,
	[Name] varchar(256) NOT NULL,
	[Display] bit NOT NULL DEFAULT(0)
);
