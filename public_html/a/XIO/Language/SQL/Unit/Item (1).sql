CREATE TABLE Item (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Type]					VARCHAR(255)				DEFAULT(NULL),
	Product 				INT 						DEFAULT(NULL),
	Device 					INT 						DEFAULT(NULL),
	Serial		 			VARCHAR(255) 				DEFAULT(NULL),
	Vendor_Purchase_Order	VARCHAR(255)				DEFAULT(NULL),
	Blueprint				VARCHAR(MAX)				DEFAULT(NULL),
	Condition	 			VARCHAR(255)				DEFAULT(NULL),
	Created 				DATETIME 		NOT NULL 	DEFAULT(CURRENT_TIMESTAMP),
	Manufactured 			DATETIME 					DEFAULT(NULL),
	Installed 				DATETIME 					DEFAULT(NULL),
	Decomissioned 			DATETIME 					DEFAULT(NULL),
	[Notes]					TEXT 						DEFAULT(NULL),
	[Image]					VARCHAR(MAX)				DEFAULT(NULL),
	[Image_Type]			VARCHAR(255)				DEFAULT(NULL),
	[Length]				VARCHAR(255)				DEFAULT(NULL),
	[Width]					VARCHAR(255)				DEFAULT(NULL),
	[Height]				VARCHAR(255)				DEFAULT(NULL)
);
CREATE TABLE Item_Image (
	ID 						INT 			NOT NULL 	identity(1, 1)		PRIMARY KEY,
	[Item]					INT				NOT NULL,
	[Image]					VARCHAR(MAX)				DEFAULT(NULL),
	[Image_Type]			VARCHAR(255)				DEFAULT(NULL)
);