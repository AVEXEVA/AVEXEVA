CREATE TABLE `Follower` (
  `User`      INT         NOT NULL AUTO_INCREMENT,
  `Follower`  VARCHAR(32) NOT NULL
  CONSTRAINT `FK_Follower_User`     FOREIGN KEY (`User`)      REFERENCES `User`(`ID`),
  CONSTRAINT `FK_Follower_Follower` FOREIGN KEY (`Follower`)  REFERENCES `Follower`(`ID`)
) ENGINE = MyISAM;
