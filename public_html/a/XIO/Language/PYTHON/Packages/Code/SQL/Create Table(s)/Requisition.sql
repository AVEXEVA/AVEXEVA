CREATE TABLE Requisition (
	ID 									INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[User]								INT				NOT NULL,
	[Date]								DATETIME		NOT NULL,
	[Required]							DATETIME		NOT NULL,
	[Location]							INT				NOT NULL,
	[DropOff]							INT				NOT NULL,
	[Unit]								INT				NOT NULL,
	[Job]								INT				NOT NULL,
	[Shutdown]							BIT,
	[ASAP]								BIT,
	[Rush]								BIT,
	LSD									BIT,
	FRM									BIT
);
