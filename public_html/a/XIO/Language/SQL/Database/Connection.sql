CREATE TABLE Connection (
	ID 							  INT 				   NOT NULL 	identity(1, 1)		PRIMARY KEY,
	User              INT            NOT NULL,
  Hash              VARCHAR(255)   NOT NULL,
  Created           DATETIME       NOT NULL,
  Last              DATETIME       NOT NULL
);
