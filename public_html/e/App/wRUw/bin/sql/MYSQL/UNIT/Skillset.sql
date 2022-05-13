CREATE TABLE Skillset (
	[User]					INT 			NOT NULL,
	Skill					INT				NOT NULL,
	Proficiency				INT				NOT NULL
);
CREATE TABLE Skill (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255),
	[Description]			VARCHAR(MAX)
);
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Heavy Labor');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Rigging');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Measuring');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Safety');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Debugging');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Wiring');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Inspecting');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Surveying');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Maintaining');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Engineering');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Masonry');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Weilding');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Carpentry');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Demolition');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Blueprinting');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Pipe Fitting');
INSERT INTO Portal.dbo.Skill([Name]) VALUES('Electrical Engineering');
CREATE TABLE Proficiency (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Name]					VARCHAR(255),
	[Description]			VARCHAR(MAX)
);
INSERT INTO Portal.dbo.Proficiency([Name]) VALUES('Beginner');
INSERT INTO Portal.dbo.Proficiency([Name]) VALUES('Intermediate');
INSERT INTO Portal.dbo.Proficiency([Name]) VALUES('Advanced');
INSERT INTO Portal.dbo.Proficiency([Name]) VALUES('Expert');
INSERT INTO Portal.dbo.Proficiency([Name]) VALUES('Master');
