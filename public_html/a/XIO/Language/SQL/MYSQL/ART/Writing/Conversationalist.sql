CREATE TABLE Conversationalist (
  Conversation INT NOT NULL,
  Entity INT NOT NULL
  CONSTRAINT FK_Conversationalist_Conversation FOREIGN KEY (Conversation) REFERENCES Conversation(ID),
  CONSTRAINT FK_Conversationalist_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
