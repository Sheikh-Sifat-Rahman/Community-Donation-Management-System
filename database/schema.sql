-- Community Donation Database Schema
-- Drop existing tables if they exist
DROP TABLE IF EXISTS DISTRIBUTION;
DROP TABLE IF EXISTS AID_REQUEST;
DROP TABLE IF EXISTS DONATION;
DROP TABLE IF EXISTS Aid_Seeker;
DROP TABLE IF EXISTS VOLUNTEERS;
DROP TABLE IF EXISTS WAREHOUSE;
DROP TABLE IF EXISTS DONOR;

-- Create DONOR table
CREATE TABLE DONOR (
    Donor_ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE,
    Donor_Type ENUM('Individual', 'Organization', 'Anonymous') DEFAULT 'Individual',
    Phone VARCHAR(50),
    Address TEXT,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create WAREHOUSE table
CREATE TABLE WAREHOUSE (
    Item_ID INT PRIMARY KEY AUTO_INCREMENT,
    Item_Name VARCHAR(255) UNIQUE NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    CHECK (quantity >= 0)
);

-- Create VOLUNTEERS table
CREATE TABLE VOLUNTEERS (
    Volunteer_ID INT PRIMARY KEY AUTO_INCREMENT,
    Vol_Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE,
    Phone VARCHAR(50),
    Gender ENUM('Male', 'Female'),
    Area_Assigned VARCHAR(100),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Aid_Seeker table
CREATE TABLE Aid_Seeker (
    Seeker_ID INT(11) PRIMARY KEY AUTO_INCREMENT,
    Seeker_Name VARCHAR(255) NOT NULL,
    National_ID VARCHAR(20) NOT NULL,
    Monthly_Income INT(11) NOT NULL,
    Family_Members INT(11) NOT NULL,
    Phone VARCHAR(20) NOT NULL,
    Email VARCHAR(50),
    District VARCHAR(20) NOT NULL,
    Thana VARCHAR(20) NOT NULL,
    Post_No VARCHAR(20) NOT NULL,
    Village VARCHAR(20),
    Street VARCHAR(20),
    House VARCHAR(20),
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create DONATION table
CREATE TABLE DONATION (
    Donation_ID INT PRIMARY KEY AUTO_INCREMENT,
    Donor_ID INT NOT NULL,
    Item_ID INT NOT NULL,
    Date_Donated DATE NOT NULL,
    Quantity_Donated INT NOT NULL,
    Payment_Method ENUM('Test Donation', 'Offline Donation') DEFAULT 'Offline Donation',
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (Donor_ID) REFERENCES DONOR(Donor_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES WAREHOUSE(Item_ID) ON DELETE CASCADE
);

-- Create AID_REQUEST table
CREATE TABLE AID_REQUEST (
    Request_ID INT NOT NULL AUTO_INCREMENT,
    Item_ID INT NOT NULL,
    Seeker_ID INT NOT NULL,
    Date DATE NOT NULL,
    Reason TEXT,
    Quantity_Requested INT NOT NULL,
    Status ENUM('Pending', 'Approved', 'Rejected', 'Fulfilled') DEFAULT 'Pending',
    
    PRIMARY KEY (Request_ID, Item_ID),
    
    FOREIGN KEY (Seeker_ID) REFERENCES Aid_Seeker(Seeker_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES WAREHOUSE(Item_ID) ON DELETE CASCADE
);

-- Create DISTRIBUTION table
CREATE TABLE DISTRIBUTION (
    Distribution_ID INT PRIMARY KEY AUTO_INCREMENT,
    Volunteer_ID INT NOT NULL,
    Item_ID INT NOT NULL,
    Seeker_ID INT NOT NULL,
    Dis_Date DATE NOT NULL,
    Dis_Area VARCHAR(100),
    Quantity INT NOT NULL,
    
    FOREIGN KEY (Volunteer_ID) REFERENCES VOLUNTEERS(Volunteer_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES WAREHOUSE(Item_ID) ON DELETE CASCADE,
    FOREIGN KEY (Seeker_ID) REFERENCES Aid_Seeker(Seeker_ID) ON DELETE CASCADE
);

-- Insert sample data for WAREHOUSE items
INSERT INTO WAREHOUSE (Item_Name, quantity) VALUES
('Food Items', 1000),
('Clothing', 500),
('Medicine', 300),
('Books', 200),
('Water', 800);

-- Insert sample volunteer
INSERT INTO VOLUNTEERS (Vol_Name, Email, Phone, Gender, Area_Assigned) VALUES
('Eluse A. Phillips', 'eluse@cleanhearts.com', '555-0100', 'Female', 'Watkins Ridge');
