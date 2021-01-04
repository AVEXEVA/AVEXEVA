CREATE TABLE Work_Order (
	ID							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Job]						INT				NOT NULL,
	[User]						INT,
	[Unit]						INT,
	[Task]						INT,
	[Duration]					FLOAT,
	[Status]					VARCHAR(255)
);
