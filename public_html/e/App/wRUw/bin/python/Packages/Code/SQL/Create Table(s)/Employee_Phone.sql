CREATE TABLE Employee_Phone (
	ID 							INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Employee_ID					INT				NOT NULL,
	Phone_Number				VARCHAR(255)
);
