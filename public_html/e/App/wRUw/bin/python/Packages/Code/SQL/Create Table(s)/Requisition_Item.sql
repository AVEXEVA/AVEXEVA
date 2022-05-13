CREATE TABLE Requisition_Item (
	Requisition							INT				NOT NULL,
	Item_Description					TEXT			NOT NULL,
	Quantity							INT				DEFAULT(1)
);
