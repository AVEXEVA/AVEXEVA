CREATE TABLE Footer (
  `HTML` INT,
  CONSTRAINT `FK_Footer_HTML` FOREIGN KEY (`HTML`) REFERENCES `HTML`(`ID`)
);