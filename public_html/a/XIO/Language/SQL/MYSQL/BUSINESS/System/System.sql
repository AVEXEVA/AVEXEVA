CREATE TABLE System (
  `ID`                                      INT NOT NULL AUTO_INCREMENT,
  `Enterprise_Resource_Planning_System`     INT,
  `Customer_Relationship_Management_System` INT,
  `Payroll_System`                          INT,
  `Inventory_Tracking_System`               INT,
  CONSTRAINT `PK_Business_System_ID` PRIMARY KEY (`ID`),
  CONSTRAINT `FK_Business_System_Enterprise_Resource_Planning`      FOREIGN KEY (`Enterprise_Resource_Planning`)      REFERENCES `Enterprise_Resource_Planning`(`ID`),
  CONSTRAINT `FK_Business_System_Customer_Relationship_Management`  FOREIGN KEY (`Customer_Relationship_Management`)  REFERENCES `Customer_Relationship_Management`(`ID`),
  CONSTRAINT `FK_Business_System_Payroll_System`                    FOREIGN KEY (`Payroll_System`)                    REFERENCES `Payroll_System`(`ID`),
  CONSTRAINT `FK_Business_System_Inventory_Tracking`                FOREIGN KEY (`Inventory_Tracking`)                REFERENCES `Inventory_Tracking`(`ID`)
);
