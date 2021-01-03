CREATE TABLE Group_Member (
  Group INT NOT NULL,
  Member INT NOT NULL,
  CONSTRAINT FK_Group_Member_Group FOREIGN KEY (Group) REFERENCES Group(ID),
  CONSTRAINT FK_Group_Member_Member FOREIGN KEY (Member) REFERENCES Member(ID)
);
