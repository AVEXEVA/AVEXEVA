CREATE TABLE `LI` (
  `HTML` INT,
  CONSTRAINT `FK_LI_HTML` FOREIGN KEY (`HTML`) REFERENCES `HTML`(`ID`)
);
