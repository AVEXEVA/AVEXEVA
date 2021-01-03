CREATE TABLE Comment (
  `ID`      INT   NOT NULL AUTO_INCREMENT,
  `Table`   INT   NOT NULL,
  `FK`      INT   NOT NULL,
  `Text`    TEXT  NOT NULL,
  `User`    INT,
  `Created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `PK_Comment_ID`    PRIMARY KEY (ID),
  CONSTRAINT `FK_Comment_Table` FOREIGN KEY (`Table`) REFERENCES `Table`(`ID`),
  CONSTRAINT `FK_Comment_User`  FOREIGN KEY (`User`)  REFERENCES `User`(`ID`)
);
CREATE TABLE Comment_File (
  Comment INT NOT NULL,
  File    INT NOT NULL,
  CONSTRAINT `FK_Comment_File_Comment`  FOREIGN KEY (`Comment`) REFERENCES `Comment`(`ID`),
  CONSTRAINT `FK_Comment_File_File`     FOREIGN KEY (`File`)    REFERENCES `File`(`ID`),
);
