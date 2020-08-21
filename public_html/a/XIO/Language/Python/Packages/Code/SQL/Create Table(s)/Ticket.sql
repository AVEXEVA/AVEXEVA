CREATE TABLE [Ticket] (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]						VARCHAR(255),
	[User]						INT,
	[Customer]					INT,
	[Location]					INT				NOT NULL,
	[Job]						INT				NOT NULL,
	[Unit]						INT				NOT NULL,
	[Status]					VARCHAR(255),
	[Category]					INT,
	[Description]				VARCHAR(MAX),
	[Emergency]					BIT,
	[Service_Call]				BIT,
	[Shutdown]					BIT,
	[Entrapment]				BIT,
	[Resolution]				VARCHAR(MAX)	,
	[Created]					DATETIME,
	[Scheduled_Start]			DATETIME,
	[Scheduled_End]				DATETIME,
	[Scheduled_Time]			FLOAT,
	[En_Route]					DATETIME,
	[On_Site]					DATETIME,
	[Completed]					DATETIME,
	[Regular]					FLOAT,
	[Overtime]					FLOAT,
	[Doubletime]				FLOAT,
	[Night_Differential]		FLOAT,
	[Total]						FLOAT
);
