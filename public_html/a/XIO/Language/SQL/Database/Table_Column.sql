CREATE TABLE Table_Column (
  `Table` INT NOT NULL,
  `Column` INT NOT NULL,
  CONSTRAINT FK_Table_Column_Table FOREIGN KEY (`Table`) REFERENCES `Table`(ID),
  CONSTRAINT FK_Table_Column_Column FOREIGN KEY (`Column`) REFERENCES `Column`(ID)
);
