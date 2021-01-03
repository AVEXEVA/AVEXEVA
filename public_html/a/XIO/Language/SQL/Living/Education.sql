CREATE TABLE Institution (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Institution_ID PRIMARY KEY (ID)
  CONSTRAINT FK_Institution_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE School (
  ID INT NOT NULL AUTO_INCREMENT,
  Institution INT NOT NULL,
  CONSTRAINT PK_School_ID PRIMARY KEY (ID),
  CONSTRAINT PK_School_Institution FOREIGN KEY (Institution) REFERENCES Institution(ID)
);
CREATE TABLE Degree (
  ID INT NOT NULL AUTO_INCREMENT,
  School INT NOT NULL,
  CONSTRAINT PK_Degree_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Degree_School FOREIGN KEY (School) REFERENCES School(ID)
);
CREATE TABLE Educator (
  ID INT NOT NULL AUTO_INCREMENT,
  Person INT NOT NULL,
  CONSTRAINT PK_Educator_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Educator_Person FOREIGN KEY (Person) REFERENCES Person(ID)
);
CREATE TABLE Professor (
  ID INT NOT NULL AUTO_INCREMENT,
  Educator INT NOT NULL,
  CONSTRAINT PK_Professor_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Professor_Educator FOREIGN KEY (Educator) REFERENCES Educator(ID)
);
CREATE TABLE Teacher (
  ID INT NOT NULL AUTO_INCREMENT,
  Educator INT NOT NULL,
  CONSTRAINT PK_Teacher_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Teacher_Educator FOREIGN KEY (Educator) REFERENCES Educator(ID)
);
CREATE TABLE Class (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Institution INT,
  Educator INT,
  CONSTRAINT PK_Class_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Class_Institution FOREIGN KEY (Institution) REFERENCES Institution(ID),
  CONSTRAINT FK_Class_Educator FOREIGN KEY (Educator) REFERENCES Educator(ID)
);
CREATE TABLE Test (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Class INT,
  Time_Lapse INT,
  CONSTRAINT PK_Test_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Test_Class FOREIGN KEY (Class) REFERENCES Class(ID),
  CONSTRAINT FK_Test_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
CREATE TABLE Quiz (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Class INT,
  Time_Lapse INT,
  CONSTRAINT PK_Quiz_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Quiz_Class FOREIGN KEY (Class) REFERENCES Class(ID),
  CONSTRAINT FK_Quiz_Time_Lapse FOREIGN KEY (Class) REFERENCES Class(ID)
);
CREATE TABLE Lesson (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Class INT,
  Time_Lapse INT,
  CONSTRAINT PK_Lesson_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Lesson_Class FOREIGN KEY (Class) REFERENCES Class(ID),
  CONSTRAINT FK_Lesson_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
CREATE TABLE Attendance (
  ID INT NOT NULL AUTO_INCREMENT,
  Class INT NOT NULL,
  Student INT NOT NULL,
  Time_Lapse INT,
  CONSTRAINT PK_Attendance_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Attendance_Class FOREIGN KEY (Class) REFERENCES Class(ID),
  CONSTRAINT FK_Attendance_Time_Lapse FOREIGN KEY (Time_Lapse) REFERENCES Time_Lapse(ID)
);
CREATE TABLE Assignment (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  Class INT,
  CONSTRAINT PK_Assignment_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Assignment_Class FOREIGN KEY (Class) REFERENCES Class(ID)
);
CREATE TABLE Student_Assignment (
  Student INT NOT NULL,
  Assignment INT NOT NULL,
  File INT NOT NULL,
  Submission DATETIME DEFAULT CURRENT_TIMESTAMP,
  Grader INT,
  Grade FLOAT,
  Graded DATETIME DEFAULT NULL,
  CONSTRAINT FK_Student_Assignment_Student FOREIGN KEY (Student) REFERENCES Student(ID),
  CONSTRAINT FK_Student_Assignment_Assignment FOREIGN KEY (Assignment) REFERENCES Assignment(ID),
  CONSTRAINT FK_Student_Assignment_File FOREIGN KEY (File) REFERENCES File(ID),
  CONSTRAINT FK_Student_Assignment_Greader FOREIGN KEY (Educator) REFERENCES Educator(ID)
);
