CREATE TABLE Email (
  ID        INT NOT NULL AUTO_INCREMENT,
  Address   VARCHAR(256)
);
CREATE TABLE Phone (
  ID      INT NOT NULL AUTO_INCREMENT,
  Number  VARCHAR(256)
);
CREATE TABLE Address (
  ID            INT NOT NULL AUTO_INCREMENT,
  Name          VARCHAR(256),
  Room          INT NOT NULL,
  Floor         INT NOT NULL,
  Street        INT NOT NULL,
  Locale        INT NOT NULL,
  City          INT NOT NULL,
  State         INT NOT NULL,
  Zip_Code      INT NOT NULL,
  Country       INT NOT NULL,
  Latitude      FLOAT,
  Longitude     FLOAT,
  CONSTRAINT PK_Address_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Address_Country FOREIGN KEY (Country) REFERENCES Country(ID),
  CONSTRAINT FK_Address_State FOREIGN KEY (State) REFERENCES State(ID),
  CONSTRAINT FK_Address_City FOREIGN KEY (City) REFERENCES City(ID),
  CONSTRAINT FK_Address_Street FOREIGN KEY (Street) REFERENCES Street(ID),
  CONSTRAINT FK_Address_Floor FOREIGN KEY (Floor) REFERENCES Floor(ID),
  CONSTRAINT FK_Address_Room FOREIGN KEY (Room) REFERENCES Room(ID)
);
CREATE TABLE Country (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_Country_ID PRIMARY KEY (ID)
);
CREATE TABLE Street (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_Street_ID PRIMARY KEY (ID)
);
CREATE TABLE City (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_City_ID PRIMARY KEY (ID)
);
CREATE TABLE State (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(255) NOT NULL,
  CONSTRAINT PK_State_ID PRIMARY KEY (ID)
);
CREATE TABLE Zip_Code (
  ID INT NOT NULL AUTO_INCREMENT,
  Number VARCHAR(25) NOT NULL,
  CONSTRAINT PK_Zip_Code_ID PRIMARY KEY (ID)
);
CREATE TABLE Person (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  First_Name VARCHAR(256),
  Middle_Name VARCHAR(256),
  Last_Name VARCHAR(256),
  Birthday DATETIME,
  Email INT,
  Home INT,
  Work INT,
  Cell INT,
  Office INT,
  CONSTRAINT PK_Person_ID     PRIMARY KEY (ID),
  CONSTRAINT PK_Person_Entity FOREIGN KEY (Entity)  REFERENCES Entity(ID),
  CONSTRAINT FK_Person_Email  FOREIGN KEY (Email)   REFERENCES Email(ID),
  CONSTRAINT FK_Person_Home   FOREIGN KEY (Home)    REFERENCES Address(ID),
  CONSTRAINT FK_Person_Work   FOREIGN KEY (Work)    REFERENCES Address(ID),
  CONSTRAINT FK_Person_Cell   FOREIGN KEY (Cell)    REFERENCES Phone(ID),
  CONSTRAINT FK_Person_Office FOREIGN KEY (Office)  REFERENCES Phone(ID)
);
CREATE TABLE Contractor (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Contractor_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Contractor_Entity PRIMARY KEY (Entity)
);
CREATE TABLE Service (
  ID INT NOT NULL,
  Parent INT NOT NULL,
  CONSTRAINT PK_Service_ID PRIMARY KEY (ID)
  CONSTRAINT PK_Service_Parent FOREIGN KEY (Service) REFERENCES Service(ID)
);
CREATE TABLE Payroll (
  ID INT NOT NULL,
  Employee INT NOT NULL,

);
CREATE TABLE Expenses (
  ID INT NOT NULL,
  Employee INT NOT NULL,
  Amount FLOAT,
  Paid FLOAT
);
CREATE TABLE Payroll_Item
CREATE TABLE User (
  ID        INT NOT NULL AUTO_INCREMENT,
  Name      VARCHAR(256),
  Password  VARCHAR(256),
  Person    INT,
  CONSTRAINT PK_User_ID     PRIMARY KEY (ID),
  CONSTRAINT FK_User_Person FOREIGN KEY (Person) REFERENCES Person(ID)
);
CREATE TABLE Company (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  Name VARCHAR(256),
  Parent INT NOT NULL,
  Founded DATETIME,
  Created DATETIME,
  Headquarters INT,
  Accounts_Payable INT,
  Accounts_Recievable INT,
  Human_Resources INT,
  Engineering INT,
  Legal INT,
  CONSTRAINT PK_Company_ID                  PRIMARY KEY (ID)
  CONSTRIANT PK_Entity_ID                   FOREIGN KEY (Entity)              REFERENCES Entity(ID),
  CONSTRAINT FK_Company_Headquarters        FOREIGN KEY (Headquarters)        REFERENCES Department(ID),
  CONSTRAINT FK_Company_Accounts_Payable    FOREIGN KEY (Accounts_Payable)    REFERENCES Department(ID),
  CONSTRAINT FK_Company_Accounts_Recievable FOREIGN KEY (Accounts_Recievable) REFERENCES Department(ID),
  CONSTRAINT FK_Company_Human_Resources     FOREIGN KEY (Human_Resources)     REFERENCES Department(ID),
  CONSTRAINT FK_Company_Engineering         FOREIGN KEY (Engineering)         REFERENCES Department(ID),
  CONSTRAINT FK_Company_Legal               FOREIGN KEY (Legal)               REFERENCES Department(ID)
);
CREATE TABLE Department (
  ID          INT NOT NULL AUTO_INCREMENT,
  Company     INT NOT NULL,
  Name        VARCHAR(256),
  Supervisor  INT,
  Rolodex     INT NOT NULL,
  CONSTRAINT PK_Department_ID         PRIMARY KEY (ID),
  CONSTRAINT FK_Department_Company    FOREIGN KEY (Company) REFERENCES Company(ID),
  CONSTRAINT FK_Department_Supervisor FOREIGN KEY (Supervisor) REFERENCES Person(ID),
  CONSTRAINT FK_Department_Rolodex    FOREIGN KEY (Rolodex) REFERENCES Rolodex(ID)
);
CREATE TABLE Owner (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Company INT NOT NULL,
  CONSTRAINT PK_Owner_ID      PRIMARY KEY (ID),
  CONSTRAINT FK_Owner_Company FOREIGN KEY (Company) REFERENCES Company(ID)
);
CREATE TABLE Chain_of_Command (
  ID INT NOT NULL AUTO_INCREMENT,
  Owner INT NOT NULL,
  Location INT NOT NULL
);
CREATE TABLE Consultant (
  ID      INT NOT NULL AUTO_INCREMENT,
  Company INT NOT NULL,
  CONSTRAINT PK_Consultant_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Consultant_Company FOREIGN KEY (Company) REFERENCES Company(ID)
);
CREATE TABLE Chain_of_Consultancy (
  Chain_of_Command INT NOT NULL AUTO_INCREMENT,
  Consultant INT NOT NULL,
  Department INT NOT NULL
);
INSERT INTO User(Name, Password, Person, Hired) VALUES('psperanza', 'protectedbyapassword', 1, '2005-07-01 00:00:00.000');
CREATE TABLE Permission (
  ID INT NOT NULL AUTO_INCREMENT,
  Read BIT DEFAULT 0,
  Write BIT DEFAULT 0,
  Execute BIT DEFAULT 0,
  CONSTRAINT PK_Permission_ID   PRIMARY KEY (ID),
  CONSTRAINT FK_Permission_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_Permission_File FOREIGN KEY (File) REFERENCES File(ID)
);
CREATE TABLE User_File_Permission (
  User        INT NOT NULL AUTO_INCREMENT,
  File        INT NOT NULL,
  Permission  INT NOT NULL,
  CONSTRAINT FK_User_File_Permission_User       FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_User_File_Permission_File       FOREIGN KEY (File) REFERENCES File(ID),
  CONSTRAINT FK_User_File_Permission_Permission FOREIGN KEY (Permission) REFERENCES Permission(ID)
);
CREATE TABLE Table (
  ID          INT NOT NULL AUTO_INCREMENT,
  Name        VARCHAR(256) NOT NULL,
  Description TEXT
);
CREATE TABLE User_Table_Permission (
  User        INT NOT NULL AUTO_INCREMENT,
  Table       INT NOT NULL,
  Permission  INT NOT NULL
  CONSTRAINT FK_User_Table_Permission_User FOREIGN KEY (User) REFERENCES User(ID),
  CONSTRAINT FK_User_Table_Permission_Table FOREIGN KEY (Table) REFERENCES Table(ID),
  CONSTRAINT FK_User_Table_Permission_Permission FOREIGN KEY (Permission) REFERENCES Permission(ID)
);
CREATE TABLE File (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Folder INT NOT NULL,
  CONSTRAINT PK_File_ID     PRIMARY KEY (ID),
  CONSTRAINT FK_File_Folder FOREIGN KEY (Folder) REFERENCES Folder(ID)
);
INSERT INTO File(Name) VALUES('index.php'), ('home.php'), ('interface.php'), ('mobile.php');
CREATE TABLE Folder (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Parent INT,
  CONSTRAINT PK_Folder_ID     PRIMARY KEY (ID),
  CONSTRAINT FK_Folder_Parent FOREIGN KEY (Parent) REFERENCES Folder(ID)
);
INSERT INTO Folder(Name)          VALUES('cgi-bin'), ('multimedia');
INSERT INTO Folder(Name, Parent)  VALUES('PHP', 1), ('SQL', 1), ('CSS', 1), ('RegEx', 1), ('Javascript', 1), ('Python', 1);
INSERT INTO Folder(Name, Parent)  VALUES('GET', 3), ('POST', 3), ('Class', 3), ('Trait', 3), ('DOM', 3), ('Page', 3);
CREATE TABLE Log (
  ID INT NOT NULL AUTO_INCREMENT,
  User INT NOT NULL,
  Connection INT NOT NULL,
  Script VARCHAR(MAX)
  CONSTRAINT PK_Log_ID          PRIMARY KEY (ID),
  CONSTRAINT FK_Log_User        FOREIGN KEY (User)        REFERENCES User(ID),
  CONSTRAINT FK_Log_Connection  FOREIGN KEY (Connection)  REFERENCES Connection(ID)
);
CREATE TABLE Location (
  ID          INT NOT NULL AUTO_INCREMENT,
  Name        VARCHAR(256),
  Address     INT NOT NULL,
  CONSTRAINT PK_Location_ID       PRIMARY KEY (ID),
  CONSTRAINT FK_Location_Address  FOREIGN KEY (Address) REFERENCES Address(ID)
);
CREATE TABLE Bank (
  ID INT NOT NULL AUTO_INCREMENT,
  Location INT NOT NULL,
  Name VARCHAR(256),
  PRIMARY KEY (ID),
  CONSTRAINT FK_Bank_Location FOREIGN KEY (Location) REFERENCES Location(ID)
);
CREATE TABLE Unit (
  ID        INT NOT NULL AUTO_INCREMENT,
  Location  INT NOT NULL,
  Bank      INT NOT NULL,
  CONSTRAINT PK_Unit_ID       PRIMARY KEY (ID)
  CONSTRAINT FK_Unit_Location FOREIGN KEY (Location) REFERENCES Location(ID)
);
CREATE TABLE Floor (
  ID INT NOT NULL AUTO_INCREMENT,
  Location INT NOT NULL,
  Name VARCHAR(256),
  CONSTRAINT FK_Floor_ID       PRIMARY KEY (ID),
  CONSTRAINT FK_Floor_Location FOREIGN KEY (Location) REFERENCES Location(ID)
);
CREATE TABLE  Access (
  Unit          INT NOT NULL AUTO_INCREMENT,
  Location      INT NOT NULL,
  Floor         INT NOT NULL,
  Bank          INT NOT NULL,
  `Accessible`  BIT DEFAULT NULL,
  Configured    BIT DEFAULT NULL,
  Obstructed    BIT DEFAULT NULL,
  CONSTRAINT FK_Unit_Location_Unit      FOREIGN KEY (Unit)      REFERENCES Unit(ID),
  CONSTRAINT FK_Unit_Location_Location  FOREIGN KEY (Location)  REFERENCES Location(ID),
  CONSTRAINT FK_Unit_Location_Bank      FOREIGN KEY (Bank)      REFERENCES Bank(ID),
  CONSTRAINT FK_Unit_Location_Floor     FOREIGN KEY (Floor)     REFERENCES Floor(ID)
);
CREATE TABLE Tenant (
  ID INT NOT NULL AUTO_INCREMENT,
  Floor   INT NOT NULL,
  Company INT NOT NULL,
  CONSTRAINT PK_Tenant_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Tenant_Floor FOREIGN KEY (Floor) REFERENCES Floor(ID),
  CONSTRAINT FK_Tenant_Company FOREIGN KEY (Company) REFERENCES Company(ID)
);
CREATE TABLE Asset_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Desription TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Asset_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Asset_Category_Parent FOREIGN KEY (Parent) REFERENCES Asset_Category(ID)
);
INSERT INTO Asset_Category(Name) VALUES('Hardware'), ('Software');
INSERT INTO Asset_Category(Name, Parent) VALUES('Monitor', 1), ('Keyboard', 1), ('Mouse', 1), ('Tower', 1);
INSERT INTO Asset_Category(Name, Parent) VALUES('Front-End', 2), ('Back-End', 2), ('Database', 2), ('Server', 2);
CREATE TABLE Product_Category (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Desription TEXT,
  Parent INT NOT NULL,
  CONSTRAINT PK_Product_Category_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Product_Category_Parent FOREIGN KEY (Parent) REFERENCES Product_Category(ID)
);
CREATE TABLE Purchase_Order (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Purchase_Order_ID PRIMARY KEY (ID)
);
CREATE TABLE Purchase_Order_Item (
  ID INT NOT NULL AUTO_INCREMENT,
  Purchase_Order INT NOT NULL,
  CONSTRAINT PK_Purchase_Order_Item PRIMARY KEY (ID),
  CONSTRAINT FK_Purchase_Order_Item_Purchase_Order FOREIGN KEY (Purchase_Order) REFERENCES Purchase_Order(ID)
);
CREATE TABLE Entity (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Entity_ID PRIMARY KEY (ID)
);
CREATE TABLE Manufacturer (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Manufacturer_ID PRIMARY KEY (ID)
);
CREATE TABLE Vendor (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Vendor_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Vendor_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Vote (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  Name VARCHAR(256),
  Description TEXT,
  Created DATETIME DEFAULT CURRENT_TIMESTAMP,
  Start DATETIME DEFAULT CURRENT_TIMESTAMP,
  End DATETIME DEFAULT NULL,
  Result bit,
  CONSTRAINT PK_Vote_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Vote_Entity FOREIGN KEY ()
);
CREATE TABLE Subject (
  ID INT NOT NULL AUTO_INCREMENT,
  CONSTRAINT PK_Subject_ID PRIMARY KEY (ID)
);
CREATE TABLE Conversation (
  ID INT NOT NULL AUTO_INCREMENT,
  Subject VARCHAR(256),
  CONSTRAINT PK_Covnersation_ID PRIMARY KEY (ID)
);
CREATE TABLE Conversationalist (
  Conversation INT NOT NULL,
  Entity INT NOT NULL
  CONSTRAINT FK_Conversationalist_Conversation FOREIGN KEY (Conversation) REFERENCES Conversation(ID),
  CONSTRAINT FK_Conversationalist_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Thread (
  ID INT NOT NULL AUTO_INCREMENT,
  Author INT,
  Name VARCHAR(256),
  Created DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT PK_Thread_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Thread_Author FOREIGN KEY (Author) REFERENCES Author(ID)
);
CREATE TABLE Book (
  ID INT NOT NULL AUTO_INCREMENT,
  Thread INT NOT NULL,
  CONSTRAINT PK_Book_ID FOREIGN KEY (ID) REFERENCES Thread(ID)
);
CREATE TABLE Chapter (
  ID INT NOT NULL AUTO_INCREMENT,
  Book INT NOT NULL,
  Number INT NOT NULL,
);
CREATE TABLE Paragraph (
  ID INT NOT NULL AUTO_INCREMENT,
  Chapter INT NOT NULL,
  Number INT NOT NULL,
  Sentence INT NOT NULL,
  CONSTRAINT PK_Primary_ID
);
CREATE TABLE Sentence (
  ID INT NOT NULL AUTO_INCREMENT,
  Paragraph INT NOT NULL,
  Number INT NOT NULL,
);
CREATE TABLE Word (
  ID INT NOT NULL AUTO_INCREMENT,
  Sentence INT NOT NULL,
  Number INT NOT NULL,
);
CREATE TABLE Character (
  ID INT NOT NULL AUTO_INCREMENT,
  Word INT NOT NULL,
  Number INT NOT NULL,
  VARCHAR(1)
);
CREATE TABLE Author (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT,
  CONSTRAINT PK_Author_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Author_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Voter (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  Decision bit,
  CONSTRAINT PK_Voter_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Voter_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Jury (
  ID INT NOT NULL,
  Case INT NOT NULL,
  CONSTRAINT PK_Jury_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Jury_Case FOREIGN KEY (Case) REFERENCES Case(ID)
);
CREATE TABLE Juror (
  ID INT NOT NULL,
  Jury INT NOT NULL,
  Number DEFAULT NULL,
  CONSTRAINT PK_Juror_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Juror_Jury FOREIGN KEY (Jury) REFERENCES Jury(ID)
);
CREATE TABLE Agreement (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Description TEXT,
  CONSTRAINT PK_Agreement_ID PRIMARY KEY (ID)
);
CREATE TABLE Agreement_Entity (
  Agreement INT NOT NULL,
  Entity INT NOT NULL,
  CONSTRAINT FK_Agreement_Entity_Agreement FOREIGN KEY Agreement REFERENCES Agreement(ID),
  CONSTRAINT PK_Agreement_Entity_Entity FOREIGN KEY Entity REFERENCES Entity(ID)
);
CREATE TABLE Transaction (
  ID INT NOT NULL,
  From INT NOT NULL,
  To INT NOT NULL,
  Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT PK_Transaction_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Transaction_From FOREIGN KEY (Entity) REFERENCES Entity(ID),
  CONSTRAINT FK_Transaction_To FOREIGN KEY (
);
CREATE TABLE General_Ledger (
  ID INT NOT NULL,
  Code INT,
  Account VARCHAR(256),
  Balance FLOAT,
  CONSTRAINT PK_General_Ledger_ID PRIMARY KEY (ID)
);
CREATE TABLE Transaction_Item (
  ID INT NOT NULL,
  Transaction INT NOT NULL,
  Item INT NOT NULL,
  CONSTRAINT PK_Transaction_Item_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Transaction_Item_Transaction FOREIGN KEY (Transaction) REFERENCES Transaction(ID),
  CONSTRAINT FK_Transaction_Item_Item FOREIGN KEY (Item) REFERENCES Item(ID)
);
CREATE TABLE Item (
  ID INT NOT NULL,
  Name VARCHAR(256),
  Product INT,
  CONSTRAINT PK_Item_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Item_Product FOREIGN KEY (Product) REFERENCES Product(ID)
);
CREATE TABLE Buyer (
  ID INT NOT NULL AUTO_INCREMENT,
  Buyer INT NOT NULL,
  CONSTRAINT PK_Buyer_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Buyer_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Seller (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Seller_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Seller_Entity FOREIGN KEY (Entity) REFERNECES Entity(ID),
);
CREATE TABLE Client (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Client_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Client_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Lawyer (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  Client INT NOT NULL
  CONSTRAINT PK_Lawyer_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Lawyer_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID),
  CONSTRAINT FK_Lawyer_Client FOREIGN KEY (Client) REFERENCES Client(ID)
);
CREATE TABLE Company (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Company_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Company_Entity PRIMARY KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Product (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  SKU VARCHAR(256),
  Category INT NOT NULL,
  Price FLOAT,
  CONSTRAINT PK_Product_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Product_Category FOREIGN KEY (Category) REFERENCES Product_Category(ID);
);
CREATE TABLE Asset (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Product INT,
  Category INT NOT NULL,
  Value FLOAT,
  CONSTRAINT PK_Asset_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Asset_Category FOREIGN KEY (Category) REFERENCES Asset_Category(ID)
);
CREATE TABLE Computer (
  ID INT NOT NULL AUTO_INCREMENT,
  Asset INT,
  Name VARCHAR(256),
  Description TEXT,
  IP VARCHAR(256)
);
CREATE TABLE Monitor (
  ID INT NOT NULL AUTO_INCREMENT,
  Asset INT,
  Name VARCHAR(256),
  Description TEXT,
);
CREATE TABLE Server (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Address VARCHAR(256),
  CONSTRAINT PK_Server_ID PRIMARY KEY (ID)
);
INSERT INTO Server(Name, Address) VALUES('Skeera6', 'vps9073.inmotionhosting.com');
CREATE TABLE Database (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Address VARCHAR(256)
  CONSTRAINT PK_Database_ID PRIMARY KEY (ID)
);
INSERT INTO Database(Name, Address) VALUES('Company', 'skeera6_Company'), ('AVEXEVA', 'skeera6_AVEXEVA'), ('Journal', 'skeera6_Journal');
CREATE TABLE Apartment (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Building INT NOT NULL,
  CONSTRAINT PK_Apartment_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Apartment_Building FOREIGN KEY (Building) REFERENCES Building(ID)
);
CREATE TABLE Case (
  ID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(256),
  Defendant INT NOT NULL,
  Prosecution INT NOT NULL,
  Jurisdiction INT NOT NULL,
  Judge INT NOT NULL,
  CONSTRAINT PK_Case_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Case_Defendant FOREIGN KEY (Defendant) REFERENCES Defendant(ID),
  CONSTRAINT PK_Case_Prosecution FOREIGN KEY (Prosecution) REFERENCES Proseuction(ID)
);
CREATE TABLE Defendant (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Defendant_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Defendant_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Prosecution (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Prosecution_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Proseuction_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Judge (
  ID INT NOT NULL AUTO_INCREMENT,
  Entity INT NOT NULL,
  CONSTRAINT PK_Judge_ID PRIMARY KEY (ID),
  CONSTRAINT FK_Judge_Entity FOREIGN KEY (Entity) REFERENCES Entity(ID)
);
CREATE TABLE Evidence (
  ID INT NOT NULL AUTO_INCREMENT,
  Case INT NOT NULL,
  Item INT NOT NULL,
  CONSTRAINT PK_Evidence_ID PRIMARY KEY (ID),
  CONSTRAINT PK_Evidence_Case FOREIGN KEY (Case),
  CONSTRAINT PK_Evidence_Item FOREIGN KEY (Item)
);
