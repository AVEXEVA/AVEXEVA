CREATE TABLE Cell (
  ID    INT NOT NULL,
  Phone INT NOT NULL,
  Item  INT,
  Sim   INT,
  CONSTRAINT `PK_Cell_Phone_ID`     PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Cell_Phone_Phone`  FOREIGN KEY (`Phone`) REFERENCES `Phone`(`ID`)
  CONSTRAINT `FK_Cell_Phone_Item`   FOREIGN KEY (`Item`)  REFERENCES `Item`(`ID`),
  CONSTRAINT `FK_Cell_Phone_Sim`    FOREIGN KEY (`Sim`)   REFERENCES `Sim`(`ID`),
);
