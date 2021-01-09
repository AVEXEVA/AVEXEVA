CREATE TABLE Painting (
  `Art`   INT NOT NULL,
  `Image` INT,
  CONSTRAINT `FK_Painting_Art`    FOREIGN KEY (`Art`)   REFERENCES `Art`(`ID`),
  CONSTRAINT `FK_Painting_Image`  FOREIGN KEY (`Image`) REFERENCES `Image`(`ID`)
);
