CREATE TABLE Product (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Type]					VARCHAR(255)				DEFAULT(NULL),
	[Name]					VARCHAR(255)				DEFAULT(NULL),
	[Description]			TEXT						DEFAULT(NULL),
	[Manufacturer]			VARCHAR(255)				DEFAULT(NULL),
	[Model]					VARCHAR(255)				DEFAULT(NULL),
	[Model_Number]			VARCHAR(255)				DEFAULT(NULL),
	[Notes]					VARCHAR(255)				DEFAULT(NULL),
	[Image]					Image						DEFAULT(NULL)
);