CREATE TABLE Contract_Category_Item (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Contract]				INT,
	[Unit]					INT,
	Elevator_Part			INT,
	[Condition]				VARCHAR(2),
	Remedy					INT,
	Covered					BIT
);
CREATE TABLE Reviewed_Category_Item (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Deficiency				INT,
	Approval				BIT,
	Responsibility			VARCHAR(255),
	[User]					INT,
	Proposal				INT
);
CREATE TABLE Category_Elevator_Part (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	External_ID				INT,
	[Name]					VARCHAR(255),
	[Grouping]				INT
);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(1,'Emergency Stop Switch',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(2,'Alarm System',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(3,'Car Enclosure',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(4,'Side Emergency Exit',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(5,'Car Door / Gate',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(6,'Car door / Gate Contact',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(7,'Door reopening device',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(8,'Car floor to landing sill',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(9,'Car floor  ',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(10,'Car door gibs',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(11,'Car button station',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(12,'Car lighting',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(13,'Emergency lighting',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(14,'Car mirror',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(15,'Certificate frame',1);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(16,'Hoistway doors',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(17,'Hoistway door gibs',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(18,'Hoistway door reinforcements',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(19,'Hoistway door safety retainer',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(20,'Vision panel',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(21,'Interlocks',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(22,'Parking device',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(23,'Hall button station',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(24,'Indicators',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(25,'Door safety retainer',2);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(26,'Top emergency exit cover',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(27,'Governor release carrier',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(28,'Door hangers and connectors',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(29,'Door operator',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(30,'Normal Limits',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(31,'Final limits',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(32,'Guide shoes/ Roller guides',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(33,'Counterweight',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(34,'Hoistway',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(35,'Electrical wiring',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(36,'Pipes and ducts',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(37,'Overhead & Deflector sheave',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(38,'Traveling cable & junction',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(39,'Car top',3);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(40,'Machine room',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(41,'Machine room door',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(42,'Controller-selector',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(43,'Reverse phase relay',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(44,'Traction sheave',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(45,'Governor  ',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(46,'Governor switch',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(47,'Drum',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(48,'Pump unit',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(49,'Drum machine limit switch',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(50,'Generator',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(51,'Slack rope switch',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(52,'Hoist cables',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(53,'Governor ropes',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(54,'Car counterweight rope',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(55,'Drum counterweight rope',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(56,'Hoist machine',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(57,'Hoist motor',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(58,'Worm/Gear/Bearing',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(59,'Machine brake',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(60,'Lighting machine space',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(61,'Machine disconnect switch',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(62,'Commutator',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(63,'Motor brushes',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(64,'NYC Device #',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(65,'Unintended car movement',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(66,'Emergency brake/rope gripper',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(67,'Communication',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(68,'Maintenance log',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(69,'Code date plate',4);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(70,'Pit',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(71,'Pit light',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(72,'Pit stop switch',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(73,'Car guide-rails & brackets',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(74,'CWT Guide-rails & brackets',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(75,'Buffers',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(76,'Car safety & tail rope',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(77,'Underside platform',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(78,'Tension Weight',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(79,'Comp/chains/ropes/switch',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(80,'Counterweight runby',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(81,'Counterweight runby signage',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(82,'Plunger gripper',5);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(83,'Fire shutters',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(84,'Skirt switch',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(85,'Skirt deflection device',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(86,'Comb plate/Comb plate teeth',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(87,'Landing plate/ Impact switches',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(88,'Handrails/ Handrail safeties',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(89,'Step/thread',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(90,'Key switch',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(91,'Emergency stop button',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(92,'Decking and ballistrades',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(93,'Ceiling guards',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(94,'Deck barricades',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(95,'Internal safeties',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(96,'Safety Signage',6);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(97,'Entire device',7);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(98,'Current five year tag',7);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(99,'Current one year tag',7);
INSERT INTO Portal.dbo.Category_Elevator_Part(External_ID, [Name], [Grouping]) VALUES(100,'Miscellaneous',7);
CREATE TABLE Category_Elevator_Part_Grouping (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255)
);
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('Inside Car');
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('Outside Hoistway');
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('Top of Car');
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('Machine Room');
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('Pit');
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('Escalator / Moving Walk');
INSERT INTO Portal.dbo.Category_Elevator_Part_Grouping([Name]) VALUES('All Types');
CREATE TABLE Category_Violation_Condition (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	External_ID				VARCHAR(2),
	[Name]					VARCHAR(255)
);
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('A','Altered');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('AA','Carbon Buildup in/on');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('B','Insufficient');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('BB','Expired Tag for');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('C','Padlocked');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('D','Unsecured');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('E','Rubbing');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('F','Lost motion of');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('G','Improper Fuses for');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('H','Worn');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('I','Damaged');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('J','Misaligned');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('K','Rusted');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('L','Defective');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('M','Missing');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('N','By-passed');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('O','Dirty');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('P','No Means of access to');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('Q','Unguarded');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('R','Illegal');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('S','Not fire retardant');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('T','Unlabeled');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('U','Device not tagged');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('V','Not Level');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('W','Unlocked');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('X','Inoperative');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('Y','Oil Leak in/on');
INSERT INTO Portal.dbo.Category_Violation_Condition([External_ID], [Name]) VALUES('Z','Water Leak in/on');
CREATE TABLE Category_Remedy (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	External_ID				INT,
	[Name]					VARCHAR(255)
);
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(1,'Adjust');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(2,'Clean');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(3,'Install Guards on');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(4,'Patch');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(5,'Perform & file test for');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(6,'Properly secure');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(7,'Provide');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(8,'Regroove');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(9,'Remove');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(10,'Repair');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(11,'Replace');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(12,'Reshackle');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(13,'Seal');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(14,'Shorten');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(15,'Tag Device for');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(16,'Provide means of access to');
INSERT INTO Portal.dbo.Category_Remedy([External_ID], [Name]) VALUES(17,'Re-inspection required for');
