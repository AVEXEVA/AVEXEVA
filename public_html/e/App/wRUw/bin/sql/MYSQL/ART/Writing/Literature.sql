CREATE TABLE Literature (
  `Art`   INT NOT NULL,
  `Book`  INT,
  CONSTRAINT `FK_Literature_Art`  FOREIGN KEY (`Art`)   REFERENCES `Art`(`ID`),
  CONSTRAINT `FK_Literature_Book` FOREIGN KEY (`Book`)  REFERENCES `Book`(`ID`)
);
