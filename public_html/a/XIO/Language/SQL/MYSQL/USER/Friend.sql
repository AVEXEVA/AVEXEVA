CREATE TABLE `Friend` (
  `User`    INT         NOT NULL AUTO_INCREMENT,
  `Friend`  VARCHAR(32) NOT NULL,
  CONSTRAINT `FK_Friend_User`   FOREIGN KEY (`User`)    REFERENCES `User`(`ID`),
  CONSTRAINT `FK_Friend_Friend` FOREIGN KEY (`Friend`)  REFERENCES `Friend`(`ID`)
) ENGINE = MyISAM;
