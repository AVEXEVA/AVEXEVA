CREATE TABLE `Index_Column` (
  `Index` INT NOT NULL,
  `Column` INT NOT NULL,
  CONSTRAINT FK_Index_Column_Index FOREIGN KEY (`Index`) REFERENCES `Index`(ID),
  CONSTRAINT FK_Index_Column_Column FOREIGN KEY (`Column`) REFERENCES `Column`(ID)
);
