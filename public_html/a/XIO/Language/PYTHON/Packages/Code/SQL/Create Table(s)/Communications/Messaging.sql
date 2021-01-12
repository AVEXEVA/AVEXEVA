CREATE TABLE Message (
  ID INT NOT NULL AUTO_INCREMENT,
  Parent INT,
  Creator INT NOT NULL,
  Created DATETIME DEFAULT CURRENT_TIMESTAMP,
  Subject VARCHAR(256),
  Text TEXT,
  CONSTRAINT PK_Message_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Message_Parent FOREIGN KEY (Parent) REFERENCES Message(ID),
  CONSTRAINT FK_Message_Creator FOREIGN KEY (User) REFERENCES User(ID)
);
CREATE TABLE Message_Recipient (
  Message INT NOT NULL,
  Recipient INT NOT NULL,
  CONSTRAINT FK_Message_Recipient_Message FOREIGN KEY (Message) REFERENCES Message(ID),
  CONSTRAINT FK_Message_Recipient_Recipient FOREIGN KEY (Recipient) REFERENCES User(ID)
);
