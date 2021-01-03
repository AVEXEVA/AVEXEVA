CREATE TABLE `Member`
  (
    `Group`   INT NOT NULL AUTO_INCREMENT,
    `Member`  INT NOT NULL,
    `Created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `FK_Member_Group`  FOREIGN KEY (`Group`)   REFERENCES `Group`(`ID`),
    CONSTRAINT `FK_Member_Member` FOREIGN KEY (`Member`)  REFERENCES `Follower`(`ID`)
  ) ENGINE = MyISAM;
