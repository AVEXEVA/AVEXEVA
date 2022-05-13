CREATE TABLE Preventative_Maintenance_Abstract (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255),
	[Observe]				BIT,
	[Inspect]				BIT,
	[Clean]					BIT,
	[Description]			VARCHAR(MAX),
	[Months]				INT
);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Car Operation', 1, 0, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Controller / Reg.', 1, 1, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Power Drive', 1, 1, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Motor Generator / Motor', 1, 1, 1, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Machine & Brake', 1, 1, 1, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Lamps & Buzzers', 1, 1, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Position Device', 1, 1, 1, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Traveling Cable', 1, 1, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Hangers & Restrictors', 0, 1, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Door Reverse Device', 0, 1, 1, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Pits & Wheel House', 0, 1, 1, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Hydro Unit Jack', 0, 1, 0, 1);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Car Top', 0, 0, 1, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Governor', 0, 1, 1, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Door Operator & Monitoring', 0, 1, 1, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Rope Gripper', 1, 0, 1, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Cables & Fastenings', 1, 0, 0, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('All Sheaves', 0, 1, 1, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Compensator Switch', 0, 1, 1, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Guide Shoes & Rollers', 1, 1, 0, 3);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Dispatching', 1, 0, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Door Interlocks', 1, 0, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Hatch Doors & Guides', 1, 1, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Car & Hall Buttons', 0, 0, 1, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Position Indicators', 1, 1, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Limit Switches', 1, 1, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Safeties & Buffers', 1, 1, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Hall Lanterns Gongs', 1, 0, 0, 12);
INSERT INTO Preventative_Maintenance_Abstract([Name], [Observe], [Inspect], [Clean], [Months]) VALUES('Brake Maintenance', 1, 1, 0, 12);
CREATE TABLE Preventative_Maintenance (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Unit]					INT				NOT NULL,
	[Ticket]				INT				NOT NULL,
	[Task]					INT				NOT NULL,
	[User]					INT				NOT NULL,
	[Timestamp]				DATETIME		NOT NULL,
	[Notes]					VARCHAR(MAX)
);
