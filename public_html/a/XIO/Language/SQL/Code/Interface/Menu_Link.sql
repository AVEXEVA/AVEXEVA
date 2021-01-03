CREATE TABLE Menu_Link (
  `Menu` INT NOT NULL,
  `Link` INT NOT NULL,
  CONSTRAINT `FK_Menu_Link_Menu` FOREIGN KEY (`Menu`) REFERENCES `Menu`(`ID`),
  CONSTRAINT `FK_Menu_Link_Link` FOREIGN KEY (`Link`) REFERENCES `Link`(`ID`)
);
