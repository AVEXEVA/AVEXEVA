CREATE TABLE Music (
  `Art`   INT NOT NULL,
  `Audio` INT,
  CONSTRAINT `FK_Music_Art`   FOREIGN KEY (`Art`)   REFERENCES `Art`(`ID`),
  CONSTRAINT `FK_Music_Audio` FOREIGN KEY (`Audio`) REFERENCES `Audio`(`ID`)
);
