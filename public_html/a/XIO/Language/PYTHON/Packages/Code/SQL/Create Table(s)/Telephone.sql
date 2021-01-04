CREATE TABLE Telephone (
	ID 									INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	Item								INT				NOT NULL,	/*Foreign Key*/
	Powered_By							VARCHAR(255),				/*Unknown*/
	[Type]								VARCHAR(255),				/*Unknown*/
	Braille								BIT,						/*null or false or true*/
	Site_Identification					BIT,						/*null or false or true*/
	Programmable_Number					BIT,						/*null or false or true*/
	Phone_Number						VARCHAR(255),				/*valid phone number*/
	ADA_Complaint						BIT,						/*null or false or true*/
	National_Elevator_Code_Complaint	BIT,						/*null or false or true*/
	NYC_DOB_Complaint					BIT							/*null or false or true*/
);