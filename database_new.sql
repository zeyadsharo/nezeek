-- Areas Table
CREATE TABLE Areas (
    AreaID INT PRIMARY KEY, ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255), Parent INT,  Latitude DECIMAL(10, 8), Longitude DECIMAL(11, 8), FOREIGN KEY (Parent) REFERENCES Areas (AreaID)
);

-- Sectors Table
CREATE TABLE Sectors (
    SectorID INT PRIMARY KEY, ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255), Description TEXT, DisplayOrder INT, DisplayState BOOLEAN, Icon VARCHAR(255), ActivationState BOOLEAN
);

-- Customers Table
CREATE TABLE Customers (
    CustomerID INT PRIMARY KEY,  ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255), Description TEXT, About TEXT, ContactInfo VARCHAR(255), DisplayOrder INT, Slug VARCHAR(255), ActivationState BOOLEAN, Logo VARCHAR(255), Latitude DECIMAL(10, 8), Longitude DECIMAL(11, 8), NextPayment DATE, AreaID INT, SectorID INT, FOREIGN KEY (AreaID) REFERENCES Areas (AreaID), FOREIGN KEY (SectorID) REFERENCES Sectors (SectorID)
);

-- Features Table
CREATE TABLE Features (
    FeatureID INT PRIMARY KEY,  ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255), Description TEXT
);

-- ProductCategories Table
CREATE TABLE ProductCategories (
    CategoryID INT PRIMARY KEY, DisplayOrder INT, ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255), CustomerID INT, FOREIGN KEY (CustomerID) REFERENCES Customers (CustomerID)
);

-- Products Table
CREATE TABLE Products (
    ProductID INT PRIMARY KEY, Title VARCHAR(255), Description TEXT, Model VARCHAR(255), Price DECIMAL(10, 2), Currency VARCHAR(3), Icon VARCHAR(255), CategoryID INT, DisplayOrder INT, DisplayTo DATE, CustomerID INT, AutoDeleteAt DATE, FOREIGN KEY (CategoryID) REFERENCES ProductCategories (CategoryID), FOREIGN KEY (CustomerID) REFERENCES Customers (CustomerID)
);

-- Posts Table
CREATE TABLE Posts (
    PostID INT PRIMARY KEY, Title VARCHAR(255), Icon VARCHAR(255), PostDate DATE, DisplayOrder INT, Content TEXT, CustomerID INT, AutoDeleteAt DATE, FOREIGN KEY (CustomerID) REFERENCES Customers (CustomerID)
);

-- Subscriptions Table
CREATE TABLE Subscriptions (
    SubscriptionID INT PRIMARY KEY, CustomerID INT, FeatureID INT, Price DECIMAL(10, 2), NumberOfRecords INT, FOREIGN KEY (CustomerID) REFERENCES Customers (CustomerID), FOREIGN KEY (FeatureID) REFERENCES Features (FeatureID)   --CustomerID+FeatureID  unique
);

-- DepartmentCategories Table
CREATE TABLE DepartmentCategories (
    CategoryID INT PRIMARY KEY, DisplayOrder INT, Icon VARCHAR(255), ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255)
);

-- Departments Table
CREATE TABLE Departments (
    DepartmentID INT PRIMARY KEY, DisplayOrder INT, Icon VARCHAR(255), ArabicTitle VARCHAR(255), KurdishTitle VARCHAR(255), CategoryID INT, FOREIGN KEY (CategoryID) REFERENCES DepartmentCategories (CategoryID)
);

-- Items Table
CREATE TABLE Items (
    ItemID INT PRIMARY KEY, Title VARCHAR(255), Details TEXT, Model VARCHAR(255), Price DECIMAL(10, 2), Size VARCHAR(255), Color VARCHAR(255), Icon VARCHAR(255), DisplayOrder INT, DisplayFrom DATE, DisplayTo DATE, OwnerName VARCHAR(255), OwnerPhone VARCHAR(255), IsNew BOOLEAN, UsagePercent INT, AreaID INT, DepartmentID INT, FOREIGN KEY (AreaID) REFERENCES Areas (AreaID), FOREIGN KEY (DepartmentID) REFERENCES Departments (DepartmentID)
);

-- Appointments Table
CREATE TABLE Appointments (
    AppointmentID INT PRIMARY KEY, PrivateLabel VARCHAR(255), PublicLabel VARCHAR(255), StartDate DATE, EndDate DATE, AutoDeleteAt DATE, Color VARCHAR(255), CustomerID INT, IsPrivate BOOLEAN, FOREIGN KEY (CustomerID) REFERENCES Customers (CustomerID)
);